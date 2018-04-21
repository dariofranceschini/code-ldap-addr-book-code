<?php
/** OpenLDAP Configuration (OLC) Schema for Dynamic Directory Services Overlay (partial) */

class openldap_dds_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"olcDDSstate",			"data_type"=>"yes_no",		"display_name"=>gettext("Enable Dynamic Directory Services")),

			array("name"=>"entryExpireTimestamp",		"data_type"=>"date_time",	"display_name"=>gettext("Entry Expiry Time"))
			);

		// Object classes
		$this->object_schema = array(
			array("name"=>"olcDDSConfig",			"icon"=>"openldap/overlay.png",		"is_folder"=>false,"rdn_attrib"=>"olcOverlay","display_name"=>gettext("Dynamic Directory Services Overlay"),"can_create"=>true,"parent_class"=>"olcOverlayConfig")
			);

		// Display layouts
		$ldap_server->add_display_layout("olcDDSConfig",array(
			array("section_name"=>gettext("Dynamic Directory Services Overlay Settings"),"new_row"=>true,
				"attributes"=>array(
					array("olcOverlay",			gettext("Overlay Object Name"),				"openldap/overlay.png"),
					array("olcDDSinterval",			gettext("Expiration Check Interval (s)"),		"time.png"),
					array("olcDDStolerance",		gettext("Expiration Tolerance Time"),			"time.png"),
					array("olcDDSmaxDynamicObjects",	gettext("Maxiumum Dynamic Objects in Naming Context"),	"generic24.png"),
					array("olcDDSstate",			gettext("Enable Dynamic Directory Services"),		"generic24.png")
					)
				),
			array("section_name"=>gettext("Object Expiry Times (Time To Live/TTL Values) in Seconds"),"new_row"=>true,
				"attributes"=>array(
					array("olcDDSdefaultTtl",		gettext("Default for New Dynamic Objects"),		"time.png"),
					array("olcDDSminTtl",			gettext("Minimum Allowed"),				"time.png"),
					array("olcDDSmaxTtl",			gettext("Maximum Allowed"),				"time.png"),
					)
				)
			));

		parent::__construct($ldap_server);
	}

	function populate_for_create_olcDDSConfig(&$ldap_server,&$entry)
	{
		$ldap_server->assign_ordered_sequence_rdn($entry,"olcOverlayConfig","dds");
		$this->add_attrib_value($ldap_server,$entry,"olcDDSinterval","3600");		// 1 hour
		$this->add_attrib_value($ldap_server,$entry,"olcDDStolerance","0");
		$this->add_attrib_value($ldap_server,$entry,"olcDDSdefaultTtl","86400");	// note: setting to 0 causes olcDDSmaxTtl to be used
		$this->add_attrib_value($ldap_server,$entry,"olcDDSminTtl","0");
		$this->add_attrib_value($ldap_server,$entry,"olcDDSmaxTtl","86400");		// 1 day
	}

	function before_create_olcDDSConfig(&$ldap_server,&$entry)
	{
		$ldap_server->ensure_openldap_module_loaded("dds");
	}
}
?>
