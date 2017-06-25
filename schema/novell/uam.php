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
			array("name"=>"posixAccount",			"icon"=>"posix-account.png",		"class_type"=>"auxiliary","display_name"=>gettext("POSIX Account"),"required_attribs"=>"cn,uniqueID,uidNumber,gidNumber,homeDirectory"),
			array("name"=>"posixGroup",			"icon"=>"group24.png",			"class_type"=>"auxiliary","display_name"=>gettext("POSIX Group"),"required_attribs"=>"cn,gidNumber"),
			array("name"=>"shadowAccount",			"icon"=>"shadow-account.png",		"class_type"=>"auxiliary","required_attribs"=>"uniqueID"),
			array("name"=>"uamPosixConfig",			"icon"=>"novell/unix-config.png",	"is_folder"=>false),
			array("name"=>"uamPosixGroup",			"icon"=>"password.png",			"class_type"=>"auxiliary","display_name"=>gettext("UAM Workstation List")),
			array("name"=>"uamPosixUser",			"icon"=>"password.png",			"class_type"=>"auxiliary","display_name"=>gettext("UAM Password Salt")),
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

		$ldap_server->add_display_layout("posixAccount",array(
			array("section_name"=>gettext("POSIX Account Settings"),
				"attributes"=>array(
					array("cn",				gettext("Object Name"),			"user24.png"),
					array("uniqueID",			gettext("Unique ID"),			"generic24.png"),
					array("uidNumber",			gettext("UID Number"),			"id.png"),
					array("gidNumber",			gettext("GID Number"),			"id.png"),
					array("homeDirectory",			gettext("Home Directory"),		"folder.png"),
					array("loginShell",			gettext("Login Shell"),			"shell24.png"),
					array("gecos",				gettext("GECOS Field"),			"description.png"),
					array("description",			gettext("Description"),			"generic24.png"),
					)
				)
			));

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

		$ldap_server->add_display_layout("shadowAccount",array(
			array("section_name"=>gettext("Shadow Password Settings"),
				"attributes"=>array(
					array("uniqueID",			gettext("Unique ID"),			"id.png"),
					array("shadowLastChange",		gettext("Date of Last Password Change"),"generic24.png"),
					array("shadowMin",			gettext("Minimum Password Age"),	"generic24.png"),
					array("shadowMax",			gettext("Maximum Password Age"),	"generic24.png"),
					array("shadowWarning",			gettext("Warning Period Before Expiration"),"generic24.png"),
					array("shadowInactive",			gettext("Time Until Account Inactive"),	"generic24.png"),
					array("shadowExpire",			gettext("Expiry Date"),			"generic24.png"),
					// array("shadowFlag",			????,					"generic24.png"),	// attribute reserved for future use
					array("description",			gettext("Description"),			"generic24.png")
					)
				)
			));

		$ldap_server->add_display_layout("uamPosixGroup",array(
			array("section_name"=>gettext("POSIX Workstation List"),
				"attributes"=>array(
					array("uamPosixWorkstationList")
					)
				)
			));

		$ldap_server->add_display_layout("uamPosixUser",array(
			array("section_name"=>gettext("Password Salt"),
				"attributes"=>array(
					array("uamPosixSalt")
					)
				)
			));

		parent::__construct($ldap_server);
	}
}
?>
