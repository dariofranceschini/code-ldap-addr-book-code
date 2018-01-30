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

if(!empty($_GET["server_id"]) && is_numeric($_GET["server_id"]))
	$server_id = $_GET["server_id"];
else
	$server_id=0;

if($ldap_server_list[$server_id]->log_on())
{
	show_site_header();

	if(!$ldap_server_list[$server_id]->get_user_setting("allow_create"))
		show_error_message(gettext("You do not have permission to create new records."));

	// TODO: guard against nasties in the DN
	$dn = $_GET["dn"];

	$search_resource = @ldap_read($ldap_server_list[$server_id]->connection,
		$dn,$browse_ldap_filter,array("objectclass"));

	if($search_resource)
	{
		$container_entry = ldap_get_entries($ldap_server_list[$server_id]->connection,
			$search_resource);

		fix_missing_object_classes($ldap_server_list[$server_id],$container_entry[0]);

		$contain_list = array("*");
		foreach($container_entry[0]["objectclass"] as $class_index=>$container_class)
		{
			if(!($class_index === "count"))
			{
				$contain_list_for_class = explode(",",$ldap_server_list[$server_id]->get_object_schema_setting(
					$container_class,"can_contain"));

				if($contain_list_for_class[0] != "*")
					// TODO: further restrict contain_list if already populated
					$contain_list = $contain_list_for_class;
			}
		}

		$container_object = $container_entry[0];
	}
	else
	{
		$container_object = array("objectclass"=>"top");
		$contain_list = array("*");
	}

	$show_all_object_classes = isset($_GET["show_all"]) && $ldap_server_list[$server_id]->get_user_setting("allow_system_admin");

	show_ldap_path($ldap_server_list[$server_id],"cn=" . gettext("New Record") . (empty($dn) ? "" : "," . $dn),"schema/generic24.png");

	echo "<form method=\"GET\" action=\"info.php\">\n";

	if($server_id!=0)
		echo "  <input type=\"hidden\" name=\"server_id\" value=\""
			. htmlentities($server_id,ENT_COMPAT,"UTF-8") . "\">\n";

	echo "  <p>\n    " . gettext("What type of record would you like to create?") . "\n  </p>\n";

	echo "  <input type=\"hidden\" name=\"dn\" value=\""
		. htmlentities($dn,ENT_COMPAT,"UTF-8") . "\">\n";

	echo "  <select name=\"create\" style=\"width:300px\" id=\"object_class_selector\">\n";

	$create_list = array();
	foreach($ldap_server_list[$server_id]->object_schema as $object_class)
		if(in_array($ldap_server_list[$server_id]->get_object_schema_setting($object_class["name"],"class_type"),array("structural","type88")))
			if($show_all_object_classes ||
					(
						// object must be marked as creatable (in general)
						$ldap_server_list[$server_id]->get_object_schema_setting($object_class["name"],"can_create")
						// ...and the container/folder is "willing" to contain it
						&& can_create_in_container($object_class,$contain_list)
						// ...and the object is "willing" to be contained here
						&& can_be_contained_by($object_class,$container_object)
					)
				)
				$create_list[] = $object_class["name"];

	natcasesort($create_list);

	foreach($create_list as $object_class)
	{
		$display_name = $ldap_server_list[$server_id]->get_object_schema_setting($object_class,
			"display_name");
		$icon = $ldap_server_list[$server_id]->get_object_schema_setting($object_class,
			"icon");

		echo "    <option value=\"" . $object_class . "\" icon=\"schema/" . $icon . "\"";

		if($object_class == $ldap_server_list[$server_id]->default_create_class) echo " selected";
		echo ">" . $display_name . "</option>\n";
	}

	echo "  </select>\n";

	if($show_all_object_classes)
		echo "  <p>" . gettext("This list contains all object classes that are recognised by the Address Book.") . "</p>";
	else
		echo "  <p>"
			. gettext("This list contains only those object classes that end users are allowed to create.")
			. "</p>\n  <p><a href=\"create.php?dn=" . urlencode($dn) . "&show_all=yes"
			. ($server_id==0 ? "" : ("&server_id=" . $server_id)) . "\">"
			. gettext("Choose from all recognised object classes") . "</a></p>";

	echo "  <p>\n    <input type=\"submit\" value=\"" . gettext("Next") . "&nbsp;&nbsp;&nbsp;&#x25B6;\">\n  </p>\n";
	echo "</form>\n";

	show_site_footer();
}
else
	show_ldap_bind_error();
?>
