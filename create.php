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

define("VIEWER_TYPE_LIST",1);
define("VIEWER_TYPE_INFO",2);

show_site_header();

// TODO: guard against nasties in the DN
$dn = $_GET["dn"];

show_ldap_path("cn=New Record," . $dn,$ldap_base_dn,"schema/generic24.png");

$object_class_schema = get_object_class_schema($ldap_server_type);

echo "<form method=\"get\" action=\"info.php\">\n";

echo "  <p>\n    What type of record would you like to create?\n  </p>\n";

echo "  <input type=\"hidden\" name=\"dn\" value=\""
	. htmlentities($dn,ENT_COMPAT,"UTF-8") . "\">\n";

echo "  <select name=\"create\" style=\"width:300px\">\n";

if($ldap_server_type == "ad")
	$default_create_class = "contact";
else
	$default_create_class = "inetOrgPerson";

foreach($object_class_schema as $object_class)
	if(get_object_class_setting($object_class_schema,
			$object_class["name"],"can_create"))
	{
		$display_name = get_object_class_setting($object_class_schema,
			$object_class["name"],"display_name");

		echo "    <option value=\"" . $object_class["name"]
			. "\" style=\"background-image:url(schema/"
			. $object_class["icon"]
			. ");background-repeat:no-repeat;height:24px;padding-left:24px\"";

		if($object_class["name"] == $default_create_class) echo " selected";
		echo ">" . $display_name . "</option>\n";
	}

echo "  </select>\n";

echo "  <p>\n    <input type=\"submit\" value=\"Next&nbsp;&nbsp;&nbsp;&#x25B6;\">\n  </p>\n";
echo "</form>\n";

show_site_footer();
?>
