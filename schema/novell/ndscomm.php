<?php
/** Novell Common Application schema (partial) */

class novell_ndscomm_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			// Definitions for homePhone, mobile and pager from novl_inet (Novell's
			// version of inetOrgPerson) are duplicated in this schema

			array("name"=>"company",			"data_type"=>"text",		"display_name"=>gettext("Company")),
			);

		parent::__construct($ldap_server);
	}
}
?>
