<?
/* *********************************************************************

 This program is free software: you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation, either version 3 of the License, or
   (at your option) any later version.

   This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with this program.  If not, see <http://www.gnu.org/licenses/>.

********************************************************************* */

include "config.php";
include "utils.php";

show_site_header();

$dn = $ldap_base_dn;

if(!empty($_GET["filter"]))
{
	$filter = $_GET["filter"];

	$filter_query = "(&(objectClass=person)(|";
	foreach($search_ldap_attrib as $attrib)
		$filter_query .= "(" . $attrib . "=" . $filter . "*)";
	$filter = $filter_query . "))";

	$search_type= "subtree";
}
else
{
	$filter = "(cn=*)";
	$search_type= "subtree";

	$filter = "(|(cn=*)(ou=*))";
	$search_type= "base";

	// TODO: sanitise base DN from URL:
	//	stop "nasties" being passed through to the LDAP server
	//	prevent access to directory outside of address book base DN
	if(!empty($_GET["base"]))
		$dn = str_replace("|","=",$_GET["base"]);
}

show_ldap_path($dn,$ldap_base_dn,"folder.png");

if(!empty($_GET["filter"]))
	show_search_box($_GET["filter"]);
else
	show_search_box("");

if(empty($ldap_server_type))	// Default server type: Active Directory
	$ldap_server_type = "ad";

$object_class_schema = get_object_class_schema($ldap_server_type);

if(ldap_bind($ldap_link,$ldap_user,$ldap_password))
{
	if($search_type == "subtree")
		$search_resource = ldap_search($ldap_link,$dn,$filter)
			or die("<br>Unable to retrieve records from "
			. htmlentities($dn));
	else
		$search_resource = ldap_list($ldap_link,$dn,$filter)
			or die("<br>Unable to retrieve records from "
			. htmlentities($dn));

	if(!empty($_GET["sort"]))
		$sort_type = $_GET["sort"];
	else
		$sort_type = $search_result_default_sort_order;

	// Only allow sorting on attributes which are actualy used in columns
	$sort_order=$search_result_default_sort_order;
	foreach($search_result_columns as $column)
		if($column["attrib"] == $sort_type);
			$sort_order = $sort_type;

	// Special handling of "sortableName" - sort on surname, then on
	// given name within records with same surname (or if neither
	// defined then sort on cn)

	if($sort_order == "sortableName")
	{
		ldap_sort($ldap_link,$search_resource,"cn");
		ldap_sort($ldap_link,$search_resource,"ou");
		ldap_sort($ldap_link,$search_resource,"givenName");
		ldap_sort($ldap_link,$search_resource,"sn");
	}
	else
		ldap_sort($ldap_link,$search_resource,$sort_order);

	$ldap_data = ldap_get_entries($ldap_link,$search_resource);

	echo "<table cellpadding=0 width=\"100%\">\n  <tr>\n";

	// Display column headings
	$colspan="colspan=2 ";
	foreach($search_result_columns as $column)
	{
		echo "    <th " . $colspan
			. "bgcolor=\"#e0e0e0\" style=\"font-size:12pt\">"
			. "<a href=\"?sort=";

		echo $column["attrib"];

		// Only the first item should have colspan=2 (so that it
		// spans both the icon and first attribute column)
		$colspan="";

		if(!empty($_GET["base"]))
			echo "&base=" . $_GET["base"];

		if(!empty($_GET["filter"]))
			echo "&filter=" . $_GET["filter"];

		echo "\">";
		echo $column["caption"];
		echo "\n</a></th>";
	}

	// Display records

	for($i=0;$i < $ldap_data["count"]; $i++)
	{
		echo "  <tr>\n";

		// Fetch object schema details for this record

		$object_data_found = $item_is_folder = false;
		$icon = "generic24.png";
		$item_object_class = "(unrecognised)";
		$object_rdn_attrib = "cn";

		foreach($object_class_schema as $object_class)
		{
			if(in_array($object_class["name"],
				$ldap_data[$i]["objectclass"])
				&& $object_data_found == false)
			{
				// Relative distinguished name (RDN)
				// attribute for the class
				// (assume "cn" if not explicitly defined)
				if(!empty($object_class["rdn_attrib"]))
					$object_rdn_attrib
						= $object_class["rdn_attrib"];

				$item_is_folder=$object_class["is_folder"];
				$icon=$object_class["icon"];
				$object_data_found = true;
				$item_object_class = $object_class["name"];
			}
		}

		// Tooltip should list all classes for unrecognised objects
		if($item_object_class == "(unrecognised)")
		{
			$item_object_class="";
			// Subtract 1 to take into account "count" (number of class entries)
			for($j=0;$j<count($ldap_data[$i]["objectclass"])-1;$j++)
			{
				if($j>0) $item_object_class .= ",";
				$item_object_class .= $ldap_data[$i]["objectclass"][$j];
			}
		}

		// Display the record's icon

		echo "    <td width=1><img alt=\"" . $item_object_class
			. "\" title=\"" . $item_object_class
			. "\" src=\"schema/" . $icon . "\"></td>\n";

		// Display the object's name and attributes in columns

		$object_dn=$object_rdn_attrib . "="
			. $ldap_data[$i][$object_rdn_attrib][0] . "," . $dn;

		if($item_is_folder)
		{
			// Display the folder name, and make it a link to
			// display the folder's contents.

			echo "    <td bgcolor=\"#f0f0f0\" colspan="
				. count($search_result_columns)
				. ">\n      <a href=\"?base="
				. htmlentities(str_replace("=","|",$object_dn))
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

				// Display the object
				echo "    <td bgcolor=\"#f0f0f0\">\n      ";
				switch($column["link_type"])
				{
					// Cell should contain a link to the
					// object
					case "object":
						echo "<a href=\"info.php?dn="
							. str_replace("=","|",
							$object_dn) . "\">"
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
else
	show_ldap_bind_error();

echo "\n</body>\n</html>\n";
?>
