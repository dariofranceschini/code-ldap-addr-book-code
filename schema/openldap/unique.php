<?php
/** OpenLDAP Attribute Uniqueness Overlay On-Line Configuration (OLC) schema */

class openldap_unique_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"olcUniqueURI",			"data_type"=>"text_list",	"display_name"=>gettext("Attribute Uniqueness Configuration")),

			// Attributes to hold legacy configuration settings
			array("name"=>"olcUniqueAttribute",		"data_type"=>"text",		"display_name"=>gettext("Attributes that Must be Unique")),
			array("name"=>"olcUniqueBase",			"data_type"=>"dn",		"display_name"=>gettext("Base DN")),
			array("name"=>"olcUniqueIgnore",		"data_type"=>"text",		"display_name"=>gettext("Ignored Attributes")),
			array("name"=>"olcUniqueStrict",		"data_type"=>"yes_no",		"display_name"=>gettext("Enforce Uniqueness for Null Values"))
			);

		// Structural object classes
		$this->object_schema = array(
			array("name"=>"olcUniqueConfig",			"icon"=>"openldap/overlay.png",		"is_folder"=>false,"rdn_attrib"=>"olcOverlay","display_name"=>gettext("Uniqueness Overlay"),"parent_class"=>"olcOverlayConfig")
			);

		parent::__construct($ldap_server);
	}
}
?>
