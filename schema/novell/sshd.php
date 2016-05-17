<?php
/** Novell Secure Shell Daemon (SSHD) schema (partial) */

class novell_sshd_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"sshadmn-ConfigurationInfo",	"data_type"=>"text_area",	"display_name"=>gettext("SSHD Configuration Info"))
			);

		// Structural object classes

		$this->object_schema = array(
			array("name"=>"sshadmnServer",		"icon"=>"novell/sshd-server.png",	"is_folder"=>true),
			);

		parent::__construct($ldap_server);
	}
}
?>
