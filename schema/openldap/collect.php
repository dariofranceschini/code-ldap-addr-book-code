<?php
/** OpenLDAP Configuration (OLC) Schema for Collective Attributes Overlay */

class openldap_collect_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"olcCollectInfo",			"data_type"=>"text",		"display_name"=>gettext("Ancestor DN/Collective Attributes"))
			);

		// Structural object classes
		$this->object_schema = array(
			array("name"=>"olcCollectConfig",		"icon"=>"openldap/overlay.png",		"is_folder"=>false,"rdn_attrib"=>"olcOverlay","display_name"=>gettext("Collective Attributes Overlay"),"can_create"=>true,"parent_class"=>"olcOverlayConfig"),
			);

		// Display layouts
		$ldap_server->add_display_layout("olcCollectConfig",array(
			array("section_name"=>gettext("Collective Attributes Overlay Settings"),"new_row"=>true,
				"attributes"=>array(
					array("olcOverlay",			gettext("Overlay Object Name"),				"openldap/overlay.png"),
					array("olcCollectInfo",			gettext("Ancestor DN/Collective Attributes"),		"document.png")
					),
				),
			));

		parent::__construct($ldap_server);
	}

	function populate_for_create_olcCollectConfig(&$ldap_server,&$entry)
	{
		$ldap_server->assign_ordered_sequence_rdn($entry,"olcOverlayConfig","collect");
	}

	function before_create_olcCollectConfig(&$ldap_server,&$entry)
	{
		$ldap_server->ensure_openldap_module_loaded("collect");
	}
}
?>
