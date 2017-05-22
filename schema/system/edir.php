<?php
/** system schema - eDirectory specific items (partial) */

class system_edir_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"attributeTypes",			"data_type"=>"ldap_schema",	"display_name"=>gettext("Attribute Types")),
			array("name"=>"createTimestamp",		"data_type"=>"date_time",	"display_name"=>gettext("Created")),
			array("name"=>"dSAName",			"data_type"=>"dn_list",		"display_name"=>gettext("DSA Name")),
			array("name"=>"modifyTimestamp",		"data_type"=>"date_time",	"display_name"=>gettext("Last Modified")),
			array("name"=>"objectClasses",			"data_type"=>"ldap_schema",	"display_name"=>gettext("Object Classes")),
			array("name"=>"subschemaSubentry",		"data_type"=>"dn",		"display_name"=>gettext("Subschema Subentry")),

			// See: draft-zeilenga-ldap-grouping-06
			array("name"=>"supportedGroupingTypes",		"data_type"=>"oid_list",	"display_name"=>gettext("Supported Grouping Types")),
			);

		$ldap_server->delete_object_class("alias");	// called 'aliasObject' instead

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
