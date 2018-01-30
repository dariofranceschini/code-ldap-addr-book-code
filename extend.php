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
        if(!$ldap_server_list[$server_id]->get_user_setting("allow_extend"))
	{
		show_site_header();
                show_error_message(gettext("You do not have permission to add auxiliary classes to records."));
	}

	// TODO: guard against nasties in the DN
	$dn = $_GET["dn"];

	if(isset($_POST["auxiliary_class"]))
		header("Location: info.php?edit=1&dn=" . $_GET["dn"]
			. "&add_aux_class=" . $_POST["auxiliary_class"]
			. ($server_id == 0 ? "" : ("&server_id=" . $server_id)));
	else
	{
		show_site_header();

		$search_resource = @ldap_read($ldap_server_list[$server_id]->connection,
			$dn,$browse_ldap_filter,array("objectclass"));

		show_ldap_path($ldap_server_list[$server_id],$dn);

		// TODO: guard against nasties in the DN
		echo "<form method=\"POST\" action=\"extend.php?dn=" . $_GET["dn"]
			. ($server_id == 0 ? "" : ("&server_id=" . $server_id)) ."\">\n";

		echo "  <p>" . gettext("You can extend this record to hold additional information.") . "</p>";
		echo "  <p>" . gettext("What type of information would you like to add?") . "</p>";

		echo "  <input type=\"hidden\" name=\"dn\" value=\""
			. htmlentities($dn,ENT_COMPAT,"UTF-8") . "\">\n";

		echo "  <select name=\"auxiliary_class\" style=\"width:300px\" id=\"object_class_selector\">\n";

		// TODO: miss out aux classes already added to the object

		$add_any_auxiliary_class = isset($_GET["show_all"]);

		$auxiliary_class_list = array();
		foreach($ldap_server_list[$server_id]->object_schema as $object_class)
		{
			if(isset($object_class["class_type"]) && $object_class["class_type"] == "auxiliary")
			{
				if($add_any_auxiliary_class || $ldap_server_list[$server_id]->get_object_schema_setting($object_class["name"],"can_create"))
				$auxiliary_class_list[] = $object_class["name"];
			}
		}

		natcasesort($auxiliary_class_list);

		foreach($auxiliary_class_list as $object_class)
		{
			$display_name = $ldap_server_list[$server_id]->get_object_schema_setting($object_class,
				"display_name");
			$icon = $ldap_server_list[$server_id]->get_object_schema_setting($object_class,
				"icon");

			echo "    <option value=\"" . $object_class . "\" icon=\"schema/" . $icon . "\"";

			echo ">" . $display_name . "</option>\n";
		}

		echo "  </select>\n";

		if($add_any_auxiliary_class)
			echo "  <p>" . gettext("This list contains all auxiliary classes that are recognised by the Address Book.") . "</p>";
		else
			echo "  <p>"
				. gettext("This list contains only those auxiliary classes that end users are allowed to add.")
				. "</p>\n  <p><a href=\"extend.php?dn=" . urlencode($dn) . "&show_all=yes\">"
				. gettext("Choose from all recognised auxiliary classes") . "</a></p>";

		echo "  <p>\n    <input type=\"submit\" value=\"" . gettext("Next") . "&nbsp;&nbsp;&nbsp;&#x25B6;\">\n  </p>\n";
		echo "</form>\n";

		show_site_footer();
	}
}
else
	show_ldap_bind_error();
?>
