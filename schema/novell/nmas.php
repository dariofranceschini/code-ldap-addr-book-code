<?php
/** Novell Modular Authentication Service (NMAS) schema (partial) */

class novell_nmas_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"sasLoginSequence","data_type"=>"text_list","display_name"=>gettext("SAS Login Sequence")),
			);

		// Object classes
		$this->object_schema = array(
			array("name"=>"sASLoginPolicy",			"icon"=>"novell/login-policy.png",	"is_folder"=>false,"display_name"=>gettext("Login Policy")),
			);

		// Display layouts
		$ldap_server->add_display_layout("sASLoginPolicy",array(
			array("section_name"=>gettext("Login Policy"),"new_row"=>true,
				"attributes"=>array(
					array("sasAuditConfiguration",		gettext("Enable Auditing"),			"generic24.png"),

					array("sasLoginSequence",		gettext("NMAS Login Sequences"),		"generic24.png"),
					array("sasDefaultLoginSequence",	gettext("Default Login Sequence"),		"generic24.png"),

					// Binary attributes - no handlers to display yet

				//	array("sasPolicyCredentials",		gettext("Policy Credentials"),			"generic24.png"),
				//	array("sasNMASProductOptions",		gettext("NMAS Product Options"),		"generic24.png"),
				//	array("masvAuthorizedRange",		gettext("MASV Authorized Range"),		"generic24.png"),
				//	array("masvDefaultRange",		gettext("MASV Default Range"),			"generic24.png"),

					array("sasLoginPolicyUpdate",		gettext("Policy Update"),			"generic24.png"),
					array("sasPolicyObjectVersion",		gettext("Policy Object Version"),		"generic24.png")
					)
				)
			));

		parent::__construct($ldap_server);
	}
}
?>
