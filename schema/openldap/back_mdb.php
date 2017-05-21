<?php
/** OpenLDAP Configuration (OLC) Schema for Memory-Mapped Database (MDB) Backend (partial) */

class openldap_back_mdb_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		// Attributes
		$this->attribute_schema = array(
			array("name"=>"olcDbMaxSize",			"data_type"=>"text",		"display_name"=>gettext("Maximum Database Size"))
			);

		// Object classes
		$this->object_schema = array(
			array("name"=>"olcMdbConfig",			"icon"=>"openldap/db.png",		"is_folder"=>false,"rdn_attrib"=>"olcDatabase","display_name"=>gettext("MDB Database"),"required_attribs"=>"olcSuffix,olcDbDirectory","can_create"=>true,"parent_class"=>"olcDatabaseConfig")
			);

		// abstract class 'olmMDBDatabase' is also defined in this schema

		// Display layouts
		$ldap_server->add_display_layout("olcMdbConfig",array(
			array("section_name"=>gettext("Database Settings"),"new_row"=>true,
				"attributes"=>array(
					array("olcDbDirectory",			gettext("Database Directory"),			"folder.png"),
					array("olcSuffix",			gettext("Naming Context"),			"alias.png"),
					array("olcLastMod",			gettext("Maintain Last Modification Info"),	"generic24.png"),
					array("olcRootDN",			gettext("Root User"),				"generic24.png"),
				//	array("olcRootPW",			gettext("Root Password"),			"generic24.png"),
					array("olcDbCheckpoint",		gettext("Checkpoint Intervals (KB, minutes)"),	"generic24.png"),
					array("olcDbMaxSize",			gettext("Maximum Size"),			"generic24.png")
					)
				),
			array("section_name"=>gettext("Database Indexes"),"new_row"=>true,
				"attributes"=>array(
					array("olcDbIndex")
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

	function populate_for_create_olcMdbConfig(&$ldap_server,&$entry)
	{
		// override the schema-defined data type that the new database's DN can be typed in
		$ldap_server->modify_attribute_schema("olcSuffix","data_type","text");

		$this->add_attrib_value($ldap_server,$entry,"olcDbDirectory","/var/lib/ldap");
		$this->add_attrib_value($ldap_server,$entry,"olcDbCheckpoint","512 30");
		$this->add_attrib_value($ldap_server,$entry,"olcDbMaxSize","1073741824");
	}

	function before_create_olcMdbConfig(&$ldap_server,&$entry)
	{
		$ldap_server->ensure_openldap_module_loaded("back_mdb");

		$ldap_server->assign_ordered_sequence_rdn($entry,"olcDatabaseConfig","mdb",-1);

		$this->add_attrib_single_value($ldap_server,$entry,"olcAccess",array(
			"{0}to * by dn.exact=gidNumber=0+uidNumber=0,cn=peercred,cn=external,cn=auth manage by * break",
			"{1}to * by dn.base=\"" . $_SESSION["LOGIN_BIND_DN"] . "\" manage",
			"{2}to attrs=userPassword by self write by anonymous auth by * none",
			"{3}to attrs=shadowLastChange by self write by * read",
			"{4}to * by * read")
			);

		$this->add_attrib_single_value($ldap_server,$entry,"olcDbIndex",array("objectClass eq"));
	}
}
?>
