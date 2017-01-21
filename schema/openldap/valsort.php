<?php
/** OpenLDAP Value Sorting Overlay On-Line Configuration (OLC) schema */

class openldap_valsort_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"olcValSortAttr",			"data_type"=>"text",		"display_name"=>gettext("Value Sorting Configuration"))		// TODO: should this be text_list?
			);

		// Structural object classes
		$this->object_schema = array(
			array("name"=>"olcValSortConfig",		"icon"=>"openldap/overlay.png",		"is_folder"=>false,"rdn_attrib"=>"olcOverlay","display_name"=>gettext("Value Sorting Overlay"),"can_create"=>true,"required_attribs"=>"olcValSortAttr","parent_class"=>"olcOverlayConfig")
			);

		// Display layouts
		$ldap_server->add_display_layout("olcValSortConfig",array(
			array("section_name"=>gettext("Value Sorting Overlay Settings"),"new_row"=>true,
				"attributes"=>array(
					array("olcOverlay",			gettext("Overlay Object Name"),				"openldap/overlay.png"),
					array("olcValSortAttr",			gettext("Value Sorting Configuration"),			"generic24.png"),
					),
				),
			));

		parent::__construct($ldap_server);
	}

	function populate_for_create_olcValSortConfig(&$ldap_server,&$entry)
	{
		$ldap_server->assign_ordered_sequence_rdn($entry,"olcOverlayConfig","valsort");
		// TODO: fill in olcValSortAttr with "member <db>" where <db> is the underlying database's naming context DN
	}

	function before_create_olcValSortConfig(&$ldap_server,&$entry)
	{
		$ldap_server->ensure_openldap_module_loaded("valsort");
	}
}
?>
