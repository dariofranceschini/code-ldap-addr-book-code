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

		// Object classes
		$this->object_schema = array(
			array("name"=>"nestedGroupAux",			"icon"=>"generic24.png",	"class_type"=>"auxiliary")
			);

		// Display layouts
		$ldap_server->add_display_layout("nestedGroupAux",array(
			array("section_name"=>gettext("Nested Group Enforcement"),
				"attributes"=>array(
					array("nestedConfig",		gettext("Enforcement Level"),		"generic24.png")
					)
				),
			array("section_name"=>gettext("Groups Which Are Members (Static Only)"),"new_row"=>true,
				"attributes"=>array(
					array("groupMember")
					)
				),
			array("section_name"=>gettext("Groups Which This is a Member Of (Static Only)"),"new_row"=>true,
				"attributes"=>array(
					array("groupMembership")
					)
				),
			array("section_name"=>gettext("Objects Excluded from Nested Membership"),"new_row"=>true,
				"attributes"=>array(
					array("excludedMember")
					)
				)
			));

		parent::__construct($ldap_server);
	}
}
?>
