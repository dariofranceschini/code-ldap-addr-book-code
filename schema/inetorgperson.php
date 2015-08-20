<?php
/** inetorgperson.schema (partial) */

class inetorgperson_schema extends ldap_schema
{
	var $attribute_schema = array(
		array("name"=>"displayName",			"data_type"=>"text",		"display_name"=>"Display/Preferred Name"),
		array("name"=>"jpegPhoto",			"data_type"=>"image",		"display_name"=>"Photograph")
		);

	// Structural object classes
	var $object_schema = array(
		array("name"=>"inetOrgPerson",			"icon"=>"user24.png",		"is_folder"=>false,"required_attribs"=>"sn","can_create"=>true)
		);
}
?>
