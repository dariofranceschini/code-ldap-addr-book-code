<?php
/** OpenLDAP Configuration (OLC) Schema for Attribute Uniqueness Overlay */

class openldap_unique_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"olcUniqueURI",			"data_type"=>"text_list",	"display_name"=>gettext("Attribute Uniqueness Configuration")),

			// Attributes to hold legacy configuration settings
			array("name"=>"olcUniqueAttribute",		"data_type"=>"text",		"display_name"=>gettext("Attributes that Must be Unique")),
			array("name"=>"olcUniqueBase",			"data_type"=>"dn",		"display_name"=>gettext("Base DN")),
			array("name"=>"olcUniqueIgnore",		"data_type"=>"text",		"display_name"=>gettext("Ignored Attributes")),
			array("name"=>"olcUniqueStrict",		"data_type"=>"yes_no",		"display_name"=>gettext("Enforce Uniqueness for Null Values"))
			);

		// Object classes
		$this->object_schema = array(
			array("name"=>"olcUniqueConfig",			"icon"=>"openldap/overlay.png",		"is_folder"=>false,"rdn_attrib"=>"olcOverlay","display_name"=>gettext("Uniqueness Overlay"),"parent_class"=>"olcOverlayConfig")
			);

		// Display layouts
		$ldap_server->add_display_layout("olcUniqueConfig",array(
			array("section_name"=>gettext("Uniqueness Overlay Settings"),"new_row"=>true,
				"attributes"=>array(
					array("olcOverlay",			gettext("Overlay Object Name"),			"openldap/overlay.png"),
					array("olcUniqueURI",			gettext("Attribute Uniqueness Configuration"),	"generic24.png")
					)
				),
			array("section_name"=>gettext("Legacy Configuration Settings"),"new_row"=>true,
				"attributes"=>array(
					array("olcUniqueBase",			gettext("Base DN"),				"generic24.png"),
					array("olcUniqueAttribute",		gettext("Attributes that Must be Unique"),	"generic24.png"),
					array("olcUniqueIgnore",		gettext("Attributes to Ignore"),		"generic24.png"),
					array("olcUniqueStrict",		gettext("Enforce Uniqueness for Null Values"),	"generic24.png")
					)
				)
			));

		parent::__construct($ldap_server);
	}

	function populate_for_create_olcUniqueConfig(&$ldap_server,&$entry)
	{
		$ldap_server->assign_ordered_sequence_rdn($entry,"olcOverlayConfig","unique");
	}

	function before_create_olcUniqueConfig(&$ldap_server,&$entry)
	{
		$ldap_server->ensure_openldap_module_loaded("unique");
	}
}
?>
