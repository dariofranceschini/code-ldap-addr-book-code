<?php
/** Novell Public Key Infrastructure schema (partial) */

class novell_pki_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"ndspkiKMOExport","data_type"=>"text_list","display_name"=>gettext("KMO Export"))		// key material object
			);

		// Object classes
		$this->object_schema = array(
			array("name"=>"ndspkiContainer",		"icon"=>"novell/pki-container.png",	"is_folder"=>true),
			array("name"=>"ndspkiCRLConfiguration",		"icon"=>"novell/pki-crl-config.png",	"is_folder"=>true),
			// Equivalent to RFC 2256 class
			array("name"=>"cRLDistributionPoint",		"icon"=>"crl-distrib-point.png",	"is_folder"=>false,"display_name"=>gettext("Certificate Revocation List Distribution Point"))
			);

		parent::__construct($ldap_server);
	}
}
?>
