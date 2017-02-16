<?php
/** OpenLDAP Configuration (OLC) Schema for Dynamic Groups Overlay */

class openldap_dyngroup_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"olcDGAttrPair",			"data_type"=>"text",		"display_name"=>gettext("Member/Member URL Attribute Pair"))
			);

		// Structural object classes
		$this->object_schema = array(
			array("name"=>"olcDGConfig",			"icon"=>"openldap/overlay.png",		"is_folder"=>false,"rdn_attrib"=>"olcOverlay","display_name"=>gettext("Dynamic Groups Overlay"),"parent_class"=>"olcOverlayConfig")
			);

		// Display layouts
		$ldap_server->add_display_layout("olcDGConfig",array(
			array("section_name"=>gettext("Dynamic Groups Overlay Settings"),"new_row"=>true,
				"attributes"=>array(
					array("olcOverlay",			gettext("Overlay Object Name"),			"openldap/overlay.png"),
					array("olcDGAttrPair",			gettext("Member/Member URL Attribute Pair"),	"generic24.png")
					),
				),
			));

		parent::__construct($ldap_server);
	}

	function populate_for_create_olcDGConfig(&$ldap_server,&$entry)
	{
		$ldap_server->assign_ordered_sequence_rdn($entry,"olcOverlayConfig","dyngroup");
		$this->add_attrib_value($ldap_server,$entry,"olcDGAttrPair","member memberURL");
	}

	function before_create_olcDGConfig(&$ldap_server,&$entry)
	{
		$ldap_server->ensure_openldap_module_loaded("dyngroup");
	}
}
?>
