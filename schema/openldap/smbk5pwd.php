<?php
/** OpenLDAP Samba/Kerberos Password Management Overlay Configuration schema (partial) */

class openldap_smbk5pwd_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"olcSmbK5PwdEnable",		"data_type"=>"text_list",	"display_name"=>gettext("Enabled Password Modifier Modules")),
			array("name"=>"olcSmbK5PwdMustChange",		"data_type"=>"text",		"display_name"=>gettext("Maximum Password Age (Seconds)")),
			array("name"=>"olcSmbK5PwdCanChange",		"data_type"=>"text",		"display_name"=>gettext("Minimum Password Age (seconds)"))
			);

		// Structural object classes
		$this->object_schema = array(
			array("name"=>"olcSmbK5PwdConfig",		"icon"=>"openldap/overlay.png",		"is_folder"=>false,"rdn_attrib"=>"olcOverlay","display_name"=>gettext("Samba/Kerberos Password Management Overlay"),"can_create"=>true,"parent_class"=>"olcOverlayConfig")
			);

		// Display layouts
		$ldap_server->add_display_layout("olcSmbK5PwdConfig",array(
			array("section_name"=>gettext("Samba/Kerberos 5 Password Management Overlay Settings"),"new_row"=>true,
				"attributes"=>array(
					array("olcOverlay",			gettext("Overlay Object Name"),				"openldap/overlay.png"),
					array("olcSmbK5PwdMustChange",		gettext("Maximum Password Age (Seconds)"),		"generic24.png"),
					array("olcSmbK5PwdCanChange",		gettext("Minimum Password Age (seconds)"),		"generic24.png")
					)
				),
			array("section_name"=>gettext("Enabled Password Modifier Modules"),"new_row"=>true,
				"attributes"=>array(
					array("olcSmbK5PwdEnable")
					)
				)
			));

		parent::__construct($ldap_server);
	}

	function populate_for_create_olcSmbK5PwdConfig(&$ldap_server,&$entry)
	{
		$ldap_server->assign_ordered_sequence_rdn($entry,"olcOverlayConfig","smbk5pwd");
	//	$this->add_attrib_value($ldap_server,$entry,"olcSmbK5PwdMustChange","???");
	//	$this->add_attrib_value($ldap_server,$entry,"olcSmbK5PwdCanChange","???");
	}

	function before_create_olcSmbK5PwdConfig(&$ldap_server,&$entry)
	{
		$ldap_server->ensure_openldap_module_loaded("smbk5pwd");

		$this->add_attrib_single_value($ldap_server,$entry,"olcSmbK5PwdEnable",
			array("krb5","samba"));
	}
}
?>
