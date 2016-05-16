<?php
/** Novell xTier schema (partial) */

class novell_xtier_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		// Structural object classes
		$this->object_schema = array(
			array("name"=>"xTier-StorageLocation",		"icon"=>"novell/xtier-storage-loc.png","is_folder"=>false),
			);

		parent::__construct($ldap_server);
	}
}
?>
