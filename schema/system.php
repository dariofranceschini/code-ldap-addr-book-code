<?php
/** system schema (partial) */

class system_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			// Root DSE attributes
			array("name"=>"altServer",			"data_type"=>"text_list",	"display_name"=>gettext("Alternative Servers")),
			array("name"=>"namingContexts",			"data_type"=>"dn_list",		"display_name"=>gettext("Naming Contexts")),
			array("name"=>"subschemaSubentry",		"data_type"=>"dn",		"display_name"=>gettext("Subschema Subentry")),
			array("name"=>"supportedControl",		"data_type"=>"oid_list",	"display_name"=>gettext("Supported Controls")),
			array("name"=>"supportedExtension",		"data_type"=>"oid_list",	"display_name"=>gettext("Supported Extended Operations")),
			array("name"=>"supportedFeatures",		"data_type"=>"oid_list",	"display_name"=>gettext("Supported Features")),
			array("name"=>"supportedLDAPVersion",		"data_type"=>"text_list",	"display_name"=>gettext("Supported LDAP Versions")),
			array("name"=>"supportedSASLMechanisms",	"data_type"=>"text_list",	"display_name"=>gettext("Supported SASL Mechanisms")),

			// Schema definition attributes
			array("name"=>"objectClasses",			"data_type"=>"ldap_schema",	"display_name"=>gettext("Object Classes")),
			array("name"=>"attributeTypes",			"data_type"=>"ldap_schema",	"display_name"=>gettext("Attribute Types")),
			array("name"=>"ldapSyntaxes",			"data_type"=>"ldap_schema",	"display_name"=>gettext("LDAP Syntaxes")),
			array("name"=>"matchingRules",			"data_type"=>"ldap_schema",	"display_name"=>gettext("Matching Rules")),
			array("name"=>"matchingRuleUse",		"data_type"=>"ldap_schema",	"display_name"=>gettext("Matching Rule Use")),

			// Operational attributes
			array("name"=>"createTimestamp",		"data_type"=>"date_time",	"display_name"=>gettext("Created")),
			array("name"=>"creatorsName",			"data_type"=>"dn",		"display_name"=>gettext("Created By")),
			array("name"=>"modifyTimestamp",		"data_type"=>"date_time",	"display_name"=>gettext("Last Modified")),
			array("name"=>"modifiersName",			"data_type"=>"dn",		"display_name"=>gettext("Last Modified By")),
			);

		// Structural object classes
		$this->object_schema = array(
			array("name"=>"subschema",			"icon"=>"schema.png",		"is_folder"=>false,"display_name"=>gettext("Schema")),
			);

		parent::__construct($ldap_server);

		// component schema
		switch($ldap_server->server_type)
		{
			case "ad":		$ldap_server->add_schema("system/ad");		break;
			case "edir":		$ldap_server->add_schema("system/edir");	break;
			case "openldap":	$ldap_server->add_schema("system/openldap");	break;

			default:		// no change made for other server types
		}

	}
}
?>
