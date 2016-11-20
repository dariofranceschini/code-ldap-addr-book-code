<?php
/** OpenLDAP Dynamic Groups Overlay On-Line Configuration (OLC) schema */

class openldap_dyngroup_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"olcDGAttrPair",			"data_type"=>"text",		"display_name"=>gettext("Member/Member URL Attribute Pair"))
			);

		// Structural object classes
		$this->object_schema = array(
			array("name"=>"olcDGConfig",			"icon"=>"openldap/overlay.png",		"is_folder"=>false,"rdn_attrib"=>"olcOverlay","display_name"=>gettext("Dynamic Groups Overlay"),"parent_class"=>"olcOverlayConfig")
			);

		parent::__construct($ldap_server);
	}
}
?>
