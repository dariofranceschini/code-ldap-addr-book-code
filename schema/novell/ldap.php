
<?php
/** Novell LDAP Agent for Novell eDirectory schema (partial) */

class novell_ldap_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		// Structural object classes
		$this->object_schema = array(
			array("name"=>"ldapServer",			"icon"=>"ldap-server.png",		"is_folder"=>false,"display_name"=>gettext("LDAP Server"),"can_create"=>true),
			array("name"=>"ldapGroup",			"icon"=>"novell/ldap-group.png",	"is_folder"=>false,"display_name"=>gettext("LDAP Group"),"can_create"=>true),
			);

		parent::__construct($ldap_server);
	}
}
