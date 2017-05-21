<?php
/** Novell Apache HTTP Server schema (partial) */

class novell_apache_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"apchadmn-ConfigurationInfo",	"data_type"=>"text_area",	"display_name"=>gettext("Apache HTTP Server Configuration Info")),
			);

		// Object classes

		$this->object_schema = array(
			array("name"=>"apchadmnConfigurationBlock",	"icon"=>"novell/apache-config-block.png",	"is_folder"=>true),
			array("name"=>"apchadmnModule",			"icon"=>"novell/apache-module.png",		"is_folder"=>true),
			array("name"=>"apchadmnVirtualHost",		"icon"=>"novell/apache-vhost.png",		"is_folder"=>true),
			array("name"=>"apchadmnServer",			"icon"=>"novell/apache-server.png",		"is_folder"=>true),
			);

		// Display layouts

		$ldap_server->add_display_layout("apchadmnConfigurationBlock",array(
			array("section_name"=>gettext("Apache HTTP Server Configuration Block"),
				"attributes"=>array(
					array("cn",				gettext("Object Name"),			"generic24.png"),
					array("apchadmn-Scope",			gettext("Scope"),			"generic24.png"),
					array("apchadmn-BlockType",		gettext("Block Type"),			"generic24.png")
					)
				),
			array("section_name"=>gettext("Settings"),"new_row"=>true,
				"attributes"=>array(
					array("apchadmn-ConfigurationInfo")
					)
				),
			array("section_name"=>gettext("Configuration Info"),"new_row"=>true,
				"attributes"=>array(
					array("apchadmn-TypeName",		gettext("Type Name"),			"generic24.png")
					)
				)
			));

		$ldap_server->add_display_layout("apchadmnModule",array(
			array("section_name"=>gettext("Apache HTTP Server Module"),
				"attributes"=>array(
					array("cn",				gettext("Object Name"),			"generic24.png"),
					array("apchadmn-ModuleDisable",		gettext("Disabled"),			"generic24.png"),
					array("apchadmn-ModuleFileName",	gettext("File Name"),			"generic24.png"),
					array("apchadmn-ModuleObjectFile",	gettext("Object File"),			"generic24.png"),
					array("apchadmn-ModuleSymbolName",	gettext("Symbol Name"),			"generic24.png")
					)
				),
			array("section_name"=>gettext("Settings"),"new_row"=>true,
				"attributes"=>array(
					array("apchadmn-ConfigurationInfo")
					)
				),
			array("section_name"=>gettext("Configuration Info"),"new_row"=>true,
				"attributes"=>array(
					array("apchadmn-TypeName",		gettext("Type Name"),			"generic24.png")
					)
				)
			));

		$ldap_server->add_display_layout("apchadmnServer",array(
			array("section_name"=>gettext("Apache HTTP Server Configuration"),
				"attributes"=>array(
					array("apchadmn-ConfigurationInfo")
					)
				),
			array("section_name"=>gettext("Configuration Info"),"new_row"=>true,
				"attributes"=>array(
					array("apchadmn-TypeName",		gettext("Type Name"),			"generic24.png")
					)
				)
			));

		parent::__construct($ldap_server);
	}
}
?>
