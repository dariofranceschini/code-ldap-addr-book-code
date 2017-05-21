<?php
/** OpenLDAP Configuration (OLC) Schema for Socket Backend */

class openldap_back_sock_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"olcDbSocketPath",		"data_type"=>"text",		"display_name"=>gettext("Socket Pathname")),
			array("name"=>"olcDbSocketExtensions",		"data_type"=>"text",		"display_name"=>gettext("Socket Extension Attributes")),
			array("name"=>"olcOvSocketOps",			"data_type"=>"text",		"display_name"=>gettext("Socket Operation Types")),
			array("name"=>"olcOvSocketResps",		"data_type"=>"text",		"display_name"=>gettext("Socket Response Types"))
			);

		// Object classes
		$this->object_schema = array(
			array("name"=>"olcDbSocketConfig",		"icon"=>"openldap/db.png",		"is_folder"=>false,"rdn_attrib"=>"olcDatabase","display_name"=>gettext("Socket Database"),"required_attribs"=>"olcSuffix,olcDbSocketPath","can_create"=>true,"parent_class"=>"olcDatabaseConfig"),
			array("name"=>"olcOvSocketConfig",		"icon"=>"openldap/overlay.png",		"is_folder"=>false,"rdn_attrib"=>"olcOverlay","display_name"=>gettext("Socket Overlay"),"can_create"=>true,"parent_class"=>"olcOverlayConfig")
			);

		// Display layouts
		$ldap_server->add_display_layout("olcDbSocketConfig",array(
			array("section_name"=>gettext("Socket Database Settings"),"new_row"=>true,
				"attributes"=>array(
					array("olcSuffix",			gettext("Naming Context"),				"alias.png"),
					array("olcDbSocketPath",		gettext("UNIX Domain Socket"),				"generic24.png"),
					array("olcDbSocketExtensions",		gettext("Additional Meta-Attributes Sent to Socket"),	"generic24.png"),
					array("olcLastMod",			gettext("Maintain Last Modification Info"),		"generic24.png"),
					array("olcRootDN",			gettext("Root User"),					"generic24.png"),
				//	array("olcRootPW",			gettext("Root Password"),				"generic24.png"),
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

		$ldap_server->add_display_layout("olcOvSocketConfig",array(
			array("section_name"=>gettext("Socket Overlay Settings"),"new_row"=>true,
				"attributes"=>array(
					array("olcOverlay",			gettext("Overlay Object Name"),				"openldap/overlay.png"),
					array("olcDbSocketPath",		gettext("UNIX Domain Socket"),				"generic24.png"),
					array("olcDbSocketExtensions",		gettext("Additional Meta-Attributes Sent to Socket"),	"generic24.png"),
					array("olcOvSocketOps",			gettext("Operation Types Sent to Socket"),		"generic24.png"),
					array("olcOvSocketResps",		gettext("Response Tyes Sent to Socket"),		"generic24.png")
					)
				)
			));

		parent::__construct($ldap_server);
	}

	function populate_for_create_olcDbSocketConfig(&$ldap_server,&$entry)
	{
		// override the schema-defined data type that the new database's DN can be typed in
		$ldap_server->modify_attribute_schema("olcSuffix","data_type","text");

		$this->add_attrib_value($ldap_server,$entry,"olcDbSocketExtensions","binddn peername ssf connid");	// ssf = security strength factor
		$this->add_attrib_value($ldap_server,$entry,"olcDbSocketOps","bind unbind search compare modify modrdn add delete");
		$this->add_attrib_value($ldap_server,$entry,"olcDbSocketResps","result search");
	}

	function before_create_olcDbSocketConfig(&$ldap_server,&$entry)
	{
		$ldap_server->ensure_openldap_module_loaded("back_sock");

		$ldap_server->assign_ordered_sequence_rdn($entry,"olcDatabaseConfig","sock",-1);

		$this->add_attrib_single_value($ldap_server,$entry,"olcAccess",array(
			"{0}to * by dn.exact=gidNumber=0+uidNumber=0,cn=peercred,cn=external,cn=auth manage by * break",
			"{1}to * by dn.base=\"" . $_SESSION["LOGIN_BIND_DN"] . "\" manage",
			"{2}to attrs=userPassword by self write by anonymous auth by * none",
			"{3}to attrs=shadowLastChange by self write by * read",
			"{4}to * by * read")
			);
	}

	function before_create_olcOvSocketConfig(&$ldap_server,&$entry)
	{
		$ldap_server->ensure_openldap_module_loaded("back_sock");
	}
}
?>
