<?php
/** Novell Native File Access Package (NFAP) schema (partial) */

class novell_nfap_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"nfapCIFSAuthent",		"data_type"=>"nfap_authent",	"display_name"=>gettext("Authentication Mode")),
			array("name"=>"nfapCIFSDFS",			"data_type"=>"yes_no",		"display_name"=>gettext("DFS Enabled")),
			array("name"=>"nfapCIFSDialect",		"data_type"=>"nfap_dialect",	"display_name"=>gettext("SMB/CIFS Dialect")),
			array("name"=>"nfapCIFSOpLocks",		"data_type"=>"yes_no",		"display_name"=>gettext("OpLocks Support")),
			array("name"=>"nfapCIFSPDCEnable",		"data_type"=>"yes_no",		"display_name"=>gettext("PDC Enabled")),
			array("name"=>"nfapCIFSShareVolsByDefault",	"data_type"=>"yes_no",		"display_name"=>gettext("Share Volumes By Default")),
			array("name"=>"nfapCIFSUnicode",		"data_type"=>"yes_no",		"display_name"=>gettext("Unicode Support"))
			);

		// Object classes
		$this->object_schema = array(
			array("name"=>"nfapCIFSConfigInfo",		"icon"=>"generic24.png",		"class_type"=>"auxiliary","display_name"=>gettext("CIFS Server Configuration"))
			);

		// Auxiliary class display layouts

		$ldap_server->add_display_layout("nfapCIFSConfigInfo",array(
			array("section_name"=>gettext("CIFS Native File Access Package"),
				"attributes"=>array(
					array("nfapCIFSServerName",		gettext("CIFS Server Name")),
					array("nfapCIFSWorkGroup",		gettext("Domain/Workgroup Name")),
					array("nfapCIFSComment",		gettext("Comment")),
					array("nfapCIFSShares",			gettext("Shares")),
					array("nfapCIFSShareVolsByDefault",	gettext("Share Volumes by Default")),
					array("nfapCIFSDFS",			gettext("DFS Enabled")),
					array("nfapCIFSDialect",		gettext("SMB/CIFS Dialect")),
					array("nfapCIFSUnicode",		gettext("Unicode Support")),
					array("nfapCIFSOpLocks",		gettext("OpLocks Support")),
					array("nfapCIFSAuthent",		gettext("Authentication Mode")),
					array("nfapCIFSSignatures",		gettext("Signatures")),
					array("nfapCIFSPDCEnable",		gettext("PDC Enabled")),
					array("nfapCIFSPDCName",		gettext("PDC Name")),
					array("nfapCIFSPDCAddr",		gettext("PDC Address")),
					array("nfapCIFSUserContext",		gettext("User Context")),
					array("nfapCIFSWINSAddr",		gettext("WINS Address")),
					)
				)
			));

		parent::__construct($ldap_server);
	}
}
?>
