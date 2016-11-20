<?php
/** OpenLDAP Socket Backend On-Line Configuration (OLC) schema */

class openldap_back_sock_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"olcDbSocketPath",		"data_type"=>"text",		"display_name"=>gettext("Socket Pathname")),
			array("name"=>"olcDbSocketExtensions",		"data_type"=>"text",		"display_name"=>gettext("Socket Extension Attributes")),
			array("name"=>"olcOvSocketOps",			"data_type"=>"text",		"display_name"=>gettext("Socket Operation Types")),
			array("name"=>"olcOvSocketResps",		"data_type"=>"text",		"display_name"=>gettext("Socket Response Types"))
			);

		// Structural object classes
		$this->object_schema = array(
			array("name"=>"olcDbSocketConfig",		"icon"=>"openldap/db.png",		"is_folder"=>false,"rdn_attrib"=>"olcDatabase","display_name"=>gettext("Socket Database"),"required_attribs"=>"olcSuffix,olcDbSocketPath","parent_class"=>"olcDatabaseConfig"),
			array("name"=>"olcOvSocketConfig",		"icon"=>"openldap/overlay.png",		"is_folder"=>false,"rdn_attrib"=>"olcOverlay","display_name"=>gettext("Socket Overlay"),"parent_class"=>"olcOverlayConfig")
			);

		parent::__construct($ldap_server);
	}
}
?>
