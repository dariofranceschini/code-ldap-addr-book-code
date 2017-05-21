<?php
/** OpenLDAP Configuration (OLC) Schema for Constraints Overlay */

class openldap_constraint_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"olcConstraintAttribute",		"data_type"=>"text",		"display_name"=>gettext("Attribute Constraints"))
			);

		// Object classes
		$this->object_schema = array(
			array("name"=>"olcConstraintConfig",		"icon"=>"openldap/overlay.png",		"is_folder"=>false,"rdn_attrib"=>"olcOverlay","display_name"=>gettext("Constraints Overlay"),"parent_class"=>"olcOverlayConfig")
			);

		// Display layouts
		$ldap_server->add_display_layout("olcConstraintConfig",array(
			array("section_name"=>gettext("Constraints Overlay Settings"),"new_row"=>true,
				"attributes"=>array(
					array("olcOverlay",			gettext("Overlay Object Name"),			"openldap/overlay.png")
					)
				),
			array("section_name"=>gettext("Attribute Constraints"),"new_row"=>true,
				"attributes"=>array(
					array("olcConstraintAttribute")
					)
				)
			));

		parent::__construct($ldap_server);
	}

	function populate_for_create_olcConstraintConfig(&$ldap_server,&$entry)
	{
		$ldap_server->assign_ordered_sequence_rdn($entry,"olcOverlayConfig","constraint");
	}

	function before_create_olcConstraintConfig(&$ldap_server,&$entry)
	{
		$ldap_server->ensure_openldap_module_loaded("constraint");
	}
}
?>
