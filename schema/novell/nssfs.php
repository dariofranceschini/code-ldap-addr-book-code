<?php
/** Novell Storage Services schema (partial) */

class novell_nssfs_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		// Structural object classes
		$this->object_schema = array(
			array("name"=>"nssfsPool",			"icon"=>"novell/nssfs-pool.png",	"is_folder"=>false)
			);

		parent::__construct($ldap_server);
	}
}
?>
