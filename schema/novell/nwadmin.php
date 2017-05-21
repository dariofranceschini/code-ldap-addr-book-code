<?php
/** Novell NetWare Administrator schema (partial) */

class novell_nwadmin_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		// Object classes
		$this->object_schema = array(
			array("name"=>"Template",			"icon"=>"novell/template.png",		"is_folder"=>false,"display_name"=>gettext("Template"))
			);

		parent::__construct($ldap_server);
	}
}
?>
