<?php
/** system schema (partial) */

class system_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			// Root DSE attributes
			array("name"=>"altServer",			"data_type"=>"text_list",	"display_name"=>"Alternative Servers"),
			array("name"=>"namingContexts",			"data_type"=>"dn_list",		"display_name"=>"Naming Contexts"),
			array("name"=>"subschemaSubentry",		"data_type"=>"dn",		"display_name"=>"Subschema Subentry"),
			array("name"=>"supportedControl",		"data_type"=>"oid_list",	"display_name"=>"Supported Controls"),
			array("name"=>"supportedExtension",		"data_type"=>"oid_list",	"display_name"=>"Supported Extended Operations"),
			array("name"=>"supportedFeatures",		"data_type"=>"oid_list",	"display_name"=>"Supported Features"),
			array("name"=>"supportedLDAPVersion",		"data_type"=>"text_list",	"display_name"=>"Supported LDAP Versions"),
			array("name"=>"supportedSASLMechanisms",	"data_type"=>"text_list",	"display_name"=>"Supported SASL Mechanisms"),

			// Schema definition attributes
			array("name"=>"objectClasses",			"data_type"=>"text_list",	"display_name"=>"Object Classes"),
			array("name"=>"attributeTypes",			"data_type"=>"text_list",	"display_name"=>"Attribute Types"),
			array("name"=>"ldapSyntaxes",			"data_type"=>"text_list",	"display_name"=>"LDAP Syntaxes"),
			array("name"=>"matchingRules",			"data_type"=>"text_list",	"display_name"=>"Matching Rules"),
			array("name"=>"matchingRuleUse",		"data_type"=>"text_list",	"display_name"=>"Matching Rule Use"),

			// Operational attributes
			array("name"=>"createTimestamp",		"data_type"=>"date_time",	"display_name"=>gettext("Created")),
			array("name"=>"creatorsName",			"data_type"=>"dn",		"display_name"=>gettext("Created By")),
			array("name"=>"modifyTimestamp",		"data_type"=>"date_time",	"display_name"=>gettext("Last Modified")),
			array("name"=>"modifiersName",			"data_type"=>"dn",		"display_name"=>gettext("Last Modified By")),
			);

		// Structural object classes
		$this->object_schema = array(
			array("name"=>"subschema",			"icon"=>"system/schema.png",	"is_folder"=>false,"display_name"=>gettext("Schema")),
			);

		parent::__construct($ldap_server);
	}
}
?>
