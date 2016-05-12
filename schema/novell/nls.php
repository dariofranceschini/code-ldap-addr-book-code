<?php
/** Novell Licensing Services (NLS) schema (partial) */

class novell_nls_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		// Structural object classes

		$this->object_schema = array(
			array("name"=>"nLSLicenseServer",		"icon"=>"novell/lic_srv.png",	"is_folder"=>false),
			);

		parent::__construct($ldap_server);
	}
}
?>
