<?php
/** microsoft.schema (partial) */

class microsoft_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"company",			"data_type"=>"text",		"display_name"=>gettext("Company")),
			array("name"=>"department",			"data_type"=>"text",		"display_name"=>gettext("Department")),
			array("name"=>"displayName",			"data_type"=>"text",		"display_name"=>gettext("Display/Preferred Name")),
			array("name"=>"groupType",			"data_type"=>"ad_group_type",	"display_name"=>gettext("Group Type/Scope")),
			array("name"=>"info",				"data_type"=>"text_area",	"display_name"=>gettext("General Information")),
			array("name"=>"memberOf",			"data_type"=>"dn_list",		"display_name"=>gettext("Member Of")),
			array("name"=>"printColor",			"data_type"=>"yes_no",		"display_name"=>gettext("Color Printing Supported")),
			array("name"=>"printDuplexSupported",		"data_type"=>"yes_no",		"display_name"=>gettext("Double-Sided Printing Supported")),
			array("name"=>"printMediaReady",		"data_type"=>"text_list",	"display_name"=>gettext("Paper Available")),
			array("name"=>"printMediaSupported",		"data_type"=>"text_list",	"display_name"=>gettext("Paper Supported")),
			array("name"=>"printStaplingSupported",		"data_type"=>"yes_no",		"display_name"=>gettext("Stapling Supported")),
			array("name"=>"sAMAccountName",			"data_type"=>"text",		"display_name"=>gettext("Pre-Windows 2000 Account Name")),
			array("name"=>"streetAddress",			"data_type"=>"text_area",	"display_name"=>gettext("Street Address")),
			array("name"=>"url",				"data_type"=>"text",		"display_name"=>gettext("URL (e.g. web page)")),
			array("name"=>"whenCreated",			"data_type"=>"date_time",	"display_name"=>gettext("Creation Date")),
			array("name"=>"whenChanged",			"data_type"=>"date_time",	"display_name"=>gettext("Last Modification Date")),
			array("name"=>"wWWHomePage",			"data_type"=>"text",		"display_name"=>gettext("WWW Home Page"))
			);

		// Structural object classes
		$this->object_schema = array(
			array("name"=>"rpcContainer",			"icon"=>"microsoft/rpc_services24.png",	"is_folder"=>true,"display_name"=>gettext("RPC Services")),
			array("name"=>"container",			"icon"=>"folder.png",	"is_folder"=>true,"display_name"=>gettext("Container"),"can_create"=>true),
			array("name"=>"builtinDomain",			"icon"=>"folder.png",	"is_folder"=>true),
			array("name"=>"lostAndFound",			"icon"=>"folder.png",	"is_folder"=>true),
			array("name"=>"msDS-QuotaContainer",		"icon"=>"folder.png",	"is_folder"=>true),
			array("name"=>"domainDNS",			"icon"=>"microsoft/domain24.png",	"is_folder"=>true,"display_name"=>gettext("Domain")),
			array("name"=>"group",				"icon"=>"group24.png",	"is_folder"=>false,"display_name"=>gettext("Group"),"can_create"=>true),
			array("name"=>"contact",			"icon"=>"contact24.png","is_folder"=>false,"display_name"=>gettext("Contact"),"can_create"=>true),
			array("name"=>"computer",			"icon"=>"microsoft/computer24.png","is_folder"=>false,"display_name"=>gettext("Computer"),"can_create"=>true),
			array("name"=>"foreignSecurityPrincipal",	"icon"=>"user-alias24.png","is_folder"=>false,"display_name"=>gettext("Foreign Security Principal")),
			array("name"=>"printQueue",			"icon"=>"microsoft/printer24.png","is_folder"=>false,"display_name"=>gettext("Printer")),
			array("name"=>"volume",				"icon"=>"microsoft/fileshare24.png","is_folder"=>false,"display_name"=>gettext("Shared Folder")),
			array("name"=>"user",				"icon"=>"user24.png",	"is_folder"=>false,"display_name"=>gettext("User"),"can_create"=>true),

			// Proprietary implementation of InetOrgPerson:
			//	- Subclass of proprietary "user" (so listed after it in schema definition)
			//	- Attribute "sn" is not mandatory
			array("name"=>"inetOrgPerson",			"icon"=>"user24.png",	"is_folder"=>false,"display_name"=>gettext("InetOrgPerson"),"can_create"=>true),

			// Specialist object classes used in Configuration Partition to be shown as folders
			array("name"=>"crossRefContainer",		"icon"=>"folder.png","is_folder"=>true),
			array("name"=>"sitesContainer",			"icon"=>"folder.png","is_folder"=>true),
			array("name"=>"interSiteTransport",		"icon"=>"folder.png","is_folder"=>true),
			array("name"=>"interSiteTransportContainer",	"icon"=>"folder.png","is_folder"=>true),
			array("name"=>"subnetContainer",		"icon"=>"folder.png","is_folder"=>true),
			array("name"=>"site",				"icon"=>"folder.png","is_folder"=>true),
			array("name"=>"serversContainer",		"icon"=>"folder.png","is_folder"=>true),
			array("name"=>"server",				"icon"=>"folder.png","is_folder"=>true),
			array("name"=>"nTDSDSA",			"icon"=>"folder.png","is_folder"=>true),
			array("name"=>"nTDSService",			"icon"=>"folder.png","is_folder"=>true),

			// Specialist object classes used in "System" container
			array("name"=>"domainPolicy",			"icon"=>"microsoft/domain_policy24.png","is_folder"=>true,"display_name"=>gettext("Domain Policy")),
			array("name"=>"nTFRSSettings",			"icon"=>"microsoft/frs_settings24.png",	"is_folder"=>true,"display_name"=>gettext("FRS Settings")),
			array("name"=>"fileLinkTracking",		"icon"=>"folder.png","is_folder"=>true),
			array("name"=>"dfsConfiguration",		"icon"=>"folder.png","is_folder"=>true),
			);

		// component schema (derived)
		$ldap_server->add_schema("microsoft/exchange");

		parent::__construct($ldap_server);

		// component schema (derived from)
		$ldap_server->add_schema("microsoft/std");
	}
}
?>
