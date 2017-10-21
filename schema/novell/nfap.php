<?php
/** Novell Native File Access Package (NFAP) schema (partial) */

class novell_nfap_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"nfapCIFSAsync",			"data_type"=>"yes_no",		"display_name"=>gettext("Asynchronous Reads Enabled")),
			array("name"=>"nfapCIFSAttach",			"data_type"=>"text",		"display_name"=>gettext("CIFS Server IP Address")),
			array("name"=>"nfapCIFSAuthent",		"data_type"=>"nfap_authent",	"display_name"=>gettext("Authentication Mode")),
			array("name"=>"nfapCIFSBeginRID",		"data_type"=>"text",		"display_name"=>gettext("Starting RID")),
			array("name"=>"nfapCIFSComment",		"data_type"=>"text",		"display_name"=>gettext("Comment")),
			array("name"=>"nfapCIFSDCGroup",		"data_type"=>"dn",		"display_name"=>gettext("DN of Domain Controller Group Object")),
			array("name"=>"nfapCIFSDCList",			"data_type"=>"dn_list",		"display_name"=>gettext("Domain Controller DN List")),
			array("name"=>"nfapCIFSDebug",			"data_type"=>"text",		"display_name"=>gettext("Debug Log Detail Level")),
			array("name"=>"nfapCIFSDFS",			"data_type"=>"yes_no",		"display_name"=>gettext("DFS Enabled")),
			array("name"=>"nfapCIFSDialect",		"data_type"=>"nfap_dialect",	"display_name"=>gettext("SMB/CIFS Dialect")),
			array("name"=>"nfapCIFSDomainDN",		"data_type"=>"dn",		"display_name"=>gettext("DN of Domain Object")),
			array("name"=>"nfapCIFSDomainEpoch",		"data_type"=>"text",		"display_name"=>gettext("Domain Epoch Number")),
			array("name"=>"nfapCIFSDomainSID",		"data_type"=>"download",	"display_name"=>gettext("Domain Security ID")),
			array("name"=>"nfapCIFSEndRID",			"data_type"=>"text",		"display_name"=>gettext("End RID")),
			array("name"=>"nfapCIFSLoginScripts",		"data_type"=>"yes_no",		"display_name"=>gettext("Login Scripts Enabled")),
			array("name"=>"nfapCIFSNDSUserContext",		"data_type"=>"text",		"display_name"=>gettext("Domain User Import Context")),
			array("name"=>"nfapCIFSNextRID",		"data_type"=>"text",		"display_name"=>gettext("Next Available RID")),
			array("name"=>"nfapCIFSOpLocks",		"data_type"=>"yes_no",		"display_name"=>gettext("OpLocks Support")),
			array("name"=>"nfapCIFSPDC",			"data_type"=>"dn",		"display_name"=>gettext("DN of Primary Domain Controller")),
			array("name"=>"nfapCIFSPDCAddr",		"data_type"=>"text",		"display_name"=>gettext("PDC IP Address")),
			array("name"=>"nfapCIFSPDCEnable",		"data_type"=>"yes_no",		"display_name"=>gettext("PDC Enabled")),
			array("name"=>"nfapCIFSPDCName",		"data_type"=>"text",		"display_name"=>gettext("PDC NetBIOS Name")),
			array("name"=>"nfapCIFSRID",			"data_type"=>"text",		"display_name"=>gettext("Relative ID")),
			array("name"=>"nfapCIFSServerName",		"data_type"=>"text",		"display_name"=>gettext("CIFS Server Name")),
			array("name"=>"nfapCIFSShares",			"data_type"=>"text",		"display_name"=>gettext("Shares")),
			array("name"=>"nfapCIFSShareVolsByDefault",	"data_type"=>"yes_no",		"display_name"=>gettext("Share Volumes By Default")),
			array("name"=>"nfapCIFSSignatures",		"data_type"=>"nfap_signing",	"display_name"=>gettext("CIFS Signing")),
			array("name"=>"nfapCIFSUnicode",		"data_type"=>"yes_no",		"display_name"=>gettext("Unicode Support")),
			array("name"=>"nfapCIFSUserContext",		"data_type"=>"text",		"display_name"=>gettext("User Lookup Context")),
			array("name"=>"nfapCIFSWalk",			"data_type"=>"yes_no",		"display_name"=>gettext("Walk Full Pathnames of Files")),
			array("name"=>"nfapCIFSWINSAddr",		"data_type"=>"text",		"display_name"=>gettext("WINS Server IP Address")),
			array("name"=>"nfapCIFSWorkGroup",		"data_type"=>"text",		"display_name"=>gettext("Domain/Workgroup Name")),
			array("name"=>"nfapLoginScript",		"data_type"=>"download",	"display_name"=>gettext("NFAP Login Script"))
			);

		// Object classes
		$this->object_schema = array(
			array("name"=>"nfapCIFSConfigInfo",		"icon"=>"generic24.png",		"class_type"=>"auxiliary","display_name"=>gettext("CIFS Server Configuration")),
			array("name"=>"nfapLoginProperties",		"icon"=>"generic24.png",		"class_type"=>"auxiliary","display_name"=>gettext("NFAP Login Settings"))
			);

		// Auxiliary class display layouts

		$ldap_server->add_display_layout("nfapCIFSConfigInfo",array(
			array("section_name"=>gettext("CIFS Native File Access Package"),
				"attributes"=>array(
					array("nfapCIFSServerName",		gettext("CIFS Server Name")),
					array("nfapCIFSAttach",			gettext("CIFS Server IP Address")),
					array("nfapCIFSWorkGroup",		gettext("Domain/Workgroup Name")),
					array("nfapCIFSComment",		gettext("Comment")),
					array("nfapCIFSShares",			gettext("Shares")),
					array("nfapCIFSShareVolsByDefault",	gettext("Share Volumes by Default")),
					array("nfapCIFSLoginScripts",		gettext("Login Scripts Enabled")),
					array("nfapCIFSDFS",			gettext("DFS Enabled")),
					array("nfapCIFSDialect",		gettext("SMB/CIFS Dialect")),
					array("nfapCIFSUnicode",		gettext("Unicode Support")),
					array("nfapCIFSOpLocks",		gettext("OpLocks Support")),
					array("nfapCIFSAsync",			gettext("Asynchronous Reads Enabled")),
					array("nfapCIFSWalk",			gettext("Walk Full Pathnames of Files")),
					array("nfapCIFSDebug",			gettext("Debug Log Detail Level")),
					array("nfapCIFSAuthent",		gettext("Authentication Mode")),
					array("nfapCIFSSignatures",		gettext("CIFS Signing")),
					array("nfapCIFSPDCEnable",		gettext("PDC Enabled")),
					array("nfapCIFSPDCName",		gettext("PDC NetBIOS Name")),
					array("nfapCIFSPDCAddr",		gettext("PDC IP Address")),
					array("nfapCIFSDomainDN",		gettext("DN of Domain Object")),
					array("nfapCIFSNDSUserContext",		gettext("Domain User Import Context")),
					array("nfapCIFSUserContext",		gettext("User Lookup Context")),
					array("nfapCIFSBeginRID",		gettext("Starting RID")),
					array("nfapCIFSEndRID",			gettext("End RID")),
					array("nfapCIFSWINSAddr",		gettext("WINS Server IP Address"))
					)
				)
			));

		$ldap_server->add_display_layout("nfapLoginProperties",array(
			array("section_name"=>gettext("NFAP Login Settings"),
				"attributes"=>array(
					array("nfapLoginScript",		gettext("NFAP Login Script")),
					array("nfapCIFSRID",			gettext("CIFS Relative ID"))
					)
				)
			));

		parent::__construct($ldap_server);
	}
}
?>
