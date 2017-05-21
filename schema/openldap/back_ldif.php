<?php
/** OpenLDAP Configuration (OLC) Schema for LDIF Backend (partial) */

class openldap_back_ldif_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		// Object classes
		$this->object_schema = array(
			array("name"=>"olcLdifConfig",			"icon"=>"openldap/db.png",		"is_folder"=>false,"rdn_attrib"=>"olcDatabase","display_name"=>gettext("LDIF Database"),"required_attribs"=>"olcSuffix,olcDbDirectory","parent_class"=>"olcDatabaseConfig","can_create"=>true)
			);

		// Display layouts
		$ldap_server->add_display_layout("olcLdifConfig",array(
			array("section_name"=>gettext("Database Settings"),"new_row"=>true,
				"attributes"=>array(
					array("olcDbDirectory",		gettext("Database Directory"),			"folder.png"),
					array("olcSuffix",		gettext("Naming Context"),			"alias.png"),
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

	function populate_for_create_olcLdifConfig(&$ldap_server,&$entry)
	{
		// override the schema-defined data type that the new database's DN can be typed in
		$ldap_server->modify_attribute_schema("olcSuffix","data_type","text");

		$this->add_attrib_value($ldap_server,$entry,"olcDbDirectory","/var/lib/ldap");
	}

	function before_create_olcLdifConfig(&$ldap_server,&$entry)
	{
		$ldap_server->assign_ordered_sequence_rdn($entry,"olcDatabaseConfig","ldif",-1);

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
