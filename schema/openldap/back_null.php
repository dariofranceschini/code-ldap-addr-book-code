<?php
/** OpenLDAP Null Backend On-Line Configuration (OLC) schema */

class openldap_back_null_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"olcDbBindAllowed",		"data_type"=>"yes_no",		"display_name"=>gettext("Allow Binds To This Database"))
			);

		// Structural object classes
		$this->object_schema = array(
			array("name"=>"olcNullConfig",			"icon"=>"openldap/db.png",		"is_folder"=>false,"rdn_attrib"=>"olcDatabase","display_name"=>gettext("Null Database"),"required_attribs"=>"olcSuffix","parent_class"=>"olcDatabaseConfig")
				);

		parent::__construct($ldap_server);
	}
}
?>
