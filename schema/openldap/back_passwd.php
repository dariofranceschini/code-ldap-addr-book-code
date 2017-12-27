<?php
/** OpenLDAP Configuration (OLC) Schema for Password File Backend */

class openldap_back_passwd_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"olcPasswdFile",			"data_type"=>"text",		"display_name"=>gettext("Password File Name"))
			);

		// Object classes
		$this->object_schema = array(
			array("name"=>"olcPasswdConfig",		"icon"=>"openldap/db.png",		"is_folder"=>false,"rdn_attrib"=>"olcDatabase","display_name"=>gettext("Password File Database"),"parent_class"=>"olcDatabaseConfig","can_create"=>true)
			);

		$ldap_server->add_display_layout("olcPasswdConfig",array(
			array("section_name"=>"Database Settings","new_row"=>true,
				"attributes"=>array(
					array("olcPasswdFile",		gettext("Password File"),			"generic24.png"),
					array("olcSuffix",		gettext("Naming Context"),			"generic24.png"),
					array("olcLastMod",		gettext("Maintain Last Modification Info"),	"generic24.png"),
					array("olcRootDN",		gettext("Root User"),				"generic24.png"),
				//	array("olcRootPW",		gettext("Root Password"),			"generic24.png")
					)
				),
			array("section_name"=>gettext("Access Controls"),"new_row"=>true,
				"attributes"=>array(
					array("olcAccess")
					)
				),
			array("section_name"=>gettext("Overlays"),"new_row"=>true,
				"attributes"=>array(
					array("__CHILD_OBJECTS__")
					)
				)
			));

		parent::__construct($ldap_server);
	}

	function populate_for_create_olcPasswdConfig(&$ldap_server,&$entry)
	{
		$ldap_server->modify_attribute_schema("olcSuffix","data_type","text");
		$this->add_attrib_value($ldap_server,$entry,"olcPasswdFile","/etc/passwd");
	}

	function before_create_olcPasswdConfig(&$ldap_server,&$entry)
	{
		$ldap_server->ensure_openldap_module_loaded("back_passwd");

		$ldap_server->assign_ordered_sequence_rdn($entry,"olcDatabaseConfig","passwd",-1);

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
