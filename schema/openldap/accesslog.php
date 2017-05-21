<?php
/** OpenLDAP Configuration (OLC) Schema for Access Logging Overlay

    @see https://tools.ietf.org/html/draft-chu-ldap-logschema-00
*/

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

			array("name"=>"auditContext",			"data_type"=>"dn",		"display_name"=>gettext("Auditing Log Naming Context")),

			array("name"=>"reqAssertion",			"data_type"=>"text_list",	"display_name"=>gettext("Attribute Value Assertion")),
			array("name"=>"reqAttr",			"data_type"=>"text_list",	"display_name"=>gettext("Requested Attribute")),
			array("name"=>"reqAttrsOnly",			"data_type"=>"yes_no",		"display_name"=>gettext("Return Attribute Type Names Only")),
			array("name"=>"reqAuthzID",			"data_type"=>"dn",		"display_name"=>gettext("Authorization ID of Requestor")),
			array("name"=>"reqControls",			"data_type"=>"text_list",	"display_name"=>gettext("Request Controls")),
			array("name"=>"reqData",			"data_type"=>"download_list",	"display_name"=>gettext("Extended Request Data")),
			array("name"=>"reqDeleteOldRDN",		"data_type"=>"yes_no",		"display_name"=>gettext("Delete Old RDN")),
			array("name"=>"reqDerefAliases",		"data_type"=>"alias_deref",	"display_name"=>gettext("Alias Dereferencing Behavior")),
			array("name"=>"reqDN",				"data_type"=>"dn",		"display_name"=>gettext("Request Target DN")),
			array("name"=>"reqEnd",				"data_type"=>"date_time",	"display_name"=>gettext("Request End Time")),
			array("name"=>"reqEntries",			"data_type"=>"text",		"display_name"=>gettext("Number of Entries Returned")),
			array("name"=>"reqEntryUUID",			"data_type"=>"text",		"display_name"=>gettext("Universally Unique Identifier")),
			array("name"=>"reqFilter",			"data_type"=>"text",		"display_name"=>gettext("Request Filter Expression")),
			array("name"=>"reqID",				"data_type"=>"text",		"display_name"=>gettext("Request ID to be Abandoned")),
			array("name"=>"reqMessage",			"data_type"=>"text",		"display_name"=>gettext("Error Details")),
			array("name"=>"reqMethod",			"data_type"=>"text",		"display_name"=>gettext("Bind Method")),
			array("name"=>"reqMod",				"data_type"=>"text_list",	"display_name"=>gettext("Modification Details")),
			array("name"=>"reqNewRDN",			"data_type"=>"text",		"display_name"=>gettext("New RDN")),
			array("name"=>"reqNewSuperior",			"data_type"=>"dn",		"display_name"=>gettext("New Containing Object")),
			array("name"=>"reqOld",				"data_type"=>"text_list",	"display_name"=>gettext("Old Values from Before Request Processed")),
			array("name"=>"reqReferral",			"data_type"=>"text_list",	"display_name"=>gettext("Referral URL")),
			array("name"=>"reqResult",			"data_type"=>"ldap_result",	"display_name"=>gettext("LDAP Result Code")),
			array("name"=>"reqRespControls",		"data_type"=>"text_list",	"display_name"=>gettext("Response Controls")),
			array("name"=>"reqScope",			"data_type"=>"search_scope",	"display_name"=>gettext("Request Scope")),
			array("name"=>"reqSession",			"data_type"=>"text",		"display_name"=>gettext("Session ID")),
			array("name"=>"reqSizeLimit",			"data_type"=>"text",		"display_name"=>gettext("Maximum Number of Entries to be Returned")),
			array("name"=>"reqStart",			"data_type"=>"date_time",	"display_name"=>gettext("Request Start Time")),
			array("name"=>"reqTimeLimit",			"data_type"=>"text",		"display_name"=>gettext("Maximum Processing Time")),
			array("name"=>"reqType",			"data_type"=>"text",		"display_name"=>gettext("Request Operation Type")),
			array("name"=>"reqVersion",			"data_type"=>"ldap_version",	"display_name"=>gettext("LDAP Protocol Version")),
			);

		// Object classes
		$this->object_schema = array(
			array("name"=>"olcAccessLogConfig",		"icon"=>"openldap/overlay.png",			"is_folder"=>false,"rdn_attrib"=>"olcOverlay","display_name"=>gettext("Access Logging Overlay"),"can_create"=>true,"create_method"=>"atomic","parent_class"=>"olcOverlayConfig"),

			array("name"=>"auditContainer",			"icon"=>"openldap/audit-container.png",		"is_folder"=>true,"display_name"=>gettext("AuditLog Container")),
			array("name"=>"auditObject",			"icon"=>"document.png",				"is_folder"=>false,"rdn_attrib"=>"reqStart","display_name"=>gettext("Auditing Event"),"required_attribs"=>"reqType,reqSession"),
			array("name"=>"auditReadObject",		"icon"=>"document.png",				"is_folder"=>false,"rdn_attrib"=>"reqStart","display_name"=>gettext("Auditing Read Event"),"required_attribs"=>"reqType,reqSession","parent_class"=>"auditObject"),
			array("name"=>"auditWriteObject",		"icon"=>"document.png",				"is_folder"=>false,"rdn_attrib"=>"reqStart","display_name"=>gettext("Auditing Write Event"),"required_attribs"=>"reqType,reqSession","parent_class"=>"auditObject"),
			array("name"=>"auditAbandon",			"icon"=>"openldap/audit-abandon.png",		"is_folder"=>false,"rdn_attrib"=>"reqStart","display_name"=>gettext("Abandoned Operation Event"),"required_attribs"=>"reqType,reqSession,reqID","parent_class"=>"auditObject"),
			array("name"=>"auditAdd",			"icon"=>"openldap/audit-add.png",		"is_folder"=>false,"rdn_attrib"=>"reqStart","display_name"=>gettext("Record Added Event"),"required_attribs"=>"reqType,reqSession,reqMod","parent_class"=>"auditWriteObject"),
			array("name"=>"auditBind",			"icon"=>"openldap/audit-bind.png",		"is_folder"=>false,"rdn_attrib"=>"reqStart","display_name"=>gettext("User Authenticated Event"),"required_attribs"=>"reqType,reqSession,reqVersion,reqMethod","parent_class"=>"auditObject"),
			array("name"=>"auditCompare",			"icon"=>"openldap/audit-compare.png",		"is_folder"=>false,"rdn_attrib"=>"reqStart","display_name"=>gettext("Attribute Values Compared Event"),"required_attribs"=>"reqType,reqSession,reqAssertion","parent_class"=>"auditReadObject"),
			array("name"=>"auditDelete",			"icon"=>"openldap/audit-delete.png",		"is_folder"=>false,"rdn_attrib"=>"reqStart","display_name"=>gettext("Record Deleted Event"),"required_attribs"=>"reqType,reqSession","parent_class"=>"auditWriteObject"),
			array("name"=>"auditModify",			"icon"=>"document-edit.png",			"is_folder"=>false,"rdn_attrib"=>"reqStart","display_name"=>gettext("Attributes Modified Event"),"required_attribs"=>"reqType,reqSession,reqMod","parent_class"=>"auditWriteObject"),
			array("name"=>"auditModRDN",			"icon"=>"openldap/audit-rename.png",		"is_folder"=>false,"rdn_attrib"=>"reqStart","display_name"=>gettext("Record Moved/Renamed Event"),"required_attribs"=>"reqType,reqSession,reqNewRDN,reqDeleteOldRDN","parent_class"=>"auditWriteObject"),
			array("name"=>"auditSearch",			"icon"=>"openldap/audit-search.png",		"is_folder"=>false,"rdn_attrib"=>"reqStart","display_name"=>gettext("Records Searched Event"),"required_attribs"=>"reqType,reqSession,reqScope,reqDerefAliases,reqAttrsOnly","parent_class"=>"auditReadObject"),
			array("name"=>"auditExtended",			"icon"=>"config-file.png",			"is_folder"=>false,"rdn_attrib"=>"reqStart","display_name"=>gettext("Extended Operation Event"),"required_attribs"=>"reqType,reqSession","parent_class"=>"auditObject")
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

		$ldap_server->add_display_layout("auditContainer",array(
			array("section_name"=>gettext("Access Log Name"),"new_row"=>true,"colspan"=>2,
				"attributes"=>array(
					array("cn")
					)
				),
			array("section_name"=>gettext("Start"),"new_row"=>true,
				"attributes"=>array(
					array("reqStart")
					)
				),
			array("section_name"=>gettext("End"),"width"=>"50%",
				"attributes"=>array(
					array("reqEnd")
					)
				)
			));

		$ldap_server->add_display_layout("auditObject",array(
			array("section_name"=>gettext("Event Details"),"new_row"=>true,"rowspan"=>2,
				"attributes"=>array(
					array("reqType",		gettext("Operation Type"),					"generic24.png"),
					array("reqStart",		gettext("Started"),						"generic24.png"),
					array("reqEnd",			gettext("Ended"),						"generic24.png")
					)
				),
			array("section_name"=>gettext("Requested By"),"width"=>"50%",
				"attributes"=>array(
					array("reqAuthzID",		gettext("User"),						"alias.png"),
					array("reqSession",		gettext("Session ID"),						"openldap/connection.png")
					)
				),
			array("section_name"=>gettext("Request"),"new_row"=>true,"colspan"=>2,
				"attributes"=>array(
					array("reqDN",			gettext("DN"),							"alias.png"),
					array("reqResult",		gettext("Result"),						"openldap/error.png")
					)
				)
			));

		$ldap_server->add_display_layout("auditAbandon",array(
			array("section_name"=>gettext("Event Details"),"new_row"=>true,"rowspan"=>2,
				"attributes"=>array(
					array("reqType",		gettext("Operation Type"),					"generic24.png"),
					array("reqStart",		gettext("Started"),						"generic24.png"),
					array("reqEnd",			gettext("Ended"),						"generic24.png")
					)
				),
			array("section_name"=>gettext("Requested By"),"width"=>"50%",
				"attributes"=>array(
					array("reqAuthzID",		gettext("User"),						"alias.png"),
					array("reqSession",		gettext("Session ID"),						"openldap/connection.png")
					)
				),
			array("section_name"=>gettext("Abandon Request"),"new_row"=>true,"colspan"=>2,
				"attributes"=>array(
					array("reqID",			gettext("Request ID to be Abandoned"),				"generic24.png")
					)
				)
			));

		$ldap_server->add_display_layout("auditAdd",array(
			array("section_name"=>gettext("Event Details"),"new_row"=>true,"rowspan"=>2,
				"attributes"=>array(
					array("reqType",		gettext("Operation Type"),					"generic24.png"),
					array("reqStart",		gettext("Started"),						"generic24.png"),
					array("reqEnd",			gettext("Ended"),						"generic24.png")
					)
				),
			array("section_name"=>gettext("Requested By"),"width"=>"50%",
				"attributes"=>array(
					array("reqAuthzID",		gettext("User"),						"alias.png"),
					array("reqSession",		gettext("Session ID"),						"openldap/connection.png")
					)
				),
			array("section_name"=>gettext("Add Entry Request"),"new_row"=>true,"colspan"=>2,
				"attributes"=>array(
					array("reqDN",			gettext("Object to be Added"),					"alias.png"),
					array("reqResult",		gettext("Result"),						"openldap/error.png"),
					array("reqEntryUUID",		gettext("UUID Assigned"),					"generic24.png")
					)
				),
			array("section_name"=>gettext("Data Added"),"new_row"=>true,"colspan"=>2,
				"attributes"=>array(
					array("reqMod")
					)
				)
			));

		$ldap_server->add_display_layout("auditBind",array(
			array("section_name"=>gettext("Event Details"),"new_row"=>true,"rowspan"=>2,
				"attributes"=>array(
					array("reqType",		gettext("Operation Type"),					"generic24.png"),
					array("reqStart",		gettext("Started"),						"generic24.png"),
					array("reqEnd",			gettext("Ended"),						"generic24.png")
					)
				),
			array("section_name"=>gettext("Requested By"),"width"=>"50%",
				"attributes"=>array(
					// reqAuthzID is typically empty for this request type
					array("reqDN",			gettext("User (Bind DN)"),					"generic24.png"),
					array("reqSession",		gettext("Session ID"),						"openldap/connection.png"),
					)
				),
			array("section_name"=>gettext("Bind Request"),"new_row"=>true,"colspan"=>2,
				"attributes"=>array(
					array("reqMethod",		gettext("Bind Method"),						"generic24.png"),
					array("reqVersion",		gettext("LDAP Version"),					"generic24.png"),
					array("reqResult",		gettext("Result"),						"openldap/error.png")
					)
				)
			));

		$ldap_server->add_display_layout("auditCompare",array(
			array("section_name"=>gettext("Event Details"),"new_row"=>true,"rowspan"=>2,
				"attributes"=>array(
					array("reqType",		gettext("Operation Type"),					"generic24.png"),
					array("reqStart",		gettext("Started"),						"generic24.png"),
					array("reqEnd",			gettext("Ended"),						"generic24.png")
					)
				),
			array("section_name"=>gettext("Requested By"),"width"=>"50%",
				"attributes"=>array(
					array("reqAuthzID",		gettext("User"),						"alias.png"),
					array("reqSession",		gettext("Session ID"),						"openldap/connection.png")
					)
				),
			array("section_name"=>gettext("Compare Attribute Values Request"),"new_row"=>true,"colspan"=>2,
				"attributes"=>array(
					array("reqDN",			gettext("Object to be Tested"),					"generic24.png"),
					array("reqAssertion",		gettext("Attribute Value Assertion"),				"generic24.png"),
					array("reqResult",		gettext("Result"),						"openldap/error.png")
					)
				)
			));

		$ldap_server->add_display_layout("auditDelete",array(
			array("section_name"=>gettext("Event Details"),"new_row"=>true,"rowspan"=>2,
				"attributes"=>array(
					array("reqType",		gettext("Operation Type"),					"generic24.png"),
					array("reqStart",		gettext("Started"),						"generic24.png"),
					array("reqEnd",			gettext("Ended"),						"generic24.png")
					)
				),
			array("section_name"=>gettext("Requested By"),"width"=>"50%",
				"attributes"=>array(
					array("reqAuthzID",		gettext("User"),						"alias.png"),
					array("reqSession",		gettext("Session ID"),						"openldap/connection.png")
					)
				),
			array("section_name"=>gettext("Delete Entry Request"),"new_row"=>true,"colspan"=>2,
				"attributes"=>array(
					array("reqDN",			gettext("Object to be Deleted"),				"alias.png"),
					array("reqEntryUUID",		gettext("UUID"),						"generic24.png"),
					array("reqResult",		gettext("Result"),						"openldap/error.png")
					)
				),
			array("section_name"=>gettext("Data Deleted"),"new_row"=>true,"colspan"=>2,
				"attributes"=>array(
					array("reqOld")
					)
				)
			));

		$ldap_server->add_display_layout("auditExtended",array(
			array("section_name"=>gettext("Event Details"),"new_row"=>true,"rowspan"=>2,
				"attributes"=>array(
					array("reqType",		gettext("Operation Type"),					"generic24.png"),
					array("reqStart",		gettext("Started"),						"generic24.png"),
					array("reqEnd",			gettext("Ended"),						"generic24.png")
					)
				),
			array("section_name"=>gettext("Requested By"),"width"=>"50%",
				"attributes"=>array(
					array("reqAuthzID",		gettext("User"),						"alias.png"),
					array("reqSession",		gettext("Session ID"),						"openldap/connection.png")
					)
				),
			array("section_name"=>gettext("Extended Operation Request"),"new_row"=>true,"colspan"=>2,
				"attributes"=>array(
					array("reqData",		gettext("Extended Request Data"),				"generic24.png.png")
					)
				)
			));

		$ldap_server->add_display_layout("auditModify",array(
			array("section_name"=>gettext("Event Details"),"new_row"=>true,"rowspan"=>2,
				"attributes"=>array(
					array("reqType",		gettext("Operation Type"),					"generic24.png"),
					array("reqStart",		gettext("Started"),						"generic24.png"),
					array("reqEnd",			gettext("Ended"),						"generic24.png")
					)
				),
			array("section_name"=>gettext("Requested By"),"width"=>"50%",
				"attributes"=>array(
					array("reqAuthzID",		gettext("User"),						"alias.png"),
					array("reqSession",		gettext("Session ID"),						"openldap/connection.png")
					)
				),
			array("section_name"=>gettext("Modify Request"),"new_row"=>true,"colspan"=>2,
				"attributes"=>array(
					array("reqDN",			gettext("Object to be Modified"),				"alias.png"),
					array("reqResult",		gettext("Result"),						"openldap/error.png"),
					array("reqMessage",		gettext("Error Details"),					"openldap/error.png")
					)
				),
			array("section_name"=>gettext("Original Values"),"new_row"=>true,
				"attributes"=>array(
					array("reqOld")
					)
				),
			array("section_name"=>gettext("Replacement Values"),
				"attributes"=>array(
					array("reqMod")
					)
				)
			));

		$ldap_server->add_display_layout("auditModRDN",array(
			array("section_name"=>gettext("Event Details"),"new_row"=>true,"rowspan"=>2,
				"attributes"=>array(
					array("reqType",		gettext("Operation Type"),					"generic24.png"),
					array("reqStart",		gettext("Started"),						"generic24.png"),
					array("reqEnd",			gettext("Ended"),						"generic24.png")
					)
				),
			array("section_name"=>gettext("Requested By"),"width"=>"50%",
				"attributes"=>array(
					array("reqAuthzID",		gettext("User"),						"alias.png"),
					array("reqSession",		gettext("Session ID"),						"openldap/connection.png")
					)
				),
			array("section_name"=>gettext("Modify RDN (Move/Rename) Request"),"new_row"=>true,"colspan"=>2,
				"attributes"=>array(
					array("reqDN",			gettext("Object to be Modified"),				"alias.png"),
					array("reqNewRDN",		gettext("New RDN to be Assigned"),				"generic24.png"),
					array("reqNewSuperior",		gettext("New Location to be Assigned"),				"generic24.png"),
					array("reqDeleteOldRDN",	gettext("Delete Old RDN"),					"generic24.png"),
					array("reqResult",		gettext("Result"),						"openldap/error.png"),
					)
				),
			array("section_name"=>gettext("Original Values"),"new_row"=>true,
				"attributes"=>array(
					array("reqOld")
					)
				),
			array("section_name"=>gettext("Replacement Values"),
				"attributes"=>array(
					array("reqMod")
					)
				)
			));

		$ldap_server->add_display_layout("auditSearch",array(
			array("section_name"=>gettext("Event Details"),"new_row"=>true,"rowspan"=>2,
				"attributes"=>array(
					array("reqType",		gettext("Operation Type"),					"generic24.png"),
					array("reqStart",		gettext("Started"),						"generic24.png"),
					array("reqEnd",			gettext("Ended"),						"generic24.png")
					)
				),
			array("section_name"=>gettext("Requested By"),"width"=>"50%",
				"attributes"=>array(
					array("reqAuthzID",		gettext("User"),						"alias.png"),
					array("reqSession",		gettext("Session ID"),						"openldap/connection.png")
					)
				),
			array("section_name"=>gettext("Search Request"),"new_row"=>true,"colspan"=>2,
				"attributes"=>array(
					array("reqDN",			gettext("Search Base"),						"generic24.png"),
					array("reqScope",		gettext("Search Scope"),					"generic24.png"),
					array("reqFilter",		gettext("Search Filter"),					"generic24.png"),
					array("reqAttr",		gettext("Requested Attributes List"),				"generic24.png"),
					array("reqAttrsOnly",		gettext("Return Attribute Type Names Only"),			"generic24.png"),
					array("reqDerefAliases",	gettext("Alias Dereferencing Behavior"),			"generic24.png"),
					array("reqSizeLimit",		gettext("Maximum Number of Entries to be Returned"),		"generic24.png"),
					array("reqTimeLimit",		gettext("Maximum Search Time (s)"),				"generic24.png")
					)
				),
			array("section_name"=>gettext("Controls"),"new_row"=>true,"colspan"=>2,
				"attributes"=>array(
					array("reqControls",		gettext("Sent with Request"),					"generic24.png"),
					array("reqRespControls",	gettext("Received with Response"),				"generic24.png")
					)
				),
			array("section_name"=>gettext("Search Result"),"new_row"=>true,"colspan"=>2,
				"attributes"=>array(
					array("reqResult",		gettext("Result"),						"openldap/error.png"),
					array("reqEntries",		gettext("Number of Entries Returned"),				"generic24.png"),
					array("reqReferral",		gettext("Referral URLs"),					"generic24.png")
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

		$this->add_attrib_value($ldap_server,$entry,"olcAccessLogOps","writes");
		$this->add_attrib_value($ldap_server,$entry,"olcAccessLogOld","(objectClass=*)");
		$this->add_attrib_value($ldap_server,$entry,"olcAccessLogOldAttr","cn");
		$this->add_attrib_value($ldap_server,$entry,"olcAccessLogPurge","30+00:00 1+00:00");	// 30 day log retention, housekeeping every 1 day
		$this->add_attrib_value($ldap_server,$entry,"olcAccessLogSuccess","FALSE");
	}

	function before_create_olcAccessLogConfig(&$ldap_server,&$entry)
	{
		$ldap_server->ensure_openldap_module_loaded("accesslog");
	}

	function populate_for_create_auditContainer(&$ldap_server,&$entry)
	{
		$this->add_attrib_value($ldap_server,$entry,"reqStart",gmdate("YmdHis.u") . "Z");
	}
}
?>
