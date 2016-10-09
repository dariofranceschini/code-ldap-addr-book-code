<?php
/** system schema - OpenLDAP specific items (partial) */

class system_openldap_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"configContext",			"data_type"=>"dn",		"display_name"=>gettext("Configuration Context")),
			array("name"=>"monitorContext",			"data_type"=>"dn",		"display_name"=>gettext("Monitoring Context")),
			);

		// Structural object classes
		$this->object_schema = array(
			array("name"=>"OpenLDAProotDSE",		"icon"=>"org.png",			"is_folder"=>true,"display_name"=>gettext("OpenLDAP Root DSE")),
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
			array("section_name"=>gettext("Supported Extensions"),"new_row"=>true,
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

		$ldap_server->add_schema("openldap/memberof");

		parent::__construct($ldap_server);
	}
}
?>
