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

// JSON search suggestion response consists of four elements:
//	Text for which search term suggestions were requested
//	Array of search term suggestions
//	Array of descriptions corresponding to each suggestion
//	Array of URLs corresponding to each suggestion

// Ref: http://www.opensearch.org/Specifications/OpenSearch/Extensions/Suggestions/1.1

$json = array($_GET["filter"],array(),array(),array());

if(isset($enable_search_suggestions) && $enable_search_suggestions
	&& !empty($_GET["filter"]) && get_user_setting("allow_search"))
{
	if($ldap_server->log_on())
	{
		$dn = $ldap_server->base_dn;

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

		$search_resource = @ldap_search($ldap_server->connection,$dn,$filter);

		// Return search resource info if successfully fetched
		if(is_resource($search_resource))
		{
			$ldap_data = ldap_get_entries($ldap_server->connection,$search_resource);

			// Copy in search results to the subsequent outer array elements
			for($i=0;$i < $ldap_data["count"]; $i++)
			{
				// Search term suggestion
				if(!empty($ldap_data[$i]["displayname"][0]))
					$suggestion=$ldap_data[$i]["displayname"][0];
				else
					$suggestion=$ldap_data[$i]["cn"][0];

				// Add the suggestion only if not already present
				if(!in_array($suggestion,$json[1]))
				{
					array_push($json[1],$suggestion);

					// Description of suggestion (not currently used)
					array_push($json[2],"");

					// URL for search result(s) for this suggestion
					array_push($json[3],current_page_folder_url() . "?filter="
						. urlencode($suggestion));
				}
			}
		}

		array_multisort($json[1],$json[2],$json[3]);
	}
}

header('Content-Type:application/x-suggestions+json');

// The following syntax can be used in PHP 5.4 or later:
// echo json_encode($json,JSON_UNESCAPED_SLASHES);

// More backwards compatible equivalent for now:
echo str_replace("\/","/",json_encode($json));
?>
