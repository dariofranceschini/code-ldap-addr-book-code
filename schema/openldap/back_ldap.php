<?php
/** OpenLDAP Configuration (OLC) Schema for LDAP Proxy Backend (partial) */

class openldap_back_ldap_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		// Attributes
		$this->attribute_schema = array(
			array("name"=>"olmDbURIList",			"data_type"=>"text_list",	"display_name"=>gettext("Remote LDAP Directory URI List"))
			);

		// Object classes
		$this->object_schema = array(
			array("name"=>"olcLDAPConfig",			"icon"=>"openldap/db.png",		"is_folder"=>false,"rdn_attrib"=>"olcDatabase","display_name"=>gettext("LDAP Proxy Database"),"required_attribs"=>"olcSuffix","can_create"=>true,"create_method"=>"atomic","parent_class"=>"olcDatabaseConfig"),
			array("name"=>"olcChainConfig",			"icon"=>"openldap/overlay.png",		"is_folder"=>false,"rdn_attrib"=>"olcOverlay","display_name"=>gettext("Chain Overlay"),"parent_class"=>"olcOverlayConfig"),
			array("name"=>"olcDistProcConfig",		"icon"=>"openldap/overlay.png",		"is_folder"=>false,"rdn_attrib"=>"olcOverlay","display_name"=>gettext("Distributed Procedure Overlay"),"parent_class"=>"olcOverlayConfig"),
			array("name"=>"olcPBindConfig",			"icon"=>"openldap/overlay.png",		"is_folder"=>false,"rdn_attrib"=>"olcOverlay","display_name"=>gettext("LDAP Proxy Bind Overlay"),"parent_class"=>"olcOverlayConfig","required_attribs"=>"olcDbURI","can_create"=>true),
			array("name"=>"olmLDAPConnection",		"icon"=>"openldap/connection.png",	"is_folder"=>false,"display_name"=>gettext("Monitored LDAP Proxy Connection"),"parent_class"=>"monitorConnection")
			);

		// auxiliary class 'olmLDAPDatabase' is also defined in this schema

		// Display layouts
		$ldap_server->add_display_layout("olcLDAPConfig",array(
			array("section_name"=>gettext("LDAP Proxy Database Settings"),"new_row"=>true,
				"attributes"=>array(
					array("olcDbURI",		gettext("Remote LDAP Directory URI"),	"ldap-server.png"),
					array("olcSuffix",		gettext("Naming Context"),		"alias.png"),
					array("olcDbChaseReferrals",	gettext("Chase Referrals"),		"generic24.png"),
					array("olcDbRebindAsUser",	gettext("Rebind as User"),		"generic24.png"),
					),
				),

			array("section_name"=>gettext("Remote Identity Assertion"),"new_row"=>true,
				"attributes"=>array(
					array("olcDbIDAssertAuthzFrom",	gettext("Authorise From"),		"generic24.png"),

					// Potentially omit olcDbIDAssertBind attribute? - may contain confidential auth credentials!
					array("olcDbIDAssertBind",	gettext("Bind Configuration"),		"generic24.png"),
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

		$ldap_server->add_display_layout("olcPBindConfig",array(
			array("section_name"=>gettext("LDAP Bind Proxy Settings"),"new_row"=>true,
				"attributes"=>array(
					array("olcOverlay",		gettext("Overlay Object Name"),		"openldap/overlay.png"),

					array("olcDbURI",		gettext("Remote LDAP Directory URI"),	"ldap-server.png"),
					array("olcDbNetworkTimeout",	gettext("Network Timeout"),		"generic24.png"),
					array("olcDbStartTLS",		gettext("StartTLS Behaviour"),		"generic24.png"),
					array("olcDbQuarantine",	gettext("URI Quarantine Behaviour"),	"generic24.png"),
					)
				)
			));

		parent::__construct($ldap_server);
	}

	function populate_for_create_olcLDAPConfig(&$ldap_server,&$entry)
	{
		// override the schema-defined data type that the new database's DN can be typed in
		$ldap_server->modify_attribute_schema("olcSuffix","data_type","text");

		$this->add_attrib_value($ldap_server,$entry,"olcDbURI","ldap://localhost/");
		$this->add_attrib_value($ldap_server,$entry,"olcDbChaseReferrals","FALSE");
	}

	function before_create_olcLDAPConfig(&$ldap_server,&$entry)
	{
		$ldap_server->ensure_openldap_module_loaded("back_ldap");

		$ldap_server->assign_ordered_sequence_rdn($entry,"olcDatabaseConfig","ldap",-1);

		$this->add_attrib_single_value($ldap_server,$entry,"olcAccess",array(
			"{0}to * by * read")
			);

		// The following must be removed rather than assigned with an empty value (triggers "Invalid syntax" error)
		if(empty($entry["olcDbIDAssertAuthzFrom"])) unset($entry["olcDbIDAssertAuthzFrom"]);
		if(empty($entry["olcDbIDAssertBind"])) unset($entry["olcDbIDAssertBind"]);
	}

	function populate_for_create_olcPBindConfig(&$ldap_server,&$entry)
	{
		$ldap_server->assign_ordered_sequence_rdn($entry,"olcOverlayConfig","pbind");

		$this->add_attrib_value($ldap_server,$entry,"olcDbURI","ldap://localhost/");
	}

	function before_create_olcPBindConfig(&$ldap_server,&$entry)
	{
		$ldap_server->ensure_openldap_module_loaded("back_ldap");
	}
}
?>
