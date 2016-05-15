<?php
/** Novell SecretStore Services (SSS) schema (partial) */

class novell_sss_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		// Structural object classes
		$this->object_schema = array(
			array("name"=>"sssServerPolicies",			"icon"=>"novell/secretstore-policies.png",		"is_folder"=>true),
			);

		parent::__construct($ldap_server);
	}
}
?>
