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

$dn = $ldap_base_dn;

if(!empty($_GET["filter"]))
{
	$filter = ldap_escape_search_value($_GET["filter"]);

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
	// Default filter expression to use if none specified in config
	// file: display objects of all classes when browsing the directory
	$filter = empty($browse_ldap_filter)
		?"objectClass=*":$browse_ldap_filter;
	$search_type= "base";

	// TODO: sanitise base DN from URL:
	//	stop "nasties" being passed through to the LDAP server
	//	prevent access to directory outside of address book base DN
	if(!empty($_GET["dn"])) $dn = $_GET["dn"];
}

show_ldap_path($dn,$ldap_base_dn,"folder.png");

if(empty($ldap_server_type))	// Default server type: Active Directory
	$ldap_server_type = "ad";

$user_info = get_user_info();

if($user_info["allow_search"] && $user_info["ldap_name"]!="__DENY__")
	if(!empty($_GET["filter"]))
		show_search_box($_GET["filter"]);
	else
		show_search_box("");
else
	echo "<br>";

$search_resource = false;

if(log_on_to_directory($ldap_link))
{
	$old_error_reporting=error_reporting();
	error_reporting(0);

	if($search_type == "subtree")
		// get search results
		if($user_info["allow_search"])
			$search_resource = ldap_search($ldap_link,
				$dn,$filter);
		else
			echo "<p>You do not have permission to search the directory</p>\n";
	else
		// browse OU contents
		if($user_info["allow_browse"])
			$search_resource = ldap_list($ldap_link,$dn,$filter);
		else
			// only show error if explicit base DN browse attempt
			if (!empty($_GET["dn"]))
				echo "<p>You do not have permission to browse the directory</p>\n";

	error_reporting($old_error_reporting);
}
else
	show_ldap_bind_error();

// Display search resource info if successfully fetched
if($search_resource)
{
	$object_class_schema = get_object_class_schema($ldap_server_type);

	if(!empty($_GET["sort"]))
		$sort_type = $_GET["sort"];
	else
		$sort_type = $search_result_default_sort_order;

	// Only allow sorting on attributes which are actualy used in columns
	$sort_order=$search_result_default_sort_order;
	foreach($search_result_columns as $column)
		if($column["attrib"] == $sort_type);
			$sort_order = $sort_type;

	$entry_list = new ldap_entry_list($search_resource,
		$search_result_columns,$sort_order);
	$entry_list->show();
}

echo "\n";

show_site_footer();
?>
