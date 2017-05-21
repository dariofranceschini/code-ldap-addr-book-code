<?php
/** Novell Secure Shell Daemon (SSHD) schema (partial) */

class novell_sshd_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"sshadmn-ConfigurationInfo",	"data_type"=>"text_area",	"display_name"=>gettext("SSHD Configuration Info"))
			);

		// Object classes

		$this->object_schema = array(
			array("name"=>"sshadmnServer",		"icon"=>"novell/sshd-server.png",	"is_folder"=>true),
			);

		// Display layouts

		$ldap_server->add_display_layout("sshadmnServer",array(
			array("section_name"=>gettext("SSH Server"),
				"attributes"=>array(
					array("cn",				gettext("Object Name"),		"generic24.png"),
					array("sshadmn-ServerName",		gettext("Server Name"),		"generic24.png"),
					array("sshadmn-ServerStatus",		gettext("Server Status"),	"generic24.png"),
					array("sshadmn-ServerStartup",		gettext("Server Startup"),	"generic24.png")
					)
				),
			array("section_name"=>gettext("Configuration Settings"),"new_row"=>true,
				"attributes"=>array(
					array("sshadmn-ConfigurationInfo")
					)
				),
			array("section_name"=>gettext("Configuration Info"),"new_row"=>true,
				"attributes"=>array(
					array("sshadmn-ServerConfLastModified",	gettext("Last Modified"),	"generic24.png"),
					array("sshadmn-TypeName",		gettext("Type Name"),		"generic24.png")
					)
				)
			));

		parent::__construct($ldap_server);
	}
}

?>
