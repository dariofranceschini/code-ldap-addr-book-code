<?php
/** OpenLDAP Password Policies Overlay On-Line Configuration (OLC) schema

    The "ppolicy" overlay implements various operational attributes for
    maintaining policy state information. These are defined in the
    LDAP Address Book's "ppolicy" schema in order to allow for more
    general use.

    @see:
	https://tools.ietf.org/html/draft-behera-ldap-password-policy-10
*/

class openldap_ppolicy_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"olcPPolicyDefault",		"data_type"=>"dn",		"display_name"=>gettext("Default Policy Object DN")),
			array("name"=>"olcPPolicyForwardUpdates",	"data_type"=>"yes_no",		"display_name"=>gettext("Forward Proxy State Updates to Master")),
			array("name"=>"olcPPolicyHashCleartext",	"data_type"=>"yes_no",		"display_name"=>gettext("Hash Passwords on Add/Modify")),
			array("name"=>"olcPPolicyUseLockout",		"data_type"=>"yes_no",		"display_name"=>gettext("Inform Clients when Account Locked Out"))
			);

		// Structural object classes
		$this->object_schema = array(
			array("name"=>"olcPPolicyConfig",		"icon"=>"openldap/overlay.png",		"is_folder"=>false,"rdn_attrib"=>"olcOverlay","display_name"=>gettext("Password Policies Overlay"),"can_create"=>true,"parent_class"=>"olcOverlayConfig")
			);

		// Display layouts
		$ldap_server->add_display_layout("olcPPolicyConfig",array(
			array("section_name"=>gettext("Password Policies Overlay Settings"),"new_row"=>true,
				"attributes"=>array(
					array("olcOverlay",			gettext("Overlay Object Name"),				"openldap/overlay.png"),
					array("olcPPolicyDefault",		gettext("Default Policy Object"),			"generic24.png"),
					array("olcPPolicyHashCleartext",	gettext("Hash Passwords on Add/Modify"),		"generic24.png"),
					array("olcPPolicyForwardUpdates",	gettext("Forward Proxy State Updates to Master"),	"generic24.png"),
					array("olcPPolicyUseLockout",		gettext("Inform Clients when Account Locked Out"),	"generic24.png")
					)
				)
			));

		parent::__construct($ldap_server);
	}

	function populate_for_create_olcPPolicyConfig(&$ldap_server,&$entry)
	{
		$ldap_server->assign_ordered_sequence_rdn($entry,"olcOverlayConfig","ppolicy");
		$this->add_attrib_value($ldap_server,$entry,"olcPPolicyHashCleartext","FALSE");
		$this->add_attrib_value($ldap_server,$entry,"olcPPolicyForwardUpdates","FALSE");
		$this->add_attrib_value($ldap_server,$entry,"olcPPolicyUseLockout","FALSE");
	}

	function before_create_olcPPolicyConfig(&$ldap_server,&$entry)
	{
		$ldap_server->ensure_openldap_module_loaded("ppolicy");
	}
}
?>
