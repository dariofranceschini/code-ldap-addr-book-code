<?php
/** OpenLDAP Metadirectory Backend On-Line Configuration (OLC) schema (partial) */

class openldap_back_meta_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"olcDbRewrite",			"data_type"=>"text_list",	"display_name"=>gettext("String Rewriting Rule"))
			);

		// Structural object classes
		$this->object_schema = array(
			array("name"=>"olcMetaConfig",			"icon"=>"openldap/db.png",		"is_folder"=>false,"rdn_attrib"=>"olcDatabase","display_name"=>gettext("Metadirectory Database"),"required_attribs"=>"olcSuffix","parent_class"=>"olcDatabaseConfig"),
			array("name"=>"olcMetaTargetConfig",		"icon"=>"openldap/meta-target.png",	"is_folder"=>false,"rdn_attrib"=>"olcMetaSub","display_name"=>gettext("Metadirectory Target Server"),"contained_by"=>"olcMetaConfig")
			);

		parent::__construct($ldap_server);
	}
}
?>
