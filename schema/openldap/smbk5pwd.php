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

		parent::__construct($ldap_server);
	}
}
?>
