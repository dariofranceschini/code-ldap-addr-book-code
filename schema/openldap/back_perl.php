<?php
/** OpenLDAP Perl Backend On-Line Configuration (OLC) schema */

class openldap_back_perl_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"olcPerlFilterSearchResults",	"data_type"=>"yes_no",		"display_name"=>gettext("Filter Search Results within OpenLDAP")),
			array("name"=>"olcPerlModule",			"data_type"=>"text",		"display_name"=>gettext("Perl Module Name")),
			array("name"=>"olcPerlModuleConfig",		"data_type"=>"text",		"display_name"=>gettext("Perl Module Configuration Directives")),
			array("name"=>"olcPerlModulePath",		"data_type"=>"text",		"display_name"=>gettext("Perl Module Path"))
			);

		// Structural object classes
		$this->object_schema = array(
			array("name"=>"olcDbPerlConfig",			"icon"=>"openldap/db.png",		"is_folder"=>false,"rdn_attrib"=>"olcDatabase","display_name"=>gettext("Perl Module Database"),"required_attribs"=>"olcSuffix","parent_class"=>"olcDatabaseConfig")
			);

		parent::__construct($ldap_server);
	}
}
?>
