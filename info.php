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
	if(!empty($_GET["dn"]) && strlen($_GET["dn"])<=MAX_DN_LENGTH) $dn = $_GET["dn"];
	else $dn = $ldap_base_dn;

	if(log_on_to_directory($ldap_link))
	{
		// Check whether the end part of the DN matches $ldap_base_dn
		if(ldap_compare_dn_to_base($ldap_link,$dn,$ldap_base_dn))
		{
			if(isset($_GET["create"]))
			{
				// TODO: guard against nasties in the parent DN and object class
				$object_class = $_GET["create"];

				$rdn_attrib = get_object_class_setting(
					$ldap_server->object_class_schema,
					$object_class,"rdn_attrib");

				// Stub LDAP entry which is to be created
		                $entry = array(
                		        "count"=>1,
		                        array(
                        		        "objectclass"=>array("count"=>1,$object_class),
                                		"dn"=>$dn
         		                       )
		                        );

				$entry_viewer = new ldap_entry_viewer($entry_layout,$entry);

				$entry_viewer->create = $entry_viewer->edit = true;

				$entry_viewer->show();
			}
			else
			{
				$search_resource = @ldap_read($ldap_link,$dn,"(objectclass=*)");

				if($search_resource)
				{
					$entry = ldap_get_entries($ldap_link,$search_resource);
					$entry_viewer = new ldap_entry_viewer($entry_layout,$entry);

					if(!empty($_GET["vcard"]))
					{
						header("Content-Type: text/x-vcard");
						header("Content-Disposition: attachment; filename=\""
							. $entry[0]["cn"][0] . ".vcf\"");
						$entry_viewer->save_vcard();
					}
					else
					{
						if(!empty($_GET["edit"]))
							$entry_viewer->edit = true;

						$entry_viewer->show();
					}
				}
				else
					show_error_message("Unable to locate LDAP record.");
			}
		}
		else
			show_error_message("Record being accessed: <code>" . htmlentities($dn)
				. "</code>\n</p>\n"
				. "<p>\n  This record is outside the part of the directory "
				. "which stores the\n  address book. You do not "
				. "have permission to display it.\n</p>\n"
				. "<p>\n  The address book is stored at: <code>"
				. htmlentities($ldap_base_dn) . "</code>");
	}
	else
	{
		show_ldap_path($ldap_base_dn,$ldap_base_dn,"schema/folder.png");
		show_ldap_bind_error();
	}

	echo "\n\n";
}

if(empty($_GET["vcard"]))
	show_site_footer();
?>
