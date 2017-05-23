<?php
/** eDirectory System Schema (partial) */

class novell_system_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
                $this->attribute_schema = array(
                        // Root DSE attributes
                        array("name"=>"altServer",                      "data_type"=>"text_list",       "display_name"=>gettext("Alternative Servers")),
			array("name"=>"dSAName",			"data_type"=>"dn",		"display_name"=>gettext("DSA Name")),
                        array("name"=>"namingContexts",                 "data_type"=>"dn_list",         "display_name"=>gettext("Naming Contexts")),
			array("name"=>"subschemaSubentry",		"data_type"=>"dn",		"display_name"=>gettext("Subschema Subentry")),
                        array("name"=>"supportedControl",               "data_type"=>"oid_list",        "display_name"=>gettext("Supported Controls")),
                        array("name"=>"supportedExtension",             "data_type"=>"oid_list",        "display_name"=>gettext("Supported Extended Operations")),
                        array("name"=>"supportedFeatures",              "data_type"=>"oid_list",        "display_name"=>gettext("Supported Features")),
			// See: draft-zeilenga-ldap-grouping-06
			array("name"=>"supportedGroupingTypes",		"data_type"=>"oid_list",	"display_name"=>gettext("Supported Grouping Types")),
                        array("name"=>"supportedLDAPVersion",           "data_type"=>"ldap_version",    "display_name"=>gettext("Supported LDAP Versions")),
                        array("name"=>"supportedSASLMechanisms",        "data_type"=>"text_list",       "display_name"=>gettext("Supported SASL Mechanisms")),

                        // Schema definition attributes
			array("name"=>"attributeTypes",			"data_type"=>"ldap_schema",	"display_name"=>gettext("Attribute Types")),
                        array("name"=>"ldapSyntaxes",                   "data_type"=>"ldap_schema",     "display_name"=>gettext("LDAP Syntaxes")),
                        array("name"=>"matchingRules",                  "data_type"=>"ldap_schema",     "display_name"=>gettext("Matching Rules")),
                        array("name"=>"matchingRuleUse",                "data_type"=>"ldap_schema",     "display_name"=>gettext("Matching Rule Use")),
			array("name"=>"objectClasses",			"data_type"=>"ldap_schema",	"display_name"=>gettext("Object Classes")),

                        // Operational attributes
                        array("name"=>"creatorsName",                   "data_type"=>"dn",              "display_name"=>gettext("Created By")),
			array("name"=>"createTimestamp",		"data_type"=>"date_time",	"display_name"=>gettext("Created")),
                        array("name"=>"modifiersName",                  "data_type"=>"dn",              "display_name"=>gettext("Last Modified By")),
			array("name"=>"modifyTimestamp",		"data_type"=>"date_time",	"display_name"=>gettext("Last Modified")),
                        );

                // Object classes
                $this->object_schema = array(
                        array("name"=>"subschema",                      "icon"=>"schema.png",           "is_folder"=>false,"display_name"=>gettext("Schema")),
                        );


		// Display layouts
		$ldap_server->add_display_layout("subschema",array(
			array("section_name"=>gettext("Object Class Definitions"),"new_row"=>true,"colspan"=>2,
				"attributes"=>array(
					array("objectClasses")
					)
				),
			array("section_name"=>gettext("Attribute Type Definitions"),"new_row"=>true,"colspan"=>2,
				"attributes"=>array(
					array("attributeTypes")
					)
				),
			array("section_name"=>gettext("LDAP Syntax Definitions"),"new_row"=>true,"colspan"=>2,
				"attributes"=>array(
					array("ldapSyntaxes")
					)
				)
			));

		parent::__construct($ldap_server);
	}
}
?>
