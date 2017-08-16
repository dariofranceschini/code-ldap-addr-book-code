<?php
/* ************************************************************************

   This program is free software: you can redistribute it and/or modify
   it under the terms of the GNU Affero General Public License as published
   by the Free Software Foundation, either version 3 of the License, or
   (at your option) any later version.

   This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU Affero General Public License for more details.

   You should have received a copy of the GNU Affero General Public License
   along with this program.  If not, see <http://www.gnu.org/licenses/>.

   ************************************************************************ */

include "utils.php";
include "config.php";

if(isset($enable_search_suggestions) && $enable_search_suggestions
	&& get_user_setting("allow_search"))
{
	$dn = $ldap_server->base_dn;

	if(!empty($_GET["filter"]))
	{
		$filter = ldap_escape($_GET["filter"],null,LDAP_ESCAPE_FILTER);

		$search_criteria = "";
		foreach($search_ldap_attrib as $attrib)
			$search_criteria .= "(" . $attrib . "=" . $filter . "*)";

		// Default filter expression to use if none specified in config
		// file: return only people in search results
		if(empty($search_ldap_filter))
			$search_ldap_filter
				= "(&(objectClass=person)___search_criteria___)";

		$filter = str_replace("___search_criteria___",
			"(|" . $search_criteria . ")",$search_ldap_filter);

		$search_type= "subtree";

		$search_resource = false;

		if($ldap_server->log_on())
			// get search results
			$search_resource = @ldap_search($ldap_server->connection,$dn,$filter);
		else
			show_ldap_bind_error();

		// Return search resource info if successfully fetched
		if(is_resource($search_resource))
		{
			$sort_type = $search_result_default_sort_order;

			// Only allow sorting on attributes which are actualy used in columns
			$sort_order=$search_result_default_sort_order;
			foreach($search_result_columns as $column)
				if($column["attrib"] == $sort_type);
					$sort_order = $sort_type;

			$ldap_data = ldap_sort_entries(ldap_get_entries($ldap_server->connection,$search_resource),
				$sort_order == "sortableName"
				? array("sn","givenName","ou","cn")
				: array($sort_order),
				LDAP_SORT_ASCENDING);

			// JSON search suggestion response consists of four elements:
			//	Text for which search term suggestions were requested
			//	Array of search term suggestions
			//	Array of descriptions corresponding to each suggestion
			//	Array of URLs corresponding to each suggestion

			// Ref: http://www.opensearch.org/Specifications/OpenSearch/Extensions/Suggestions/1.1

			$json = array($_GET["filter"],array(),array(),array());

			// Copy in search results to the subsequent outer array elements
			for($i=0;$i < $ldap_data["count"]; $i++)
			{
				// Search term suggestion
				if(!empty($ldap_data[$i]["displayname"][0]))
					array_push($json[1],$ldap_data[$i]["displayname"][0]);
				else
					array_push($json[1],$ldap_data[$i]["cn"][0]);

				// Description of suggestion (not currently used)
				array_push($json[2],"");

				// URL for search result(s) for this suggestion
				array_push($json[3],current_page_folder_url() . "?filter="
					. urlencode($_GET["filter"]));

				array_push($json[3],current_page_folder_url() . "info.php?dn="
					. urlencode($ldap_data[$i]["dn"]));
			}

			header('Content-Type:application/x-suggestions+json');

			// The following syntax can be used in PHP 5.4 or later:
			// echo json_encode($json,JSON_UNESCAPED_SLASHES);

			// More backwards compatible equivalent for now:
			echo str_replace("\/","/",json_encode($json));
		}
	}
}
?>
