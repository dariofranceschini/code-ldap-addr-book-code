<?php
/** OpenLDAP Configuration (OLC) Schema for Referential Integrity Overlay */

class openldap_refint_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"olcRefintAttribute",		"data_type"=>"text",		"display_name"=>gettext("Attributes with Referential Integrity")),
			array("name"=>"olcRefintNothing",		"data_type"=>"dn",		"display_name"=>gettext("Placeholder Value for Empty Mandatory Attributes")),
			array("name"=>"olcRefintModifiersName",		"data_type"=>"dn",		"display_name"=>gettext("Modifier's DN"))
			);

		// Object classes
		$this->object_schema = array(
			array("name"=>"olcRefintConfig",			"icon"=>"openldap/overlay.png",		"is_folder"=>false,"rdn_attrib"=>"olcOverlay","display_name"=>gettext("Referential Integrity Overlay"),"parent_class"=>"olcOverlayConfig","can_create"=>true)
			);

		// Display layouts
		$ldap_server->add_display_layout("olcRefintConfig",array(
			array("section_name"=>gettext("Referential Integrity Overlay Settings"),"new_row"=>true,
				"attributes"=>array(
					array("olcOverlay",			gettext("Overlay Object Name"),					"openldap/overlay.png"),
					array("olcRefintAttribute",		gettext("Maintain Referential Integrity for These Attributes"),	"generic24.png"),
					array("olcRefintNothing",		gettext("Placeholder Value for Empty Mandatory Attributes"),	"generic24.png"),
					array("olcRefintModifiersName",		gettext("Modifier's Name to be Used"),				"generic24.png"),
					),
				),
			));

		parent::__construct($ldap_server);
	}

	function populate_for_create_olcRefintConfig(&$ldap_server,&$entry)
	{
		$ldap_server->assign_ordered_sequence_rdn($entry,"olcOverlayConfig","refint");
	}

	function before_create_olcRefintConfig(&$ldap_server,&$entry)
	{
		$ldap_server->ensure_openldap_module_loaded("refint");
	}
}
?>
