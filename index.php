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
	$dn = $ldap_base_dn;

	if(!empty($_GET["filter"]))
	{
		$filter = ldap_escape($_GET["filter"],null,LDAP_ESCAPE_FILTER);

		$search_criteria = "";
		foreach($search_ldap_attrib as $attrib)
			$search_criteria .= "(" . $attrib . "=" . $filter . "*)";

		// Default filter expression to use if none specified in config
		// file: return only people in search results
		if(empty($search_ldap_filter))
			$search_ldap_filter
				= "(&(objectClass=person)___search_criteria___)";

		$filter = str_replace("___search_criteria___",
			"(|" . $search_criteria . ")",$search_ldap_filter);

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
		// TODO: sanitise base DN from URL:
		//	stop "nasties" being passed through to the LDAP server
		//	prevent access to directory outside of address book base DN

		if(!empty($_GET["dn"]) && strlen($_GET["dn"])<=MAX_DN_LENGTH && $ldap_server->compare_dn_to_base($dn,$ldap_base_dn))
			$dn = $_GET["dn"];
		else
			$dn = $ldap_base_dn;
	}

	if(empty($_GET["vcard"]))
		show_ldap_path($dn,$ldap_base_dn,"schema/folder.png");

	$search_resource = false;

	if($ldap_server->log_on())
	{
		if(empty($_GET["vcard"]))
			if(get_user_setting("allow_search") && get_user_setting("ldap_name")!="__DENY__")
				if(!empty($_GET["filter"]))
					show_search_box($_GET["filter"]);
				else
					show_search_box("");

		if($search_type == "subtree")
			// get search results
			if(get_user_setting("allow_search"))
				$search_resource = @ldap_search($ldap_server->connection,
					$dn,$filter);
			else
				echo "<p>You do not have permission to search the directory</p>\n";
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
					if(!get_user_setting("allow_search"))
					{
						if($ldap_server->per_user_login_enabled())
							echo "<p><a href=\"user.php\">"
								. "Please log in to use the address book.</a></p>\n";
						else
							echo "<p>You do not have permission to access the directory</p>\n";
					}
				}
				else
					echo "<p>You do not have permission to browse the directory</p>\n";
			}
	}
	else
		show_ldap_bind_error();

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

		$entry_list = new ldap_entry_list($ldap_server,$search_resource,
			$search_result_columns,$sort_order);

		if(empty($_GET["vcard"]))
		{
			// extra space between LDAP path and results if no search box
			if(!get_user_setting("allow_search"))
				echo "<br>";

			$entry_list->show();
		}
		else
		{
			if($dn == $ldap_base_dn)
				$filename = $site_name;
			else
			{
				$rdn_list = ldap_explode_dn2($dn);
				$filename = $rdn_list[0]["value"];
			}

			header("Content-Type: text/x-vcard");
			header("Content-Disposition: attachment; filename=\""
				. $filename . ".vcf\"");
			$entry_list->save_vcard();
		}
	}

	echo "\n";
}

if(empty($_GET["vcard"]))
{
	$buttons = "";

	if(get_user_setting("allow_folder_info"))
		$buttons .= "<a href=\"info.php?dn="
			. htmlentities($dn,ENT_COMPAT,"UTF-8")
				. "\"><button>Folder Details</button></a>\n";

	if(get_user_setting("allow_create"))
		$buttons .= "<a href=\"create.php?dn="
			. htmlentities($dn,ENT_COMPAT,"UTF-8")
			. "\"><button>New Record</button></a>\n";

	if(get_user_setting("allow_export_bulk") && empty($_GET["filter"]))
		$buttons .= "<a href=\"index.php?vcard=1&dn="
			. htmlentities($dn,ENT_COMPAT,"UTF-8")
			. "\"><button>Export Records</button></a>\n";

	if(!empty($buttons))
		echo "<hr>\n" . $buttons;

	show_site_footer();
}
?>
