<?php
/** Novell UNIX Account Management schema (partial) */

class novell_uam_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"uamPosixWorkstationContexts",	"data_type"=>"dn_list",	"display_name"=>gettext("UAM POSIX Workstation Contexts")),
			array("name"=>"uamPosixUidNumberReuse",		"data_type"=>"yes_no",	"display_name"=>gettext("Reuse UID Numbers")),
			array("name"=>"uamPosixGidNumberReuse",		"data_type"=>"yes_no",	"display_name"=>gettext("Reuse GID Numbers")),
			);

		// Structural object classes
		$this->object_schema = array(
			array("name"=>"uamPosixWorkstation",		"icon"=>"novell/unix-workstation.png",	"is_folder"=>false),
			array("name"=>"uamPosixConfig",			"icon"=>"novell/unix-config.png",	"is_folder"=>false),
			);

		// Display layouts
		$ldap_server->add_display_layout("uamPosixConfig",array(
			array("section_name"=>gettext("Workstation Contexts for Unix Account Management"),"colspan"=>2,
				"attributes"=>array(
					array("uamPosixWorkstationContexts"),
					)
				),
			array("section_name"=>gettext("User ID Mapping"),"new_row"=>true,
				"attributes"=>array(
					array("uamPosixUidNumberStart",		gettext("Starting UID"),			"generic24.png"),
					array("uamPosixUidNumberEnd",		gettext("End UID"),				"generic24.png"),
					array("uamPosixUidNumberLastAssigned",	gettext("Last Assigned UID"),			"generic24.png"),
					array("uamPosixUidNumberReuse",		gettext("Reuse UID Numbers"),			"generic24.png"),
			//		array("uamPosixUidNumberDeletedMap",	gettext("Deleted UID Map"),			"generic24.png"),
					)
				),
			array("section_name"=>gettext("Group ID Mapping"),"width"=>"50%",
				"attributes"=>array(
					array("uamPosixGidNumberStart",		gettext("Starting GID"),			"generic24.png"),
					array("uamPosixGidNumberEnd",		gettext("End GID"),				"generic24.png"),
					array("uamPosixGidNumberLastAssigned",	gettext("Last Assigned GID"),			"generic24.png"),
					array("uamPosixGidNumberReuse",		gettext("Reuse GID Numbers"),			"generic24.png"),
			//		array("uamPosixGidNumberDeletedMap",	gettext("Deleted GID Map"),			"generic24.png"),
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

		parent::__construct($ldap_server);
	}
}
?>
