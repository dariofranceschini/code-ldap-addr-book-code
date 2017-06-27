<?php
/** Novell Secure Password Manager (NSPM) schema (partial) */

class novell_nspm_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		// Object classes
		$this->object_schema = array(
			array("name"=>"nspmPasswordPolicyContainer",	"icon"=>"novell/password-policy-container.png",	"is_folder"=>true),
			array("name"=>"nspmPasswordPolicy",		"icon"=>"password-policy.png",			"is_folder"=>false),
			);

		parent::__construct($ldap_server);
	}
}
?>
