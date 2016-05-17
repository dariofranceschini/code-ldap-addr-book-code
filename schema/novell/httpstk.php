<?php
/** Novell HTTPstk Server (iMonitor) schema (partial) */

class novell_httpstk_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"httpHostServerDN",		"data_type"=>"dn_list",		"display_name"=>gettext("HTTP Server Host")),
			array("name"=>"httpKeyMaterialObject",		"data_type"=>"dn_list",		"display_name"=>gettext("HTTP Server TLS Certificate")),
			array("name"=>"httpServerDN",			"data_type"=>"dn_list",		"display_name"=>gettext("HTTP Server (iMonitor) DN"))
			);

		// Structural object classes
		$this->object_schema = array(
			array("name"=>"httpServer",			"icon"=>"novell/http-server.png",	"is_folder"=>false),
			);

		parent::__construct($ldap_server);
	}
}
?>
