<?php
/** Novell Directory Services (NDS) schema (partial) */

class novell_nds_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		// Structural object classes
		$this->object_schema = array(
			array("name"=>"ndsPredicateStats",		"icon"=>"novell/stats.png",		"is_folder"=>false),
			);

		parent::__construct($ldap_server);
	}
}
?>
