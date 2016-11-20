<?php
/** OpenLDAP LDAP Proxy Backend On-Line Configuration (OLC) schema (partial) */

class openldap_back_ldap_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		// Structural object classes
		$this->object_schema = array(
			array("name"=>"olcLDAPConfig",			"icon"=>"openldap/db.png",		"is_folder"=>false,"rdn_attrib"=>"olcDatabase","display_name"=>gettext("LDAP Proxy Database"),"required_attribs"=>"olcSuffix","parent_class"=>"olcDatabaseConfig"),
			array("name"=>"olcChainConfig",			"icon"=>"openldap/overlay.png",		"is_folder"=>false,"rdn_attrib"=>"olcOverlay","display_name"=>gettext("Chain Overlay"),"parent_class"=>"olcOverlayConfig")
			);

		parent::__construct($ldap_server);
	}
}
?>
