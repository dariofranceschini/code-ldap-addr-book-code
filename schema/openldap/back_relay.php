<?php
/** OpenLDAP Relay Backend On-Line Configuration (OLC) schema */

class openldap_back_relay_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"olcRelay",			"data_type"=>"dn",		"display_name"=>gettext("Relay Naming Context DN"))
			);

		// Structural object classes
		$this->object_schema = array(
			array("name"=>"olcRelayConfig",			"icon"=>"openldap/db.png",		"is_folder"=>false,"rdn_attrib"=>"olcDatabase","display_name"=>gettext("Relay Database"),"required_attribs"=>"olcSuffix,olcRelay","parent_class"=>"olcDatabaseConfig")
			);

		parent::__construct($ldap_server);
	}
}
?>
