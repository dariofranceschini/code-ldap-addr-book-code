<?php
/** OpenLDAP NSS/PAM Lookup Overlay On-Line Configuration (OLC) schema (partial) */

class openldap_nssov_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"olcNssPamGroupDN",		"data_type"=>"dn",		"display_name"=>gettext("NSS/PAM Group DN")),
			array("name"=>"olcNssSsd",			"data_type"=>"text_list",	"display_name"=>gettext("Service Search Descriptor")),
			array("name"=>"olcNssMap",			"data_type"=>"text_list",	"display_name"=>gettext("Service Attribute Mappings")),
			array("name"=>"olcNssPam",			"data_type"=>"text_list",	"display_name"=>gettext("PAM Authentication and Authorization Options")),
			array("name"=>"olcNssPamSession",		"data_type"=>"text_list",	"display_name"=>gettext("NSS/PAM Services with Session Logging"))
			);

		// Structural object classes
		$this->object_schema = array(
			array("name"=>"olcNssOvConfig",			"icon"=>"openldap/overlay.png",		"is_folder"=>false,"rdn_attrib"=>"olcOverlay","display_name"=>gettext("NSS/PAM Lookup Overlay"),"parent_class"=>"olcOverlayConfig")
			);

		$ldap_server->add_schema("openldap/ldapns");

		parent::__construct($ldap_server);
	}
}
?>
