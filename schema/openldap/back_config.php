<?php
/** OpenLDAP On-Line Configuration (OLC) Backend schema (partial) */

class openldap_back_config_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			// online config attributes
			array("name"=>"olcAccess",			"data_type"=>"text_list",	"display_name"=>gettext("Access Control")),
			array("name"=>"olcAttributeTypes",		"data_type"=>"text_list",	"display_name"=>gettext("Attribute Types")),
			array("name"=>"olcBackend",			"data_type"=>"text_list",	"display_name"=>gettext("Back End Name")),
			array("name"=>"olcInclude",			"data_type"=>"text",		"display_name"=>gettext("Configuration Include File Name")),
			array("name"=>"olcLastMod",			"data_type"=>"yes_no",		"display_name"=>gettext("Maintain Last Modification Info")),
			array("name"=>"olcLdapSyntaxes",		"data_type"=>"text_list",	"display_name"=>gettext("LDAP Syntaxes")),
			array("name"=>"olcModuleLoad",			"data_type"=>"openldap_module",	"display_name"=>gettext("Module Name")),
			array("name"=>"olcObjectClasses",		"data_type"=>"text_list",	"display_name"=>gettext("Object Classes")),
			array("name"=>"olcObjectIdentifier",		"data_type"=>"text_list",	"display_name"=>gettext("Object Identifier")),
			array("name"=>"olcRootDN",			"data_type"=>"dn",		"display_name"=>gettext("Root User DN")),
			array("name"=>"olcSuffix",			"data_type"=>"dn",		"display_name"=>gettext("Naming Context")),

			// Attribute used by back_bdb and back_hdb
			array("name"=>"olcDbConfig",			"data_type"=>"text_list",	"display_name"=>gettext("DB_CONFIG Directives")),

			// Attribute used by back_bdb, back_hdb, back_mdb and back_wt
			array("name"=>"olcDbIndex",			"data_type"=>"text_list",	"display_name"=>gettext("Database Index")),

			// Attribute used by back_bdb, back_hdb, back_ldif, back_mdb and back_wt
			array("name"=>"olcDbDirectory",			"data_type"=>"text",		"display_name"=>gettext("Database Directory Path")),

			// Attributes used by back_ldap and back_meta
			array("name"=>"olcDbChaseReferrals",		"data_type"=>"yes_no",		"display_name"=>gettext("Referal Chasing Enabled")),
			array("name"=>"olcDbIDAssertBind",		"data_type"=>"text_area",	"display_name"=>gettext("Remote Identity Assertion Settings"))
			);

		// Structural object classes
		$this->object_schema = array(
			array("name"=>"olcSchemaConfig",		"icon"=>"schema.png",		"is_folder"=>false,"display_name"=>gettext("OpenLDAP Schema Configuration")),
			array("name"=>"olcBackendConfig",		"icon"=>"openldap/backend.png",	"is_folder"=>false,"rdn_attrib"=>"olcBackend","display_name"=>gettext("OpenLDAP Back End")),
			array("name"=>"olcFrontendConfig",		"icon"=>"openldap/frontend.png","is_folder"=>false,"rdn_attrib"=>"olcDatabase","display_name"=>gettext("OpenLDAP Front End")),	// aux class
			array("name"=>"olcOverlayConfig",		"icon"=>"openldap/overlay.png",	"is_folder"=>false,"rdn_attrib"=>"olcOverlay","display_name"=>gettext("OpenLDAP Overlay"),"contained_by"=>"olcDatabaseConfig"),
			array("name"=>"olcDatabaseConfig",		"icon"=>"openldap/db.png",	"is_folder"=>false,"rdn_attrib"=>"olcDatabase","display_name"=>gettext("OpenLDAP Database"),"required_attribs"=>"olcSuffix","can_contain"=>"olcOverlayConfig,olcMetaTargetConfig","contained_by"=>"olcGlobal"),
			array("name"=>"olcIncludeFile",			"icon"=>"config-file.png",	"is_folder"=>false,"display_name"=>gettext("OpenLDAP Configuration Include File"),"required_attribs"=>"olcInclude","contained_by"=>"olcGlobal"),
			array("name"=>"olcGlobal",			"icon"=>"config-folder.png",	"is_folder"=>true,"display_name"=>gettext("OpenLDAP Global Configuration"),"can_contain"=>"olcBackendConfig,olcFrontendConfig,olcDatabaseConfig,olcModuleList,olcSchemaConfig,olcIncludeFile"),
			array("name"=>"olcModuleList",			"icon"=>"openldap/module.png",	"is_folder"=>false,"display_name"=>gettext("OpenLDAP Module"),"contained_by"=>"olcGlobal")
			);

		// abstract class 'olcConfig' is also defined in this schema

		parent::__construct($ldap_server);
	}
}
?>
