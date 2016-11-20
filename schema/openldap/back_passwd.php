<?php
/** OpenLDAP Password File Backend On-Line Configuration (OLC) schema */

class openldap_back_passwd_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"olcPasswdFile",			"data_type"=>"text",		"display_name"=>gettext("Password File Name"))
			);

		// Structural object classes
		$this->object_schema = array(
			array("name"=>"olcPasswdConfig",		"icon"=>"openldap/db.png",		"is_folder"=>false,"rdn_attrib"=>"olcDatabase","display_name"=>gettext("Password File Database"),"parent_class"=>"olcDatabaseConfig")
			);

		parent::__construct($ldap_server);
	}
}
?>
