<?php
/** OpenLDAP Audit Logging Overlay On-Line Configuration (OLC) schema */

class openldap_auditlog_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"olcAuditlogFile",		"data_type"=>"text",		"display_name"=>gettext("Audit Log File Name"))
			);

		// Structural object classes
		$this->object_schema = array(
			array("name"=>"olcAuditlogConfig",		"icon"=>"openldap/overlay.png",		"is_folder"=>false,"rdn_attrib"=>"olcOverlay","display_name"=>gettext("Audit Logging Overlay"),"parent_class"=>"olcOverlayConfig")
			);

		parent::__construct($ldap_server);
	}
}
?>
