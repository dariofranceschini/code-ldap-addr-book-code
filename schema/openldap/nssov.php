<?php
/** OpenLDAP Configuration (OLC) Schema for NSS/PAM Lookup Overlay */

class openldap_nssov_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"olcNssMap",			"data_type"=>"text_list",	"display_name"=>gettext("Service Attribute Mappings")),
			array("name"=>"olcNssPam",			"data_type"=>"text_list",	"display_name"=>gettext("PAM Authentication and Authorization Options")),
			array("name"=>"olcNssPamDefHost",		"data_type"=>"text",		"display_name"=>gettext("Default Host Name for Service Checks")),
			array("name"=>"olcNssPamGroupAD",		"data_type"=>"text",		"display_name"=>gettext("NSS/PAM Group Member Attribute")),
			array("name"=>"olcNssPamGroupDN",		"data_type"=>"dn",		"display_name"=>gettext("NSS/PAM Group DN")),
			array("name"=>"olcNssPamMaxUid",		"data_type"=>"text",		"display_name"=>gettext("Minimum UID Value Allowed to Log In")),
			array("name"=>"olcNssPamMinUid",		"data_type"=>"text",		"display_name"=>gettext("Maximum UID Value Allowed to Log In")),
			array("name"=>"olcNssPamPwdProhibitMsg",	"data_type"=>"text",		"display_name"=>gettext("Error Message when Password Change Service is Disabled")),
			array("name"=>"olcNssPamSession",		"data_type"=>"text_list",	"display_name"=>gettext("NSS/PAM Services with Session Logging")),
			array("name"=>"olcNssPamTemplate",		"data_type"=>"text",		"display_name"=>gettext("Default Login Name")),
			array("name"=>"olcNssPamTemplateAD",		"data_type"=>"text",		"display_name"=>gettext("Template Login Name Attribute")),
			array("name"=>"olcNssSsd",			"data_type"=>"text_list",	"display_name"=>gettext("Service Search Descriptor")),

			array("name"=>"olcPamPwdmgrDn",			"data_type"=>"dn",		"display_name"=>gettext("Password Manager DN")),
			array("name"=>"olcPamPwdmgrPwd",		"data_type"=>"text",		"display_name"=>gettext("Password Manager PWD"))
			);

		// Object classes
		$this->object_schema = array(
			array("name"=>"olcNssOvConfig",			"icon"=>"openldap/overlay.png",		"is_folder"=>false,"rdn_attrib"=>"olcOverlay","display_name"=>gettext("NSS/PAM Lookup Overlay"),"parent_class"=>"olcOverlayConfig")
			);

		// Display layouts
		$ldap_server->add_display_layout("olcNssOvConfig",array(
			array("section_name"=>gettext("NSS/PAM Lookup Overlay Settings"),"new_row"=>true,
				"attributes"=>array(
					array("olcOverlay",			gettext("Overlay Object Name"),					"openldap/overlay.png"),
					array("olcNssPamDefHost",		gettext("Default Host Name for Service Checks"),		"server.png"),
					array("olcNssPamGroupDN",		gettext("Group of Users Allowed to Authenticate"),		"group24.png"),
					array("olcNssPamGroupAD",		gettext("Group Member Attribute"),				"user24.png"),
					array("olcNssPamMinUid",		gettext("Minimum UID Value Allowed to Log In"),			"generic24.png"),
					array("olcNssPamMaxUid",		gettext("Maximum UID Value Allowed to Log In"),			"generic24.png"),
					array("olcNssPamTemplateAD",		gettext("Template Login Name Attribute"),			"generic24.png"),
					array("olcNssPamTemplate",		gettext("Login Name to Use if Template Attribute is Empty"),	"generic24.png"),

					// TODO: add password mananager/password policy attributes?

					)
				),
			array("section_name"=>gettext("Service Search Descriptors"),"new_row"=>true,
				"attributes"=>array(
					array("olcNssSsd")
					)
				),
			array("section_name"=>gettext("Service Attribute Mappings"),"new_row"=>true,
				"attributes"=>array(
					array("olcNssMap")
					)
				),
			array("section_name"=>gettext("Services with Session Logging"),"new_row"=>true,
				"attributes"=>array(
					array("olcNssPamSession")
					)
				),
			array("section_name"=>gettext("PAM Authentication and Authorization Options"),"new_row"=>true,
				"attributes"=>array(
					array("olcNssPam")
					)
				)
			));

		$ldap_server->add_schema("openldap/ldapns");

		parent::__construct($ldap_server);
	}

	function populate_for_create_olcNssOvConfig(&$ldap_server,&$entry)
	{
		$ldap_server->assign_ordered_sequence_rdn($entry,"olcOverlayConfig","nssov");

		$this->add_attrib_value($ldap_server,$entry,"olcNssPamGroupAD","member");
		$this->add_attrib_value($ldap_server,$entry,"olcNssPamMinUid","0");
		$this->add_attrib_value($ldap_server,$entry,"olcNssPamMaxUid","0");
	}

	function before_create_olcNssOvConfig(&$ldap_server,&$entry)
	{
		$ldap_server->ensure_openldap_module_loaded("nssov");
	}
}
?>
