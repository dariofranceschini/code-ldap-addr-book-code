<?php
/** OpenLDAP Configuration (OLC) Schema for Server Side Sorting and Virtual List View Overlay */

class openldap_sssvlv_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		// Attributes
		$this->attribute_schema = array(
			array("name"=>"olcSssVlvMax",			"data_type"=>"text",		"display_name"=>gettext("Maximum Concurrent Sort Requests")),
			array("name"=>"olcSssVlvMaxKeys",		"data_type"=>"text",		"display_name"=>gettext("Maximum Keys per Sort Request")),
			array("name"=>"olcSssVlvMaxPerConn",		"data_type"=>"text",		"display_name"=>gettext("Maximum Concurrent Paged Search Requests per Connection"))
			);

		// Structural object classes
		$this->object_schema = array(
			array("name"=>"olcSssVlvConfig",			"icon"=>"openldap/overlay.png",		"is_folder"=>false,"rdn_attrib"=>"olcOverlay","display_name"=>gettext("Server Side Sorting and Virtual List View Overlay"),"can_create"=>true,"parent_class"=>"olcOverlayConfig")
			);

		// Display layouts
		$ldap_server->add_display_layout("olcSssVlvConfig",array(
			array("section_name"=>gettext("Server Side Sorting and Virtual List View Overlay Settings"),"new_row"=>true,
				"attributes"=>array(
					array("olcOverlay",			gettext("Overlay Object Name"),						"openldap/overlay.png"),
					array("olcSssVlvMax",			gettext("Maximum Concurrent Sort Requests for All Connections"),	"generic24.png"),
					array("olcSssVlvMaxKeys",		gettext("Maximum Number of Keys per Sort Request"),			"generic24.png"),
					array("olcSssVlvMaxPerConn",		gettext("Maximum Concurrent Paged Search Requests per Connection"),	"generic24.png")
					)
				)
			));

		parent::__construct($ldap_server);
	}

	function populate_for_create_olcSssVlvConfig(&$ldap_server,&$entry)
	{
		$ldap_server->assign_ordered_sequence_rdn($entry,"olcOverlayConfig","sssvlv");

//		$this->add_attrib_value($ldap_server,$entry,"olcSssVlvMax","???");	// default to half number of server threads
		$this->add_attrib_value($ldap_server,$entry,"olcSssVlvMaxKeys","5");
		$this->add_attrib_value($ldap_server,$entry,"olcSssVlvMaxPerConn","5");

	}

	function before_create_olcSssVlvConfig(&$ldap_server,&$entry)
	{
		$ldap_server->ensure_openldap_module_loaded("sssvlv");
	}
}
?>
