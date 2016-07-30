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

		// Display layouts

		$ldap_server->add_display_layout("nLSProductContainer",array(
			array("section_name"=>gettext("License Product Container"),
				"attributes"=>array(
					array("nLSProduct",		gettext("Product"),			"generic24.png"),
					array("nLSRevision",		gettext("Revision"),			"generic24.png"),
					array("nLSVersion",		gettext("Version"),			"generic24.png"),
					array("nLSPublisher",		gettext("Publisher"),			"generic24.png")
					)
				)
			));

		parent::__construct($ldap_server);
	}
}
?>
