<?php
/** OpenLDAP Constraints Overlay On-Line Configuration (OLC) schema */

class openldap_constraint_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"olcConstraintAttribute",		"data_type"=>"text",		"display_name"=>gettext("Attribute Constraints"))
			);

		// Structural object classes
		$this->object_schema = array(
			array("name"=>"olcConstraintConfig",		"icon"=>"openldap/overlay.png",		"is_folder"=>false,"rdn_attrib"=>"olcOverlay","display_name"=>gettext("Constraints Overlay"),"parent_class"=>"olcOverlayConfig")
			);

		parent::__construct($ldap_server);
	}
}
?>
