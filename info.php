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

show_site_header();

if(prereq_components_ok())
{
	if(!empty($_GET["dn"]) && strlen($_GET["dn"])<=MAX_DN_LENGTH) $dn = $_GET["dn"];
	else $dn = $ldap_base_dn;

	// Check whether the end part of the DN matches $ldap_base_dn
	if(substr($dn,-strlen($ldap_base_dn)) == $ldap_base_dn)
	{
		if(log_on_to_directory($ldap_link))
		{
			$search_resource = @ldap_read($ldap_link,$dn,"(objectclass=*)");

			if($search_resource)
			{
				$entry = ldap_get_entries($ldap_link,$search_resource);
				$entry_viewer = new ldap_entry_viewer($entry,$entry_layout);
				$entry_viewer->show();
			}
			else
				show_error_message("Unable to locate LDAP record.");
		}
		else
		{
			show_ldap_path($ldap_base_dn,$ldap_base_dn,"folder.png");
			show_ldap_bind_error();
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

	echo "\n\n";
}

show_site_footer();
?>
