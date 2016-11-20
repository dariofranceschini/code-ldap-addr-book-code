<?php
/** OpenLDAP Asynchronous Metadirectory Backend On-Line Configuration (OLC) schema (partial) */

class openldap_back_asyncmeta_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		// Structural object classes
		$this->object_schema = array(
			array("name"=>"olcAsyncMetaConfig",		"icon"=>"openldap/db.png",		"is_folder"=>false,"rdn_attrib"=>"olcDatabase","display_name"=>gettext("Asynchronous Metadirectory Database"),"required_attribs"=>"olcSuffix","parent_class"=>"olcDatabaseConfig"),
			array("name"=>"olcAsyncMetaTargetConfig",	"icon"=>"openldap/meta-target.png",	"is_folder"=>false,"rdn_attrib"=>"olcAsyncMetaSub","required_attribs"=>"olcDbURI","display_name"=>gettext("Asynchronous Metadirectory Target Server"),"contained_by"=>"olcAsyncMetaConfig")
			);

		parent::__construct($ldap_server);
	}
}
?>
