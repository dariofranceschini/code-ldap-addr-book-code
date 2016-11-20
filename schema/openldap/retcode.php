<?php
/** OpenLDAP Return Code Overlay On-Line Configuration (OLC) schema (partial) */

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

		parent::__construct($ldap_server);
	}
}
?>
