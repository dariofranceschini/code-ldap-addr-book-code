<?php
/** OpenLDAP System Schema (partial) */

class openldap_system_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			// Root DSE attributes
			array("name"=>"altServer",			"data_type"=>"text_list",	"display_name"=>gettext("Alternative Servers")),
			array("name"=>"configContext",			"data_type"=>"dn",		"display_name"=>gettext("Configuration Context")),
			array("name"=>"monitorContext",			"data_type"=>"dn",		"display_name"=>gettext("Monitoring Context")),
			array("name"=>"namingContexts",			"data_type"=>"dn_list",		"display_name"=>gettext("Naming Contexts")),
			array("name"=>"subschemaSubentry",		"data_type"=>"dn",		"display_name"=>gettext("Subschema Subentry")),
			array("name"=>"supportedControl",		"data_type"=>"oid_list",	"display_name"=>gettext("Supported Controls")),
			array("name"=>"supportedExtension",		"data_type"=>"oid_list",	"display_name"=>gettext("Supported Extended Operations")),
			array("name"=>"supportedFeatures",		"data_type"=>"oid_list",	"display_name"=>gettext("Supported Features")),
			array("name"=>"supportedLDAPVersion",		"data_type"=>"ldap_version",	"display_name"=>gettext("Supported LDAP Versions")),
			array("name"=>"supportedSASLMechanisms",	"data_type"=>"text_list",	"display_name"=>gettext("Supported SASL Mechanisms")),

			// Schema definition attributes
			array("name"=>"attributeTypes",			"data_type"=>"ldap_schema",	"display_name"=>gettext("Attribute Types")),
			array("name"=>"dITContentRules",		"data_type"=>"ldap_schema",	"display_name"=>gettext("DIT Content Rules")),
			array("name"=>"ldapSyntaxes",			"data_type"=>"ldap_schema",	"display_name"=>gettext("LDAP Syntaxes")),
			array("name"=>"matchingRules",			"data_type"=>"ldap_schema",	"display_name"=>gettext("Matching Rules")),
			array("name"=>"matchingRuleUse",		"data_type"=>"ldap_schema",	"display_name"=>gettext("Matching Rule Use")),
			array("name"=>"objectClasses",			"data_type"=>"ldap_schema",	"display_name"=>gettext("Object Classes")),

			// Operational attributes
			array("name"=>"createTimestamp",		"data_type"=>"date_time",	"display_name"=>gettext("Created")),
			array("name"=>"creatorsName",			"data_type"=>"dn",		"display_name"=>gettext("Created By")),
			array("name"=>"modifiersName",			"data_type"=>"dn",		"display_name"=>gettext("Last Modified By")),
			array("name"=>"modifyTimestamp",		"data_type"=>"date_time",	"display_name"=>gettext("Last Modified")),
			);

		// Object classes
		$this->object_schema = array(
			array("name"=>"OpenLDAProotDSE",		"icon"=>"org.png",			"is_folder"=>true,"display_name"=>gettext("OpenLDAP Root DSE"),"alias_names"=>"LDAProotDSE"),
			array("name"=>"subschema",			"icon"=>"schema.png",		"is_folder"=>false,"display_name"=>gettext("Schema")),
			);

		// Display layouts
		$ldap_server->add_display_layout("OpenLDAProotDSE",array(
			array("section_name"=>gettext("OpenLDAP Server"),"new_row"=>true,
				"attributes"=>array(
					array("supportedLDAPVersion",		gettext("Supported LDAP Versions"),	"generic24.png"),
					array("namingContexts",			gettext("Naming Contexts"),		"alias.png"),
					array("configContext",			gettext("Configuration Context"),	"alias.png"),
					array("monitorContext",			gettext("Monitoring Context"),		"alias.png"),
					array("subschemaSubentry",		gettext("Subschema Subentry"),		"alias.png")
					)
				),
			array("section_name"=>gettext("Supported Controls"),"new_row"=>true,
				"attributes"=>array(
					array("supportedControl")
					)
				),
			array("section_name"=>gettext("Supported Features"),"new_row"=>true,
				"attributes"=>array(
					array("supportedFeatures")
					)
				),
			array("section_name"=>gettext("Supported SASL Mechanisms"),"new_row"=>true,
				"attributes"=>array(
					array("supportedSASLMechanisms")
					)
				),
			array("section_name"=>gettext("Supported Extended Operations"),"new_row"=>true,
				"attributes"=>array(
					array("supportedExtension")
					)
				)
			));

		$ldap_server->add_display_layout("subschema",array(
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
			array("section_name"=>gettext("LDAP Syntax Definitions"),"new_row"=>true,
				"attributes"=>array(
					array("ldapSyntaxes")
					)
				),
			array("section_name"=>gettext("Matching Rules"),"new_row"=>true,
				"attributes"=>array(
					array("matchingRules")
					)
				),
			array("section_name"=>gettext("Matching Rule Use"),"new_row"=>true,
				"attributes"=>array(
					array("matchingRuleUse")
					)
				)
			));

		// OpenLDAP module schemas

		$ldap_server->add_schema("openldap/accesslog");
		$ldap_server->add_schema("openldap/autogroup");
		$ldap_server->add_schema("openldap/auditlog");
		$ldap_server->add_schema("openldap/back_asyncmeta");
		$ldap_server->add_schema("openldap/back_bdb");
		$ldap_server->add_schema("openldap/back_hdb");
		$ldap_server->add_schema("openldap/back_ldap");
		$ldap_server->add_schema("openldap/back_ldif");
		$ldap_server->add_schema("openldap/back_mdb");
		$ldap_server->add_schema("openldap/back_meta");
		$ldap_server->add_schema("openldap/back_monitor");
		$ldap_server->add_schema("openldap/back_ndb");
		$ldap_server->add_schema("openldap/back_null");
		$ldap_server->add_schema("openldap/back_passwd");
		$ldap_server->add_schema("openldap/back_perl");
		$ldap_server->add_schema("openldap/back_relay");
		$ldap_server->add_schema("openldap/back_shell");
		$ldap_server->add_schema("openldap/back_sock");
		$ldap_server->add_schema("openldap/back_sql");
		$ldap_server->add_schema("openldap/back_wt");
		$ldap_server->add_schema("openldap/collect");
		$ldap_server->add_schema("openldap/config");
		$ldap_server->add_schema("openldap/constraint");
		$ldap_server->add_schema("openldap/dds");
		$ldap_server->add_schema("openldap/dyngroup");
		$ldap_server->add_schema("openldap/dynlist");
		$ldap_server->add_schema("openldap/lastbind");
		$ldap_server->add_schema("openldap/memberof");
		$ldap_server->add_schema("openldap/nssov");
		$ldap_server->add_schema("openldap/pcache");
		$ldap_server->add_schema("openldap/ppolicy");
		$ldap_server->add_schema("openldap/refint");
		$ldap_server->add_schema("openldap/retcode");
		$ldap_server->add_schema("openldap/rwm");
		$ldap_server->add_schema("openldap/smbk5pwd");
		$ldap_server->add_schema("openldap/sssvlv");
		$ldap_server->add_schema("openldap/syncprov");
		$ldap_server->add_schema("openldap/translucent");
		$ldap_server->add_schema("openldap/unique");
		$ldap_server->add_schema("openldap/valsort");

		parent::__construct($ldap_server);
	}
}
?>
