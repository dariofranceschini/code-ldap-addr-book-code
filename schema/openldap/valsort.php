<?php
/** OpenLDAP Value Sorting Overlay On-Line Configuration (OLC) schema */

class openldap_valsort_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"olcValSortAttr",			"data_type"=>"text",		"display_name"=>gettext("Value Sorting Configuration"))		// TODO: should this be text_list?
			);

		// Structural object classes
		$this->object_schema = array(
			array("name"=>"olcValSortConfig",		"icon"=>"openldap/overlay.png",		"is_folder"=>false,"rdn_attrib"=>"olcOverlay","display_name"=>gettext("Value Sorting Overlay"),"can_create"=>true,"required_attribs"=>"olcValSortAttr","parent_class"=>"olcOverlayConfig")
			);

		parent::__construct($ldap_server);
	}
}
?>
