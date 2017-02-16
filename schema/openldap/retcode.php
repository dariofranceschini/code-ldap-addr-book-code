<?php
/** OpenLDAP Configuration (OLC) Schema for Return Code Overlay (partial) */

class openldap_retcode_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"olcRetcodeInDir",		"data_type"=>"yes_no",		"display_name"=>gettext("Use Error Information from errAbsObject Attributes")),
			array("name"=>"olcRetcodeItem",			"data_type"=>"text_list",	"display_name"=>gettext("Dynamically Generated Entries")),
			array("name"=>"olcRetcodeParent",		"data_type"=>"dn",		"display_name"=>gettext("Parent DN for Dynamically Generated Entries")),
			array("name"=>"olcRetcodeSleep",		"data_type"=>"text",		"display_name"=>gettext("Response Delay")),

			array("name"=>"errCode",			"data_type"=>"ldap_result",	"display_name"=>gettext("LDAP Error Code")),
			array("name"=>"errDisconnect",			"data_type"=>"yes_no",		"display_name"=>gettext("Disconnect Without Notice")),
			array("name"=>"errMatchedDN",			"data_type"=>"dn",		"display_name"=>gettext("Matched DN")),
			array("name"=>"errOp",				"data_type"=>"text",		"display_name"=>gettext("Operation Type(s) Which Trigger Error")),
			array("name"=>"errSleepTime",			"data_type"=>"text",		"display_name"=>gettext("Error Response Delay")),
			array("name"=>"errText",			"data_type"=>"text",		"display_name"=>gettext("Diagnostic Message")),
			array("name"=>"errUnsolicitedData",		"data_type"=>"text",		"display_name"=>gettext("OID to Return within Unsolicited Response")),
			array("name"=>"errUnsolicitedOID",		"data_type"=>"text",		"display_name"=>gettext("Data to Return within Unsolicited Response"))
			);

		// Structural object classes
		$this->object_schema = array(
			array("name"=>"olcRetcodeConfig",			"icon"=>"openldap/overlay.png",		"is_folder"=>false,"rdn_attrib"=>"olcOverlay","display_name"=>gettext("Return Code Overlay"),"parent_class"=>"olcOverlayConfig"),
			array("name"=>"errObject",				"icon"=>"openldap/error.png",		"is_folder"=>false,"display_name"=>gettext("Error Debugging Object"),"required_attribs"=>"errCode")
			);

		// abstract class 'errAbsObject' is also defined in this schema
		// auxiliary class 'errAuxObject' is also defined in this schema

		// Display layouts
		$ldap_server->add_display_layout("olcRetcodeConfig",array(
			array("section_name"=>gettext("Return Code Overlay Settings"),"new_row"=>true,
				"attributes"=>array(
					array("olcOverlay",			gettext("Overlay Object Name"),					"openldap/overlay.png"),
					array("olcRetcodeParent",		gettext("Parent DN for Dynamically Generated Entries"),		"generic24.png"),
					array("olcRetcodeInDir",		gettext("Use Error Information from errAbsObject Attributes"),	"generic24.png"),
					array("olcRetcodeSleep",		gettext("Response Delay (s)"),					"generic24.png"),
					)
				),
			array("section_name"=>gettext("Dynamically Generated Entries"),"new_row"=>true,
				"attributes"=>array(
					array("olcRetcodeItem")
					)
				)
			));

		$ldap_server->add_display_layout("errObject",array(
			array("section_name"=>gettext("Error Debugging Object"),"new_row"=>true,
				"attributes"=>array(
					array("cn",				gettext("Object Name"),						"openldap/overlay.png"),
					array("description",			gettext("Description"),						"description.png"),
					)
				),
			array("section_name"=>gettext("Error Behavior"),"new_row"=>true,
				"attributes"=>array(
					array("errDisconnect",			gettext("Immediately Disconnect (Before Error)"),		"generic24.png"),
					array("errCode",			gettext("LDAP Error Code"),					"openldap/error.png"),
					array("errOp",				gettext("Operation Type(s) Which Trigger Error"),		"generic24.png"),
					array("errText",			gettext("Diagnostic Message"),					"generic24.png"),
					array("errSleepTime",			gettext("Response Delay (s)"),					"generic24.png"),
					array("errMatchedDN",			gettext("Value to Return as Matched DN"),			"generic24.png"),
					array("errUnsolicitedOID",		gettext("OID to Return within Unsolicited Response"),		"generic24.png"),
					array("errUnsolicitedData",		gettext("Data to Return within Unsolicited Response"),		"generic24.png"),
					)
				)
			));

		parent::__construct($ldap_server);
	}

	function populate_for_create_olcRetcodeConfig(&$ldap_server,&$entry)
	{
		$ldap_server->assign_ordered_sequence_rdn($entry,"olcOverlayConfig","retcode");
	}

	function before_create_olcRetcodeConfig(&$ldap_server,&$entry)
	{
		$ldap_server->ensure_openldap_module_loaded("retcode");
	}

	function populate_for_create_errObject(&$ldap_server,&$entry)
	{
		$this->add_attrib_value($ldap_server,$entry,"errDisconnect","FALSE");
	}
}
?>
