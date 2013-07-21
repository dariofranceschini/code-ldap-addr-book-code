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
	foreach($ldap_search_attrib as $attrib)
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

if(!empty($_GET["sort"])) $sort_type = $_GET["sort"]; else $sort_type = "sn";
show_ldap_path($dn,$ldap_base_dn,"folder.png");

if(!empty($_GET["filter"]))
	show_search_box($_GET["filter"]);
else
	show_search_box("");

if(empty($ldap_server_type))	// Default server type: Active Directory
	$ldap_server_type = "ad";

$object_class_schema = get_object_class_schema($ldap_server_type);

if (ldap_bind($ldap_link,$ldap_user,$ldap_password))
{
	if($search_type == "subtree")
		$search_resource = ldap_search($ldap_link,$dn,$filter)
			or die("<br>Unable to retrieve records from "
			. htmlentities($dn));
	else
		$search_resource = ldap_list($ldap_link,$dn,$filter)
			or die("<br>Unable to retrieve records from "
			. htmlentities($dn));

	switch($sort_type)
	{
		case "company":
			$sort_order="company";
			break;
		case "mail":
			$sort_order="mail";
			break;
		case "sn":
		default:
			$sort_order="sn";
			break;
	}

	ldap_sort($ldap_link,$search_resource,$sort_order);
	$ldap_data = ldap_get_entries($ldap_link,$search_resource);

	echo "<table cellpadding=0 width=\"100%\">\n  <tr>\n";

	echo "    <th colspan=2 bgcolor=\"#e0e0e0\" style=\"font-size:12pt\">";
	echo "<a href=\"/?sort=sn";
	if(!empty($_GET["filter"])) echo "&filter=" . $_GET["filter"];
	echo "\">Name</a>"
		. "</th>\n    <th bgcolor=\"#e0e0e0\" style=\"font-size:12pt\">"
		. "<a href=\"/?sort=mail";
	if(!empty($_GET["filter"])) echo "&filter=" . $_GET["filter"];
	echo "\">E-mail</a>"
		. "</th>\n    <th bgcolor=\"#e0e0e0\" style=\"font-size:12pt\">"
		. "<a href=\"/?sort=company";
	if(!empty($_GET["filter"])) echo "&filter=" . $_GET["filter"];
	echo"\">Organisation</a></th>\n  </tr>\n";

	for($i=0;$i < $ldap_data["count"]; $i++)
	{
		echo "  <tr>\n";
		echo "    <td width=1>";

		$object_data_found = $item_is_folder = false;
		$icon = "generic24.png";

		$item_object_class = "(object)";
		foreach($object_class_schema as $object_class)
		{
			if(in_array($object_class["name"],
				$ldap_data[$i]['objectclass'])
				&& $object_data_found == false)
			{
				$item_is_folder=$object_class["is_folder"];
				$icon=$object_class["icon"];
				$object_data_found = true;
				$item_object_class = $object_class["name"];
			}
		}
		echo "<img alt=\"" . $item_object_class . "\" src=\"schema/"
			. $icon . "\">";

		echo "</td>\n";

		if($item_is_folder)
		{
			// TODO: correct behaviour should be to check the
			// object class to decide what attribute to use in
			// the DN (get from schema array)
			if(!empty($ldap_data[$i]["ou"][0]))
			{
				$object_rdn_value = mb_convert_encoding(
					$ldap_data[$i]["ou"][0],
					"HTML-ENTITIES","UTF-8");
				$object_dn="OU=" . $object_rdn_value
					. "," . $dn;
			}
			else
			{
				$object_rdn_value = mb_convert_encoding(
					$ldap_data[$i]["cn"][0],
					"HTML-ENTITIES","UTF-8");
				$object_dn="CN=" . $object_rdn_value . ","
					. $dn;
			}
			echo "    <td bgcolor=\"#f0f0f0\" colspan=3>\n"
				. "      <a href=\"?base="
				. htmlentities(str_replace("=","|",$object_dn))
				. "\">\n        " . $object_rdn_value
				. "\n      </a>\n    ";
		}
		else
		{
			switch($ldap_server_type)
			{
				case "edir";
					echo "    <td bgcolor=\"#f0f0f0\">\n"
						. "      <a href=\"info.php?dn="
						. str_replace("=","|",
						$ldap_data[$i]['dn'])
						. "\">\n        ";
					break;
				case "ad":
				default:
					echo "    <td bgcolor=\"#f0f0f0\">\n"
						. "      <a href=\"info.php?dn="
						. str_replace("=","|",
						$ldap_data[$i][
						'distinguishedname'][0])
						. "\">\n        ";
			}

			// TODO: correct behaviour should be to check the
			// object class to decide what attribute to use in
			// the DN (get from schema array)
			if(!empty($ldap_data[$i]['sn'][0]))
				echo mb_convert_encoding(
					$ldap_data[$i]['sn'][0],
					"HTML-ENTITIES","UTF-8");
			else if(!empty($ldap_data[$i]['displayname'][0]))
				echo mb_convert_encoding(
					$ldap_data[$i]['displayname'][0],
					"HTML-ENTITIES","UTF-8");
			else
				echo mb_convert_encoding(
					$ldap_data[$i]['cn'][0],
					"HTML-ENTITIES","UTF-8");

			if(!empty($ldap_data[$i]['givenname'][0]))
				echo ", " . mb_convert_encoding(
					$ldap_data[$i]['givenname'][0],
					"HTML-ENTITIES","UTF-8");
			echo "\n      </a>\n";

			echo "    </td>\n";
			echo "    <td bgcolor=\"#f0f0f0\">";
			if(!empty($ldap_data[$i]['mail'][0]))
				echo "<a href=\"mailto:"
					. $ldap_data[$i]['mail'][0] . "\">"
					. $ldap_data[$i]['mail'][0] . "</a>";
			echo "</td>\n";
			echo "    <td bgcolor=\"#f0f0f0\">";
			if(!empty($ldap_data[$i]['company'][0]))
				echo $ldap_data[$i]['company'][0];
		}
		echo "</td>\n";
		echo "  </tr>\n";
	}
	echo "</table>\n";
}
else
	show_ldap_bind_error();

echo "\n</body>\n</html>\n";
?>
