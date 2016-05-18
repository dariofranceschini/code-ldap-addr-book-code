<?php
/** Novell Secure Authentication Services (SAS) schema (partial) */

class novell_sas_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"sasServiceDN",		"data_type"=>"dn_list",		"display_name"=>gettext("SAS Service DN")),
			);

		// Structural object classes
		$this->object_schema = array(
			array("name"=>"sASService",			"icon"=>"novell/security.png",		"is_folder"=>false,"display_name"=>gettext("SAS:Service"),"can_create"=>true),
			array("name"=>"sASSecurity",			"icon"=>"novell/security-container.png","is_folder"=>true,"display_name"=>gettext("Security Container")),
			array("name"=>"sASLoginMethodContainer",	"icon"=>"novell/login-method-container.png",	"is_folder"=>true,"display_name"=>gettext("Login Method Container")),
			array("name"=>"sasPostLoginMethodContainer",	"icon"=>"novell/login-method-container.png",	"is_folder"=>true,"display_name"=>gettext("Post-Login Method Container")),
			array("name"=>"sASNMASLoginMethod",		"icon"=>"novell/login-method.png",	"is_folder"=>false,"display_name"=>gettext("Login Method")),
			);

		parent::__construct($ldap_server);
	}
}
?>
