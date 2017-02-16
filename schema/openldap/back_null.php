<?php
/** OpenLDAP Configuration (OLC) Schema for Null Backend (partial) */

class openldap_back_null_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"olcDbBindAllowed",		"data_type"=>"yes_no",		"display_name"=>gettext("Allow Binds To This Database"))
			);

		// Structural object classes
		$this->object_schema = array(
			array("name"=>"olcNullConfig",			"icon"=>"openldap/db.png",		"is_folder"=>false,"rdn_attrib"=>"olcDatabase","display_name"=>gettext("Null Database"),"required_attribs"=>"olcSuffix","can_create"=>true,"parent_class"=>"olcDatabaseConfig")
			);

		// Display layouts
		$ldap_server->add_display_layout("olcNullConfig",array(
			array("section_name"=>gettext("Database Settings"),"new_row"=>true,
				"attributes"=>array(
					array("olcSuffix",		gettext("Naming Context"),			"alias.png"),
					array("olcDbBindAllowed",	gettext("Allow Binds To This Database"),	"generic24.png"),
					array("olcLastMod",		gettext("Maintain Last Modification Info"),	"generic24.png"),
					array("olcRootDN",		gettext("Root User"),				"generic24.png"),
				//	array("olcRootPW",		gettext("Root Password"),			"generic24.png"),
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

	function populate_for_create_olcNullConfig(&$ldap_server,&$entry)
	{
		// override the schema-defined data type that the new database's DN can be typed in
		$ldap_server->modify_attribute_schema("olcSuffix","data_type","text");
	}

	function before_create_olcNullConfig(&$ldap_server,&$entry)
	{
		$ldap_server->ensure_openldap_module_loaded("back_null");

		$ldap_server->assign_ordered_sequence_rdn($entry,"olcDatabaseConfig","null",-1);

		$this->add_attrib_single_value($ldap_server,$entry,"olcAccess",array(
			"{0}to * by dn.exact=gidNumber=0+uidNumber=0,cn=peercred,cn=external,cn=auth manage by * break",
			"{1}to * by dn.base=\"" . $_SESSION["LOGIN_BIND_DN"] . "\" manage",
			"{2}to attrs=userPassword by self write by anonymous auth by * none",
			"{3}to attrs=shadowLastChange by self write by * read",
			"{4}to * by * read")
			);
	}
}
?>
