<?php
/** Novell Service Location Protocol schema (partial) */

class novell_slp_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		// Object classes
		$this->object_schema = array(
			array("name"=>"SLPScopeUnit",		"icon"=>"novell/slp-scope-unit.png",		"is_folder"=>true,"rdn_attrib"=>"su","display_name"=>gettext("SLP Scope Unit")),
			array("name"=>"SLPDirectoryAgent",	"icon"=>"novell/slp-directory-agent.png",	"is_folder"=>false,"display_name"=>gettext("SLP Directory Agent")),
			array("name"=>"SLPService",		"icon"=>"novell/slp-service.png",		"is_folder"=>false,"display_name"=>gettext("SLP Service"))
			);

		// Display layouts
		$ldap_server->add_display_layout("SLPScopeUnit",array(
			array("section_name"=>gettext("SLP Scope Unit"),
				"attributes"=>array(
					array("su",			gettext("Name"),			"generic24.png"),
					)
				),
			));

		$ldap_server->add_display_layout("SLPService",array(
			array("section_name"=>gettext("SLP Service"),
				"attributes"=>array(
					array("SLPType",		gettext("Type"),			"generic24.png"),
					array("SLPLifetime",		gettext("Lifetime"),			"generic24.png"),
					array("SLPLanguage",		gettext("Language"),			"generic24.png"),
					array("SLPURL",			gettext("URL"),				"generic24.png")
					)
				),
			));

		parent::__construct($ldap_server);
	}
}
?>
