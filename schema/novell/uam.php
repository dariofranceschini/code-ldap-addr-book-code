<?php
/** Novell UNIX Account Management schema (partial) */

class novell_uam_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"memberUid",			"data_type"=>"text_list",	"display_name"=>gettext("Member User ID")),
			array("name"=>"uamPosixWorkstationContexts",	"data_type"=>"dn_list",		"display_name"=>gettext("POSIX Workstation Contexts")),
			array("name"=>"uamPosixWorkstationList",	"data_type"=>"dn_list",		"display_name"=>gettext("POSIX Workstation List"))
			);

		// Object classes
		$this->object_schema = array(
			array("name"=>"posixGroup",			"icon"=>"group24.png",			"class_type"=>"auxiliary","display_name"=>gettext("POSIX Group"),"required_attribs"=>"cn,gidNumber"),
			array("name"=>"uamPosixConfig",			"icon"=>"novell/unix-config.png",	"is_folder"=>false),
			array("name"=>"uamPosixWorkstation",		"icon"=>"novell/unix-workstation.png",	"is_folder"=>false)
			);

		// Display layouts
		$ldap_server->add_display_layout("uamPosixConfig",array(
			array("section_name"=>gettext("Workstation Contexts for Unix Account Management"),"colspan"=>2,
				"attributes"=>array(
					array("uamPosixWorkstationContexts"),
					)
				),
			));

		$ldap_server->add_display_layout("uamPosixWorkstation",array(
			array("section_name"=>gettext("Groups with access to LUM-enabled Services"),"new_row"=>true,
				"attributes"=>array(
					array("groupMembership")
					)
				)
			));

		// Auxiliary class layouts

		$ldap_server->add_display_layout("posixGroup",array(
			array("section_name"=>gettext("POSIX Group Information"),
				"attributes"=>array(
					array("cn",				gettext("Group Name"),			"group24.png"),
					array("description",			gettext("Description"),			"description.png"),
					array("gidNumber",			gettext("Group ID Number"),		"id.png")
					)
				),
			array("section_name"=>gettext("POSIX Group Members"),"new_row"=>true,
				"attributes"=>array(
					array("memberUid")
					)
				),
			));

		parent::__construct($ldap_server);
	}
}
?>
