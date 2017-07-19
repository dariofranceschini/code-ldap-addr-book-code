<?php
/** OpenLDAP Configuration (OLC) Shared Schema Elements (partial) */

class openldap_shared_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		// Attributes

		$this->attribute_schema = array(
			// shared by back_bdb and back_hdb
			array("name"=>"olcDbConfig",			"data_type"=>"text_list",	"display_name"=>gettext("DB_CONFIG Directives")),
			array("name"=>"olmBDBDNCache",			"data_type"=>"text",		"display_name"=>gettext("Number of DN Cache Items")),
			array("name"=>"olmBDBEntryCache",		"data_type"=>"text",		"display_name"=>gettext("Number of Entry Cache Items")),
			array("name"=>"olmBDBIDLCache",			"data_type"=>"text",		"display_name"=>gettext("Number of IDL Cache Items")),

			// shared by back_bdb, back_hdb, back_mdb
			array("name"=>"olmDbDirectory",			"data_type"=>"text",		"display_name"=>gettext("Database Directory Path")),

			// shared by back_bdb, back_hdb, back_mdb and back_wt
			array("name"=>"olcDbIndex",			"data_type"=>"text_list",	"display_name"=>gettext("Database Index")),

			// shared by back_bdb, back_hdb, back_ldif, back_mdb and back_wt
			array("name"=>"olcDbDirectory",			"data_type"=>"text",		"display_name"=>gettext("Database Directory Path")),

			// shared by back_ldap and back_meta
			array("name"=>"olcDbChaseReferrals",		"data_type"=>"yes_no",		"display_name"=>gettext("Referal Chasing Enabled")),
			array("name"=>"olcDbIDAssertBind",		"data_type"=>"text_area",	"display_name"=>gettext("Remote Identity Assertion Settings")),
			array("name"=>"olcDbRebindAsUser",		"data_type"=>"yes_no",		"display_name"=>gettext("Rebind as User"))
			);

		// Object classes

		$this->object_schema = array(
			// shared by back_bdb, back_hdb
			array("name"=>"olmBDBDatabase",			"icon"=>"generic24.png",	"class_type"=>"auxiliary","display_name"=>gettext("OpenLDAP BDB Monitoring Data"))
			);

		// Auxiliary class display layouts

		$ldap_server->add_display_layout("olmBDBDatabase",array(
			array("section_name"=>gettext("OpenLDAP Monitored BDB Database"),
				"attributes"=>array(
					array("olmBDBEntryCache",	gettext("Number of Entry Cache Items"),		"generic24.png"),
					array("olmBDBDNCache",		gettext("Number of DN Cache Items"),		"generic24.png"),
					array("olmBDBIDLCache",		gettext("Number of IDL Cache Items"),		"generic24.png"),
					array("olmDbDirectory",		gettext("Database Directory Path"),		"folder.png")
					)
				)
			));

		parent::__construct($ldap_server);
	}
}
?>
