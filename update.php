<?php
include "config.php";
include "utils.php";

show_site_header();

// TODO - updates to RDN attributes need to be dealt with as a
// special case (as they effectively rename the object)

// TODO: guard against nasties in the DN
$dn = $_GET["dn"];

if(log_on_to_directory($ldap_link))
{
	$user_info = get_user_info();
	$search_resource = @ldap_read($ldap_link,$dn,"(objectclass=*)");

	// Update record

	if(isset($user_info["allow_edit"]) && $user_info["allow_edit"])

		if($search_resource)
		{
			$entry = ldap_get_entries($ldap_link,$search_resource);

			$change_list = "";

			foreach($entry_layout as $section)
				foreach($section["attributes"] as $attrib_spec)
					foreach(explode(":",$attrib_spec[0]) as $attribute_line)
						foreach(explode("+",$attribute_line) as $attrib)
						{
							$change_description = update_ldap_attribute($entry,$attrib);
							if(!empty($change_description))
								$change_list .= "  <li>" . $change_description . "</li>\n";
						}

			// TODO: display correct icon or thumbnail image here (NOT folder)
			show_ldap_path($dn,$ldap_base_dn,"folder.png");

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
	else
		show_error_message("You do not have permission to change this record");
}
else
        show_ldap_bind_error();

show_site_footer();
?>
