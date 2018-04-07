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

if(prereq_components_ok())
{
	if(!empty($_GET["server_id"]) && is_numeric($_GET["server_id"]))
                $server_id = $_GET["server_id"];
        else
                $server_id=0;

	if(isset($_GET["dn"]) && strlen($_GET["dn"])<=MAX_DN_LENGTH) $dn = $_GET["dn"];
	else $dn = $ldap_server_list[$server_id]->base_dn;

        if($ldap_server_list[$server_id]->log_on())
        {
		if(!$ldap_server_list[$server_id]->get_user_setting("allow_move"))
			show_error_message(gettext("You do not have permission to move this record"));

		$search_resource = @ldap_read($ldap_server_list[$server_id]->connection,$dn,"(objectclass=*)");

		if($search_resource)
		{
			if(isset($_GET["new_parent_dn"]))
			{
				if(strlen($_GET["new_parent_dn"])<=MAX_DN_LENGTH)
				{
					$new_parent_dn = $_GET["new_parent_dn"];

					// TODO: ability to move records with multi-valued RDNs
					$rdn_list = ldap_explode_dn2($dn);
					$new_rdn=$rdn_list[0]["attrib"] . "=" . $rdn_list[0]["value"];

					if(ldap_rename($ldap_server_list[$server_id]->connection,$dn,$new_rdn,$new_parent_dn,true))
						header("Location: info.php?dn=" . $new_rdn . "," . $new_parent_dn
							. ($server_id == 0 ? "" : ("&server_id=" . $server_id)));
					else
						show_error_message(gettext("Unable to move record") . ": "
							. ldap_error($ldap_server_list[$server_id]->connection));
				}
				else
					show_error_message(gettext("Unable to move record") . ": " .
						gettext("Target object DN is missing"));
			}
			else
			{
				$entry = ldap_get_entries($ldap_server_list[$server_id]->connection,$search_resource);

        	                show_ldap_path($ldap_server_list[$server_id],$dn);

				echo "<p>" . gettext("Where do you want to move this record to?") . "</p>";

				// TODO: Show list of target OUs for user to select
				//	- starting location should be parent of the OU containing the object
				//	- only list child objects where is_folder=true
				//	- don't include self (in case object to be moved is a folder)

				echo "<form>";

				echo "<input type=\"hidden\" name=\"dn\" value=\"" . htmlentities($dn,
					ENT_COMPAT,"UTF-8") . "\">\n";

				echo "<input type=\"hidden\" name=\"server_id\" value=\"" . htmlentities($server_id,
					ENT_COMPAT,"UTF-8") . "\">\n";

				echo "<table width=\"100%\"><tr><td style=\"width:1px;white-space:nowrap\">"
					. gettext("New Location") . "</td><td>";

				echo "<input type=\"text\" name=\"new_parent_dn\"";
        	       		echo " value=\"" . htmlentities(get_parent_dn($dn),ENT_COMPAT,"UTF-8") . "\"";
			        echo " style=\"width:100%\"></td></tr></table>";

				echo "<p><input type=\"submit\" value=\"" . gettext("Move Record") . "\">";

				echo "<a href=\"info.php?dn=" . $dn
					. ($server_id == 0 ? "" : "&server_id=" . $server_id)
					. "\"><button>" . gettext("Cancel") . "</button></a></p>\n";

				echo "</form>";
				show_site_footer();
			}
		}
		else
		{
			show_site_header();
			show_ldap_path($ldap_server_list[$server_id],$dn);
			echo "<p>" . gettext("Unable to locate LDAP record") . "</p>"
				. "<p><a href=\"" . current_page_folder_url() . "\">"
				. gettext("Return to the Address Book") . "</a></p>";
			show_site_footer();
		}
	}
}
?>
