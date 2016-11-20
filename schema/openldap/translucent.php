<?php
/** OpenLDAP Translucent Proxy Overlay On-Line Configuration (OLC) schema */

class openldap_translucent_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"olcTranslucentBindLocal",	"data_type"=>"yes_no",		"display_name"=>gettext("Use Local Bind Credentials if Remote Bind Fails")),
			array("name"=>"olcTranslucentLocal",		"data_type"=>"text",		"display_name"=>gettext("Attribute List for Local Searching")),
			array("name"=>"olcTranslucentNoGlue",		"data_type"=>"yes_no",		"display_name"=>gettext("Don't Create 'Glue' Records for Add or ModRDN Operations")),
			array("name"=>"olcTranslucentPwModLocal",	"data_type"=>"yes_no",		"display_name"=>gettext("Local Password Modify Extended Operations")),
			array("name"=>"olcTranslucentRemote",		"data_type"=>"text",		"display_name"=>gettext("Attribute List for Remote Searching")),
			array("name"=>"olcTranslucentStrict",		"data_type"=>"yes_no",		"display_name"=>gettext("Deleting Attribute Triggers Constraint Violation Error"))
			);

		// Structural object classes
		$this->object_schema = array(
			array("name"=>"olcTranslucentConfig",			"icon"=>"openldap/overlay.png",		"is_folder"=>false,"rdn_attrib"=>"olcOverlay","display_name"=>gettext("Translucent Proxy Overlay"),"parent_class"=>"olcOverlayConfig")
			);

		// auxiliary class 'olcTranslucentConfig' is also defined in this schema

		parent::__construct($ldap_server);
	}
}
?>
