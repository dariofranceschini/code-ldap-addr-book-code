<?php
/** Novell Directory Services Public Key Infrastructure schema (partial) */

class novell_ndspki_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"ndspkiNotBefore",		"data_type"=>"date_time",	"display_name"=>gettext("Valid From Date")),
			array("name"=>"ndspkiNotAfter",			"data_type"=>"date_time",	"display_name"=>gettext("Valid To Date")),
			array("name"=>"ndspkiKeyMaterialDN",		"data_type"=>"dn_list",		"display_name"=>gettext("Key Material DN List")),
			array("name"=>"ndspkiIssuedCertContainerDN",	"data_type"=>"dn_list",		"display_name"=>gettext("Issued Certificates Container DN")),
			array("name"=>"ndspkiCRLContainerDN",		"data_type"=>"dn_list",		"display_name"=>gettext("CRL Container DN")),
			array("name"=>"ndspkiCRLConfigurationDNList",	"data_type"=>"dn_list",		"display_name"=>gettext("CRL Configuration DN List")),
			array("name"=>"cACertificate;binary",		"data_type"=>"download",	"display_name"=>gettext("CA Certificate")),
			array("name"=>"ndspkiCertificateChain",		"data_type"=>"download_list",	"display_name"=>gettext("Certificate Chain")),
			array("name"=>"ndspkiPublicKeyCertificate",	"data_type"=>"download",	"display_name"=>gettext("Public Key Certificate")),
			array("name"=>"ndspkiPrivateKey",		"data_type"=>"download",	"display_name"=>gettext("Private Key")),
			array("name"=>"ndspkiPublicKey",		"data_type"=>"download",	"display_name"=>gettext("Public Key"))
			);

		// Structural object classes
		$this->object_schema = array(
			array("name"=>"nDSPKIKeyMaterial",		"icon"=>"novell/key-material.png",	"is_folder"=>false,"display_name"=>gettext("Key Material")),
			array("name"=>"nDSPKISDKeyAccessPartition",	"icon"=>"novell/sd-key-access-partition.png","is_folder"=>true,"display_name"=>gettext("Security Domain Key Access Partition")),
			array("name"=>"nDSPKISDKeyList",		"icon"=>"novell/sd-key-list.png",	"is_folder"=>true,"display_name"=>gettext("Security Domain Key List")),
			array("name"=>"nDSPKICertificateAuthority",	"icon"=>"novell/cert-authority.png",	"is_folder"=>false,"display_name"=>gettext("Certificate Authority"))
			);

		parent::__construct($ldap_server);
	}
}
?>
