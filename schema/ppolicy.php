<?php
/** Password Policy for LDAP Directories (ppolicy.schema)

    This schema defines attributes for implementing a password policy, for use
    with directories that support this functionality in the manner described
    in the IETF draft, "Password Policy for LDAP Directories".

    @see:
	https://tools.ietf.org/html/draft-behera-ldap-password-policy-10

    (Note: The OpenLDAP source code includes a "version 11" of this draft
    which is not published by the IETF at the time of writing.)
*/

class ppolicy_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			// Attributes used to define a password policy

			array("name"=>"pwdAllowUserChange",		"data_type"=>"yes_no",		"display_name"=>gettext("Allow Users to Change their Password")),
			array("name"=>"pwdAttribute",			"data_type"=>"text",		"display_name"=>gettext("Password Attribute Name")),
			array("name"=>"pwdCheckQuality",		"data_type"=>"pwd_qualitycheck","display_name"=>gettext("Password Quality Check Behavior")),
			array("name"=>"pwdExpireWarning",		"data_type"=>"text",		"display_name"=>gettext("Length of Warning Period Before Password Expiry")),
			array("name"=>"pwdFailureCountInterval",	"data_type"=>"text",		"display_name"=>gettext("Reset Time for Failed Logins Counter")),
			array("name"=>"pwdGraceAuthNLimit",		"data_type"=>"text",		"display_name"=>gettext("Number of Grace Logins Allowed for Expired Passwords")),
			array("name"=>"pwdInHistory",			"data_type"=>"text",		"display_name"=>gettext("Number of Passwords to Store in History")),
			array("name"=>"pwdLockout",			"data_type"=>"yes_no",		"display_name"=>gettext("Lock Accounts after Consecutive Login Failures")),
			array("name"=>"pwdLockoutDuration",		"data_type"=>"text",		"display_name"=>gettext("Account Lock Time after Consecutive Login Failures")),
			array("name"=>"pwdMaxAge",			"data_type"=>"text",		"display_name"=>gettext("Maximum Password Age (Expiry Time)")),
			array("name"=>"pwdMaxFailure",			"data_type"=>"text",		"display_name"=>gettext("Number of Consecutive Login Failures before Lock-Out Policy Applies")),
			array("name"=>"pwdMinAge",			"data_type"=>"text",		"display_name"=>gettext("Minimum Password Age")),
			array("name"=>"pwdMinLength",			"data_type"=>"text",		"display_name"=>gettext("Minimum Accepted Password Length")),
			array("name"=>"pwdMustChange",			"data_type"=>"yes_no",		"display_name"=>gettext("Users Must Change Password at Next Logon After Reset")),
			array("name"=>"pwdSafeModify",			"data_type"=>"yes_no",		"display_name"=>gettext("Users Must Send Old and New Passwords When Changing")),

			// pwdCheckModule is not described in draft-behera-ldap-password-policy-10
			array("name"=>"pwdCheckModule",			"data_type"=>"text",		"display_name"=>gettext("Password Quality Check Module Name")),

			// The following attributes are described in draft-behera-ldap-password-policy-10
			// but are not defined in the ppolicy.schema file provided with OpenLDAP 2.4 and
			// earlier.

			array("name"=>"pwdGraceExpiry",			"data_type"=>"text",		"display_name"=>gettext("Grace Login Validity Period")),
			array("name"=>"pwdMaxDelay",			"data_type"=>"text",		"display_name"=>gettext("Maximum Delay Introduced when Responding to Login Failure")),
			array("name"=>"pwdMaxIdle",			"data_type"=>"text",		"display_name"=>gettext("Time after which Unused Accounts are Locked Out")),
			array("name"=>"pwdMaxLength",			"data_type"=>"text",		"display_name"=>gettext("Maximum Accepted Password Length")),
			array("name"=>"pwdMinDelay",			"data_type"=>"text",		"display_name"=>gettext("Initial Delay Introduced when Responding to Login Failure")),

			// Operational attributes used to maintain policy state for each user account

			array("name"=>"pwdAccountLockedTime",		"data_type"=>"date_time",	"display_name"=>gettext("Time When Account Locked")),
			array("name"=>"pwdChangedTime",			"data_type"=>"date_time",	"display_name"=>gettext("Time When Password Last Changed")),
			array("name"=>"pwdFailureTime",			"data_type"=>"text_list",	"display_name"=>gettext("Times of Last Consecutive Login Failures")),		// ideally a list of date_time
			array("name"=>"pwdGraceUseTime",		"data_type"=>"text_list",	"display_name"=>gettext("Times of Grace Logins after Password Expiry")),	// ideally a list of date_time
			array("name"=>"pwdHistory",			"data_type"=>"text_list",	"display_name"=>gettext("Password History")),
			array("name"=>"pwdPolicySubentry",		"data_type"=>"dn",		"display_name"=>gettext("Policy Object DN")),
			array("name"=>"pwdReset",			"data_type"=>"yes_no",		"display_name"=>gettext("Password Has Been Reset")),

			// The following attributes are described in draft-behera-ldap-password-policy-10
			// but are not defined in the ppolicy.schema file or by the "ppolicy" overlay
			// module provided with OpenLDAP 2.4 and earlier.

			array("name"=>"pwdEndTime",			"data_type"=>"date_time",	"display_name"=>gettext("End of Password Validity Period")),
			array("name"=>"pwdLastSuccess",			"data_type"=>"date_time",	"display_name"=>gettext("Time of Last Successful Login")),
			array("name"=>"pwdStartTime",			"data_type"=>"date_time",	"display_name"=>gettext("Start of Password Validity Period"))
			);

		// Object classes

                $this->object_schema = array(
			array("name"=>"pwdPolicy",			"icon"=>"password-policy.png",		"class_type"=>"auxiliary","display_name"=>gettext("Password Policy"),"required_attribs"=>"pwdAttribute"),
			array("name"=>"pwdPolicyChecker",		"icon"=>"password-checker.png",		"class_type"=>"auxiliary","display_name"=>gettext("Password Policy Checker Module")),
			);

		// Auxiliary class display layouts

		$ldap_server->add_display_layout("pwdPolicy",array(
			array("section_name"=>gettext("Password Expiry Policy"),"new_row"=>true,
				"attributes"=>array(
					array("pwdMaxAge",			gettext("Maximum Password Age (s)"),						"time.png"),
					array("pwdExpireWarning",		gettext("Length of Warning Period Before Password Expiry (s)"),			"time.png"),
					array("pwdGraceAuthNLimit",		gettext("Number of Grace Logins Allowed After Expiry"),				"generic24.png")
					)
				),
			array("section_name"=>gettext("Password Changing Policy"),"new_row"=>true,
				"attributes"=>array(
					array("pwdMinAge",			gettext("Minimum Password Age (s)"),						"time.png"),
					array("pwdSafeModify",			gettext("Users Must Send Old and New Passwords When Changing"),			"generic24.png"),
					array("pwdMustChange",			gettext("Users Must Change Password at Next Logon After Reset"),		"generic24.png"),
					array("pwdAllowUserChange",		gettext("Allow Users to Change their Password"),				"generic24.png"),
					array("pwdCheckQuality",		gettext("Check Quality of New Passwords"),					"generic24.png"),
					array("pwdMinLength",			gettext("Minimum Accepted Password Length"),					"generic24.png"),
					array("pwdInHistory",			gettext("Number of Passwords to Store in History"),				"generic24.png")
					)
				),
			array("section_name"=>gettext("Consecutive Login Failures Policy"),"new_row"=>true,
				"attributes"=>array(
					array("pwdLockout",			gettext("Lock Accounts after Consecutive Login Failures"),			"generic24.png"),
					array("pwdMaxFailure",			gettext("Number of Consecutive Login Failures before Policy Applies"),		"generic24.png"),
					array("pwdLockoutDuration",		gettext("Account Lock Time after Consecutive Login Failures (s)"),		"time.png"),
					array("pwdFailureCountInterval",	gettext("Reset Time for Failed Logins Counter (s)"),				"time.png")
					)
				),
			array("section_name"=>gettext("Password Storage"),"new_row"=>true,
				"attributes"=>array(
					array("pwdAttribute",			gettext("Password Attribute"),							"generic24.png")
					)
				)
			));

		$ldap_server->add_display_layout("pwdPolicyChecker",array(
			array("section_name"=>gettext("Password Policy Enforcement"),
				"attributes"=>array(
					array("pwdCheckModule",			gettext("Password Check Module"),						"password-checker.png")
					)
				)
			));

		parent::__construct($ldap_server);
	}

	function populate_for_create_pwdPolicy(&$ldap_server,&$entry)
	{
		$this->add_attrib_value($ldap_server,$entry,"pwdAttribute","userPassword");
		$this->add_attrib_value($ldap_server,$entry,"pwdMustChange","FALSE");
	}
}
?>
