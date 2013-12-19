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

include "config.php";
include "utils.php";

show_site_header();

$dn = $ldap_base_dn;

if(!empty($_GET["filter"]))
{
	$filter = $_GET["filter"];

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
}
else
{
	// Default filter expression to use if none specified in config
	// file: display objects of all classes when browsing the directory
	$filter = empty($browse_ldap_filter)
		?"objectClass=*":$browse_ldap_filter;
	$search_type= "base";

	// TODO: sanitise base DN from URL:
	//	stop "nasties" being passed through to the LDAP server
	//	prevent access to directory outside of address book base DN
	if(!empty($_GET["base"]))
		$dn = str_replace("|","=",$_GET["base"]);
}

show_ldap_path($dn,$ldap_base_dn,"folder.png");

if(empty($ldap_server_type))	// Default server type: Active Directory
	$ldap_server_type = "ad";

$user_info = get_user_info();

if($user_info["allow_search"] && $user_info["ldap_name"]!="__DENY__")
	if(!empty($_GET["filter"]))
		show_search_box($_GET["filter"]);
	else
		show_search_box("");
else
	echo "<br>";

$search_resource = false;

if(log_on_to_directory($ldap_link))
{
	$old_error_reporting=error_reporting();
	error_reporting(0);

	if($search_type == "subtree")
		// get search results
		if($user_info["allow_search"])
			$search_resource = ldap_search($ldap_link,$dn,$filter);
        	else
        	        echo "<p>You do not have permission to search the directory</p>\n";
	else
		// browse OU contents
		if($user_info["allow_browse"])
			$search_resource = ldap_list($ldap_link,$dn,$filter);
		else
			// only show error if explicit base DN browse attempt
			if (!empty($_GET["base"]))
	        	        echo "<p>You do not have permission to browse the directory</p>\n";

	error_reporting($old_error_reporting);
}
else
	show_ldap_bind_error();

// Display search resource info if successfully fetched
if($search_resource)
{
	$object_class_schema = get_object_class_schema($ldap_server_type);

	if(!empty($_GET["sort"]))
		$sort_type = $_GET["sort"];
	else
		$sort_type = $search_result_default_sort_order;

	// Only allow sorting on attributes which are actualy used in columns
	$sort_order=$search_result_default_sort_order;
	foreach($search_result_columns as $column)
		if($column["attrib"] == $sort_type);
			$sort_order = $sort_type;

	if($sort_order == "sortableName")
	{
		ldap_sort($ldap_link,$search_resource,"cn");
		ldap_sort($ldap_link,$search_resource,"ou");
		ldap_sort($ldap_link,$search_resource,"givenName");
		ldap_sort($ldap_link,$search_resource,"sn");
	}
	else
		ldap_sort($ldap_link,$search_resource,$sort_order);

	echo "<table class=\"search_results_viewer\">\n  <tr>\n";

	// Display column headings
	$colspan="colspan=2 ";
	foreach($search_result_columns as $column)
	{
		echo "    <th " . $colspan
			. "class=\"column_header\">"
			. "<a href=\"?sort=";

		echo urlencode($column["attrib"]);

		// Only the first item should have colspan=2 (so that it
		// spans both the icon and first attribute column)
		$colspan="";

		if(!empty($_GET["base"]))
			echo "&base=" . $_GET["base"];

		if(!empty($_GET["filter"]))
			echo "&filter=" . $_GET["filter"];

		echo "\">";
		echo $column["caption"];
		echo "</a></th>\n";
	}
	echo "  </tr>\n";

	// Display records

	$ldap_data = ldap_get_entries($ldap_link,$search_resource);

	for($i=0;$i < $ldap_data["count"]; $i++)
	{
		echo "  <tr>\n";

		// Fetch object schema details for this record

		$item_object_class = get_object_class(
			$object_class_schema,$ldap_data[$i]);

		$icon = get_object_class_setting(
			$object_class_schema,$item_object_class,"icon");
		$item_is_folder = get_object_class_setting(
			$object_class_schema,$item_object_class,"is_folder");
		$object_rdn_attrib = get_object_class_setting(
			$object_class_schema,$item_object_class,"rdn_attrib");

		// Tooltip should list all classes for unrecognised objects
		if($item_object_class == "(unrecognised)")
		{
			$item_object_class="";
			// Subtract 1 is to take into account "count" (number of class entries)
			for($j=0;$j<count($ldap_data[$i]["objectclass"])-1;$j++)
			{
				if($j>0) $item_object_class .= ",";
				$item_object_class .= $ldap_data[$i]["objectclass"][$j];
			}
		}

		// Display the record's icon

		echo "    <td class=\"object_class_icon\"><img alt=\"" . $item_object_class
			. "\" title=\"" . $item_object_class
			. "\" src=\"schema/" . $icon . "\"></td>\n";

		// Display the record's name and attributes in columns

		switch($ldap_server_type)
 		{
			case "ad":
				$object_dn = $ldap_data[$i]["distinguishedname"][0];

			case "edir":
			case "openldap":
			default:
				$object_dn = $ldap_data[$i]["dn"];
				break;
		}

		if($item_is_folder)
		{
			// Display the folder name, and make it a link to
			// display the folder's contents.

			echo "    <td colspan=" . count($search_result_columns)
				. ">\n      <a href=\"?base="
				. urlencode(str_replace("=","|",$object_dn))
				. "\">\n        "
				. mb_convert_encoding(
				$ldap_data[$i][$object_rdn_attrib][0],
				"HTML-ENTITIES","UTF-8")
				. "\n      </a>\n    </td>\n";
		}
		else
		{
			// Display user's chosen set of columns (attributes).

			foreach($search_result_columns as $column)
			{
				// Get the object name

				// sortableName is an internal "synthesised"
				// attribute rather than retrieved from
				// the LDAP server itself.
				if($column["attrib"] == "sortableName")
				{
					if(!empty($ldap_data[$i]["sn"][0]))
						$object_name
							= $ldap_data[$i]["sn"][0];
					else if(!empty($ldap_data[$i]["displayname"][0]))
						$object_name
							= $ldap_data[$i]["displayname"][0];
					else
						$object_name
							= $ldap_data[$i]["cn"][0];

					if(!empty($ldap_data[$i]["givenname"][0]))
						$object_name .= ", "
							. $ldap_data[$i]['givenname'][0];
				}
				else
					if(!empty($ldap_data[$i][strtolower($column["attrib"])][0]))
					{
						$object_name =
							$ldap_data[$i][strtolower($column["attrib"])][0];
					}
					else $object_name = "";

				$object_name = mb_convert_encoding($object_name,"HTML-ENTITIES","UTF-8");

				// Don't make the cell a link to the object if the
				// user doesn't have view permissions
				if($column["link_type"] == "object" && !$user_info["allow_view"])
					$column["link_type"] = "none";

				// Display the object
				echo "    <td class=\""
					. ldap_attribute_to_css_class($column["attrib"])
					. "\">\n      ";
				switch($column["link_type"])
				{
					// Cell should contain a link to the
					// object
					case "object":
						echo "<a href=\"info.php?dn="
							. urlencode(str_replace("=","|",
							$object_dn)) . "\">"
							. $object_name
							. "</a>";
						break;

					// Cell should contain a link to an
					// e-mail address
					case "mailto":
						echo "<a href=\"mailto:"
							. $object_name . "\">"
							. $object_name
							. "</a>";
						break;

					// Cell is not a link
					case "none":
					default:
						echo $object_name;
						break;
				}
				echo "\n    </td>\n";
			}
		}
		echo "  </tr>\n";
	}
	echo "</table>\n";
}

echo "\n";

show_site_footer();

?>
