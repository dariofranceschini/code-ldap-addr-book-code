<?php
/** Novell Native File Access Package (NFAP) schema (partial) */

class novell_nfap_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"nfapCIFSShareVolsByDefault",	"data_type"=>"yes_no",		"display_name"=>gettext("Share Volumes By Default")),
			array("name"=>"nfapCIFSDFS",			"data_type"=>"yes_no",		"display_name"=>gettext("DFS")),
			array("name"=>"nfapCIFSUnicode",		"data_type"=>"yes_no",		"display_name"=>gettext("Unicode Support")),
			array("name"=>"nfapCIFSPDCEnable",		"data_type"=>"yes_no",		"display_name"=>gettext("PDC Enable")),
			array("name"=>"nfapCIFSOpLocks",		"data_type"=>"yes_no",		"display_name"=>gettext("OpLocks Support"))
			);

		parent::__construct($ldap_server);
	}
}
?>
