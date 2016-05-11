<?php
/** Novell Secure Authentication Services (SAS) schema (partial) */

class novell_sas_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		// Structural object classes
		$this->object_schema = array(
			array("name"=>"sASService",			"icon"=>"novell/security.png",		"is_folder"=>false,"display_name"=>gettext("SAS:Service"),"can_create"=>true)
			);

		parent::__construct($ldap_server);
	}
}
?>
