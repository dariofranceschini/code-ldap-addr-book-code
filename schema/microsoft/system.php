<?php
/** system schema - Active Directory specific items (partial) */

class microsoft_system_schema extends ldap_schema
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
			array("name"=>"namingContexts",			"data_type"=>"dn_list",		"display_name"=>gettext("Naming Contexts")),
			array("name"=>"rootDomainNamingContext",	"data_type"=>"dn",		"display_name"=>gettext("Root Domain Naming Context")),
			array("name"=>"schemaNamingContext",		"data_type"=>"dn",		"display_name"=>gettext("Schema Naming Context")),
			array("name"=>"supportedCapabilities",		"data_type"=>"oid_list",	"display_name"=>gettext("Supported Capabilities")),
			array("name"=>"supportedControl",		"data_type"=>"oid_list",	"display_name"=>gettext("Supported Controls")),
			array("name"=>"supportedLDAPVersion",		"data_type"=>"ldap_version",	"display_name"=>gettext("Supported LDAP Versions")),
			array("name"=>"supportedSASLMechanisms",	"data_type"=>"text_list",	"display_name"=>gettext("Supported SASL Mechanisms")),
			);

		// Object classes
		$this->object_schema = array(
			array("name"=>"rootDSE",			"icon"=>"generic24.png",	"is_folder"=>true,"display_name"=>gettext("Root DSE")),
			);

		// Display layouts
		$ldap_server->add_display_layout("rootDSE",array(
			array("section_name"=>gettext("Active Directory Domain Controller"),"width"=>"50%",
				"attributes"=>array(
					array("serverName",			gettext("Server Name"),			"alias.png","allow_edit"=>false),
					array("vendorName",			gettext("Vendor Name"),			"generic24.png","allow_edit"=>false),
					array("dNSHostName",			gettext("DNS Host Name"),		"generic24.png","allow_edit"=>false),
					array("dSServiceName",			gettext("Directory Service"),		"alias.png","allow_edit"=>false),
					array("supportedLDAPVersion",		gettext("Supported LDAP Versions"),	"generic24.png","allow_edit"=>false),
					array("ldapServiceName",		gettext("LDAP Service Name"),		"generic24.png","allow_edit"=>false),
					array("namingContexts",			gettext("Naming Contexts"),		"alias.png","allow_edit"=>false),
					array("subschemaSubentry",		gettext("Subschema Subentry"),		"alias.png","allow_edit"=>false),
					array("isSynchronized",			gettext("Is Synchronized"),		"generic24.png","allow_edit"=>false),
					array("currentTime",			gettext("Current Time at Server"),	"time.png","allow_edit"=>false),
					array("highestCommittedUSN",		gettext("Highest Committed USN"),	"generic24.png","allow_edit"=>false),
					array("domainControllerFunctionality",	gettext("Server Functional Level"),	"generic24.png","allow_edit"=>false),
					array("domainFunctionality",		gettext("Domain Functional Level"),	"generic24.png","allow_edit"=>false),
					array("forestFunctionality",		gettext("Forest Functional Level"),	"generic24.png","allow_edit"=>false)
					),
				),
			array("section_name"=>gettext("Well-Known Naming Contexts"),
				"attributes"=>array(
					array("defaultNamingContext",		gettext("Default (Domain)"),		"alias.png","allow_edit"=>false),
					array("configurationNamingContext",	gettext("Configuration"),		"alias.png","allow_edit"=>false),
					array("rootDomainNamingContext",	gettext("Forest Root Domain"),		"alias.png","allow_edit"=>false),
					array("schemaNamingContext",		gettext("Schema"),			"alias.png","allow_edit"=>false)
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
					array("supportedSASLMechanisms","allow_edit"=>false)
					)
				)
			));

		$ldap_server->add_display_layout("subSchema",array(
			array("section_name"=>gettext("Object Class Definitions"),"new_row"=>true,
				"attributes"=>array(
					array("objectClasses")
					)
				),
			array("section_name"=>gettext("Attribute Type Definitions"),"new_row"=>true,
				"attributes"=>array(
					array("attributeTypes")
					)
				),
			array("section_name"=>gettext("Extended Class Information"),"new_row"=>true,
				"attributes"=>array(
					array("extendedClassInfo")
					)
				),
			array("section_name"=>gettext("Extended Attribute Information"),"new_row"=>true,
				"attributes"=>array(
					array("extendedAttributeInfo")
					)
				),
			array("section_name"=>gettext("DIT Content Rules"),"new_row"=>true,
				"attributes"=>array(
					array("dITContentRules")
					)
				)
			));

		parent::__construct($ldap_server);
	}
}
?>
