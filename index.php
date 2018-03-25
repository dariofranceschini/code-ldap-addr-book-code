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
	if(!empty($_GET["server_id"]) && is_numeric($_GET["server_id"]))
		$server_id = $_GET["server_id"];
	else
		$server_id=0;

	if($ldap_server_list[$server_id]->log_on())
	{
		// TODO: sanitise base DN from URL:
		//	stop "nasties" being passed through to the LDAP server

		if(isset($_GET["dn"]) && strlen($_GET["dn"])<=MAX_DN_LENGTH
				&& $ldap_server_list[$server_id]->dn_user_access_allowed($_GET["dn"]))
			$dn = $_GET["dn"];
		else
			$dn = $ldap_server_list[$server_id]->base_dn;

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
		else if($ldap_server_list[$server_id]->get_user_setting("front_page_search_filter") && $dn == $ldap_server_list[$server_id]->base_dn)
		{
			$filter = str_replace("___search_criteria___",
				"(objectClass=*)",$ldap_server_list[$server_id]->get_user_setting("front_page_search_filter"));
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
			show_ldap_path($ldap_server_list[$server_id],$dn);

			if($ldap_server_list[$server_id]->get_user_setting("allow_search") && $ldap_server_list[$server_id]->get_user_setting("allow_login"))
				if(!empty($_GET["filter"]))
					show_search_box($_GET["filter"]);
				else
					show_search_box("");
		}

		if($search_type == "subtree")
			// get search results
			if($ldap_server_list[$server_id]->get_user_setting("allow_search"))
				$search_resource = @ldap_search($ldap_server_list[$server_id]->connection,
					$ldap_server_list[$server_id]->base_dn,$filter);
			else
				echo "<p>" . gettext("You do not have permission to search the directory") . "</p>\n";
		else
			// browse OU contents
			if($ldap_server_list[$server_id]->get_user_setting("allow_browse"))
				$search_resource = @ldap_list($ldap_server_list[$server_id]->connection,
					$dn,$filter);
			else
			{
				// only show error if explicit base DN browse attempt
				if(empty($_GET["dn"]))
				{
					if($ldap_server_list[$server_id]->get_user_setting("allow_search"))
					{
						echo "<p>" . gettext("Please enter the text you want to look up in the Address Book.") . "</p>";
						echo "<p>" . gettext("You can search for text in any of the following fields:") . "</p>";

		                                show_searchable_attributes();
					}
					else
					{
						if($ldap_server_list[$server_id]->per_user_login_enabled())
							echo "<p><a href=\"user.php\">"
								. gettext("Please log in to use the address book.") . "</a></p>\n";
						else
							echo "<p>" . gettext("You do not have permission to access the directory") . "</p>\n";
					}
				}
				else
					echo "<p>" . gettext("You do not have permission to browse the directory") . "</p>\n";
			}

		if($ldap_server_list[$server_id]->server_type == "openldap" && strcasecmp($dn,"cn=config")==0)
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
			$entry_list->add_entries($ldap_server_list[$server_id],$search_resource);

			if(!empty($_GET["filter"]))
			{
				foreach($ldap_server_list as $ldap_server_to_search)
				{
					if($ldap_server_to_search->server_id != $server_id)
					{
						if($ldap_server_to_search->log_on()
							&& $ldap_server_to_search->get_user_setting("allow_search"))
						{
							$link_search_resource = @ldap_search($ldap_server_to_search->connection,
								$ldap_server_to_search->base_dn,$filter);

							$entry_list->add_entries($ldap_server_to_search,$link_search_resource);
						}
					}
				}
			}
			else
			{
				// add any linked servers/DNs (browsing)
				foreach($linked_ldap_dn_list as $linked_ldap_dn)
				{
					if($linked_ldap_dn["source_server"]==$server_id && !strcasecmp($dn,$linked_ldap_dn["source_dn"]))
					{
						// only show records if user has allow_browse permission for the destination
						if($ldap_server_list[$linked_ldap_dn["destination_server"]]->get_user_setting("allow_browse"))
						{
							if($ldap_server_list[$linked_ldap_dn["destination_server"]]->log_on())
							{
								switch($linked_ldap_dn["link_method"])
								{
									case "folder":
										$link_search_resource = @ldap_read(
											$ldap_server_list[$linked_ldap_dn["destination_server"]]->connection,
											$linked_ldap_dn["destination_dn"],$filter);

										if(ldap_errno($ldap_server_list[$linked_ldap_dn["destination_server"]]->connection)==LDAP_SUCCESS)
										{
											$entry_list->add_entries($ldap_server_list[$linked_ldap_dn["destination_server"]],
												$link_search_resource);

											if(!is_null($linked_ldap_dn["object_class"]))
											{
												$ndx = $entry_list->ldap_entries["count"];
												$entry_list->ldap_entries[($entry_list->ldap_entries["count"])-1]["objectclass"]
													=array($linked_ldap_dn["object_class"]);

												fix_missing_object_classes($ldap_server_list[$linked_ldap_dn["destination_server"]],
													$entry_list->ldap_entries[($entry_list->ldap_entries["count"])-1]);
											}

											if(!is_null($linked_ldap_dn["name"]))
											{
												$ndx = $entry_list->ldap_entries["count"];

												$link_object_class = $ldap_server_list[$linked_ldap_dn["destination_server"]]->get_object_class(
													$entry_list->ldap_entries[($entry_list->ldap_entries["count"])-1]);

												$link_object_rdn_attrib = explode(",",
													$ldap_server_list[$linked_ldap_dn["destination_server"]]->get_object_schema_setting(
													$link_object_class,"rdn_attrib"));

												foreach($link_object_rdn_attrib as $link_object_rdn)
													$entry_list->ldap_entries[($entry_list->ldap_entries["count"])-1][$link_object_rdn][0]
														=$linked_ldap_dn["name"];
											}
										}
										else
										{
											$error_ldap_entry = array(array(
												"dn"=>$linked_ldap_dn["destination_dn"],
												"objectclass"=>array(0=>"___ldap_error___"),
												"displayname"=>array(0=>"Linked DN: "
												. ldap_error($ldap_server_list[$linked_ldap_dn["destination_server"]]->connection)),
												"count"=>3
												),"count"=>1);

											$entry_list->add_entries($ldap_server_list[$linked_ldap_dn["destination_server"]],
												$error_ldap_entry);
										}

										break;
									case "contents":
									default:
										$link_search_resource = @ldap_list(
											$ldap_server_list[$linked_ldap_dn["destination_server"]]->connection,
											$linked_ldap_dn["destination_dn"],$filter);

										if(ldap_errno($ldap_server_list[$linked_ldap_dn["destination_server"]]->connection)==LDAP_SUCCESS)
										{
											$entry_list->add_entries($ldap_server_list[$linked_ldap_dn["destination_server"]],
												$link_search_resource);
										}
										else
										{
											$error_ldap_entry = array(array(
												"dn"=>$linked_ldap_dn["destination_dn"],
												"objectclass"=>array(0=>"___ldap_error___"),
												"displayname"=>array(0=>"Linked DN: "
												. ldap_error($ldap_server_list[$linked_ldap_dn["destination_server"]]->connection)),
												"count"=>3
												),"count"=>1);

											$entry_list->add_entries($ldap_server_list[$linked_ldap_dn["destination_server"]],
												$error_ldap_entry);
										}
								}
							}
							else
							{
								$error_ldap_entry = array(array(
									"dn"=>"cn=___error___",
									"objectclass"=>array(0=>"___ldap_error___"),
									"displayname"=>array(0=>"Linked DN: "
									. ldap_error($ldap_server_list[$linked_ldap_dn["destination_server"]]->connection)),
									"count"=>3
									),"count"=>1);

								$entry_list->add_entries($ldap_server_list[$linked_ldap_dn["destination_server"]],
									$error_ldap_entry);
							}
						}
						else
						{
							// show dummy record - item not accessible
							$error_ldap_entry = array(array(
								"dn"=>"cn=___error___",
								"objectclass"=>array(0=>"___ldap_error___"),
								"displayname"=>array(0=>"Linked DN: "
								. "No read access to " . $ldap_server_list[$linked_ldap_dn["destination_server"]]->host_or_url),
								"count"=>3
								),"count"=>1);

							$entry_list->add_entries($ldap_server_list[$linked_ldap_dn["destination_server"]],
								$error_ldap_entry);
						}
					}
				}
			}

			$entry_list->sort($sort_order);

			if(!empty($_GET["filter"]))
				$entry_list->contains_search_results=true;

			if(empty($_GET["vcard"]))
			{
				// extra space between LDAP path and results if no search box
				if(!$ldap_server_list[$server_id]->get_user_setting("allow_search"))
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

			$server_id_str = $server_id==0 ? "" : ("&server_id=" . $server_id);

			if(empty($_GET["filter"]) && $ldap_server_list[$server_id]->get_user_setting("allow_folder_info")
					&& $ldap_server_list[$server_id]->get_user_setting("allow_browse") && $ldap_server_list[$server_id]->get_user_setting("allow_view"))
				$buttons .= "<a href=\"info.php?dn="
					. urlencode($dn) . $server_id_str
						. "\"><button>" . gettext("Folder Details") . "</button></a>\n";

			if($ldap_server_list[$server_id]->get_user_setting("allow_create"))
				$buttons .= "<a href=\"create.php?dn="
					. urlencode($dn) . $server_id_str
					. "\"><button>" . gettext("New Record") . "</button></a>\n";

			if($ldap_server_list[$server_id]->get_user_setting("allow_export_bulk") && empty($_GET["filter"]))
				$buttons .= "<a href=\"index.php?vcard=1&dn="
					. urlencode($dn) . $server_id_str
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
