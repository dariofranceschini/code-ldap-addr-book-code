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

		// Object classes
		$this->object_schema = array(
			array("name"=>"nDSPKIKeyMaterial",		"icon"=>"novell/key-material.png",	"is_folder"=>false,"display_name"=>gettext("Key Material")),
			array("name"=>"nDSPKISDKeyAccessPartition",	"icon"=>"novell/sd-key-access-partition.png","is_folder"=>true,"display_name"=>gettext("Security Domain Key Access Partition")),
			array("name"=>"nDSPKISDKeyList",		"icon"=>"novell/sd-key-list.png",	"is_folder"=>true,"display_name"=>gettext("Security Domain Key List")),
			array("name"=>"nDSPKICertificateAuthority",	"icon"=>"cert-authority.png",		"is_folder"=>false,"display_name"=>gettext("Certificate Authority"))
			);

		// Display layouts
		$ldap_server->add_display_layout("nDSPKIKeyMaterial",array(
			array("section_name"=>gettext("Certificate Information"),
				"attributes"=>array(
					array("ndspkiGivenName",			gettext("Distinguished Name"),			"generic24.png"),
					array("hostServer",				gettext("Certificate for Server"),		"generic24.png"),
					array("ndspkiSubjectName",			gettext("Subject Name"),			"generic24.png"),
					array("ndspkiNotBefore",			gettext("Valid From"),				"generic24.png"),
					array("ndspkiNotAfter",				gettext("Valid To"),				"generic24.png"),
					array("ndspkiCertificateChain",			gettext("Certificate Chain"),			"generic24.png"),
					array("ndspkiPublicKeyCertificate",		gettext("Public Key Certificate"),		"generic24.png"),
					// array("ndspkiPrivateKey",			gettext("Private Key"),				"generic24.png"),
					array("ndspkiPublicKey",			gettext("Public Key"),				"generic24.png")
					)
				),
			));

		$ldap_server->add_display_layout("nDSPKICertificateAuthority",array(
			array("section_name"=>gettext("Certificate Authority"),
				"attributes"=>array(
					array("cn",					gettext("Object Name"),				"generic24.png"),
					array("ndspkiSubjectName",			gettext("CA Subject Name"),			"generic24.png"),
					array("ndspkiParentCA",				gettext("Parent CA"),				"generic24.png"),
					array("hostServer",				gettext("Host Server"),				"generic24.png"),
					array("ndspkiIssuedCertContainerDN",		gettext("Issued Certificate Container"),	"generic24.png"),
					array("ndspkiCRLContainerDN",			gettext("CRL Container"),			"generic24.png"),
					array("ndspkiCRLConfigurationDNList",		gettext("CRL Configuration DNs"),		"generic24.png"),
					array("ndspkiCertificateChain",			gettext("Certificate Chain"),			"generic24.png"),
					array("ndspkiPublicKeyCertificate",		gettext("Public Key Certificate"),		"generic24.png"),
					// array("ndspkiPrivateKey",			gettext("Private Key"),				"generic24.png"),
					array("ndspkiPublicKey",			gettext("Public Key"),				"generic24.png"),
					array("ndsCrossCertificatePair",		gettext("Cross Certificate Pair"),		"generic24.png"),
					array("cACertificate;binary",			gettext("CA Certificate"),			"generic24.png")
					)
				),
			));

		parent::__construct($ldap_server);
	}
}
?>
