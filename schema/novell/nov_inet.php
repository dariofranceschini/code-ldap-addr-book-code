<?php
/** Novell InetOrgPerson schema (partial) */

class novell_nov_inet_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			// Equivalents to cosine.schema classes
			// homePhone, mobile and pager are also duplicated in the ndscomm schema
			array("name"=>"homePhone",			"data_type"=>"phone_number",	"display_name"=>gettext("Home Telephone Number")),
			array("name"=>"mobile",				"data_type"=>"phone_number",	"display_name"=>gettext("Mobile/Cell Telephone Number")),
			array("name"=>"pager",				"data_type"=>"text",		"display_name"=>gettext("Pager Telephone Number")),

			// matches inetorgperson.schema
			array("name"=>"displayName",			"data_type"=>"text",		"display_name"=>gettext("Display/Preferred Name")),
			array("name"=>"jpegPhoto",			"data_type"=>"image",		"display_name"=>gettext("Photograph")),
			);

		parent::__construct($ldap_server);
	}
}
?>
