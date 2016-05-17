<?php
/** Novell Apache HTTP Server schema (partial) */

class novell_apache_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"apchadmn-ConfigurationInfo",	"data_type"=>"text_area",	"display_name"=>gettext("Apache HTTP Server Configuration Info")),
			);

		// Structural object classes

		$this->object_schema = array(
			array("name"=>"apchadmnConfigurationBlock",	"icon"=>"novell/apache-config-block.png",	"is_folder"=>true),
			array("name"=>"apchadmnModule",			"icon"=>"novell/apache-module.png",		"is_folder"=>true),
			array("name"=>"apchadmnVirtualHost",		"icon"=>"novell/apache-vhost.png",		"is_folder"=>true),
			array("name"=>"apchadmnServer",			"icon"=>"novell/apache-server.png",		"is_folder"=>true),
			);

		parent::__construct($ldap_server);
	}
}
?>
