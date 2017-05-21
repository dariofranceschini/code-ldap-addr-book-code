<?php
/** OpenLDAP Configuration (OLC) Schema for Audit Logging Overlay */

class openldap_auditlog_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"olcAuditlogFile",		"data_type"=>"text",		"display_name"=>gettext("Audit Log File Name"))
			);

		// Object classes
		$this->object_schema = array(
			array("name"=>"olcAuditlogConfig",		"icon"=>"openldap/overlay.png",		"is_folder"=>false,"rdn_attrib"=>"olcOverlay","display_name"=>gettext("Audit Logging Overlay"),"can_create"=>true,"create_method"=>"atomic","parent_class"=>"olcOverlayConfig")
			);

		$ldap_server->add_display_layout("olcAuditlogConfig",array(
			array("section_name"=>gettext("Audit Logging Overlay Settings"),"new_row"=>true,
				"attributes"=>array(
					array("olcOverlay",			gettext("Overlay Object Name"),			"openldap/overlay.png"),
					array("olcAuditlogFile",		gettext("Audit Log File"),			"document.png")
					),
				),
			));

		parent::__construct($ldap_server);
	}

	function populate_for_create_olcAuditlogConfig(&$ldap_server,&$entry)
	{
		$ldap_server->assign_ordered_sequence_rdn($entry,"olcOverlayConfig","auditlog");
	}

	function before_create_olcAuditlogConfig(&$ldap_server,&$entry)
	{
		$ldap_server->ensure_openldap_module_loaded("auditlog");
	}
}
?>
