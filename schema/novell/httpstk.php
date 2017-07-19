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

		// Object classes
		$this->object_schema = array(
			array("name"=>"httpServer",			"icon"=>"novell/http-server.png",	"is_folder"=>false),
			);

		// Display layouts
		$ldap_server->add_display_layout("httpServer",array(
			array("section_name"=>gettext("HTTP Server (iMonitor)"),"new_row"=>true,
				"attributes"=>array(
					array("httpHostServerDN",		gettext("Host Server"),				"generic24.png"),
					array("httpDefaultClearPort",		gettext("Default Port - HTTP"),			"generic24.png"),
					array("httpDefaultTLSPort",		gettext("Default Port - HTTPS"),		"generic24.png"),
					array("httpAuthRequiresTLS",		gettext("TLS Required for Authentication"),	"generic24.png"),
					array("httpKeyMaterialObject",		gettext("Certificate"),				"generic24.png")
					)
				),
			array("section_name"=>gettext("Server Configuration"),"new_row"=>true,
				"attributes"=>array(
					array("httpBindRestrictions",		gettext("Bind Restrictions"),			"generic24.png"),
					array("httpTraceLevel",			gettext("Trace Level"),				"generic24.png"),
					array("httpSessionTimeout",		gettext("Session Timeout (s)"),			"time.png"),
					array("httpKeepAliveRequestTimeout",	gettext("Keep-Alive Request Timeout (s)"),	"time.png"),
					array("httpRequestTimeout",		gettext("Request Timeout (s)"),			"time.png"),
					array("httpIOBufferSize",		gettext("I/O Buffer Size"),			"generic24.png"),
					array("httpThreadsPerCPU",		gettext("Threads per CPU"),			"generic24.png")
					)
				)
			));

		parent::__construct($ldap_server);
	}
}
?>
