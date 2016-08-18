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

if(empty($_GET["vcard"]))
	show_site_header();

if(prereq_components_ok())
{
	if(isset($_GET["dn"]) && strlen($_GET["dn"])<=MAX_DN_LENGTH) $dn = $_GET["dn"];
	else $dn = $ldap_base_dn;

	if($ldap_server->log_on())
	{
		// Check whether the end part of the DN matches $ldap_base_dn
		if($ldap_server->compare_dn_to_base($dn,$ldap_base_dn)
			|| get_user_setting("allow_system_admin"))
		{
			if(isset($_GET["create"]))
			{
				// TODO: guard against nasties in the parent DN and object class
				$object_class = $_GET["create"];

				// Stub LDAP entry which is to be created
				$entry = array(
					"count"=>1,
					array(
						"objectclass"=>array("count"=>1,$object_class),
						"dn"=>$dn
						)
					);

				$ldap_server->call_schema_function("populate_for_create_" . $object_class,$entry[0]);

				$entry_viewer = new ldap_entry_viewer($ldap_server,$entry);

				$entry_viewer->create = $entry_viewer->edit = true;

				$entry_viewer->show();
			}
			else
			{
				$search_resource = @ldap_read($ldap_server->connection,$dn,$browse_ldap_filter,array("*","+"));

				if($search_resource)
				{
					$entry = ldap_get_entries($ldap_server->connection,$search_resource);

					if($entry["count"]>0)
					{
						// assign an object class to the root DSE object, if the directory doesn't
						// report one itself

						if($dn == "" && !isset($entry[0]["objectclass"]))
						{
							switch($ldap_server->server_type)
							{
								case "ad":
									// made up rootDSE class
									$entry[0][$entry[0]["count"]] = "objectclass";
									$entry[0]["objectclass"][0] = "rootDSE";
									$entry[0]["count"]++;
									break;

								case "edir":
									$entry[0][$entry[0]["count"]] = "objectclass";
									$entry[0]["objectclass"][0] = "treeRoot";
									$entry[0]["count"]++;
									break;

								default:
									// no change made for other server types
							}
						}

						$ldap_server->call_schema_function("before_show_"
							. $ldap_server->get_object_class($entry[0]),$entry[0]);

						$entry_viewer = new ldap_entry_viewer($ldap_server,$entry);

						if(!empty($_GET["vcard"]))
							$entry_viewer->save_vcard();
						else
						{
							if(!empty($_GET["edit"]))
								$entry_viewer->edit = true;

							$entry_viewer->show();
						}
					}
					else
						show_error_message(gettext("Unable to locate LDAP record."));
				}
				else
					show_error_message(gettext("Unable to locate LDAP record."));
			}
		}
		else
			show_error_message(gettext("Record being accessed:") . " <code>" . htmlentities($dn)
				. "</code>\n</p>\n"
				. "<p>\n  " . gettext("This record is outside the part of the directory "
				. "which stores the address book. You do not "
				. "have permission to display it.") . "\n</p>\n"
				. "<p>\n  " . gettext("The address book is stored at:") . " <code>"
				. htmlentities($ldap_base_dn) . "</code>");
	}
	else
	{
		show_ldap_path("");
		show_ldap_bind_error();
	}

	echo "\n\n";
}

if(empty($_GET["vcard"]))
	show_site_footer();
?>
