<?php
/** OpenLDAP Configuration (OLC) Schema for Shell Backend */

class openldap_back_shell_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"olcShellAdd",			"data_type"=>"text",		"display_name"=>gettext("LDAP Add Command and Arguments")),
			array("name"=>"olcShellBind",			"data_type"=>"text",		"display_name"=>gettext("LDAP Bind Command and Arguments")),
			array("name"=>"olcShellCompare",		"data_type"=>"text",		"display_name"=>gettext("LDAP Compare Command and Arguments")),
			array("name"=>"olcShellDelete",			"data_type"=>"text",		"display_name"=>gettext("LDAP Delete Command and Arguments")),
			array("name"=>"olcShellModify",			"data_type"=>"text",		"display_name"=>gettext("LDAP Modify Command and Arguments")),
			array("name"=>"olcShellModRDN",			"data_type"=>"text",		"display_name"=>gettext("LDAP Modify RDN Command and Arguments")),
			array("name"=>"olcShellSearch",			"data_type"=>"text",		"display_name"=>gettext("LDAP Search Command and Arguments")),
			array("name"=>"olcShellUnbind",			"data_type"=>"text",		"display_name"=>gettext("LDAP Unbind Command and Arguments"))
			);

		// Object classes
		$this->object_schema = array(
			array("name"=>"olcShellConfig",			"icon"=>"openldap/db.png",		"is_folder"=>false,"rdn_attrib"=>"olcDatabase","display_name"=>gettext("Shell Database"),"required_attribs"=>"olcSuffix","can_create"=>true,"parent_class"=>"olcDatabaseConfig")
			);

		// Display layouts
		$ldap_server->add_display_layout("olcShellConfig",array(
			array("section_name"=>gettext("Shell Database Settings"),"new_row"=>true,
				"attributes"=>array(
					array("olcSuffix",			gettext("Naming Context"),			"alias.png"),
					array("olcLastMod",			gettext("Maintain Last Modification Info"),	"generic24.png"),
					array("olcRootDN",			gettext("Root User"),				"generic24.png"),
				//	array("olcRootPW",			gettext("Root Password"),			"generic24.png")
					)
				),

			array("section_name"=>gettext("Shell Commands to Run for each LDAP Operation"),"new_row"=>true,
				"attributes"=>array(
					array("olcShellBind",			gettext("Bind (Log On)"),			"generic24.png"),
					array("olcShellUnbind",			gettext("Unbind (Log Off)"),			"generic24.png"),
					array("olcShellSearch",			gettext("Search Records"),			"generic24.png"),
					array("olcShellCompare",		gettext("Compare Records"),			"generic24.png"),
					array("olcShellAdd",			gettext("Add Record"),				"generic24.png"),
					array("olcShellModify",			gettext("Modify Attributes"),			"generic24.png"),
					array("olcShellModRDN",			gettext("Modify RDN (Move/Rename)"),		"generic24.png"),
					array("olcShellDelete",			gettext("Delete Record"),			"generic24.png")
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

	function populate_for_create_olcShellConfig(&$ldap_server,&$entry)
	{
		// override the schema-defined data type that the new database's DN can be typed in
		$ldap_server->modify_attribute_schema("olcSuffix","data_type","text");
	}

	function before_create_olcShellConfig(&$ldap_server,&$entry)
	{
		$ldap_server->ensure_openldap_module_loaded("back_shell");

		$ldap_server->assign_ordered_sequence_rdn($entry,"olcDatabaseConfig","shell",-1);

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
