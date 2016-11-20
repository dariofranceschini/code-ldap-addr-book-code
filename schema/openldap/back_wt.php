<?php
/** OpenLDAP WiredTiger Backend On-Line Configuration (OLC) schema */

class openldap_back_wt_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			// olcDbIndex, olcDbDirectory - defined in back_config
			array("name"=>"olcWtConfig",			"data_type"=>"text",		"display_name"=>gettext("WiredTiger Configuration"))
			);

		// Structural object classes
		$this->object_schema = array(
			array("name"=>"olcWtConfig",			"icon"=>"openldap/db.png",		"is_folder"=>false,"rdn_attrib"=>"olcDatabase","display_name"=>gettext("WiredTiger Database"),"required_attribs"=>"olcSuffix,olcDbDirectory","parent_class"=>"olcDatabaseConfig")
			);

		parent::__construct($ldap_server);
	}
}
?>
