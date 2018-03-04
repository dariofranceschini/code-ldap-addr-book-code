<?
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

if(prereq_components_ok())
{
	if(!empty($_GET["server_id"]) && is_numeric($_GET["server_id"]))
		$server_id = $_GET["server_id"];
	else
		$server_id=0;

	if($ldap_server_list[$server_id]->log_on())
	{
		if(isset($_GET["attrib"]))
			$attrib = $_GET["attrib"];
		else
		{
			show_site_header();
			show_error_message(sprintf(gettext("Unable to add value to attribute: %s"),
				gettext("No attribute specified")));
		}

		// DN of object that will be written to
		if(isset($_GET["target_dn"]) && strlen($_GET["target_dn"])<=MAX_DN_LENGTH)
			$target_dn = $_GET["target_dn"];
		else
		{
			show_site_header();
			show_error_message(sprintf(gettext("Unable to add value to attribute: %s"),
				gettext("Target object DN is missing")));
		}

		if(isset($_GET["confirm"]) && $_GET["confirm"]=="yes")
		{
			if($ldap_server_list[$server_id]->get_user_setting("allow_edit"))
			{
				$new_value[$attrib] = $_POST["value"];

				$result = @ldap_mod_add($ldap_server_list[$server_id]->connection,
					$target_dn,$new_value);
				$error = ldap_error($ldap_server_list[$server_id]->connection);
				if($result)
				{
					$search_resource = @ldap_read($ldap_server_list[$server_id]->connection,
						$target_dn,$browse_ldap_filter,array("*","+"));
					if($search_resource)
					{
						$entry = ldap_get_entries($ldap_server_list[$server_id]->connection,
							$search_resource);
						$ldap_server_list[$server_id]->call_schema_function("after_add_"
							. $ldap_server_list[$server_id]->get_object_class($entry[0])
							. "_" . $attrib,$entry[0]);

						header("Location: info.php?dn="
							. urlencode($target_dn)
							. ($server_id == 0 ? "" : ("&server_id=" . $server_id)));
					}
					else
						show_error_message(gettext("Unable to access LDAP object."));
				}
				else
				{
					show_site_header();
					show_ldap_path($ldap_server_list[$server_id],$target_dn);

					echo "<p>" . gettext("Error whilst setting attribute") . " '"
						. $attrib . "': " . $error . "</p>";

					echo "<p>\n  <a href=\"info.php?dn=" . urlencode($target_dn)
						. ($server_id == 0 ? "" : ("&server_id=" . $server_id))
						. "\">" . gettext("Return to the Address Book")
						. "</a>\n</p>";
				}
			}
			else
				show_error_message(gettext("You do not have permission to change this record"));
		}
		else
		{
			show_site_header();
			show_ldap_path($ldap_server_list[$server_id],$target_dn);

			if(empty($target_dn))
				$entry_name = "rootDSE";
			else
			{
				$target_dn_array = ldap_explode_dn2($target_dn);
				$entry_name = $target_dn_array[0]["value"];
			}

			echo "<p style=\"font-weight:bold\">"
				. sprintf(gettext("Enter a new value to add to the '%s' attribute of '%s':"),
				$attrib,$entry_name) . "</p>\n<hr>\n";

			echo "<form method=\"POST\" action=\"add_text_value.php?target_dn="
				. urlencode($target_dn) . "&attrib=" . urlencode($attrib)
				. ($server_id == 0 ? "" : ("&server_id=" . $server_id)) . "&confirm=yes\">\n"
				. "  <table>\n  <tr>\n    <td>New value</td>\n    <td><input name=\"value\" type=\"text\"></td>\n  </tr>\n";

			echo "  <tr>\n    <td></td>\n    <td>\n      <input type=\"submit\" value=\""
				. gettext("Add value") . "\">\n";

			echo "      <a href=\"info.php?dn="
				. urlencode($target_dn)
				. ($server_id == 0 ? "" : ("&server_id=" . $server_id))
				. "\"><button>" . gettext("Cancel") . "</button></a>\n    </td>\n  </tr>\n";

			echo "  </table>\n";
			echo "</form>\n";

			show_site_footer();
		}
	}
	else
		 show_ldap_bind_error();
}
?>
