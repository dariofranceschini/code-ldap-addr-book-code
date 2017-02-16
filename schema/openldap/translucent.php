<?php
/** OpenLDAP Configuration (OLC) Schema for Translucent Proxy Overlay */

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

		// Display layouts
		$ldap_server->add_display_layout("olcTranslucentConfig",array(
			array("section_name"=>gettext("Translucent Proxy Overlay Settings"),
				"attributes"=>array(
					array("olcOverlay",			gettext("Overlay Object Name"),						"openldap/overlay.png"),
					array("olcTranslucentStrict",		gettext("Deleting Attribute Triggers Constraint Violation Error"),	"generic24.png"),
					array("olcTranslucentNoGlue",		gettext("Don't Create 'Glue' Records for Add or ModRDN Operations"),	"generic24.png"),
					array("olcTranslucentLocal",		gettext("Attribute List for Local Searching"),				"generic24.png"),
					array("olcTranslucentRemote",		gettext("Attribute List for Remote Searching"),				"generic24.png"),
					array("olcTranslucentBindLocal",	gettext("Use Local Bind Credentials if Remote Bind Fails"),		"generic24.png"),
					array("olcTranslucentPwModLocal",	gettext("Local Password Modify Extended Operations"),			"generic24.png"),
					),
				),
			array("section_name"=>gettext("Remote Translucent Database"),"new_row"=>true,
				"attributes"=>array(
					array("__CHILD_OBJECTS__")
					)
				)
			));

		parent::__construct($ldap_server);
	}

	function populate_for_create_olcTranslucentConfig(&$ldap_server,&$entry)
	{
		$ldap_server->assign_ordered_sequence_rdn($entry,"olcOverlayConfig","translucent");
	}

	function before_create_olcTranslucentConfig(&$ldap_server,&$entry)
	{
		$ldap_server->ensure_openldap_module_loaded("translucent");
	}
}
?>
