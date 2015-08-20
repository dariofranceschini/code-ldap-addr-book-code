<?php
/** cosine.schema (partial) */

class cosine_schema extends ldap_schema
{
	var $attribute_schema = array(
		array("name"=>"homePhone",			"data_type"=>"phone_number",	"display_name"=>"Home Telephone Number"),
		array("name"=>"info",				"data_type"=>"text_area",	"display_name"=>"General Information"),
		array("name"=>"mobile",				"data_type"=>"phone_number",	"display_name"=>"Mobile/Cell Telephone Number"),
		array("name"=>"pager",				"data_type"=>"text",		"display_name"=>"Pager Telephone Number"),
		);
}
?>
