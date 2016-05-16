<?php
/** Novell Service Location Protocol schema (partial) */

class novell_slp_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		// Structural object classes
		$this->object_schema = array(
			array("name"=>"SLPScopeUnit",		"icon"=>"novell/slp-scope-unit.png",		"is_folder"=>true,"rdn_attrib"=>"su","display_name"=>gettext("SLP Scope Unit")),
			array("name"=>"SLPDirectoryAgent",	"icon"=>"novell/slp-directory-agent.png",	"is_folder"=>false,"display_name"=>gettext("SLP Directory Agent")),
			array("name"=>"SLPService",		"icon"=>"novell/slp-service.png",		"is_folder"=>false,"display_name"=>gettext("SLP Service"))
			);

		parent::__construct($ldap_server);
	}
}
?>
