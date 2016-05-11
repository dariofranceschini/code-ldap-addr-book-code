<?php
/** Novell Directory Services Public Key Infrastructure schema (partial) */

class novell_ndspki_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		// Structural object classes
		$this->object_schema = array(
			array("name"=>"nDSPKIKeyMaterial",		"icon"=>"novell/key-material.png",	"is_folder"=>false,"display_name"=>gettext("NDSPKI:Key Material"),"can_create"=>true),
			);

		parent::__construct($ldap_server);
	}
}
?>
