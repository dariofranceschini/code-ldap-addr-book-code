<?php
/** OpenLDAP Configuration (OLC) Schema for Last Bind Overlay */

class openldap_lastbind_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"olcLastBindPrecision",		"data_type"=>"text",		"display_name"=>gettext("Authentication Timestamp Precision")),

			array("name"=>"authTimestamp",			"data_type"=>"text",		"display_name"=>gettext("Date/Time of Last Successful Authentication"))
			);

		// Object classes
		$this->object_schema = array(
			array("name"=>"olcLastBindConfig",			"icon"=>"openldap/overlay.png",		"is_folder"=>false,"rdn_attrib"=>"olcOverlay","display_name"=>gettext("Last Bind Overlay"),"parent_class"=>"olcOverlayConfig","can_create"=>true)
			);

		// Display layouts
		$ldap_server->add_display_layout("olcLastBindConfig",array(
			array("section_name"=>gettext("Last Bind Overlay Settings"),"new_row"=>true,
				"attributes"=>array(
					array("olcOverlay",			gettext("Overlay Object Name"),				"openldap/overlay.png"),
					array("olcLastBindPrecision",		gettext("Only Update Timestamps Older Than (seconds)"),	"generic24.png")
					)
				)
			));

		parent::__construct($ldap_server);
	}

	function populate_for_create_olcLastBindConfig(&$ldap_server,&$entry)
	{
		$ldap_server->assign_ordered_sequence_rdn($entry,"olcOverlayConfig","lastbind");
		$this->add_attrib_value($ldap_server,$entry,"olcLastBindPrecision","0");
	}

	function before_create_olcLastBindConfig(&$ldap_server,&$entry)
	{
		$ldap_server->ensure_openldap_module_loaded("lastbind");
	}
}
?>
