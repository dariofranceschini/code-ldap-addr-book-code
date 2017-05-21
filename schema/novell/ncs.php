<?php
/** Novell Cluster Services schema (partial) */

class novell_ncs_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		// Object classes
		$this->object_schema = array(
			array("name"=>"nCSNetWareCluster",	"icon"=>"novell/cluster.png",			"is_folder"=>true,"display_name"=>gettext("NCS:NetWare Cluster")),
			array("name"=>"nCSClusterResource",	"icon"=>"novell/cluster-resource.png",		"is_folder"=>false,"display_name"=>gettext("NCS:Cluster Resource")),
			array("name"=>"nCSResourceTemplate",	"icon"=>"novell/cluster-resource-template.png",	"is_folder"=>false,"display_name"=>gettext("NCS:Resource Template")),
			array("name"=>"nCSVolumeResource",	"icon"=>"novell/cluster-volume.png",		"is_folder"=>false,"display_name"=>gettext("NCS:Volume Resource")),
			array("name"=>"nCSNCPServer",		"icon"=>"novell/cluster-server.png",		"is_folder"=>false,"display_name"=>gettext("NCS:NCP Server"))
			);

		parent::__construct($ldap_server);
	}
}
?>
