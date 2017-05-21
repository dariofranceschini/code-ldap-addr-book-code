<?php
/** OpenLDAP Configuration (OLC) Schema for WiredTiger Backend */

class openldap_back_wt_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			// olcDbIndex, olcDbDirectory - defined in common config schema
			array("name"=>"olcWtConfig",			"data_type"=>"text",		"display_name"=>gettext("WiredTiger Configuration"))
			);

		// Object classes
		$this->object_schema = array(
			array("name"=>"olcWtConfig",			"icon"=>"openldap/db.png",		"is_folder"=>false,"rdn_attrib"=>"olcDatabase","display_name"=>gettext("WiredTiger Database"),"required_attribs"=>"olcSuffix,olcDbDirectory","parent_class"=>"olcDatabaseConfig")
			);

		parent::__construct($ldap_server);
	}
}
?>
