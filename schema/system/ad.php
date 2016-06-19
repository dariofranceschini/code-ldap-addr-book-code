<?php
/** system schema - Active Directory specific items (partial) */

class system_ad_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"configContext",			"data_type"=>"dn",		"display_name"=>gettext("Configuration Context")),
			array("name"=>"configurationNamingContext",	"data_type"=>"dn",		"display_name"=>gettext("Configuration Naming Context")),
			array("name"=>"currentTime",			"data_type"=>"date_time",	"display_name"=>gettext("Current Time")),
			array("name"=>"defaultNamingContext",		"data_type"=>"dn",		"display_name"=>gettext("Default Naming Context")),
			array("name"=>"domainControllerFunctionality",	"data_type"=>"ad_func_level",	"display_name"=>gettext("Server Functional Level")),
			array("name"=>"domainFunctionality",		"data_type"=>"ad_func_level",	"display_name"=>gettext("Domain Functional Level")),
			array("name"=>"dSServiceName",			"data_type"=>"dn",		"display_name"=>gettext("Directory Service Name")),
			array("name"=>"forestFunctionality",		"data_type"=>"ad_func_level",	"display_name"=>gettext("Forest Functional Level")),
			array("name"=>"isSynchronized",			"data_type"=>"yes_no",		"display_name"=>gettext("Is Synchronized")),
			array("name"=>"rootDomainNamingContext",	"data_type"=>"dn",		"display_name"=>gettext("Root Domain Naming Context")),
			array("name"=>"schemaNamingContext",		"data_type"=>"dn",		"display_name"=>gettext("Schema Naming Context")),
			array("name"=>"supportedCapabilities",		"data_type"=>"oid_list",	"display_name"=>gettext("Supported Capabilities")),
			);

		// Structural object classes
		$this->object_schema = array(
			array("name"=>"rootDSE",			"icon"=>"generic24.png",	"is_folder"=>true,"display_name"=>gettext("Root DSE")),
			);

		// remove various "RFC standard" items that are not implemented in Active Directory
		$ldap_server->delete_attribute_class("altServer");
		$ldap_server->delete_attribute_class("supportedExtension");
		$ldap_server->delete_attribute_class("supportedFeatures");
		$ldap_server->delete_attribute_class("ldapSyntaxes");
		$ldap_server->delete_attribute_class("matchingRules");
		$ldap_server->delete_attribute_class("matchingRuleUse");
		$ldap_server->delete_attribute_class("creatorsName");
		$ldap_server->delete_attribute_class("modifiersName");

		// capitalised as "subSchema" in microsoft.std.schema
		$ldap_server->delete_object_class("subschema");

		// Display layouts
		$ldap_server->add_display_layout("rootDSE",array(
			array("section_name"=>gettext("Active Directory Domain Controller"),"width"=>"50%",
				"attributes"=>array(
					array("serverName",			gettext("Server Name"),			"alias.png"),
					array("vendorName",			gettext("Vendor Name"),			"generic24.png"),
					array("dnsHostName",			gettext("DNS Host Name"),		"generic24.png"),
					array("dSServiceName",			gettext("Directory Service"),		"alias.png"),
					array("supportedLDAPVersion",		gettext("Supported LDAP Versions"),	"generic24.png"),
					array("ldapServiceName",		gettext("LDAP Service Name"),		"generic24.png"),
					array("namingContexts",			gettext("Naming Contexts"),		"alias.png"),
					array("subschemaSubentry",		gettext("Subschema Subentry"),		"alias.png"),
					array("isSynchronized",			gettext("Is Synchronized"),		"generic24.png"),
					array("currentTime",			gettext("Current Time at Server"),	"generic24.png"),
					array("highestCommittedUSN",		gettext("Highest Committed USN"),	"generic24.png"),
					array("domainControllerFunctionality",	gettext("Server Functional Level"),	"generic24.png"),
					array("domainFunctionality",		gettext("Domain Functional Level"),	"generic24.png"),
					array("forestFunctionality",		gettext("Forest Functional Level"),	"generic24.png")
					),
				),
			array("section_name"=>gettext("Well-Known Naming Contexts"),
				"attributes"=>array(
					array("defaultNamingContext",		gettext("Default (Domain)"),		"alias.png"),
					array("configurationNamingContext",	gettext("Configuration"),		"alias.png"),
					array("rootDomainNamingContext",	gettext("Forest Root Domain"),		"alias.png"),
					array("schemaNamingContext",		gettext("Schema"),			"alias.png")
					)
				),
			array("section_name"=>gettext("Supported Controls"),"new_row"=>true,"colspan"=>2,
				"attributes"=>array(
					array("supportedControl")
					)
				),
			array("section_name"=>gettext("Supported Capabilities"),"new_row"=>true,"colspan"=>2,
				"attributes"=>array(
					array("supportedCapabilities")
					)
				),
			array("section_name"=>gettext("Supported SASL Mechanisms"),"new_row"=>true,"colspan"=>2,
				"attributes"=>array(
					array("supportedSASLMechanisms")
					)
				)
			));

		parent::__construct($ldap_server);
	}
}
?>
