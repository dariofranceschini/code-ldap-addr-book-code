<?php
/** OpenLDAP LDIF Backend On-Line Configuration (OLC) schema (partial) */

class openldap_back_ldif_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		// Structural object classes
		$this->object_schema = array(
			array("name"=>"olcLdifConfig",			"icon"=>"openldap/db.png",		"is_folder"=>false,"rdn_attrib"=>"olcDatabase","display_name"=>gettext("LDIF Database"),"required_attribs"=>"olcSuffix,olcDbDirectory","parent_class"=>"olcDatabaseConfig")
			);

		parent::__construct($ldap_server);
	}
}
?>
