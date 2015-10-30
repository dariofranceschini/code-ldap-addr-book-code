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

show_site_header();

// TODO: guard against nasties in the DN
$dn = $_GET["dn"];

show_ldap_path("cn=" . gettext("New Record") . "," . $dn,$ldap_base_dn,"schema/generic24.png");

echo "<form method=\"get\" action=\"info.php\">\n";

echo "  <p>\n    " . gettext("What type of record would you like to create?") . "\n  </p>\n";

echo "  <input type=\"hidden\" name=\"dn\" value=\""
	. htmlentities($dn,ENT_COMPAT,"UTF-8") . "\">\n";

echo "  <select name=\"create\" style=\"width:300px\">\n";

foreach($ldap_server->object_schema as $object_class)
	if($ldap_server->get_object_schema_setting($object_class["name"],"can_create"))
	{
		$display_name = $ldap_server->get_object_schema_setting($object_class["name"],
			"display_name");

		echo "    <option value=\"" . $object_class["name"]
			. "\" style=\"background-image:url(schema/"
			. $object_class["icon"]
			. ");background-repeat:no-repeat;height:24px;padding-left:24px\"";

		if($object_class["name"] == $ldap_server->default_create_class) echo " selected";
		echo ">" . $display_name . "</option>\n";
	}

echo "  </select>\n";

echo "  <p>\n    <input type=\"submit\" value=\"" . gettext("Next") . "&nbsp;&nbsp;&nbsp;&#x25B6;\">\n  </p>\n";
echo "</form>\n";

show_site_footer();
?>
