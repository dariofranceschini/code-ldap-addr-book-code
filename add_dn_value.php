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
	// DN of object that will be written to
	if(isset($_GET["target_dn"]) && strlen($_GET["target_dn"])<=MAX_DN_LENGTH)
		$target_dn = $_GET["target_dn"];
	else
	{
		show_site_header();
		show_error_message(sprintf(gettext("Unable to add DN value to attribute: %s"),
			gettext("Target object DN is missing")));
	}

	// DN being displayed
	if(isset($_GET["dn"]) && strlen($_GET["dn"])<=MAX_DN_LENGTH)
		$dn = $_GET["dn"];
	else
		$dn = get_parent_dn($target_dn);

	if(!$ldap_server->compare_dn_to_base($dn,$ldap_server->base_dn) && !get_user_setting("allow_system_admin"))
		$dn = $ldap_server->base_dn;

	if(isset($_GET["attrib"]))
		$attrib = $_GET["attrib"];
	else
	{
		show_site_header();
		show_error_message(sprintf(gettext("Unable to add DN value to attribute: %s"),
			gettext("No attribute specified")));
	}
}

if($ldap_server->log_on())
{
	if(get_user_setting("allow_browse"))
	{
		$confirm= isset($_GET["confirm"]) && $_GET["confirm"]=="yes";

		if($confirm)
		{
			if(get_user_setting("allow_edit"))
			{
				$new_value[$attrib] = $dn;

				$result = @ldap_mod_add($ldap_server->connection,
					$target_dn,$new_value);
				$error = ldap_error($ldap_server->connection);
				if($result)
				{
					$search_resource = @ldap_read($ldap_server->connection,
						$target_dn,$browse_ldap_filter,array("*","+"));
					if($search_resource)
					{
						$entry = ldap_get_entries($ldap_server->connection,
							$search_resource);
						$ldap_server->call_schema_function("after_add_"
							. $ldap_server->get_object_class($entry[0])
							. "_" . $attrib,$entry[0]);

						header("Location: info.php?dn="
							. urlencode($target_dn));
					}
					else
						show_error_message(gettext("Unable to access LDAP object."));
				}
				else
				{
					show_site_header();
					show_ldap_path($target_dn);

					echo "<p>" . gettext("Error whilst setting attribute") . " '"
                	                                . $attrib . "': " . $error . "</p>";

				        echo  "<p>\n  <a href=\"info.php?dn=" . urlencode($target_dn)
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
			show_ldap_path($target_dn);

			if(empty($target_dn))
				$entry_name = "rootDSE";
			else
			{
				$target_dn_array = ldap_explode_dn2($target_dn);
				$entry_name = $target_dn_array[0]["value"];
			}

			if(isset($_GET["dn"]))
				if($dn == $ldap_server->base_dn)
					echo "<p>" . gettext("You are currently viewing the top level of the Address Book") . "</p>";
				else if($dn == "")
					echo "<p>" . gettext("You are currently viewing the root of the directory") . "</p>";
				else
				{
					$dn_exploded = ldap_explode_dn2($dn);
					echo "<p>" . sprintf(gettext("You are currently viewing the folder '%s'"),$dn_exploded[0]["value"]) . "</p>";
				}

			echo "<p style=\"font-weight:bold\">"
				. sprintf(gettext("Select an object to assign to the '%s' attribute of '%s':"),
				$attrib,$entry_name) . "</p><hr>";

			// Default filter expression to use if none specified in config
        	        // file: display objects of all classes when browsing the directory
                	$filter = empty($browse_ldap_filter)
				?"objectClass=*":$browse_ldap_filter;

			$search_resource = @ldap_list($ldap_server->connection,
				$dn,$filter);

			if(is_resource($search_resource))
			{
				// TODO: allow user to change sort order
				$sort_order=$search_result_default_sort_order;

				$entry_list = new ldap_entry_list(
					array(array("caption"=>"Objects","attrib"=>"sortableName","link_type"=>"add_dn_value")));

				$entry_list->add_entries($ldap_server,$search_resource);

				$entry_list->sort($sort_order);

				$parent_dn = get_parent_dn($dn);

				if($dn != "" && (get_user_setting("allow_system_admin")
					|| $ldap_server->compare_dn_to_base($parent_dn,
					$ldap_server->base_dn)))
				{
					$parent_dn_exploded = ldap_explode_dn2($parent_dn);

					if($parent_dn == $ldap_server->base_dn)
						$go_to_parent_message = gettext("Go up a level to the top level of the Address Book");
					else if($parent_dn == "")
						$go_to_parent_message = gettext("Go up a level to the root of the directory");
					else
						$go_to_parent_message = sprintf(gettext("Go up a level to the '%s' folder"),$parent_dn_exploded[0]["value"]);

					array_unshift($entry_list->ldap_entries,array(
						"objectclass"=>array(0=>"organizationalUnit"),
						"ou"=>array(0=>$go_to_parent_message),
						"dn"=>$parent_dn,
						"SERVER"=>$ldap_server
						));
					$entry_list->ldap_entries["count"]++;
				}

				$entry_list->object_dn_select_mode = true;
				$entry_list->show();
			}

			echo "<hr><a href=\"info.php?dn=" . urlencode($target_dn)
				. "\"><button>" . gettext("Cancel") . "</button></a>";

			show_site_footer();
		}
	}
	else
		show_error_message(gettext("You do not have permission to browse the directory to select an object"));
}
else
	 show_ldap_bind_error();
?>
