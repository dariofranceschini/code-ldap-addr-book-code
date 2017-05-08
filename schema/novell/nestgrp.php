<?php
/** Nested Groups schema (partial) */

class novell_nestgrp_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		// Attributes
		$this->attribute_schema = array(
			array("name"=>"groupMember",			"data_type"=>"dn_list",		"display_name"=>gettext("Group Membership (Nested Groups)")),
			array("name"=>"nestedConfig",			"data_type"=>"nested_config",	"display_name"=>gettext("Nested Group Enforcement"))
			);

		parent::__construct($ldap_server);
	}
}
?>
