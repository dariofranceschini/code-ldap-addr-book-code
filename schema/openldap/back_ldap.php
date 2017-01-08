<?php
/** OpenLDAP LDAP Proxy Backend On-Line Configuration (OLC) schema (partial) */

class openldap_back_ldap_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		// Structural object classes
		$this->object_schema = array(
			array("name"=>"olcLDAPConfig",			"icon"=>"openldap/db.png",		"is_folder"=>false,"rdn_attrib"=>"olcDatabase","display_name"=>gettext("LDAP Proxy Database"),"required_attribs"=>"olcSuffix","parent_class"=>"olcDatabaseConfig"),
			array("name"=>"olcChainConfig",			"icon"=>"openldap/overlay.png",		"is_folder"=>false,"rdn_attrib"=>"olcOverlay","display_name"=>gettext("Chain Overlay"),"parent_class"=>"olcOverlayConfig"),
			array("name"=>"olcDistProcConfig",		"icon"=>"openldap/overlay.png",		"is_folder"=>false,"rdn_attrib"=>"olcOverlay","display_name"=>gettext("Distributed Procedure Overlay"),"parent_class"=>"olcOverlayConfig"),
			array("name"=>"olmLDAPConnection",		"icon"=>"openldap/connection.png",	"is_folder"=>false,"display_name"=>gettext("Monitored LDAP Proxy Connection"),"parent_class"=>"monitorConnection")
			);

		// auxiliary class 'olmLDAPDatabase' is also defined in this schema

		parent::__construct($ldap_server);
	}
}
?>
