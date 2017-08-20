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
	if($ldap_server->log_on())
	{
		// TODO: sanitise base DN from URL:
		//	stop "nasties" being passed through to the LDAP server

		if(isset($_GET["dn"]) && strlen($_GET["dn"])<=MAX_DN_LENGTH
				&& ($ldap_server->compare_dn_to_base($_GET["dn"],$ldap_server->base_dn)
				|| get_user_setting("allow_system_admin")))
			$dn = $_GET["dn"];
		else
			$dn = $ldap_server->base_dn;

		// Default filter expression to use if none specified in config
		// file: return only people in search results
		if(empty($search_ldap_filter))
			$search_ldap_filter
				= "(&(objectClass=person)___search_criteria___)";

		$search_resource = false;

		if(!empty($_GET["filter"]))
		{
			$filter = ldap_escape($_GET["filter"],null,LDAP_ESCAPE_FILTER);

			if(!isset($search_method)) $search_method = 1;

			switch($search_method)
			{
				case 2: // match start of string
					$attribute_search_template = "(___search_attrib___=___search_value___*)";
					break;
				case 1:	// match anywhere in string
				default:
					$attribute_search_template = "(___search_attrib___=*___search_value___*)";
					break;
			}

			$search_criteria = "";
			foreach($search_ldap_attrib as $attrib)
				$search_criteria .= str_replace("___search_value___",$filter,
					str_replace("___search_attrib___",$attrib,
					$attribute_search_template));

			$filter = str_replace("___search_criteria___",
				"(|" . $search_criteria . ")",$search_ldap_filter);

			$search_type= "subtree";
		}
		else if(get_user_setting("front_page_search_filter") && $dn == $ldap_server->base_dn)
		{
			$filter = str_replace("___search_criteria___",
				"(objectClass=*)",get_user_setting("front_page_search_filter"));
			$search_type= "subtree";
		}
		else
		{
			if(empty($_GET["vcard"]))
			{
				// Default filter expression to use if none specified in config
				// file: display objects of all classes when browsing the directory
				$filter = empty($browse_ldap_filter)
					?"objectClass=*":$browse_ldap_filter;
				$search_type= "base";
			}
			else
			{
				// vCard export settings
				$filter = empty($search_ldap_filter)
					?"objectClass=person":$search_ldap_filter;

				$filter = str_replace("___search_criteria___",
					"",$search_ldap_filter);

				$search_type = "subtree";
			}
		}

		if(empty($_GET["vcard"]))
		{
			show_ldap_path($dn);

			if(get_user_setting("allow_search") && get_user_setting("allow_login"))
				if(!empty($_GET["filter"]))
					show_search_box($_GET["filter"]);
				else
					show_search_box("");
		}

		if($search_type == "subtree")
			// get search results
			if(get_user_setting("allow_search"))
				$search_resource = @ldap_search($ldap_server->connection,
					$dn,$filter);
			else
				echo "<p>" . gettext("You do not have permission to search the directory") . "</p>\n";
		else
			// browse OU contents
			if(get_user_setting("allow_browse"))
				$search_resource = @ldap_list($ldap_server->connection,
					$dn,$filter);
			else
			{
				// only show error if explicit base DN browse attempt
				if(empty($_GET["dn"]))
				{
					if(get_user_setting("allow_search"))
					{
						echo "<p>" . gettext("Please enter the text you want to look up in the Address Book.") . "</p>";
						echo "<p>" . gettext("You can search for text in any of the following fields:") . "</p>";

		                                show_searchable_attributes();
					}
					else
					{
						if($ldap_server->per_user_login_enabled())
							echo "<p><a href=\"user.php\">"
								. gettext("Please log in to use the address book.") . "</a></p>\n";
						else
							echo "<p>" . gettext("You do not have permission to access the directory") . "</p>\n";
					}
				}
				else
					echo "<p>" . gettext("You do not have permission to browse the directory") . "</p>\n";
			}

		if($ldap_server->server_type == "openldap" && strcasecmp($dn,"cn=config")==0)
		{
			$search_result_columns = array(
				array("caption"=>gettext("OpenLDAP Server Configuration"),
				"attrib"=>"sortableName","link_type"=>"object"));
		}

		// Display search resource info if successfully fetched
		if(is_resource($search_resource))
		{
			if(!empty($_GET["sort"]))
				$sort_type = $_GET["sort"];
			else
				$sort_type = $search_result_default_sort_order;

			// Only allow sorting on attributes which are actualy used in columns
			$sort_order=$search_result_default_sort_order;
			foreach($search_result_columns as $column)
				if($column["attrib"] == $sort_type);
					$sort_order = $sort_type;

			$entry_list = new ldap_entry_list($search_result_columns);
			$entry_list->add_entries($ldap_server,$search_resource);

			$entry_list->sort($sort_order);

			if(!empty($_GET["filter"]))
				$entry_list->contains_search_results=true;

			if(empty($_GET["vcard"]))
			{
				// extra space between LDAP path and results if no search box
				if(!get_user_setting("allow_search"))
					echo "<br>";

				$entry_list->show();
			}
			else
				$entry_list->save_vcard($dn);
		}

		echo "\n";

		if(empty($_GET["vcard"]))
		{
			$buttons = "";

			if(empty($_GET["filter"]) && get_user_setting("allow_folder_info")
					&& get_user_setting("allow_browse") && get_user_setting("allow_view"))
				$buttons .= "<a href=\"info.php?dn="
					. urlencode($dn)
						. "\"><button>" . gettext("Folder Details") . "</button></a>\n";

			if(get_user_setting("allow_create"))
				$buttons .= "<a href=\"create.php?dn="
					. urlencode($dn)
					. "\"><button>" . gettext("New Record") . "</button></a>\n";

			if(get_user_setting("allow_export_bulk") && empty($_GET["filter"]))
				$buttons .= "<a href=\"index.php?vcard=1&dn="
					. urlencode($dn)
					. "\"><button>" . gettext("Export Records") . "</button></a>\n";

			if(!empty($buttons))
				echo "<hr class=\"button_separator_line\">\n" . $buttons;

		}
	}
	else
		show_ldap_bind_error();
}

if(empty($_GET["vcard"]))
	show_site_footer();
?>
