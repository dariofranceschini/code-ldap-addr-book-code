<?php
/** OpenLDAP AutoGroup Overlay On-Line Configuration (OLC) schema */

class openldap_autogroup_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"olcAGattrSet",			"data_type"=>"text_list",	"display_name"=>gettext("Attribute Set")),
			array("name"=>"olcAGmemberOfAd",		"data_type"=>"text",		"display_name"=>gettext("Member Of Attribute"))
			);

		// Structural object classes
		$this->object_schema = array(
			array("name"=>"olcAutomaticGroups",		"icon"=>"openldap/overlay.png",		"is_folder"=>false,"rdn_attrib"=>"olcOverlay","display_name"=>gettext("AutoGroup Overlay"),"parent_class"=>"olcOverlayConfig")
			);

		parent::__construct($ldap_server);
	}
}
?>
