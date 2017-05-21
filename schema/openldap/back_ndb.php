<?php
/** OpenLDAP Configuration (OLC) Schema for MySQL Network Database (NDB) Cluster Backend (partial) */

class openldap_back_ndb_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		// Object classes
		$this->object_schema = array(
			array("name"=>"olcNdbConfig",			"icon"=>"openldap/db.png",		"is_folder"=>false,"rdn_attrib"=>"olcDatabase","display_name"=>gettext("MySQL NDB Cluster Database"),"required_attribs"=>"olcSuffix,olcDbHost,olcDbName,olcDbConnect","parent_class"=>"olcDatabaseConfig")
			);

		parent::__construct($ldap_server);
	}
}
?>
