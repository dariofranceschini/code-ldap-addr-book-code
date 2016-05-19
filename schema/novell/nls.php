<?php
/** Novell Licensing Services (NLS) schema (partial) */

class novell_nls_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		// Structural object classes

		$this->object_schema = array(
			array("name"=>"nLSLicenseServer",		"icon"=>"novell/license-server.png",	"is_folder"=>false,"display_name"=>gettext("License Service Provider")),
			array("name"=>"nLSProductContainer",		"icon"=>"novell/license-container.png",	"is_folder"=>true,"rdn_attrib"=>"nLSPublisher,nLSProduct,nLSVersion","required_attribs"=>"nLSRevision","display_name"=>gettext("License Product Container")),
			array("name"=>"nLSLicenseCertificate",		"icon"=>"novell/license-cert.png",	"is_folder"=>false,"rdn_attrib"=>"nLSLicenseID","display_name"=>gettext("License Certificate")),
			);

		parent::__construct($ldap_server);
	}
}
?>
