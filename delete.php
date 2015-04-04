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

// TODO: guard against nasties in the DN
$dn = $_GET["dn"];

// if successful delete then should take the user back to displaying parent
// container object afterwards.
$rdn_list = ldap_explode_dn2($dn);
$return_page_if_deleted = "index.php?dn=" . urlencode($rdn_list[1]["dn"]);

// Determine where to return to if delete was cancelled or failed
if(isset($_GET["page"]) && $_GET["page"] == "info")
	$return_page_if_not_deleted = "info.php?dn=" . urlencode($dn);
else
	$return_page_if_not_deleted = "index.php?dn=" . urlencode($rdn_list[1]["dn"]);

// Resume existing session (if any exists) in order to get
// currently logged in user
if(!isset($_SESSION)) session_start();

if($ldap_server->log_on())
{
	// check record exists before offering to delete it
	$search_resource = @ldap_read($ldap_server->connection,$dn,"(objectclass=*)");

	if($search_resource)
	{
		$user_info = get_user_info();

		// Use generic contact24.png instead of actual
		// icon/thumbnail if the user isn't granted allow_view
		// permission.
		if($user_info["allow_view"])
		{
			$entry = ldap_get_entries($ldap_server->connection,$search_resource);
			$icon = $ldap_server->get_icon_for_ldap_entry($entry[0]);
		}
		else
			$icon = "contact24.png";

		if(isset($user_info["allow_delete"]) && $user_info["allow_delete"])
		{
			show_site_header();
			show_ldap_path($dn,$ldap_base_dn,$icon);

			if(empty($_GET["confirm"]))
			{
				echo "<p>Are you sure you want to delete this record?</p>\n";

				echo "<a href=\"delete.php?dn=" . urlencode($dn)
					. "&confirm=yes\"><button>Yes</button></a>\n";

				echo "<a href=\"" . $return_page_if_not_deleted
					. "\"><button>No</button></a>\n";
			}
			else
			{
				if(@ldap_delete($ldap_server->connection,$dn))
					header("Location: " . $return_page_if_deleted);
				else
				{
					show_site_header();
					show_ldap_path($dn,$ldap_base_dn,$icon);
					echo "<p>Unable to delete record</p>";

					echo "<a href=\"" . $return_page_if_not_deleted
						. "\">Return to the Address Book</a>\n";
				}
			}
		}
		else
		{
			show_site_header();
			show_ldap_path($dn,$ldap_base_dn,$icon);
			echo "<p>You do not have permission to delete this record</p>"
				. "<p><a href=\"" . $return_page_if_not_deleted
				. "\">Return to the Address Book</a></p>";
		}
	}
	else
	{
		show_site_header();
		show_ldap_path($dn,$ldap_base_dn,"contact24.png");
		echo "<p>Unable to locate LDAP record</p>"
			. "<p><a href=\"" . $return_page_if_not_deleted
			. "\">Return to the Address Book</a></p>";
	}
}
else
{
	show_site_header();
        show_ldap_bind_error();
}
show_site_footer();
?>
