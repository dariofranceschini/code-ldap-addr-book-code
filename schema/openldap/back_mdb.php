<?php
/** OpenLDAP MDB Backend On-Line Configuration (OLC) schema (partial) */

class openldap_back_mdb_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		// Attributes
		$this->attribute_schema = array(
			array("name"=>"olcDbMaxSize",			"data_type"=>"text",		"display_name"=>gettext("Maximum Database Size"))
			);

		// Structural object classes
		$this->object_schema = array(
			array("name"=>"olcMdbConfig",			"icon"=>"openldap/db.png",		"is_folder"=>false,"rdn_attrib"=>"olcDatabase","display_name"=>gettext("MDB Database"),"required_attribs"=>"olcSuffix,olcDbDirectory","parent_class"=>"olcDatabaseConfig")
			);

		// abstract class 'olmMDBDatabase' is also defined in this schema

		parent::__construct($ldap_server);
	}
}
?>
