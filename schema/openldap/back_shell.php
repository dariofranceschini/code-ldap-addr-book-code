<?php
/** OpenLDAP Shell Backend On-Line Configuration (OLC) schema */

class openldap_back_shell_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"olcShellAdd",			"data_type"=>"text",		"display_name"=>gettext("LDAP Add Command and Arguments")),
			array("name"=>"olcShellBind",			"data_type"=>"text",		"display_name"=>gettext("LDAP Bind Command and Arguments")),
			array("name"=>"olcShellCompare",		"data_type"=>"text",		"display_name"=>gettext("LDAP Compare Command and Arguments")),
			array("name"=>"olcShellDelete",			"data_type"=>"text",		"display_name"=>gettext("LDAP Delete Command and Arguments")),
			array("name"=>"olcShellModify",			"data_type"=>"text",		"display_name"=>gettext("LDAP Modify Command and Arguments")),
			array("name"=>"olcShellModRDN",			"data_type"=>"text",		"display_name"=>gettext("LDAP Modify RDN Command and Arguments")),
			array("name"=>"olcShellSearch",			"data_type"=>"text",		"display_name"=>gettext("LDAP Search Command and Arguments")),
			array("name"=>"olcShellUnbind",			"data_type"=>"text",		"display_name"=>gettext("LDAP Unbind Command and Arguments"))
			);

		// Structural object classes
		$this->object_schema = array(
			array("name"=>"olcShellConfig",			"icon"=>"openldap/db.png",		"is_folder"=>false,"rdn_attrib"=>"olcDatabase","display_name"=>gettext("Shell Database"),"required_attribs"=>"olcSuffix","parent_class"=>"olcDatabaseConfig")
			);

		parent::__construct($ldap_server);
	}
}
?>
