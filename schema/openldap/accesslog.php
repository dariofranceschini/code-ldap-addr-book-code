<?php
/** OpenLDAP Access Logging Overlay On-Line Configuration (OLC) schema (partial) */

class openldap_accesslog_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"olcAccessLogDB",			"data_type"=>"dn",		"display_name"=>gettext("Access Log Database Naming Context DN")),
			array("name"=>"olcAccessLogBase",		"data_type"=>"text_list",	"display_name"=>gettext("Subtree-Specific Access Logging Options")),
			array("name"=>"olcAccessLogOld",		"data_type"=>"text",		"display_name"=>gettext("Object Modification Logging Filter")),
			array("name"=>"olcAccessLogOldAttr",		"data_type"=>"text",		"display_name"=>gettext("Attributes to Always Log")),
			array("name"=>"olcAccessLogOps",		"data_type"=>"text",		"display_name"=>gettext("Operation Types to be Logged")),
			array("name"=>"olcAccessLogPurge",		"data_type"=>"text",		"display_name"=>gettext("Old Log Entry Removal Settings")),
			array("name"=>"olcAccessLogSuccess",		"data_type"=>"yes_no",		"display_name"=>gettext("Only Record Successful Operations")),

			array("name"=>"reqAttrsOnly",			"data_type"=>"yes_no",		"display_name"=>gettext("Return Attribute Type Names Only")),
			array("name"=>"reqAuthzID",			"data_type"=>"dn",		"display_name"=>gettext("Authorization ID of Requestor")),
			array("name"=>"reqDeleteOldRDN",		"data_type"=>"yes_no",		"display_name"=>gettext("Delete Old RDN")),
			array("name"=>"reqDN",				"data_type"=>"dn",		"display_name"=>gettext("Request Target DN")),
			array("name"=>"reqEnd",				"data_type"=>"date_time",	"display_name"=>gettext("Request End Time")),
			array("name"=>"reqMod",				"data_type"=>"text_list",	"display_name"=>gettext("Modification Data")),
			array("name"=>"reqOld",				"data_type"=>"text_list",	"display_name"=>gettext("Old Values from Before Request Processed")),
			array("name"=>"reqResult",			"data_type"=>"ldap_result",	"display_name"=>gettext("LDAP Result Code")),
			array("name"=>"reqStart",			"data_type"=>"date_time",	"display_name"=>gettext("Request Start Time"))
			);

		// Structural object classes
		$this->object_schema = array(
			array("name"=>"olcAccessLogConfig",		"icon"=>"openldap/overlay.png",			"is_folder"=>false,"rdn_attrib"=>"olcOverlay","display_name"=>gettext("Access Logging Overlay"),"can_create"=>true,"create_method"=>"atomic","parent_class"=>"olcOverlayConfig"),

			array("name"=>"auditContainer",			"icon"=>"openldap/audit-container.png",		"is_folder"=>true,"display_name"=>gettext("AuditLog Container")),
			array("name"=>"auditObject",			"icon"=>"document.png",				"is_folder"=>false,"rdn_attrib"=>"reqStart","display_name"=>gettext("Auditing Event"),"required_attribs"=>"reqType,reqSession"),
			array("name"=>"auditReadObject",		"icon"=>"document.png",				"is_folder"=>false,"rdn_attrib"=>"reqStart","display_name"=>gettext("Auditing Read Event"),"required_attribs"=>"reqType,reqSession"),
			array("name"=>"auditWriteObject",		"icon"=>"document.png",				"is_folder"=>false,"rdn_attrib"=>"reqStart","display_name"=>gettext("Auditing Write Event"),"required_attribs"=>"reqType,reqSession"),
			array("name"=>"auditAbandon",			"icon"=>"openldap/audit-abandon.png",		"is_folder"=>false,"rdn_attrib"=>"reqStart","display_name"=>gettext("Abandoned Operation Event"),"required_attribs"=>"reqType,reqSession,reqID"),
			array("name"=>"auditAdd",			"icon"=>"openldap/audit-add.png",		"is_folder"=>false,"rdn_attrib"=>"reqStart","display_name"=>gettext("Record Added Event"),"required_attribs"=>"reqType,reqSession,reqMod"),
			array("name"=>"auditBind",			"icon"=>"openldap/audit-bind.png",		"is_folder"=>false,"rdn_attrib"=>"reqStart","display_name"=>gettext("User Authenticated Event"),"required_attribs"=>"reqType,reqSession,reqVersino,reqMethod"),
			array("name"=>"auditCompare",			"icon"=>"openldap/audit-compare.png",		"is_folder"=>false,"rdn_attrib"=>"reqStart","display_name"=>gettext("Records Compared Event"),"required_attribs"=>"reqType,reqSession,reqAssertion"),
			array("name"=>"auditDelete",			"icon"=>"openldap/audit-delete.png",		"is_folder"=>false,"rdn_attrib"=>"reqStart","display_name"=>gettext("Record Deleted Event"),"required_attribs"=>"reqType,reqSession"),
			array("name"=>"auditModify",			"icon"=>"document-edit.png",			"is_folder"=>false,"rdn_attrib"=>"reqStart","display_name"=>gettext("Attributes Modified Event"),"required_attribs"=>"reqType,reqSession,reqMod"),
			array("name"=>"auditModRDN",			"icon"=>"openldap/audit-rename.png",		"is_folder"=>false,"rdn_attrib"=>"reqStart","display_name"=>gettext("Record Moved/Renamed Event"),"required_attribs"=>"reqType,reqSession,reqNewRDN,reqDeleteOldRDN"),
			array("name"=>"auditSearch",			"icon"=>"openldap/audit-search.png",		"is_folder"=>false,"rdn_attrib"=>"reqStart","display_name"=>gettext("Records Searched Event"),"required_attribs"=>"reqType,reqSession,reqScope,reqDerefAliases,reqAttrsOnly"),
			array("name"=>"auditExtended",			"icon"=>"config-file.png",			"is_folder"=>false,"rdn_attrib"=>"reqStart","display_name"=>gettext("Extended Operation Event"),"required_attribs"=>"reqType,reqSession")
			);

		$ldap_server->add_display_layout("olcAccessLogConfig",array(
			array("section_name"=>gettext("Access Logging Overlay Settings"),"new_row"=>true,
				"attributes"=>array(
					array("olcOverlay",		gettext("Overlay Object Name"),					"openldap/overlay.png"),

					// TODO: must be DN of another database's naming context
					// higher numbered than the one to which the overlay applies???
					array("olcAccessLogDB",		gettext("Database in Which to Store the Access Log"),		"openldap/db.png"),
					array("olcAccessLogOps",	gettext("Operation Types to be Logged (Space-Seperated)"),	"generic24.png"),
					array("olcAccessLogSuccess",	gettext("Only Record Successful Operations"),			"generic24.png"),
					array("olcAccessLogPurge",	gettext("Remove Old Log Entries (Maximum Age, Cleanup Interval)"),"generic24.png")
					)
				),
			array("section_name"=>gettext("Subtree-Specific Logging Options"),"new_row"=>true,
				"attributes"=>array(
					array("olcAccessLogBase")
					)
				),
			array("section_name"=>gettext("Log Old Values When Attributes are Modified"),"new_row"=>true,
				"attributes"=>array(
					array("olcAccessLogOldAttr",	gettext("Always Log for the Following Attributes"),		"generic24.png"),
					array("olcAccessLogOld",	gettext("When Objects Matching this LDAP Filter are Modified"),	"generic24.png")
					)
				)
			));

		parent::__construct($ldap_server);
	}

	function populate_for_create_olcAccessLogConfig(&$ldap_server,&$entry)
	{
		$ldap_server->assign_ordered_sequence_rdn($entry,"olcOverlayConfig","accesslog");

		// override the schema-defined data type that the access log database's DN can be typed in
		$ldap_server->modify_attribute_schema("olcAccessLogDB","data_type","text");

		$this->add_attrib_value($ldap_server,$entry,"olcAccessLogOps","all");
		$this->add_attrib_value($ldap_server,$entry,"olcAccessLogOld","(objectClass=*)");
		$this->add_attrib_value($ldap_server,$entry,"olcAccessLogOldAttr","cn");
		$this->add_attrib_value($ldap_server,$entry,"olcAccessLogPurge","30+00:00 1+00:00");	// 30 day log retention, housekeeping every 1 day
		$this->add_attrib_value($ldap_server,$entry,"olcAccessLogSuccess","FALSE");
	}

	function before_create_olcAccessLogConfig(&$ldap_server,&$entry)
	{
		$ldap_server->ensure_openldap_module_loaded("accesslog");
	}
}
?>
