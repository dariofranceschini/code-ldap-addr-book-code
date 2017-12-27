<?php
/** OpenLDAP Configuration (OLC) Schema for Perl Backend */

class openldap_back_perl_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"olcPerlFilterSearchResults",	"data_type"=>"yes_no",		"display_name"=>gettext("Filter Search Results within OpenLDAP")),
			array("name"=>"olcPerlModule",			"data_type"=>"text",		"display_name"=>gettext("Perl Module Name")),
			array("name"=>"olcPerlModuleConfig",		"data_type"=>"text",		"display_name"=>gettext("Perl Module Configuration Directives")),
			array("name"=>"olcPerlModulePath",		"data_type"=>"text",		"display_name"=>gettext("Perl Module Path"))
			);

		// Object classes
		$this->object_schema = array(
			array("name"=>"olcDbPerlConfig",			"icon"=>"openldap/db.png",		"is_folder"=>false,"rdn_attrib"=>"olcDatabase","display_name"=>gettext("Perl Module Database"),"required_attribs"=>"olcSuffix","can_create"=>true,"parent_class"=>"olcDatabaseConfig")
			);

		// Display layouts
		$ldap_server->add_display_layout("olcDbPerlConfig",array(
			array("section_name"=>gettext("Perl Database Settings"),"new_row"=>true,
				"attributes"=>array(
					array("olcSuffix",			gettext("Naming Context"),				"alias.png"),
					array("olcPerlModule",			gettext("Module Name"),					"generic24.png"),
					array("olcPerlModulePath",		gettext("Module Path"),					"folder.png"),
					array("olcPerlModuleConfig",		gettext("Module Config Directives"),			"generic24.png"),
					array("olcPerlFilterSearchResults",	gettext("Filter Search Results within OpenLDAP"),	"generic24.png"),
					array("olcLastMod",			gettext("Maintain Last Modification Info"),		"generic24.png"),
					array("olcRootDN",			gettext("Root User"),					"generic24.png"),
				//	array("olcRootPW",			gettext("Root Password"),				"generic24.png"),
					),
				),
			array("section_name"=>gettext("Access Controls"),"new_row"=>true,
				"attributes"=>array(
					array("olcAccess")
					),
				),
			array("section_name"=>gettext("Overlays"),"new_row"=>true,"colspan"=>2,
				"attributes"=>array(
					array("__CHILD_OBJECTS__")
					)
				)
			));

		parent::__construct($ldap_server);
	}

	function populate_for_create_olcDbPerlConfig(&$ldap_server,&$entry)
	{
		// override the schema-defined data type that the new database's DN can be typed in
		$ldap_server->modify_attribute_schema("olcSuffix","data_type","text");
	}

	function before_create_olcDbPerlConfig(&$ldap_server,&$entry)
	{
		$ldap_server->ensure_openldap_module_loaded("back_perl");

		$ldap_server->assign_ordered_sequence_rdn($entry,"olcDatabaseConfig","perl",-1);

		$this->add_attrib_single_value($ldap_server,$entry,"olcAccess",array(
			"{0}to * by dn.exact=gidNumber=0+uidNumber=0,cn=peercred,cn=external,cn=auth manage by * break",
			"{1}to * by dn.base=\"" . $_SESSION["LOGIN_BIND_DN"][$ldap_server->server_id] . "\" manage",
			"{2}to attrs=userPassword by self write by anonymous auth by * none",
			"{3}to attrs=shadowLastChange by self write by * read",
			"{4}to * by * read")
			);
	}
}
?>
