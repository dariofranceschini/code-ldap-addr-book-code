<?php
/** OpenLDAP Dynamic Directory Services Overlay On-Line Configuration (OLC) schema (partial) */

class openldap_dds_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"olcDDSstate",			"data_type"=>"yes_no",		"display_name"=>gettext("Enable Dynamic Directory Services")),

			array("name"=>"entryExpireTimestamp",		"data_type"=>"date_time",	"display_name"=>gettext("Entry Expiry Time"))
			);

		// Structural object classes
		$this->object_schema = array(
			array("name"=>"olcDDSConfig",			"icon"=>"openldap/overlay.png",		"is_folder"=>false,"rdn_attrib"=>"olcOverlay","display_name"=>gettext("Dynamic Directory Services Overlay"),"parent_class"=>"olcOverlayConfig")
			);

		parent::__construct($ldap_server);
	}
}
?>
