<?php
/** OpenLDAP Referential Integrity Overlay On-Line Configuration (OLC) schema */

class openldap_refint_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"olcRefintAttribute",		"data_type"=>"text",		"display_name"=>gettext("Attributes with Referential Integrity")),
			array("name"=>"olcRefintNothing",		"data_type"=>"dn",		"display_name"=>gettext("Placeholder Value for Empty Mandatory Attributes")),
			array("name"=>"olcRefintModifiersName",		"data_type"=>"dn",		"display_name"=>gettext("Modifier's DN"))
			);

		// Structural object classes
		$this->object_schema = array(
			array("name"=>"olcRefintConfig",			"icon"=>"openldap/overlay.png",		"is_folder"=>false,"rdn_attrib"=>"olcOverlay","display_name"=>gettext("Referential Integrity Overlay"),"parent_class"=>"olcOverlayConfig")
			);

		parent::__construct($ldap_server);
	}
}
?>
