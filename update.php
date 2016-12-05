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

		// Add the RDN attribute to $dn (which refers to the container)
		// when creating a new entry.
		if(!empty($_POST["create"]))
		{
			$entry["objectclass"] = $_POST["create"];

			$rdn_attrib = $ldap_server->get_object_schema_setting(
				$entry["objectclass"],
				"rdn_attrib");

			// Allow for blank RDN attribute - e.g. if populated
			// programatically by schema-specific function (called below)

			/** @todo enhance to support multi-value RDNs */
			if(isset($_POST["ldap_attribute_" . $rdn_attrib]))
				$dn = $rdn_attrib . "=" . $_POST["ldap_attribute_"
					. $rdn_attrib] . (empty($dn) ? "" : "," . $dn);
			else
				$dn = $rdn_attrib . "=" . (empty($dn) ? "" : "," . $dn);
		}

		$search_resource = @ldap_read($ldap_server->connection,$dn,"(objectclass=*)");

		if(!empty($_POST["create"]))
		{
			if($search_resource)
			{
				show_error_message(gettext("Unable to create - A record already exists with this name."));
				$create_failed = true;
			}
			else
			{
				// check request is for a valid creatable object class before
				// attempting ldap_add()
				if($ldap_server->get_object_schema_setting($entry["objectclass"],
					"can_create") || get_user_setting("allow_system_admin"))
				{
					if($ldap_server->get_object_schema_setting($entry["objectclass"],"create_method","normal")=="atomic")
					{
						// Include every attribute which appears in the display layout
						$required_attribs=array();
						$entry_layout = $ldap_server->get_display_layout($entry["objectclass"]);
						foreach($entry_layout as $section)
							foreach($section["attributes"] as $attrib_spec)
								foreach(explode(":",$attrib_spec[0]) as $attribute_line)
									foreach(explode("+",$attribute_line) as $attrib)
										$required_attribs[] = $attrib;
					}
					else
						$required_attribs = explode(",",
							$ldap_server->get_object_schema_setting(
							$entry["objectclass"],
							"required_attribs"));

					// TODO: guard against nasties in attribute value
					foreach($required_attribs as $attrib)
						if(!empty($required_attribs[0])
								&& isset($_POST["ldap_attribute_"
								. $attrib]))
							$entry[$attrib] = $_POST["ldap_attribute_"
								. $attrib];

					// Allow schema function to see/modify the DN to be created
					$entry["dn"] = $dn;

					$ldap_server->call_schema_function("before_create_" . $entry["objectclass"],$entry);

					// Update the DN to be created (if modified by the schema function)
					$dn = $entry["dn"];
					unset($entry["dn"]);

					$name_of_object_created = ldap_explode_dn2($dn);
					$name_of_object_created = $name_of_object_created[0]["value"];

					$result = @ldap_add($ldap_server->connection,$dn,$entry);

					if(!$result)
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

				if(empty($_POST["create"]))
					$change_list = "";
				else
				{
					$change_list = "  <li>"
						. sprintf(gettext("New '%s' record created: '%s'"),
						htmlentities($_POST["create"],ENT_COMPAT,"UTF-8"),
						htmlentities($name_of_object_created,ENT_COMPAT,"UTF-8"))
						. "</li>\n";

					$ldap_server->call_schema_function("after_create_"
						. $ldap_server->get_object_class($entry[0]),$entry[0]);
				}

				/** @todo enhance to support multi-value RDNs - comma-separated list */

				$rdn_attrib = $ldap_server->get_object_schema_setting(
					$ldap_server->get_object_class($entry[0]),
					"rdn_attrib");

				$entry_layout = $ldap_server->get_display_layout(
					$ldap_server->get_object_class($entry[0]));

				foreach($entry_layout as $section)
					foreach($section["attributes"] as $attrib_spec)
						foreach(explode(":",$attrib_spec[0]) as $attribute_line)
							foreach(explode("+",$attribute_line) as $attrib)
							{
								// Omit the RDN attribute - needs to be
								// changed last as modifying it renames
								// the object

								/** @todo enhance to support multi-value RDNs - comma-separated list */

								if($attrib != $rdn_attrib)
								{
									$change_description
										= $ldap_server->update_attribute($entry,$attrib);
									if(!empty($change_description))
										$change_list .= "  <li>"
											. $change_description
											. "</li>\n";
								}
							}

				// update the RDN attribute after all others (if present in form data)
				/** @todo enhance to support multi-value RDNs - comma-separated list */

				if(isset($_POST["ldap_attribute_" . $rdn_attrib]))
				{
					// TODO: guard against nasties in the new RDN value
					$change_description = $ldap_server->update_attribute($entry,
						$rdn_attrib,LDAP_ATTRIBUTE_IS_RDN);

					if(!empty($change_description))
						$change_list .= "  <li>"
							. $change_description
							. "</li>\n";

					// update the value of $dn to reflect object's new
					// name (used for breadcrumb navigation and
					// "Back to record" link)

					$rdn_list = ldap_explode_dn2($dn);
					$dn = $rdn_attrib . "="
						. $_POST["ldap_attribute_" . $rdn_attrib]
						. (empty($rdn_list[1]["dn"]) ? "" : "," . $rdn_list[1]["dn"]);
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
