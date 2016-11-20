<?php
/** OpenLDAP Rewrite/Remap Overlay On-Line Configuration (OLC) schema */

class openldap_rwm_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"olcRwmDropUnrequested",		"data_type"=>"yes_no",		"display_name"=>gettext("Drop Attributes Not Explicitly Requested")),
			array("name"=>"olcRwmMap",			"data_type"=>"text_list",	"display_name"=>gettext("Attribute/Object Class Mapping Rules")),
			array("name"=>"olcRwmNormalizeMapped",		"data_type"=>"yes_no",		"display_name"=>gettext("Normalise Mapped Attribute/Object Classes")),
			array("name"=>"olcRwmRewrite",			"data_type"=>"text_list",	"display_name"=>gettext("String Rewriting Rule")),
			array("name"=>"olcRwmTFSupport",		"data_type"=>"text",		"display_name"=>gettext("True/False Filter Support"))		// rfc4526
			);

		// Structural object classes
		$this->object_schema = array(
			array("name"=>"olcRwmConfig",			"icon"=>"openldap/overlay.png",		"is_folder"=>false,"rdn_attrib"=>"olcOverlay","display_name"=>gettext("Rewrite/Remap Overlay"),"parent_class"=>"olcOverlayConfig")
			);

		parent::__construct($ldap_server);
	}
}
?>
