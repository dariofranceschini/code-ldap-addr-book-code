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

		parent::__construct($ldap_server);
	}
}
?>
