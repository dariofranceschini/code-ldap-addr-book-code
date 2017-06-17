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

show_site_header();

// TODO: guard against nasties in the DN
$dn = $_GET["dn"];

if($ldap_server->log_on())
{
	// Update record

	if(get_user_setting("allow_edit")
		|| (get_user_setting("allow_edit_self")
		&& !strcasecmp($_SESSION["LOGIN_BIND_DN"],$dn)))
	{
		$create_failed = false;

		$change_list = "";

		// Add the RDN attribute to $dn (which refers to the container)
		// when creating a new entry.
		if(!empty($_POST["create"]))
		{
			// TODO: guard against nasties in the object class list
			$entry["objectclass"] = explode(",",$_POST["create"]);

			// Add schema-specified auxiliary classes
			$auxiliary_classes_from_schema
				= $ldap_server->get_object_schema_setting($entry["objectclass"][0],"extensions");

			if(!empty($auxiliary_classes_from_schema))
				$entry["objectclass"] = array_merge($entry["objectclass"],
					explode(",",$auxiliary_classes_from_schema));

			// Add user-specified auxiliary classes
			// TODO: guard against nasties in the object class list
			if(isset($_POST["add_aux_class"]) && get_user_setting("allow_extend"))
				$entry["objectclass"] = array_merge($entry["objectclass"],
					explode(",",$_POST["add_aux_class"]));

			fix_missing_object_classes($ldap_server,$entry);

			/** @todo take into account RDN attributes of all object classes
			    and support entry-specific RDNs that deviate from schema default */
			$rdn_attribs = explode(",",$ldap_server->get_object_schema_setting(
				$entry["objectclass"][0],"rdn_attrib"));

			// Allow for blank RDN attribute - e.g. if populated
			// programatically by schema-specific function (called below)

			$rdn="";
			foreach($rdn_attribs as $rdn_attrib)
			{
				if(!empty($rdn)) $rdn .= "+";

				$rdn .= $rdn_attrib . "=";
				if(isset($_POST["ldap_attribute_" . $rdn_attrib]))
					$rdn .= ldap_escape($_POST["ldap_attribute_" . $rdn_attrib],null,LDAP_ESCAPE_DN);
			}
			$dn = $rdn . (empty($dn) ? "" : "," . $dn);
		}
		else
			// Add auxiliary classes to existing object

			if(isset($_POST["add_aux_class"]) && get_user_setting("allow_extend"))
			{
				$search_resource = @ldap_read($ldap_server->connection,$dn,"(objectclass=*)");

				$entry = ldap_get_entries($ldap_server->connection,$search_resource);

				// TODO: guard against nasties!
				$add_aux_class = explode(",",$_POST["add_aux_class"]);

				if(isset($entry[0]["objectclass"]["count"]))
					unset($entry[0]["objectclass"]["count"]);

				$updates["objectclass"] = array_values(array_unique(array_merge(
					$entry[0]["objectclass"],$add_aux_class)));

				$required_attribs = get_required_attribs($updates);

				// TODO: guard against nasties in each attribute values
				// TODO: canonicalise attrib names to handle aliases (e.g. uid vs. userid)
				$add_aux_change_list = "";
				foreach($required_attribs as $attrib)
				{
					if(!empty($required_attribs[0])
						&& isset($_POST["ldap_attribute_"
						. $attrib]) && !isset($entry[0][$attrib]))
					{
						$updates[$attrib] = $_POST["ldap_attribute_"
							. $attrib];
						$add_aux_change_list .= "  <li>"
							. sprintf(gettext("Set attribute '%s' to '%s' %s(required attribute)%s"),
							$attrib,htmlentities($updates[$attrib],ENT_COMPAT,"UTF-8"),
							"<span style=\"font-style:italic\">","</span>") . "</li>\n";
					}
				}

				$result = @ldap_modify($ldap_server->connection,$dn,$updates);

				$aux_class_list = "";
				$aux_classes_listed = 0;

				foreach($add_aux_class as $aux)
				{
					if($aux_classes_listed > 0)
					{
						if($aux_classes_listed == count($add_aux_class)-1)
							$aux_class_list .= " " . gettext("and") . " ";
						else
							$aux_class_list .= ", ";
					}

					$aux_class_list .= "'" . $aux . "'";
					$aux_classes_listed++;
				}

				if($result)
				{
					$change_list .= "  <li>"
						. sprintf(ngettext("Added auxiliary class %s to the record",
						"Added auxiliary classes %s to the record",count($add_aux_class)),
						htmlentities($aux_class_list,ENT_COMPAT,"UTF-8")) . "</li>\n";

					if(!empty($add_aux_change_list))
						$change_list .= $add_aux_change_list;
				}
				else
					$change_list .= "  <li>"
						. sprintf(ngettext("Unable to add auxiliary class %s:",
						"Unable to add auxiliary classes %s:",count($add_aux_class)),
						htmlentities($aux_class_list,ENT_COMPAT,"UTF-8"))
						. " " . ldap_error($ldap_server->connection) . "</li>\n";
			}

		if(!empty($_POST["create"]))
		{
		        if(!get_user_setting("allow_create"))
                		show_error_message(gettext("You do not have permission to create new records."));

			$search_resource = @ldap_read($ldap_server->connection,$dn,"(objectclass=*)");

			if($search_resource)
			{
				show_error_message(gettext("Unable to create - A record already exists with this name."));
				$create_failed = true;
			}
			else
			{
				// check request is for a valid creatable object class before
				// attempting ldap_add()
				if($ldap_server->get_object_schema_setting($entry["objectclass"][0],
					"can_create") || get_user_setting("allow_system_admin"))
				{
					if($ldap_server->get_object_schema_setting($entry["objectclass"][0],"create_method","normal")=="atomic")
					{
						// Include every attribute which appears in the display layout
						$required_attribs=array();
						$entry_layout = $ldap_server->get_display_layout($entry["objectclass"][0]);

						// Include attributes from each auxiliary class display layout
						// TODO: only do it if auto-add aux layout setting is configured
						add_auxiliary_layouts($entry,$entry_layout);

						foreach($entry_layout as $section)
							foreach($section["attributes"] as $attrib_spec)
								if(!isset($attrib_spec["allow_edit"]) || $attrib_spec["allow_edit"])
									foreach(explode(":",$attrib_spec[0]) as $attribute_line)
										foreach(explode("+",$attribute_line) as $attrib)
											$required_attribs[] = $attrib;
					}
					else
						$required_attribs = get_required_attribs($entry);

					$create_attrib_change_list = "";
					// TODO: guard against nasties in attribute value
					foreach($required_attribs as $attrib)
						if(!empty($required_attribs[0])
								&& isset($_POST["ldap_attribute_"
								. $attrib]))
					{
							$entry[$attrib] = $_POST["ldap_attribute_"
								. $attrib];

							$create_attrib_change_list .= "  <li>"
								. sprintf(gettext("Set attribute '%s' to '%s' %s(required attribute)%s"),
								$attrib,htmlentities($_POST["ldap_attribute_" . $attrib],
								ENT_COMPAT,"UTF-8"),"<span style=\"font-style:italic\">","</span>")
								. "</li>\n";
					}

					// Allow schema function to see/modify the DN to be created
					$entry["dn"] = $dn;

					$ldap_server->call_schema_function("before_create_" . $entry["objectclass"][0],$entry);

					// Update the DN to be created (if modified by the schema function)
					$dn = $entry["dn"];
					unset($entry["dn"],$entry["objectclass"]["count"]);

					$name_of_object_created = ldap_explode_dn2($dn);
					$name_of_object_created = $name_of_object_created[0]["value"];

					$result = @ldap_add($ldap_server->connection,$dn,$entry);

					if($result)
					{
						$change_list = "  <li>"
							. sprintf(gettext("New '%s' record created: '%s'"),
							htmlentities($_POST["create"],ENT_COMPAT,"UTF-8"),
							htmlentities($name_of_object_created,ENT_COMPAT,"UTF-8"))
							. "</li>\n";

						if(!empty($create_attrib_change_list))
							$change_list .= $create_attrib_change_list;
					}
					else
					{
						$create_failed = true;
						show_error_message(gettext("Unable to create LDAP record: ")
							. ldap_error($ldap_server->connection));
					}
				}
				else
				{
					$create_failed = true;
					show_error_message(gettext("Unable to create this type of object."));
				}
			}
		}

		if($create_failed == false)
		{
			$search_resource = @ldap_read($ldap_server->connection,$dn,"(objectclass=*)");

			if($search_resource)
			{
				$entry = ldap_get_entries($ldap_server->connection,$search_resource);

				if(!empty($_POST["create"]))
				{
					$ldap_server->call_schema_function("after_create_"
						. $ldap_server->get_object_class($entry[0]),$entry[0]);
				}

				/** @todo enhance to support entries with RDN attributes other than the
				    Address Book schema default for that class */

				$rdn_attribs = explode(",",$ldap_server->get_object_schema_setting(
					$entry[0]["objectclass"][0],
					"rdn_attrib"));

				$entry_layout = $ldap_server->get_display_layout(
					$ldap_server->get_object_class($entry[0]));

				// Include attributes from each auxiliary class display layout
				// TODO: only do it if auto-add aux layout setting is configured
				add_auxiliary_layouts($entry[0],$entry_layout);

				foreach($entry_layout as $section)
					foreach($section["attributes"] as $attrib_spec)
						foreach(explode(":",$attrib_spec[0]) as $attribute_line)
							foreach(explode("+",$attribute_line) as $attrib)
							{
								// Omit the RDN attribute - needs to be
								// changed last as modifying it renames
								// the object

								if(!in_array($attrib,$rdn_attribs))
								{
									$change_description
										= $ldap_server->update_attribute($entry[0],$attrib);
									if(!empty($change_description))
										$change_list .= "  <li>"
											. $change_description
											. "</li>\n";
								}
							}

				// update the RDN attributes after all others (if present in form data)

				foreach($rdn_attribs as $rdn_attrib)
				{
					if(isset($_POST["ldap_attribute_" . $rdn_attrib]))
					{
						// TODO: guard against nasties in the new RDN value
						$change_description = $ldap_server->update_attribute($entry[0],
							$rdn_attrib,LDAP_ATTRIBUTE_IS_RDN);

						if(!empty($change_description))
							$change_list .= "  <li>"
								. $change_description
								. "</li>\n";

						// update the value of $dn to reflect object's new
						// name (used for breadcrumb navigation and
						// "Back to record" link)

						$rdn="";
						foreach($rdn_attribs as $rdn_attrib2)
						{
							if(!empty($rdn)) $rdn .= "+";
							// TODO: figure out new RDN attribute value
							// perhaps update_attribute() should receive $entry by
							// reference and update it when successful
							$rdn .= $rdn_attrib2 . "=";

							if(is_array($entry[0][strtolower($rdn_attrib2)]))
								$rdn .= $entry[0][strtolower($rdn_attrib2)][0];
							else
								$rdn .= $entry[0][strtolower($rdn_attrib2)];
						}
						$rdn_list = ldap_explode_dn2($dn);
						$dn = $rdn . (empty($rdn_list[1]["dn"]) ? "" : "," . $rdn_list[1]["dn"]);
					}
				}

				show_ldap_path($dn);

				if(get_user_setting("allow_search") && get_user_setting("allow_login"))
					show_search_box("");

				if($change_list == "")
					echo gettext("No changes were made to this record");
				else
					echo "<p>" . gettext("Changes made to this record:") . "</p>\n"
						. "<ul>\n" . $change_list . "</ul>\n\n";

				echo "<p><a href=\"info.php?dn="
					. urlencode($dn) . "\">"
					. gettext("Back to record")
					. "</a></p>\n";
			}
			else
				show_error_message(gettext("Unable to locate LDAP record."));
		}
	}
	else
		show_error_message(gettext("You do not have permission to change this record"));
}
else
	show_ldap_bind_error();

show_site_footer();
?>
