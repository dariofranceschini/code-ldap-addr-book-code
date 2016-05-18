<?php
/** Novell Encryption schema (partial) */

class novell_encrypt_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"encryptionPolicyDN",		"data_type"=>"dn_list",		"display_name"=>gettext("Encryption Policy DN")),
			array("name"=>"attrEncryptionDefinition",	"data_type"=>"text_list",	"display_name"=>gettext("Encrypted Attributes")),
			);

		// Structural object classes

		$this->object_schema = array(
			array("name"=>"encryptionPolicy",		"icon"=>"novell/encryption-policy.png",	"is_folder"=>false,"display_name"=>gettext("Encrypted Attributes Policy")),
			);

		parent::__construct($ldap_server);
	}
}
?>
