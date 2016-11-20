<?php
/** OpenLDAP Dynamic Lists Overlay On-Line Configuration (OLC) schema */

class openldap_dynlist_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"olcDlAttrSet",			"data_type"=>"text_list",	"display_name"=>gettext("Member/Member URL Attribute Pair"))
			);

		// Structural object classes
		$this->object_schema = array(
			array("name"=>"olcDynamicList",			"icon"=>"openldap/overlay.png",		"is_folder"=>false,"rdn_attrib"=>"olcOverlay","display_name"=>gettext("Dynamic Lists Overlay"),"parent_class"=>"olcOverlayConfig")
			);

		parent::__construct($ldap_server);
	}
}
?>
