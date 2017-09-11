<?php
/** OpenLDAP Configuration (OLC) Schema (partial) */

class openldap_config_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			// online config attributes
			array("name"=>"olcAccess",			"data_type"=>"text_list",	"display_name"=>gettext("Access Control")),
			array("name"=>"olcArgsFile",			"data_type"=>"text",		"display_name"=>gettext("Command Line Arguments File")),
			array("name"=>"olcAttributeTypes",		"data_type"=>"ldap_schema",	"display_name"=>gettext("Attribute Types")),
			array("name"=>"olcAuthzPolicy",			"data_type"=>"openldap_authpol","display_name"=>gettext("Proxy Authorization Policy")),
			array("name"=>"olcBackend",			"data_type"=>"openldap_backend","display_name"=>gettext("Back End Name")),
			array("name"=>"olcDatabase",			"data_type"=>"text",		"display_name"=>gettext("Database Object Name")),
			array("name"=>"olcDefaultSearchBase",		"data_type"=>"text",		"display_name"=>gettext("Default Search Base")),
			array("name"=>"olcInclude",			"data_type"=>"text",		"display_name"=>gettext("Configuration Include File Name")),
			array("name"=>"olcLastMod",			"data_type"=>"yes_no",		"display_name"=>gettext("Maintain Last Modification Info")),
			array("name"=>"olcLdapSyntaxes",		"data_type"=>"ldap_schema",	"display_name"=>gettext("LDAP Syntaxes")),
			array("name"=>"olcLogLevel",			"data_type"=>"text",		"display_name"=>gettext("Debug Log Detail Level")),
			array("name"=>"olcModuleLoad",			"data_type"=>"openldap_module",	"display_name"=>gettext("Module Name")),
			array("name"=>"olcModulePath",			"data_type"=>"text",		"display_name"=>gettext("Module Pathname")),
			array("name"=>"olcObjectClasses",		"data_type"=>"ldap_schema",	"display_name"=>gettext("Object Classes")),
			array("name"=>"olcObjectIdentifier",		"data_type"=>"oid_macro_list",	"display_name"=>gettext("Object Identifier")),
			array("name"=>"olcOverlay",			"data_type"=>"text",		"display_name"=>gettext("Overlay Object Name")),
			array("name"=>"olcPasswordHash",		"data_type"=>"text",		"display_name"=>gettext("Password Hash")),
			array("name"=>"olcPidFile",			"data_type"=>"text",		"display_name"=>gettext("Process Identifier (PID) File")),
			array("name"=>"olcRootDN",			"data_type"=>"dn",		"display_name"=>gettext("Root User DN")),
			array("name"=>"olcSizeLimit",			"data_type"=>"text",		"display_name"=>gettext("Size Limit")),
			array("name"=>"olcSortVals",			"data_type"=>"text",		"display_name"=>gettext("Attributes with Sorted Values")),
			array("name"=>"olcSuffix",			"data_type"=>"dn",		"display_name"=>gettext("Naming Context")),
			array("name"=>"olcTLSCACertificateFile",	"data_type"=>"text",		"display_name"=>gettext("CA Certificate File")),
			array("name"=>"olcTLSCertificateFile",		"data_type"=>"text",		"display_name"=>gettext("Server Certificate File")),
			array("name"=>"olcTLSCertificateKeyFile",	"data_type"=>"text",		"display_name"=>gettext("Server Certificate Key File")),
			array("name"=>"olcTLSProtocolMin",		"data_type"=>"openldap_tlsver",	"display_name"=>gettext("Minimum TLS Protocol Version")),
			array("name"=>"olcTLSVerifyClient",		"data_type"=>"openldap_clicrt",	"display_name"=>gettext("Client Certificate Checking Policy")),
			array("name"=>"olcToolThreads",			"data_type"=>"text",		"display_name"=>gettext("Number of Tool Threads"))
			);

		// Object classes
		$this->object_schema = array(
			array("name"=>"olcBackendConfig",		"icon"=>"openldap/backend.png",	"is_folder"=>false,"rdn_attrib"=>"olcBackend","display_name"=>gettext("OpenLDAP Back End Configuration"),"contained_by"=>"olcGlobal","can_create"=>true),
			array("name"=>"olcConfig",			"icon"=>"generic24.png",	"class_type"=>"abstract","display_name"=>gettext("OpenLDAP Configuration")),
			array("name"=>"olcDatabaseConfig",		"icon"=>"openldap/db.png",	"is_folder"=>false,"rdn_attrib"=>"olcDatabase","display_name"=>gettext("OpenLDAP Database"),"required_attribs"=>"olcSuffix","can_contain"=>"olcOverlayConfig,olcMetaTargetConfig,olcAsyncMetaTargetConfig","contained_by"=>"olcGlobal"),
			array("name"=>"olcFrontendConfig",		"icon"=>"openldap/frontend.png","class_type"=>"auxiliary","rdn_attrib"=>"olcDatabase","display_name"=>gettext("OpenLDAP Front End Configuration")),
			array("name"=>"olcGlobal",			"icon"=>"config-folder.png",	"is_folder"=>false,"display_name"=>gettext("OpenLDAP Global Configuration"),"can_contain"=>"olcBackendConfig,olcFrontendConfig,olcDatabaseConfig,olcModuleList,olcSchemaConfig,olcIncludeFile"),
			array("name"=>"olcIncludeFile",			"icon"=>"config-file.png",	"is_folder"=>false,"display_name"=>gettext("OpenLDAP Configuration Include File"),"required_attribs"=>"olcInclude","contained_by"=>"olcGlobal","can_create"=>true),
			array("name"=>"olcModuleList",			"icon"=>"openldap/module.png",	"is_folder"=>false,"display_name"=>gettext("OpenLDAP Module"),"can_create"=>true,"create_method"=>"atomic","contained_by"=>"olcGlobal"),
			array("name"=>"olcOverlayConfig",		"icon"=>"openldap/overlay.png",	"is_folder"=>false,"rdn_attrib"=>"olcOverlay","display_name"=>gettext("OpenLDAP Overlay"),"contained_by"=>"olcDatabaseConfig"),
			array("name"=>"olcSchemaConfig",		"icon"=>"schema.png",		"is_folder"=>false,"display_name"=>gettext("OpenLDAP Schema Configuration"))
			);

		// Display layouts
		$ldap_server->add_display_layout("olcGlobal",array(
			array("section_name"=>gettext("OpenLDAP Server Configuration"),
				"attributes"=>array(
					array("__CHILD_OBJECTS__")
					)
				),
			array("section_name"=>gettext("Global Settings"),"new_row"=>true,
				"attributes"=>array(
					array("olcArgsFile",		gettext("Command Line Arguments File"),		"generic24.png"),
					array("olcPidFile",		gettext("Process Identifier (PID) File"),	"generic24.png"),
					array("olcLogLevel",		gettext("Debug Log Detail Level"),		"generic24.png"),
					array("olcToolThreads",		gettext("Number of Tool Threads"),		"generic24.png"),
					array("olcAuthzPolicy",		gettext("Proxy Authorization Policy"),		"generic24.png")
					)
				),


			array("section_name"=>gettext("Transport Layer Security (TLS)"),"new_row"=>true,
				"attributes"=>array(
					array("olcTLSCACertificateFile",gettext("CA Certificate File"),			"generic24.png"),
					array("olcTLSCertificateFile",	gettext("Server Certificate File"),		"generic24.png"),
					array("olcTLSCertificateKeyFile",gettext("Server Certificate Key File"),	"generic24.png"),
					array("olcTLSVerifyClient",	gettext("Client Certificate Checking"),		"generic24.png","allow_edit"=>false)
					)
				),
			array("section_name"=>gettext("OpenSSL Options"),"new_row"=>true,
				"attributes"=>array(
					array("olcTLSProtocolMin",	gettext("Minimum Protocol Version"),		"generic24.png","allow_edit"=>false)
					)
				)
			));

		$ldap_server->add_display_layout("olcIncludeFile",array(
			array("section_name"=>gettext("OpenLDAP Configuration Include File"),
				"attributes"=>array(
					array("cn",			gettext("Object Name"),				"generic24.png"),
					array("olcInclude",		gettext("File Name"),				"generic24.png")
					)
				)
			));

		$ldap_server->add_display_layout("olcSchemaConfig",array(
			array("section_name"=>gettext("Schema Name"),"colspan"=>6,
				"attributes"=>array(
					array("cn"),
					)
				),
			array("section_name"=>gettext("Object Identifier Macro Definitions"),"new_row"=>true,
				"attributes"=>array(
					array("olcObjectIdentifier")
					),
				),
			array("section_name"=>gettext("Object Class Definitions"),"new_row"=>true,
				"attributes"=>array(
					array("olcObjectClasses")
					),
				),
			array("section_name"=>gettext("Attribute Type Definitions"),"new_row"=>true,
				"attributes"=>array(
					array("olcAttributeTypes")
					),
				),
			array("section_name"=>gettext("LDAP Syntaxes"),"new_row"=>true,
				"attributes"=>array(
					array("olcLdapSyntaxes")
					),
				),
			array("section_name"=>gettext("Child Objects"),"new_row"=>true,"colspan"=>2,
				"attributes"=>array(
					array("__CHILD_OBJECTS__")
					)
				)
			));

		$ldap_server->add_display_layout("olcModuleList",array(
			array("section_name"=>gettext("OpenLDAP Module List"),
				"attributes"=>array(
					array("olcModuleLoad",		gettext("Modules"),				"openldap/module.png"),
					array("olcModulePath",		gettext("Path"),				"folder.png"),
					),
				)
			));

		$ldap_server->add_display_layout("olcDatabaseConfig",array(
			array("section_name"=>gettext("Database Settings"),
				"attributes"=>array(
					array("olcSizeLimit",		gettext("Size Limit")),
					),
				),
			array("section_name"=>gettext("Access Controls"),"new_row"=>true,
				"attributes"=>array(
					array("olcAccess")
					),
				),
			array("section_name"=>gettext("Overlays"),"new_row"=>true,
				"attributes"=>array(
					array("__CHILD_OBJECTS__")
					)
				)
			));

		$ldap_server->add_display_layout("olcBackendConfig",array(
			array("section_name"=>gettext("Back End Configuration"),
				"attributes"=>array(
					array("olcBackend",		gettext("Back End Module"),	"openldap/module.png"),
					)
				)
			));

		// Auxiliary class display layouts

		$ldap_server->add_display_layout("olcFrontendConfig",array(
			array("section_name"=>gettext("OpenLDAP Front End Configuration"),
				"attributes"=>array(
					array("olcDefaultSearchBase",	gettext("Default Search Base"),			"generic24.png"),
					array("olcPasswordHash",	gettext("Password Hash"),			"generic24.png"),
					array("olcSortVals",		gettext("Attributes with Sorted Values"),	"generic24.png")
					)
				)
			));

		parent::__construct($ldap_server);
	}

	/** Assign default value for olcModulePath

	   If the config file doesn't define a default module path
	   then use /usr/lib/ldap

	*/

	function populate_for_create_olcModuleList(&$ldap_server,&$entry)
	{
		global $openldap_module_path;

		if(!isset($openldap_module_path))
			$openldap_module_path = "/usr/lib/ldap";

		$this->add_attrib_value($ldap_server,$entry,"olcModulePath",$openldap_module_path);
	}

	/** Assign object name for olcModuleList based on the module to be loaded.

	    Module list objects are processed in code point order of object name as OpenLDAP
	    starts up. The module name for back_monitor gets modified to start with a tilde
	    (ASCII 126) to ensure that it gets loaded last.
	*/

	function before_create_olcModuleList(&$ldap_server,&$entry)
	{
		$module_name = explode("}",$entry["olcModuleLoad"]);
		$module_name = $module_name[1];

		// create after all standard databases, but before monitor database
		if($module_name == "auditlog")
			$module_name = "~auditlog";

		// create after other objects
		if($module_name == "back_monitor")
			$module_name = "~~back_monitor";

		$ldap_server->assign_ordered_sequence_rdn($entry,"olcModuleList",$module_name . "{%d}");
	}

	function before_create_olcBackendConfig(&$ldap_server,&$entry)
	{
		$backend_name = ldap_explode_dn2($entry["dn"]);
		$backend_name = $backend_name[0]["value"];

		$ldap_server->assign_ordered_sequence_rdn($entry,"olcBackendConfig",$backend_name);

		$ldap_server->ensure_openldap_module_loaded("back_" . $backend_name);
	}
}
?>
