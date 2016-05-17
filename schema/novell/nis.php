<?php
/** Novell Network Information Service (NIS) schema (partial) */

class novell_nis_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		// Structural object classes

		$this->object_schema = array(
			array("name"=>"nisServer",		"icon"=>"novell/nis-server.png",		"is_folder"=>false),
			);

		parent::__construct($ldap_server);
	}
}
?>
