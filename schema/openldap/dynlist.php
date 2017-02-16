<?php
/** OpenLDAP Configuration (OLC) Schema for Dynamic Lists Overlay */

class openldap_dynlist_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"olcDlAttrSet",			"data_type"=>"text_list",	"display_name"=>gettext("Member/Member URL Attribute Pair"))
			);

		// Structural object classes
		$this->object_schema = array(
			array("name"=>"olcDynamicList",			"icon"=>"openldap/overlay.png",		"is_folder"=>false,"rdn_attrib"=>"olcOverlay","display_name"=>gettext("Dynamic Lists Overlay"),"parent_class"=>"olcOverlayConfig")
			);

		// Display layouts
		$ldap_server->add_display_layout("olcDynamicList",array(
			array("section_name"=>gettext("Dynamic Lists Overlay Settings"),"new_row"=>true,
				"attributes"=>array(
					array("olcOverlay",			gettext("Overlay Object Name"),				"openldap/overlay.png"),
				//	array("olcDlAttrSet",			gettext("Member/Member URL Attribute Pair"),		"generic24.png")
					)
				)
			));

		parent::__construct($ldap_server);
	}

	function populate_for_create_olcDynamicList(&$ldap_server,&$entry)
	{
		$ldap_server->assign_ordered_sequence_rdn($entry,"olcOverlayConfig","dynlist");
	//	$this->add_attrib_value($ldap_server,$entry,"olcDlAttrSet","groupOfURLs memberURL");
	}

	function before_create_olcDynamicList(&$ldap_server,&$entry)
	{
		$ldap_server->ensure_openldap_module_loaded("dynlist");
	}
}
?>
