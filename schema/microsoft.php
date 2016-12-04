<?php
/** microsoft.schema (partial) */

class microsoft_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"adminContextMenu",		"data_type"=>"text_list",	"display_name"=>gettext("Admin Context Menu Entries")),
			array("name"=>"adminPropertyPages",		"data_type"=>"text_list",	"display_name"=>gettext("Admin Property Pages")),
			array("name"=>"attributeDisplayNames",		"data_type"=>"text_list",	"display_name"=>gettext("Attribute Display Names")),
			array("name"=>"company",			"data_type"=>"text",		"display_name"=>gettext("Company")),
			array("name"=>"contextMenu",			"data_type"=>"text_list",	"display_name"=>gettext("Context Menu Entries")),
			array("name"=>"createWizardExt",		"data_type"=>"text_list",	"display_name"=>gettext("Object Creation Wizard Extensions")),
			array("name"=>"department",			"data_type"=>"text",		"display_name"=>gettext("Department")),
			array("name"=>"displayName",			"data_type"=>"text",		"display_name"=>gettext("Display/Preferred Name")),
			array("name"=>"groupType",			"data_type"=>"ad_group_type",	"display_name"=>gettext("Group Type/Scope")),
			array("name"=>"info",				"data_type"=>"text_area",	"display_name"=>gettext("General Information")),
			array("name"=>"managedBy",			"data_type"=>"dn",		"display_name"=>gettext("Managed By")),
			array("name"=>"memberOf",			"data_type"=>"dn_list",		"display_name"=>gettext("Member Of")),
			array("name"=>"printColor",			"data_type"=>"yes_no",		"display_name"=>gettext("Color Printing Supported")),
			array("name"=>"printDuplexSupported",		"data_type"=>"yes_no",		"display_name"=>gettext("Double-Sided Printing Supported")),
			array("name"=>"printMediaReady",		"data_type"=>"text_list",	"display_name"=>gettext("Paper Available")),
			array("name"=>"printMediaSupported",		"data_type"=>"text_list",	"display_name"=>gettext("Paper Supported")),
			array("name"=>"printStaplingSupported",		"data_type"=>"yes_no",		"display_name"=>gettext("Stapling Supported")),
			array("name"=>"sAMAccountName",			"data_type"=>"text",		"display_name"=>gettext("Pre-Windows 2000 Account Name")),

			// Data type of "serverName" is changed to "dn" when used with the Active Directory
			// Root DSE object (hardcoded workaround). The data type "text" (as defined below) is
			// used for the "serverName" attribute of "printQueue" objects.
			array("name"=>"serverName",			"data_type"=>"text",		"display_name"=>gettext("Server Name")),

			array("name"=>"servicePrincipalName",		"data_type"=>"text_list",	"display_name"=>gettext("Service Principal Names")),
			array("name"=>"shellPropertyPages",		"data_type"=>"text_list",	"display_name"=>gettext("Shell Property Pages")),
			// Non-standard implementation of streetAddress as a separate attribute, rather than an alias of street:
			array("name"=>"streetAddress",			"data_type"=>"text_area",	"display_name"=>gettext("Street Address")),
			array("name"=>"treatAsLeaf",			"data_type"=>"yes_no",		"display_name"=>gettext("Treat as Leaf Object")),
			array("name"=>"url",				"data_type"=>"text",		"display_name"=>gettext("URL (e.g. web page)")),
			array("name"=>"whenCreated",			"data_type"=>"date_time",	"display_name"=>gettext("Creation Date")),
			array("name"=>"whenChanged",			"data_type"=>"date_time",	"display_name"=>gettext("Last Modification Date")),
			array("name"=>"wWWHomePage",			"data_type"=>"text",		"display_name"=>gettext("WWW Home Page")),

			// Added for Windows Server 2003
			array("name"=>"adminMultiSelectPropertyPages",	"data_type"=>"text_list",	"display_name"=>gettext("Admin Multi Select Property Pages")),
			array("name"=>"extraColumns",			"data_type"=>"text_list",	"display_name"=>gettext("Extra Columns")),
			array("name"=>"msDS-AllowedToDelegateTo",	"data_type"=>"text_list",	"display_name"=>gettext("Allowed to Delegate To"))
			);

		// Structural object classes
		$this->object_schema = array(
			// Added for Windows Server 2012
			array("name"=>"msDS-GroupManagedServiceAccount","icon"=>"microsoft/service.png",	"is_folder"=>false,"display_name"=>gettext("Group Managed Service Account")),

			// Added for Windows Server 2008 R2
			array("name"=>"msDS-ManagedServiceAccount",	"icon"=>"microsoft/service.png",	"is_folder"=>false,"display_name"=>gettext("Managed Service Account")),

			// Proprietary implementation of InetOrgPerson:
			//	- Subclass of proprietary "user" (so listed before it in schema definition)
			//	- Attribute "sn" is not mandatory
			array("name"=>"inetOrgPerson",			"icon"=>"user24.png",			"is_folder"=>false,"display_name"=>gettext("InetOrgPerson"),"can_create"=>true),

			array("name"=>"groupPolicyContainer",		"icon"=>"microsoft/policy.png",		"is_folder"=>true),
			array("name"=>"rpcContainer",			"icon"=>"microsoft/rpc_services24.png",	"is_folder"=>true,"display_name"=>gettext("RPC Services")),
			array("name"=>"container",			"icon"=>"folder.png",			"is_folder"=>true,"display_name"=>gettext("Container")),
			array("name"=>"builtinDomain",			"icon"=>"folder.png",			"is_folder"=>true),
			array("name"=>"lostAndFound",			"icon"=>"folder.png",			"is_folder"=>true),
			array("name"=>"msDS-QuotaContainer",		"icon"=>"folder.png",			"is_folder"=>true),
			array("name"=>"domainDNS",			"icon"=>"microsoft/domain24.png",	"is_folder"=>true,"display_name"=>gettext("Domain")),
			array("name"=>"group",				"icon"=>"group24.png",			"is_folder"=>false,"display_name"=>gettext("Group"),"can_create"=>true),
			array("name"=>"contact",			"icon"=>"contact24.png",		"is_folder"=>false,"display_name"=>gettext("Contact"),"can_create"=>true),
			array("name"=>"computer",			"icon"=>"microsoft/computer24.png",	"is_folder"=>false,"display_name"=>gettext("Computer"),"can_create"=>true),
			array("name"=>"foreignSecurityPrincipal",	"icon"=>"user-alias24.png",		"is_folder"=>false,"display_name"=>gettext("Foreign Security Principal")),
			array("name"=>"printQueue",			"icon"=>"microsoft/printer24.png",	"is_folder"=>false,"display_name"=>gettext("Printer"),"can_create"=>true),
			array("name"=>"volume",				"icon"=>"microsoft/fileshare24.png",	"is_folder"=>false,"display_name"=>gettext("Shared Folder"),"can_create"=>true),
			array("name"=>"user",				"icon"=>"user24.png",			"is_folder"=>false,"display_name"=>gettext("User"),"can_create"=>true),
			array("name"=>"mSMQConfiguration",		"icon"=>"microsoft/msmq-settings.png",	"is_folder"=>false,"display_name"=>gettext("MSMQ Configuration")),
			array("name"=>"mSMQMigratedUser",		"icon"=>"generic24.png",		"is_folder"=>false,"display_name"=>gettext("MSMQ Upgraded User")),
			array("name"=>"mSMQQueue",			"icon"=>"microsoft/msmq-queue.png",	"is_folder"=>false,"display_name"=>gettext("MSMQ Queue")),
			array("name"=>"mSMQSettings",			"icon"=>"microsoft/msmq-settings.png",	"is_folder"=>false,"display_name"=>gettext("MSMQ Settings")),
			array("name"=>"mSMQEnterpriseSettings",		"icon"=>"microsoft/msmq-settings.png",	"is_folder"=>true,"display_name"=>gettext("MSMQ Enterprise")),
			array("name"=>"mSMQSiteLink",			"icon"=>"microsoft/msmq-site-link.png",	"is_folder"=>false,"display_name"=>gettext("MSMQ Routing Link")),
			array("name"=>"classSchema",			"icon"=>"object.png",			"is_folder"=>false),
			array("name"=>"attributeSchema",		"icon"=>"attribute.png",		"is_folder"=>false),
			array("name"=>"configuration",			"icon"=>"config-folder.png",		"is_folder"=>true,"display_name"=>gettext("Configuration")),
			array("name"=>"crossRefContainer",		"icon"=>"folder.png",			"is_folder"=>true),
			array("name"=>"displaySpecifier",		"icon"=>"generic24.png",		"is_folder"=>false),
			array("name"=>"sitesContainer",			"icon"=>"folder.png",			"is_folder"=>true,"display_name"=>gettext("Sites Container")),
			array("name"=>"interSiteTransport",		"icon"=>"folder.png",			"is_folder"=>true,"display_name"=>gettext("Inter-Site Transport")),
			array("name"=>"interSiteTransportContainer",	"icon"=>"folder.png",			"is_folder"=>true,"display_name"=>gettext("Inter-Site Transports Container")),
			array("name"=>"subnetContainer",		"icon"=>"folder.png",			"is_folder"=>true,"display_name"=>gettext("Subnets Container")),
			array("name"=>"subnet",				"icon"=>"microsoft/subnet.png",		"is_folder"=>false,"display_name"=>gettext("Subnet")),
			array("name"=>"site",				"icon"=>"microsoft/site.png",		"is_folder"=>true,"display_name"=>gettext("Site")),
			array("name"=>"serversContainer",		"icon"=>"folder.png",			"is_folder"=>true,"display_name"=>gettext("Servers Container")),
			array("name"=>"server",				"icon"=>"server.png",			"is_folder"=>true,"display_name"=>gettext("Server")),
			array("name"=>"nTDSDSA",			"icon"=>"microsoft/ntds-settings.png",	"is_folder"=>true,"display_name"=>gettext("Domain Controller Settings")),
			array("name"=>"nTDSSiteSettings",		"icon"=>"microsoft/ntds-settings.png",	"is_folder"=>false,"display_name"=>gettext("Site Settings")),
			array("name"=>"licensingSiteSettings",		"icon"=>"microsoft/cert.png",		"is_folder"=>false,"display_name"=>gettext("Licensing Site Settings")),
			array("name"=>"nTDSService",			"icon"=>"folder.png",			"is_folder"=>true,"display_name"=>gettext("Active Directory Domain Services")),
			array("name"=>"nTDSConnection",			"icon"=>"microsoft/ntds-connection.png","is_folder"=>false,"display_name"=>gettext("Connection")),
			array("name"=>"siteLink",			"icon"=>"microsoft/site-link.png",	"is_folder"=>false,"display_name"=>gettext("Site Link")),
			array("name"=>"siteLinkBridge",			"icon"=>"microsoft/site-link.png",	"is_folder"=>false,"display_name"=>gettext("Site Link Bridge")),
			array("name"=>"pKICertificateTemplate",		"icon"=>"microsoft/cert-template.png",	"is_folder"=>false,"display_name"=>gettext("Certificate Template")),
			array("name"=>"domainPolicy",			"icon"=>"microsoft/domain_policy24.png","is_folder"=>true,"display_name"=>gettext("Domain Policy")),
			array("name"=>"nTFRSSettings",			"icon"=>"microsoft/frs_settings24.png",	"is_folder"=>true,"display_name"=>gettext("FRS Settings")),
			array("name"=>"nTFRSReplicaSet",		"icon"=>"microsoft/frs_settings24.png",	"is_folder"=>false,"display_name"=>gettext("FRS Replica Set")),
			array("name"=>"nTFRSMember",			"icon"=>"microsoft/frs_settings24.png",	"is_folder"=>true,"display_name"=>gettext("FRS Member")),
			array("name"=>"addressBookContainer",		"icon"=>"microsoft/addr-book.png",	"is_folder"=>true,"display_name"=>gettext("Address List")),
			array("name"=>"linkTrackObjectMoveTable",	"icon"=>"folder.png",			"is_folder"=>true),
			array("name"=>"linkTrackVolumeTable",		"icon"=>"folder.png",			"is_folder"=>true),
			array("name"=>"fileLinkTracking",		"icon"=>"folder.png",			"is_folder"=>true),
			array("name"=>"physicalLocation",		"icon"=>"folder.png",			"is_folder"=>true),
			array("name"=>"cRLDistributionPoint",		"icon"=>"crl-distrib-point.png",	"is_folder"=>false),
			array("name"=>"certificationAuthority",		"icon"=>"cert-authority.png",		"is_folder"=>false),
			array("name"=>"dfsConfiguration",		"icon"=>"folder.png",			"is_folder"=>true),
			array("name"=>"msDS-PasswordSettingsContainer",	"icon"=>"folder.png",			"is_folder"=>true),
			array("name"=>"msImaging-PSPs",			"icon"=>"folder.png",			"is_folder"=>true),
			array("name"=>"msTPM-InformationObjectsContainer","icon"=>"folder.png",			"is_folder"=>true),
			array("name"=>"msMQ-Custom-Recipient",		"icon"=>"microsoft/msmq-queue-alias.png","is_folder"=>false,"display_name"=>gettext("MSMQ Queue Alias"),"can_create"=>true),
			array("name"=>"msMQ-Group",			"icon"=>"generic24.png",		"is_folder"=>false,"display_name"=>gettext("MSMQ Group")),
			array("name"=>"dMD",				"icon"=>"folder.png",			"is_folder"=>true,"display_name"=>gettext("Directory Management Domain")),
			array("name"=>"msPKI-Enterprise-Oid",		"icon"=>"folder.png",			"is_folder"=>true),
			array("name"=>"msDFSR-GlobalSettings",		"icon"=>"folder.png",			"is_folder"=>true),
			array("name"=>"msDFSR-ReplicationGroup",	"icon"=>"folder.png",			"is_folder"=>true),
			array("name"=>"msDFSR-Member",			"icon"=>"folder.png",			"is_folder"=>true),
			array("name"=>"msDFSR-Content",			"icon"=>"folder.png",			"is_folder"=>true),
			array("name"=>"msDFSR-Topology",		"icon"=>"folder.png",			"is_folder"=>true),
			array("name"=>"msDFSR-LocalSettings",		"icon"=>"folder.png",			"is_folder"=>true),
			array("name"=>"msDFSR-Subsriber",		"icon"=>"folder.png",			"is_folder"=>true),
			array("name"=>"msDFS-NamespaceAnchor",		"icon"=>"folder.png",			"is_folder"=>true),
			array("name"=>"msDFS-Namespacev2",		"icon"=>"folder.png",			"is_folder"=>true),
			array("name"=>"dnsZone",			"icon"=>"microsoft/dns-zone.png",	"is_folder"=>true,"rdn_attrib"=>"dc"),
			array("name"=>"dnsNode",			"icon"=>"generic24.png",		"is_folder"=>false,"rdn_attrib"=>"dc"),
			array("name"=>"classStore",			"icon"=>"folder.png",			"is_folder"=>true),
			array("name"=>"packageRegistration",		"icon"=>"app.png",			"is_folder"=>false)
			);

		// Display layouts
		$ldap_server->add_display_layout("group",array(
			array("colspan"=>2,"new_row"=>true,
				"attributes"=>array(
					array("cn",				gettext("Group Name (Active Directory)"),	"contact24.png",true),
					array("sAMAccountName",			gettext("Group Name (pre-Windows 2000)"),	"contact24.png"),
					array("description",			gettext("Description"),				"description.png"),
					array("mail",				gettext("E-mail"),				"mail.png"),
					array("groupType",			gettext("Type/Scope"),				"generic24.png")
					)
				),
			array("section_name"=>gettext("Group Members"),"new_row"=>true,"width"=>"50%",
				"attributes"=>array(
					array("member")
					)
				),
			array("section_name"=>gettext("Group is a Member Of"),
				"attributes"=>array(
					array("memberOf")
					)
				),
			array("section_name"=>gettext("Additional Notes"),"new_row"=>true,"colspan"=>2,
				"attributes"=>array(
					array("info")
					)
				)
			));

		$ldap_server->add_display_layout("domainDNS",array(
			array("section_name"=>gettext("Active Directory Domain Details"),
				"attributes"=>array(
					array("distinguishedName",		gettext("Domain Name"),				"microsoft/domain24.png")
					)
				)
			));

		$ldap_server->add_display_layout("container,builtinDomain,lostAndFound,msDS-QuotaContainer,crossRefContainer,sitesContainer",array(
			array("section_name"=>gettext("Folder Details"),
				"attributes"=>array(
					array("cn",				gettext("Name"),				"folder.png"),
					array("description",			gettext("Description"),				"description.png"),
					)
				)
			));

		$ldap_server->add_display_layout("printQueue",array(
			array("section_name"=>gettext("Shared Printer"),
				"attributes"=>array(
					array("cn",				gettext("Object Name"),				"generic24.png",true),
					array("printerName",			gettext("Printer Name"),			"microsoft/printer24.png"),
					array("driverName",			gettext("Driver"),				"app.png"),
					array("location",			gettext("Location"),				"address.png"),
					array("description",			gettext("Description"),				"description.png")
					)
				),
			array("section_name"=>gettext("Capabilities"),"width"=>"50%",
				"attributes"=>array(
					array("printColor",			gettext("Color Printing"),			"generic24.png"),
					array("printStaplingSupported",		gettext("Stapling"),				"generic24.png"),
					array("printDuplexSupported",		gettext("Double-Sided Printing"),		"generic24.png"),
					array("printRate",			gettext("Printing Speed (PPM)"),		"generic24.png"),
					array("printMaxResolutionSupported",	gettext("Max. Resolution (DPI)"),		"generic24.png"),
					)
				),
			array("section_name"=>gettext("Print Server"),"new_row"=>true,"colspan"=>2,
				"attributes"=>array(
					array("serverName",			gettext("Host Name"),				"microsoft/computer24.png"),
					array("uNCName",			gettext("Printer UNC Path"),			"folder.png")
					)
				),
			array("section_name"=>gettext("Paper Available"),"new_row"=>true,"colspan"=>2,
				"attributes"=>array(
					array("printMediaReady")
					)
				)
			));

		$ldap_server->add_display_layout("volume",array(
			array("section_name"=>gettext("Shared Folder"),
				"attributes"=>array(
					array("cn",				gettext("Object Name"),				"microsoft/fileshare24.png"),
					array("uNCName",			gettext("UNC Path"),				"folder.png")
					)
				)
			));

		$ldap_server->add_display_layout("computer",array(
			array("section_name"=>gettext("Computer Information"),"width"=>"50%",
				"attributes"=>array(
					array("cn",				gettext("Computer Name (Active Directory)"),	"generic24.png"),
					array("sAMAccountName",			gettext("Computer Name (pre-Windows 2000)"),	"generic24.png"),
					array("dNSHostName",			gettext("DNS Name"),				"generic24.png"),
					array("description",			gettext("Description"),				"description.png"),
					array("managedBy",			gettext("Managed By"),				"alias.png"),
					array("location",			gettext("Location"),				"address.png"),
					array("msDS-AllowedToDelegateTo",	gettext("Delegation Service List"),		"generic24.png"),
					array("operatingSystem",		gettext("Operating System"),			"app.png"),
					array("operatingSystemVersion",		gettext("OS Version"),				"generic24.png"),
					array("operatingSystemServicePack",	gettext("OS Service Pack"),			"generic24.png"),
					)
				),
			array("section_name"=>gettext("Group Membership"),"new_row"=>true,"colspan"=>2,
				"attributes"=>array(
					array("memberOf")
					)
				),
			array("section_name"=>gettext("Service Principal Names"),"new_row"=>true,"colspan"=>2,
				"attributes"=>array(
					array("servicePrincipalName")
					)
				),
			array("section_name"=>gettext("Contained Records"),"new_row"=>true,"colspan"=>2,
				"attributes"=>array(
					array("__CHILD_OBJECTS__")
					)
				),
			));

		$ldap_server->add_display_layout("foreignSecurityPrincipal",array(
			array("colspan"=>2,"new_row"=>true,
				"attributes"=>array(
					array("cn",				gettext("Name"),				"contact24.png",true),
					array("description",			gettext("Description"),				"description.png"),
					)
				),
			array("section_name"=>gettext("Group Membership"),"new_row"=>true,"colspan"=>2,
				"attributes"=>array(
					array("memberOf")
					)
				),
			));

		$ldap_server->add_display_layout("msMQ-Custom-Recipient",array(
			array("section_name"=>gettext("MSMQ Queue Alias"),"width"=>"50%",
				"attributes"=>array(
					array("cn",				gettext("Queue Alias Name"),			"generic24.png"),
					array("msMQ-Recipient-FormatName",	gettext("Format Name of Recipient"),		"generic24.png"),
					)
				)
			));

		$ldap_server->add_display_layout("displaySpecifier",array(
			array("section_name"=>gettext("Class Display Name"),"new_row"=>true,"colspan"=>2,
				"attributes"=>array(
					array("classDisplayName")
					)
				),
			array("section_name"=>gettext("Icon Path"),"new_row"=>true,"colspan"=>2,
				"attributes"=>array(
					array("iconPath")
					)
				),
			array("section_name"=>gettext("Treat as Leaf Object"),"new_row"=>true,"colspan"=>2,
				"attributes"=>array(
					array("treatAsLeaf")
					)
				),
			array("section_name"=>gettext("User Interface Extensions"),"new_row"=>true,"colspan"=>2,
				"attributes"=>array(
					array("adminPropertyPages",		gettext("Admin Property Pages"),		"generic24.png"),
					array("adminMultiSelectPropertyPages",	gettext("Admin Multi Select Property Pages"),	"generic24.png"),
					array("shellPropertyPages",		gettext("Shell Property Pages"),		"generic24.png"),
					array("adminContextMenu",		gettext("Admin Context Menu Entries"),		"generic24.png"),
					array("contextMenu",			gettext("Context Menu Entries"),		"generic24.png"),
					array("createWizardExt",		gettext("Object Creation Wizard Extensions"),	"generic24.png")
					)
				),
			array("section_name"=>gettext("Attribute Display Names"),"new_row"=>true,"colspan"=>2,
				"attributes"=>array(
					array("attributeDisplayNames")
					)
				),
			array("section_name"=>gettext("Extra Columns"),"new_row"=>true,"colspan"=>2,
				"attributes"=>array(
					array("extraColumns")
					)
				)
			));

		// component schema (derived)
		$ldap_server->add_schema("microsoft/exchange");
		$ldap_server->add_schema("microsoft/laps");
		$ldap_server->add_schema("microsoft/sms");

		parent::__construct($ldap_server);

		// component schema (derived from)
		$ldap_server->add_schema("microsoft/std");
	}
}
?>
