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

if(!empty($_GET["server_id"]) && is_numeric($_GET["server_id"]))
	$server_id = $_GET["server_id"];
else
	$server_id=0;

$server_id_str = $server_id == 0 ? "" : ("&server_id=" . $server_id);

// if successful delete then should take the user back to displaying parent
// container object afterwards.
$rdn_list = ldap_explode_dn2($dn);
if(isset($rdn_list[1]["dn"]))
	$return_page_if_deleted = "index.php?dn=" . urlencode($rdn_list[1]["dn"]) . $server_id_str;
else
	$return_page_if_deleted = "index.php";

// Determine where to return to if delete was cancelled or failed
if(isset($_GET["page"]) && $_GET["page"] == "info")
	$return_page_if_not_deleted = "info.php?dn=" . urlencode($dn) . $server_id_str;
else
	if(isset($rdn_list[1]["dn"]))
		$return_page_if_not_deleted = "index.php?dn=" . urlencode($rdn_list[1]["dn"]) . $server_id_str;
	else
		$return_page_if_not_deleted = "index.php";

if($ldap_server_list[$server_id]->log_on())
{
	// check record exists before offering to delete it
	$search_resource = @ldap_read($ldap_server_list[$server_id]->connection,$dn,"(objectclass=*)");

	if($search_resource)
	{
		if(get_user_setting("allow_delete"))
		{
			if(empty($_GET["confirm"]))
			{
				show_site_header();
				show_ldap_path($dn);

				echo "<p>" . gettext("Are you sure you want to delete this record?") . "</p>\n";

				echo "<a href=\"delete.php?dn=" . urlencode($dn) . $server_id_str;
				echo "&confirm=yes\"><button>" . gettext("Yes") . "</button></a>\n";

				echo "<a href=\"" . $return_page_if_not_deleted
					. "\"><button>" . gettext("No") . "</button></a>\n";
			}
			else
			{
				if(@ldap_delete($ldap_server_list[$server_id]->connection,$dn))
					header("Location: " . $return_page_if_deleted);
				else
				{
					$error=ldap_error($ldap_server_list[$server_id]->connection);

					show_site_header();
					show_ldap_path($dn);

					echo "<p>" . gettext("Unable to delete record") . ": " . $error . "</p>";

					echo "<a href=\"" . $return_page_if_not_deleted
						. "\">" . gettext("Return to the Address Book") . "</a>\n";
				}
			}
		}
		else
		{
			show_site_header();
			show_ldap_path($dn);
			echo "<p>" . gettext("You do not have permission to delete this record") . "</p>"
				. "<p><a href=\"" . $return_page_if_not_deleted
				. "\">" . gettext("Return to the Address Book") . "</a></p>";
		}
	}
	else
	{
		show_site_header();
		show_ldap_path($dn);
		echo "<p>" . gettext("Unable to locate LDAP record") . "</p>"
			. "<p><a href=\"" . $return_page_if_not_deleted
			. "\">" . gettext("Return to the Address Book") . "</a></p>";
	}
}
else
{
	show_site_header();
	show_ldap_bind_error();
}
show_site_footer();
?>
