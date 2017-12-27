<?php
/** OpenLDAP Configuration (OLC) Schema for Metadirectory Backend (partial) */

class openldap_back_meta_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"olcDbRewrite",			"data_type"=>"text_list",	"display_name"=>gettext("String Rewriting Rule"))
			);

		// Object classes
		$this->object_schema = array(
			array("name"=>"olcMetaConfig",			"icon"=>"openldap/db.png",		"is_folder"=>false,"rdn_attrib"=>"olcDatabase","display_name"=>gettext("Metadirectory Database"),"required_attribs"=>"olcSuffix","parent_class"=>"olcDatabaseConfig","can_create"=>true),
			array("name"=>"olcMetaTargetConfig",		"icon"=>"openldap/meta-target.png",	"is_folder"=>false,"rdn_attrib"=>"olcMetaSub","display_name"=>gettext("Metadirectory Target Server"),"required_attribs"=>"olcDbURI","contained_by"=>"olcMetaConfig","can_create"=>true)
			);

		// Display layouts
		$ldap_server->add_display_layout("olcMetaConfig",array(
			array("section_name"=>gettext("Metadirectory Database"),"new_row"=>true,
				"attributes"=>array(
					array("olcSuffix",			gettext("Naming Context"),		"alias.png"),
					array("olcDbChaseReferrals",		gettext("Chase Referrals"),		"generic24.png"),

					array("olcRootDN",			gettext("Root User"),			"generic24.png"),
				//	array("olcRootPW",			gettext("Root Password"),		"generic24.png")
					)
				),
			array("section_name"=>gettext("Remote Identity Assertion"),"new_row"=>true,
				"attributes"=>array(
					array("olcDbIDAssertAuthzFrom",		gettext("Authorise From"),		"generic24.png"),
					array("olcDbIDAssertBind",		gettext("Bind Configuration"),		"generic24.png")
					)
				),
			array("section_name"=>gettext("String Rewriting Rules"),"new_row"=>true,
				"attributes"=>array(
					array("olcDbRewrite")
					)
				),
			array("section_name"=>gettext("Access Controls"),"new_row"=>true,
				"attributes"=>array(
					array("olcAccess")
					),
				),
			array("section_name"=>gettext("Target LDAP Servers and Overlays"),"new_row"=>true,
				"attributes"=>array(
					array("__CHILD_OBJECTS__")
					)
				)
			));

		$ldap_server->add_display_layout("olcMetaTargetConfig",array(
			array("section_name"=>gettext("Metadirectory Target Server"),"new_row"=>true,
				"attributes"=>array(
					array("olcDbURI",			gettext("Remote LDAP Directory URI"),	"ldap-server.png"),
					array("olcDbChaseReferrals",		gettext("Chase Referrals"),		"generic24.png"),
					),
				),
			array("section_name"=>gettext("String Rewriting Rules"),"new_row"=>true,
				"attributes"=>array(
					array("olcDbRewrite"),
					),
				),
			));

		parent::__construct($ldap_server);
	}

	function populate_for_create_olcMetaConfig(&$ldap_server,&$entry)
	{
		// override the schema-defined data type that the new database's DN can be typed in
		$ldap_server->modify_attribute_schema("olcSuffix","data_type","text");

		$this->add_attrib_value($ldap_server,$entry,"olcDbChaseReferrals","FALSE");
	}

	function before_create_olcMetaConfig(&$ldap_server,&$entry)
	{
		$ldap_server->ensure_openldap_module_loaded("back_ldap");	// required as a dependency
		$ldap_server->ensure_openldap_module_loaded("back_meta");

		$ldap_server->assign_ordered_sequence_rdn($entry,"olcDatabaseConfig","meta",-1);

		$this->add_attrib_single_value($ldap_server,$entry,"olcAccess",array(
			"{0}to * by dn.exact=gidNumber=0+uidNumber=0,cn=peercred,cn=external,cn=auth manage by * break",
			"{1}to * by dn.base=\"" . $_SESSION["LOGIN_BIND_DN"][$ldap_server->server_id] . "\" manage",
			"{2}to attrs=userPassword by self write by anonymous auth by * none",
			"{3}to attrs=shadowLastChange by self write by * read",
			"{4}to * by * read")
			);
	}

	// create first target URI for newly created metadirectory  - at least one
	// target URI must exist for OpenLDAP to continue running (as of OpenLDAP 2.4)

	function after_create_olcMetaConfig(&$ldap_server,&$entry)
	{
		$uri_entry = array(
			"dn"=>"olcMetaSub=uri," . $entry["dn"],
			"objectclass"=>"olcMetaTargetConfig",
			"olcMetaSub"=>"uri",
			"olcDbURI"=>"ldap://localhost/dc=" . gettext("target1") . "," . $entry["olcsuffix"][0],
    			"olcDbChaseReferrals"=>"TRUE"
			);

		$ldap_server->assign_ordered_sequence_rdn($uri_entry,"olcMetaTargetConfig","uri");

		$dn = $uri_entry["dn"];
		unset($uri_entry["dn"]);

		@ldap_add($ldap_server->connection,$dn,$uri_entry);
	}

	function before_create_olcMetaTargetConfig(&$ldap_server,&$entry)
	{
		$ldap_server->assign_ordered_sequence_rdn($entry,"olcMetaTargetConfig","uri");
	}
}
?>
