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

// TODO: guard against nasties in the DN
$dn = $_GET["dn"];

if(log_on_to_directory($ldap_link))
{
	$user_info = get_user_info();

	// Update record

	if(isset($user_info["allow_edit"]) && $user_info["allow_edit"])
	{
		$create_failed = false;
		$search_resource = @ldap_read($ldap_link,$dn,"(objectclass=*)");

		$object_class_schema = get_object_class_schema($ldap_server_type);

                if(!empty($_POST["create"]))
                {
			if($search_resource)
			{
				show_error_message("Unable to create - A record already exists with this name.");
				$create_failed = true;
			}
			else
			{
				$entry["objectclass"] = $_POST["create"];

				// check request is for a valid creatable object class before
				// attempting ldap_add()
				if(get_object_class_setting($object_class_schema,
					$entry["objectclass"],"can_create"))
				{
					$required_attribs = explode(",",
						get_object_class_setting(
						$object_class_schema,
						$entry["objectclass"],
						"required_attribs"));

					// TODO: guard against nasties in attribute value
					foreach($required_attribs as $attrib)
						if(!empty($required_attribs[0])
								&& isset($_POST["ldap_attribute_"
								. $attrib]))
							$entry[$attrib] = $_POST["ldap_attribute_"
								. $attrib];

					$result = @ldap_add($ldap_link,$dn,$entry);

					if(!$result)
					{
						$create_failed = true;
						show_error_message("Unable to create LDAP record: "
							. ldap_error($ldap_link));
					}
				}
				else
				{
					$create_failed = true;
					show_error_message("Unable to create this type of object.");
				}
			}
                }

		if($create_failed == false)
		{
			$search_resource = @ldap_read($ldap_link,$dn,"(objectclass=*)");

			if($search_resource)
			{
				$entry = ldap_get_entries($ldap_link,$search_resource);

		                if(empty($_POST["create"]))
					$change_list = "";
				else
					$change_list = "  <li>New '" . $_POST["create"]
						. "' record created: '" . $_POST["ldap_attribute_"
						. $rdn_attrib] . "'</li>\n";

				$rdn_attrib = get_object_class_setting(
					$object_class_schema,
					get_object_class($object_class_schema,$entry[0]),
					"rdn_attrib");

				foreach($entry_layout as $section)
					foreach($section["attributes"] as $attrib_spec)
						foreach(explode(":",$attrib_spec[0]) as $attribute_line)
							foreach(explode("+",$attribute_line) as $attrib)
							{
								// Omit the RDN attribute - needs to be
								// changed last as modifying it renames
								// the object
								if($attrib != $rdn_attrib)
								{
									$change_description
										= update_ldap_attribute($entry,$attrib);
									if(!empty($change_description))
										$change_list .= "  <li>"
											. $change_description
											. "</li>\n";
								}
							}

				// update the RDN attribute after all others (if present in form data)
				if(isset($_POST["ldap_attribute_" . $rdn_attrib]))
				{
					// TODO: guard against nasties in the new RDN value
					$change_description = update_ldap_attribute($entry,
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
						. "," . $rdn_list[1]["dn"];
				}

				show_ldap_path($dn,$ldap_base_dn,get_icon_for_ldap_entry($entry[0]));

				if($user_info["allow_search"] && $user_info["ldap_name"]!="__DENY__")
					show_search_box("");

				if($change_list == "")
					echo "No changes were made to this record";
				else
					echo "<p>Changes made to this record:</p>\n"
						. "<ul>\n" . $change_list . "</ul>\n\n";

				echo "<p><a href=\"info.php?dn=" . $dn . "\">Back to record</a></p>\n";
			}
			else
				show_error_message("Unable to locate LDAP record.");
		}
	}
	else
		show_error_message("You do not have permission to change this record");
}
else
        show_ldap_bind_error();

show_site_footer();
?>
