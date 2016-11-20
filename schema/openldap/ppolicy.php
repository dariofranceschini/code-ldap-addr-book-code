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
			array("name"=>"olcPPolicyConfig",		"icon"=>"openldap/overlay.png",		"is_folder"=>false,"rdn_attrib"=>"olcOverlay","display_name"=>gettext("Password Policies Overlay"),"parent_class"=>"olcOverlayConfig")
			);

		parent::__construct($ldap_server);
	}
}
?>
