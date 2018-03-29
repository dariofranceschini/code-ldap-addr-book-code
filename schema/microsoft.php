<?php
/** microsoft.schema (partial) */

class microsoft_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"adminContextMenu",		"data_type"=>"text_list",	"display_name"=>gettext("Admin Context Menu Entries")),
			array("name"=>"adminMultiSelectPropertyPages",	"data_type"=>"text_list",	"display_name"=>gettext("Admin Multi Select Property Pages")),
			array("name"=>"adminPropertyPages",		"data_type"=>"text_list",	"display_name"=>gettext("Admin Property Pages")),
			array("name"=>"attributeDisplayNames",		"data_type"=>"text_list",	"display_name"=>gettext("Attribute Display Names")),
			array("name"=>"bridgeheadTransportList",	"data_type"=>"dn_list",		"display_name"=>gettext("Bridgehead Transports")),
			array("name"=>"canUpgradeScript",		"data_type"=>"text_list",	"display_name"=>gettext("Package Upgrade Relationships")),
			array("name"=>"categories",			"data_type"=>"text_list",	"display_name"=>gettext("Category GUIDs")),
			array("name"=>"company",			"data_type"=>"text",		"display_name"=>gettext("Company")),
			array("name"=>"contextMenu",			"data_type"=>"text_list",	"display_name"=>gettext("Context Menu Entries")),
			array("name"=>"createWizardExt",		"data_type"=>"text_list",	"display_name"=>gettext("Object Creation Wizard Extensions")),
			array("name"=>"department",			"data_type"=>"text",		"display_name"=>gettext("Department")),
			array("name"=>"displayName",			"data_type"=>"text",		"display_name"=>gettext("Display/Preferred Name")),
			array("name"=>"dMDLocation",			"data_type"=>"dn",		"display_name"=>gettext("Schema Partition DN")),
			array("name"=>"driverName",			"data_type"=>"text",		"display_name"=>gettext("Driver Name")),
			array("name"=>"enabledConnection",		"data_type"=>"yes_no",		"display_name"=>gettext("Connection Is Enabled")),
			array("name"=>"extendedAttributeInfo",		"data_type"=>"ldap_schema",	"display_name"=>gettext("Extended Attribute Information")),
			array("name"=>"extendedClassInfo",		"data_type"=>"ldap_schema",	"display_name"=>gettext("Extended Class Information")),
			array("name"=>"extraColumns",			"data_type"=>"text_list",	"display_name"=>gettext("Extra Columns")),
			array("name"=>"flatName",			"data_type"=>"text",		"display_name"=>gettext("Flat (NetBIOS) Domain Name")),
			array("name"=>"fromServer",			"data_type"=>"dn",		"display_name"=>gettext("Replication Source Server")),
			array("name"=>"fSMORoleOwner",			"data_type"=>"dn",		"display_name"=>gettext("FSMO Role Owner")),
			array("name"=>"groupType",			"data_type"=>"ad_group_type",	"display_name"=>gettext("Group Type/Scope")),
			array("name"=>"hasMasterNCs",			"data_type"=>"dn_list",		"display_name"=>gettext("Naming Contexts Served")),
			array("name"=>"info",				"data_type"=>"text_area",	"display_name"=>gettext("General Information")),
			array("name"=>"invocationId",			"data_type"=>"download",	"display_name"=>gettext("Invocation ID Data")),
			array("name"=>"location",			"data_type"=>"text",		"display_name"=>gettext("Location")),
			array("name"=>"managedBy",			"data_type"=>"dn",		"display_name"=>gettext("Managed By")),
			array("name"=>"memberOf",			"data_type"=>"dn_list",		"display_name"=>gettext("Member Of")),
			array("name"=>"mS-DS-ReplicatesNCReason",	"data_type"=>"text_list",	"display_name"=>gettext("Usefulness to Replication Topology")),
			array("name"=>"msDS-AllowedToDelegateTo",	"data_type"=>"text_list",	"display_name"=>gettext("Allowed to Delegate To")),
			array("name"=>"msDS-HasDomainNCs",		"data_type"=>"dn_list",		"display_name"=>gettext("Domain Naming Contexts")),
			array("name"=>"msDS-HasInstantiatedNCs",	"data_type"=>"text_list",	"display_name"=>gettext("Naming Context Replication Status")),
			array("name"=>"msDS-SupportedEncryptionTypes",	"data_type"=>"ad_encrypt_type",	"display_name"=>gettext("Supported Encryption/Authentication Types")),
			array("name"=>"msDS-TrustForestTrustInfo",	"data_type"=>"download",	"display_name"=>gettext("Forest Trust Data")),
			array("name"=>"msiFileList",			"data_type"=>"text_list",	"display_name"=>gettext("Package Deployment Source List")),
			array("name"=>"msWMI-ChangeDate",		"data_type"=>"date_time",	"display_name"=>gettext("WMI Object Last Modification Date")),
			array("name"=>"msWMI-CreationDate",		"data_type"=>"date_time",	"display_name"=>gettext("WMI Object Creation Date")),

			// Bitfield, the interpretation of which varies according to the object class
			// in which it is being used.
			array("name"=>"options",			"data_type"=>"text",		"display_name"=>gettext("Options")),

			array("name"=>"printColor",			"data_type"=>"yes_no",		"display_name"=>gettext("Color Printing Supported")),
			array("name"=>"printerName",			"data_type"=>"text",		"display_name"=>gettext("Printer Display Name")),
			array("name"=>"printDuplexSupported",		"data_type"=>"yes_no",		"display_name"=>gettext("Double-Sided Printing Supported")),
			array("name"=>"printMaxResolutionSupported",	"data_type"=>"text",		"display_name"=>gettext("Maximum Supported Resolution")),
			array("name"=>"printMediaReady",		"data_type"=>"text_list",	"display_name"=>gettext("Paper Available")),
			array("name"=>"printMediaSupported",		"data_type"=>"text_list",	"display_name"=>gettext("Paper Supported")),
			array("name"=>"printStaplingSupported",		"data_type"=>"yes_no",		"display_name"=>gettext("Stapling Supported")),
			array("name"=>"queryPolicyObject",		"data_type"=>"dn",		"display_name"=>gettext("Query Policy Object DN")),
			array("name"=>"rIDAvailablePool",		"data_type"=>"download",	"display_name"=>gettext("Available RID Pool")),
			array("name"=>"sAMAccountName",			"data_type"=>"text",		"display_name"=>gettext("Pre-Windows 2000 Account Name")),
			array("name"=>"schedule",			"data_type"=>"download",	"display_name"=>gettext("Replication Schedule")),

			// Data type of "serverName" is changed to "dn" when used with the Active Directory
			// Root DSE object (hardcoded workaround). The data type "text" (as defined below) is
			// used for the "serverName" attribute of "printQueue" objects.
			array("name"=>"serverName",			"data_type"=>"text",		"display_name"=>gettext("Server Name")),

			array("name"=>"serverReference",		"data_type"=>"dn",		"display_name"=>gettext("Server's Computer Object DN")),
			array("name"=>"servicePrincipalName",		"data_type"=>"text_list",	"display_name"=>gettext("Service Principal Names")),
			array("name"=>"shellPropertyPages",		"data_type"=>"text_list",	"display_name"=>gettext("Shell Property Pages")),
			// Non-standard implementation of streetAddress as a separate attribute, rather than an alias of street:
			array("name"=>"streetAddress",			"data_type"=>"text_area",	"display_name"=>gettext("Street Address")),

			array("name"=>"transportType",			"data_type"=>"dn",		"display_name"=>gettext("Replication Transport")),
			array("name"=>"treatAsLeaf",			"data_type"=>"yes_no",		"display_name"=>gettext("Treat as Leaf Object")),
			array("name"=>"trustAttributes",		"data_type"=>"ad_trust_attribs","display_name"=>gettext("Trust Attributes")),
			array("name"=>"trustDirection",			"data_type"=>"ad_trust_dir",	"display_name"=>gettext("Direction of Trust")),
			array("name"=>"trustType",			"data_type"=>"ad_trust_type",	"display_name"=>gettext("Trust Type")),
			array("name"=>"uNCName",			"data_type"=>"text",		"display_name"=>gettext("UNC Path Name")),
			array("name"=>"url",				"data_type"=>"text",		"display_name"=>gettext("URL (e.g. web page)")),
			array("name"=>"whenCreated",			"data_type"=>"date_time",	"display_name"=>gettext("Creation Date")),
			array("name"=>"whenChanged",			"data_type"=>"date_time",	"display_name"=>gettext("Last Modification Date")),
			array("name"=>"wWWHomePage",			"data_type"=>"text",		"display_name"=>gettext("WWW Home Page")),
			);

		// Object classes
		$this->object_schema = array(
			array("name"=>"account"),
			array("name"=>"aCSPolicy"),
			array("name"=>"aCSResourceLimits"),
			array("name"=>"aCSSubnet"),
			array("name"=>"addressBookContainer",		"icon"=>"microsoft/addr-book.png",	"is_folder"=>true,"display_name"=>gettext("Address List")),
			array("name"=>"addressTemplate",							"parent_class"=>"displayTemplate"),
			array("name"=>"applicationSettings",		"icon"=>"generic24.png",		"class_type"=>"abstract"),
			array("name"=>"applicationSiteSettings",	"icon"=>"generic24.png",		"class_type"=>"abstract"),
			array("name"=>"applicationVersion",							"parent_class"=>"applicationSettings"),
			array("name"=>"attributeSchema",		"icon"=>"attribute.png",		"is_folder"=>false),
			array("name"=>"builtinDomain",			"icon"=>"folder.png",			"is_folder"=>true),
			array("name"=>"categoryRegistration",							"parent_class"=>"leaf"),
			array("name"=>"classRegistration",							"parent_class"=>"leaf"),
			array("name"=>"classSchema",			"icon"=>"object.png",			"is_folder"=>false),
			array("name"=>"classStore",			"icon"=>"folder.png",			"is_folder"=>true),
			array("name"=>"comConnectionPoint",							"parent_class"=>"connectionPoint"),
			array("name"=>"computer",			"icon"=>"microsoft/computer24.png",	"is_folder"=>false,"display_name"=>gettext("Computer"),"can_create"=>true,"parent_class"=>"user"),
			array("name"=>"configuration",			"icon"=>"config-folder.png",		"is_folder"=>true,"display_name"=>gettext("Configuration")),
			array("name"=>"connectionPoint",		"icon"=>"generic24.png",		"class_type"=>"abstract","parent_class"=>"leaf"),
			array("name"=>"contact",			"icon"=>"contact24.png",		"is_folder"=>false,"display_name"=>gettext("Contact"),"can_create"=>true,"parent_class"=>"organizationalPerson"),
			array("name"=>"container",			"icon"=>"folder.png",			"is_folder"=>true,"display_name"=>gettext("Container")),
			array("name"=>"controlAccessRight"),
			array("name"=>"crossRef"),
			array("name"=>"crossRefContainer",		"icon"=>"folder.png",			"is_folder"=>true),
			array("name"=>"dfsConfiguration",		"icon"=>"folder.png",			"is_folder"=>true),
			array("name"=>"dHCPClass"),
			array("name"=>"displaySpecifier",		"icon"=>"generic24.png",		"is_folder"=>false),
			array("name"=>"displayTemplate"),
			array("name"=>"dMD",				"icon"=>"folder.png",			"is_folder"=>true,"display_name"=>gettext("Directory Management Domain")),
			array("name"=>"dnsNode",			"icon"=>"generic24.png",		"is_folder"=>false,"rdn_attrib"=>"dc"),
			array("name"=>"dnsZone",			"icon"=>"microsoft/dns-zone.png",	"is_folder"=>true,"rdn_attrib"=>"dc"),
			array("name"=>"dnsZoneScope"),
			array("name"=>"dnsZoneScopeContainer"),
			array("name"=>"domain",				"icon"=>"generic24.png",		"class_type"=>"abstract"),
			array("name"=>"domainDNS",			"icon"=>"domain24.png",			"is_folder"=>true,"display_name"=>gettext("Domain"),"parent_class"=>"domain"),
			array("name"=>"domainPolicy",			"icon"=>"microsoft/domain_policy24.png","is_folder"=>true,"display_name"=>gettext("Domain Policy"),"parent_class"=>"leaf"),
			array("name"=>"dSUISettings"),
			array("name"=>"fileLinkTracking",		"icon"=>"folder.png",			"is_folder"=>true),
			array("name"=>"fileLinkTrackingEntry"),
			array("name"=>"foreignSecurityPrincipal",	"icon"=>"user-alias24.png",		"is_folder"=>false,"display_name"=>gettext("Foreign Security Principal")),
			array("name"=>"fTDfs"),
			array("name"=>"group",				"icon"=>"group24.png",			"is_folder"=>false,"display_name"=>gettext("Group"),"can_create"=>true),
			array("name"=>"groupPolicyContainer",		"icon"=>"microsoft/policy.png",		"is_folder"=>true,"parent_class"=>"container"),
			array("name"=>"indexServerCatalog",							"parent_class"=>"connectionPoint"),
			array("name"=>"infrastructureUpdate",		"icon"=>"server-alias.png",		"is_folder"=>false),
			array("name"=>"intellimirrorGroup",							"display_name"=>gettext("IntelliMirror Group")),
			array("name"=>"intellimirrorSCP",							"display_name"=>gettext("IntelliMirror Service"),"parent_class"=>"serviceAdministrationPoint"),
			array("name"=>"interSiteTransport",		"icon"=>"folder.png",			"is_folder"=>true,"display_name"=>gettext("Inter-Site Transport")),
			array("name"=>"interSiteTransportContainer",	"icon"=>"folder.png",			"is_folder"=>true,"display_name"=>gettext("Inter-Site Transports Container")),
			array("name"=>"ipsecBase",			"icon"=>"generic24.png",		"class_type"=>"abstract"),
			array("name"=>"ipsecFilter",								"parent_class"=>"ipsecBase"),
			array("name"=>"ipsecISAKMPPolicy",							"parent_class"=>"ipsecBase"),
			array("name"=>"ipsecNegotiationPolicy",							"parent_class"=>"ipsecBase"),
			array("name"=>"ipsecNFA",								"parent_class"=>"ipsecBase"),
			array("name"=>"ipsecPolicy",								"parent_class"=>"ipsecBase"),
			array("name"=>"leaf",				"icon"=>"generic24.png",		"class_type"=>"abstract"),
			array("name"=>"licensingSiteSettings",		"icon"=>"cert.png",			"is_folder"=>false,"display_name"=>gettext("Licensing Site Settings"),"parent_class"=>"applicationSiteSettings"),
			array("name"=>"linkTrackObjectMoveTable",	"icon"=>"folder.png",			"is_folder"=>true,"parent_class"=>"fileLinkTracking"),
			array("name"=>"linkTrackOMTEntry",							"parent_class"=>"leaf"),
			array("name"=>"linkTrackVolEntry",							"parent_class"=>"leaf"),
			array("name"=>"linkTrackVolumeTable",		"icon"=>"folder.png",			"is_folder"=>true,"parent_class"=>"fileLinkTracking"),
			array("name"=>"lostAndFound",			"icon"=>"folder.png",			"is_folder"=>true),
			array("name"=>"mailRecipient",								"class_type"=>"auxiliary"),
			array("name"=>"meeting"),
			array("name"=>"ms-net-ieee-80211-GroupPolicy"),
			array("name"=>"ms-net-ieee-8023-GroupPolicy"),
			array("name"=>"mS-SQL-OLAPCube"),
			array("name"=>"mS-SQL-OLAPDatabase"),
			array("name"=>"mS-SQL-OLAPServer",							"parent_class"=>"serviceConnectionPoint"),
			array("name"=>"mS-SQL-SQLDatabase"),
			array("name"=>"mS-SQL-SQLPublication"),
			array("name"=>"mS-SQL-SQLRepository"),
			array("name"=>"mS-SQL-SQLServer",							"parent_class"=>"serviceConnectionPoint"),
			array("name"=>"msAuthz-CentralAccessPolicies"),
			array("name"=>"msAuthz-CentralAccessPolicy"),
			array("name"=>"msAuthz-CentralAccessRule"),
			array("name"=>"msAuthz-CentralAccessRules"),
			array("name"=>"msCOM-Partition"),
			array("name"=>"msCOM-PartitionSet"),
			array("name"=>"msDFS-DeletedLinkv2"),
			array("name"=>"msDFS-Linkv2"),
			array("name"=>"msDFS-NamespaceAnchor",		"icon"=>"folder.png",			"is_folder"=>true),
			array("name"=>"msDFS-Namespacev2",		"icon"=>"folder.png",			"is_folder"=>true),
			array("name"=>"msDFSR-Connection"),
			array("name"=>"msDFSR-Content",			"icon"=>"folder.png",			"is_folder"=>true),
			array("name"=>"msDFSR-ContentSet"),
			array("name"=>"msDFSR-GlobalSettings",		"icon"=>"folder.png",			"is_folder"=>true),
			array("name"=>"msDFSR-LocalSettings",		"icon"=>"folder.png",			"is_folder"=>true),
			array("name"=>"msDFSR-Member",			"icon"=>"folder.png",			"is_folder"=>true),
			array("name"=>"msDFSR-ReplicationGroup",	"icon"=>"folder.png",			"is_folder"=>true),
			array("name"=>"msDFSR-Subscriber",		"icon"=>"folder.png",			"is_folder"=>true),
			array("name"=>"msDFSR-Subscription"),
			array("name"=>"msDFSR-Topology",		"icon"=>"folder.png",			"is_folder"=>true),
			array("name"=>"msDNS-ServerSettings"),
			array("name"=>"msDS-App-Configuration",							"parent_class"=>"applicationSettings"),
			array("name"=>"msDS-AppData",								"parent_class"=>"applicationSettings"),
			array("name"=>"msDS-AuthNPolicies"),
			array("name"=>"msDS-AuthNPolicy"),
			array("name"=>"msDS-AuthNPolicySilo"),
			array("name"=>"msDS-AuthNPolicySilos"),
			array("name"=>"msDS-AzAdminManager"),
			array("name"=>"msDS-AzApplication"),
			array("name"=>"msDS-AzOperation"),
			array("name"=>"msDS-AzRole"),
			array("name"=>"msDS-AzScope"),
			array("name"=>"msDS-AzTask"),
			array("name"=>"msDS-ClaimsTransformationPolicies"),
			array("name"=>"msDS-ClaimsTransformationPolicyType"),
			array("name"=>"msDS-ClaimType",								"parent_class"=>"msDS-ClaimTypePropertyBase"),
			array("name"=>"msDS-ClaimTypePropertyBase",	"icon"=>"generic24.png",		"class_type"=>"abstract"),
			array("name"=>"msDS-ClaimTypes"),
			array("name"=>"msDS-CloudExtensions",							"class_type"=>"auxiliary"),
			array("name"=>"msDS-Device"),
			array("name"=>"msDS-DeviceContainer"),
			array("name"=>"msDS-DeviceRegistrationService"),
			array("name"=>"msDS-DeviceRegistrationServiceContainer"),
			array("name"=>"msDS-GroupManagedServiceAccount","icon"=>"microsoft/service.png",	"is_folder"=>false,"display_name"=>gettext("Group Managed Service Account"),"parent_class"=>"computer"),
			array("name"=>"msDS-KeyCredential"),
			array("name"=>"msDS-ManagedServiceAccount",	"icon"=>"microsoft/service.png",	"is_folder"=>false,"display_name"=>gettext("Managed Service Account"),"parent_class"=>"computer"),
			array("name"=>"msDS-OptionalFeature"),
			array("name"=>"msDS-PasswordSettings"),
			array("name"=>"msDS-PasswordSettingsContainer",	"icon"=>"folder.png",			"is_folder"=>true),
			array("name"=>"msDS-QuotaContainer",		"icon"=>"folder.png",			"is_folder"=>true),
			array("name"=>"msDS-QuotaControl"),
			array("name"=>"msDS-ResourceProperties"),
			array("name"=>"msDS-ResourceProperty",							"parent_class"=>"msDS-ClaimTypePropertyBase"),
			array("name"=>"msDS-ResourcePropertyList"),
			array("name"=>"msDS-ShadowPrincipal"),
			array("name"=>"msDS-ShadowPrincipalContainer",						"parent_class"=>"cointainer"),
			array("name"=>"msDS-ValueType"),
			array("name"=>"msExchConfigurationContainer",						"parent_class"=>"cointainer"),	// note not in Exch schema!
			array("name"=>"msFVE-RecoveryInformation"),
			array("name"=>"msieee80211-Policy"),
			array("name"=>"msImaging-PostScanProcess"),
			array("name"=>"msImaging-PSPs",			"icon"=>"folder.png",			"is_folder"=>true,"parent_class"=>"container"),
			array("name"=>"msKds-ProvRootKey"),
			array("name"=>"msKds-ProvServerConfiguration"),
			array("name"=>"msMQ-Custom-Recipient",		"icon"=>"microsoft/msmq-queue-alias.png","is_folder"=>false,"display_name"=>gettext("MSMQ Queue Alias"),"can_create"=>true),
			array("name"=>"msMQ-Group",			"icon"=>"generic24.png",		"is_folder"=>false,"display_name"=>gettext("MSMQ Group")),
			array("name"=>"mSMQConfiguration",		"icon"=>"microsoft/msmq-settings.png",	"is_folder"=>false,"display_name"=>gettext("MSMQ Configuration")),
			array("name"=>"mSMQEnterpriseSettings",		"icon"=>"microsoft/msmq-settings.png",	"is_folder"=>true,"display_name"=>gettext("MSMQ Enterprise")),
			array("name"=>"mSMQMigratedUser",		"icon"=>"generic24.png",		"is_folder"=>false,"display_name"=>gettext("MSMQ Upgraded User")),
			array("name"=>"mSMQQueue",			"icon"=>"microsoft/msmq-queue.png",	"is_folder"=>false,"display_name"=>gettext("MSMQ Queue")),
			array("name"=>"mSMQSettings",			"icon"=>"microsoft/msmq-settings.png",	"is_folder"=>false,"display_name"=>gettext("MSMQ Settings")),
			array("name"=>"mSMQSiteLink",			"icon"=>"microsoft/msmq-site-link.png",	"is_folder"=>false,"display_name"=>gettext("MSMQ Routing Link")),
			array("name"=>"msPKI-Enterprise-Oid",		"icon"=>"folder.png",			"is_folder"=>true),
			array("name"=>"msPKI-Key-Recovery-Agent",						"parent_class"=>"user"),
			array("name"=>"msPKI-PrivateKeyRecoveryAgent"),
			array("name"=>"msPrint-ConnectionPolicy"),
			array("name"=>"msSFU30DomainInfo"),
			array("name"=>"msSFU30MailAliases"),
			array("name"=>"msSFU30NetID"),
			array("name"=>"msSFU30NetworkUser"),
			array("name"=>"msSFU30NISMapConfig"),
			array("name"=>"msSPP-ActivationObject"),
			array("name"=>"msSPP-ActivationObjectsContainer"),
			array("name"=>"msTAPI-RtConference"),
			array("name"=>"msTAPI-RtPerson"),
			array("name"=>"msTPM-InformationObject"),
			array("name"=>"msTPM-InformationObjectsContainer","icon"=>"folder.png",			"is_folder"=>true),
			array("name"=>"msWMI-IntRangeParam",							"parent_class"=>"msWMI-RangeParam"),
			array("name"=>"msWMI-IntSetParam",							"parent_class"=>"msWMI-RangeParam"),
			array("name"=>"msWMI-MergeablePolicyTemplate",						"parent_class"=>"msWMI-PolicyTemplate"),
			array("name"=>"msWMI-ObjectEncoding"),
			array("name"=>"msWMI-PolicyTemplate"),
			array("name"=>"msWMI-PolicyType"),
			array("name"=>"msWMI-RangeParam"),
			array("name"=>"msWMI-RealRangeParam",							"parent_class"=>"msWMI-RangeParam"),
			array("name"=>"msWMI-Rule"),
			array("name"=>"msWMI-ShadowObject"),
			array("name"=>"msWMI-SimplePolicyTemplate",						"parent_class"=>"msWMI-PolicyTemplate"),
			array("name"=>"msWMI-Som",			"icon"=>"microsoft/wmi-filter.png",	"is_folder"=>false,"required_attribs"=>"msWMI-ID,msWMI-Name"),
			array("name"=>"msWMI-StringSetParam",							"parent_class"=>"msWMI-RangeParam"),
			array("name"=>"msWMI-UintRangeParam",							"parent_class"=>"msWMI-RangeParam"),
			array("name"=>"msWMI-UintSetParam",							"parent_class"=>"msWMI-RangeParam"),
			array("name"=>"msWMI-UnknownRangeParam",						"parent_class"=>"msWMI-RangeParam"),
			array("name"=>"msWMI-WMIGPO"),
			array("name"=>"nTDSConnection",			"icon"=>"microsoft/ntds-connection.png","is_folder"=>false,"display_name"=>gettext("Connection"),"parent_class"=>"leaf"),
			array("name"=>"nTDSDSA",			"icon"=>"microsoft/ntds-settings.png",	"is_folder"=>false,"display_name"=>gettext("Domain Controller Settings"),"parent_class"=>"applicationSettings"),
			array("name"=>"nTDSDSARO",								"parent_class"=>"nTDSDSA"),
			array("name"=>"nTDSService",			"icon"=>"folder.png",			"is_folder"=>true,"display_name"=>gettext("Active Directory Domain Services")),
			array("name"=>"nTDSSiteSettings",		"icon"=>"microsoft/ntds-settings.png",	"is_folder"=>false,"display_name"=>gettext("Site Settings"),"parent_class"=>"applicationSiteSettings"),
			array("name"=>"nTFRSMember",			"icon"=>"microsoft/frs_settings24.png",	"is_folder"=>true,"display_name"=>gettext("FRS Member")),
			array("name"=>"nTFRSReplicaSet",		"icon"=>"microsoft/frs_settings24.png",	"is_folder"=>false,"display_name"=>gettext("FRS Replica Set")),
			array("name"=>"nTFRSSettings",			"icon"=>"microsoft/frs_settings24.png",	"is_folder"=>true,"display_name"=>gettext("FRS Settings"),"parent_class"=>"applicationSettings"),
			array("name"=>"nTFRSSubscriber",							"display_name"=>gettext("FRS Subscriber")),
			array("name"=>"nTFRSSubscriptions",							"display_name"=>gettext("FRS Subscriptions")),
			array("name"=>"packageRegistration",		"icon"=>"app.png",			"is_folder"=>false),
			array("name"=>"physicalLocation",		"icon"=>"folder.png",			"is_folder"=>true,"parent_class"=>"locality"),
			array("name"=>"pKICertificateTemplate",		"icon"=>"cert-config.png",		"is_folder"=>false,"display_name"=>gettext("Certificate Template")),
			array("name"=>"pKIEnrollmentService"),
			array("name"=>"printQueue",			"icon"=>"microsoft/printer24.png",	"is_folder"=>false,"display_name"=>gettext("Printer"),"can_create"=>true,"parent_class"=>"connectionPoint"),
			array("name"=>"queryPolicy",								"display_name"=>gettext("Query Policy")),
			array("name"=>"remoteMailRecipient"),
			array("name"=>"remoteStorageServicePoint",						"display_name"=>gettext("Remote Storage Service"),"parent_class"=>"serviceAdministrationPoint"),
			array("name"=>"rIDManager",			"icon"=>"server-alias.png",		"is_folder"=>false),
			array("name"=>"rIDSet"),
			array("name"=>"rpcContainer",			"icon"=>"microsoft/rpc_services24.png",	"is_folder"=>true,"display_name"=>gettext("RPC Services"),"parent_class"=>"container"),
			array("name"=>"rpcEntry",			"icon"=>"generic24.png",		"class_type"=>"abstract","parent_class"=>"connectionPoint"),
			array("name"=>"rpcGroup",								"parent_class"=>"rpcEntry"),
			array("name"=>"rpcProfile",								"parent_class"=>"rpcEntry"),
			array("name"=>"rpcProfileElement",							"parent_class"=>"rpcEntry"),
			array("name"=>"rpcServer",								"parent_class"=>"rpcEntry"),
			array("name"=>"rpcServerElement",							"parent_class"=>"rpcEntry"),
			array("name"=>"rRASAdministrationConnectionPoint",					"parent_class"=>"serviceAdministrationPoint"),
			array("name"=>"rRASAdministrationDictionary"),
			array("name"=>"samDomain",								"class_type"=>"auxiliary"),
			array("name"=>"samDomainBase",								"class_type"=>"auxiliary"),
			array("name"=>"samServer",			"icon"=>"microsoft/sam-server.png",	"parent_class"=>"securityObject"),
			array("name"=>"secret",				"icon"=>"password.png",			"parent_class"=>"leaf"),
			array("name"=>"securityObject",			"icon"=>"generic24.png",		"class_type"=>"abstract"),
			array("name"=>"securityPrincipal",							"class_type"=>"auxiliary"),
			array("name"=>"server",				"icon"=>"server.png",			"is_folder"=>false,"display_name"=>gettext("Server")),
			array("name"=>"serversContainer",		"icon"=>"folder.png",			"is_folder"=>true,"display_name"=>gettext("Servers Container")),
			array("name"=>"serviceAdministrationPoint",						"display_name"=>gettext("Service"),"parent_class"=>"serviceConnectionPoint"),
			array("name"=>"serviceClass",								"parent_class"=>"leaf"),
			array("name"=>"serviceConnectionPoint",							"parent_class"=>"connectionPoint"),
			array("name"=>"serviceInstance",							"parent_class"=>"connectionPoint"),
			array("name"=>"site",				"icon"=>"microsoft/site.png",		"is_folder"=>true,"display_name"=>gettext("Site")),
			array("name"=>"siteLink",			"icon"=>"microsoft/site-link.png",	"is_folder"=>false,"display_name"=>gettext("Site Link")),
			array("name"=>"siteLinkBridge",			"icon"=>"microsoft/site-link.png",	"is_folder"=>false,"display_name"=>gettext("Site Link Bridge")),
			array("name"=>"sitesContainer",			"icon"=>"folder.png",			"is_folder"=>true,"display_name"=>gettext("Sites Container")),
			array("name"=>"storage",								"parent_class"=>"connectionPoint"),
			array("name"=>"subnet",				"icon"=>"microsoft/subnet.png",		"is_folder"=>false,"display_name"=>gettext("Subnet")),
			array("name"=>"subnetContainer",		"icon"=>"folder.png",			"is_folder"=>true,"display_name"=>gettext("Subnets Container")),
			array("name"=>"trustedDomain",			"icon"=>"domain24.png",			"display_name"=>gettext("Trusted Domain"),"parent_class"=>"leaf"),
			array("name"=>"typeLibrary"),
			array("name"=>"user",				"icon"=>"user24.png",			"is_folder"=>false,"display_name"=>gettext("User"),"can_create"=>true,"parent_class"=>"organizationalPerson"),
			array("name"=>"volume",				"icon"=>"microsoft/fileshare24.png",	"is_folder"=>false,"display_name"=>gettext("Shared Folder"),"can_create"=>true,"parent_class"=>"connectionPoint"),

			// undefined object classes which have placeholder display specifiers
			array("name"=>"localPolicy",			"icon"=>"generic24.png",		"display_name"=>gettext("Local Policy")),
			array("name"=>"nTDSSettings",			"icon"=>"generic24.png",		"display_name"=>gettext("Settings"))
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
			array("section_name"=>gettext("Active Directory Domain"),
				"attributes"=>array(
					array("distinguishedName",		gettext("Domain Name"),				"domain24.png")
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

		$ldap_server->add_display_layout("packageRegistration",array(
			array("section_name"=>gettext("Product Information"),"new_row"=>true,
				"attributes"=>array(
					array("packageName",			gettext("Name"),				"generic24.png"),
					array("versionNumberHi",		gettext("Major Version Number"),		"generic24.png"),
					array("versionNumberLo",		gettext("Minor Version Number"),		"generic24.png"),
					array("localeID",			gettext("Language"),				"generic24.png"),
					array("machineArchitecture",		gettext("Platform"),				"generic24.png"),
					array("url",				gettext("Support URL"),				"generic24.png")
					)
				),
			array("section_name"=>gettext("Deployment Information"),"new_row"=>true,
				"attributes"=>array(
					array("msiFileList",			gettext("Deployment Source"),			"generic24.png")
					)
				),
			array("section_name"=>gettext("Diagnostic Information"),"new_row"=>true,
				"attributes"=>array(
					array("productCode",			gettext("Product Code"),			"generic24.png"),
					array("msiScriptPath",			gettext("Script Name"),				"generic24.png")
					)
				),
			array("section_name"=>gettext("Package Upgrade Relationships"),"new_row"=>true,
				"attributes"=>array(
					array("canUpgradeScript")
					)
				),
			array("section_name"=>gettext("Category GUIDs"),"new_row"=>true,
				"attributes"=>array(
					array("categories")
					)
				),
			array("section_name"=>gettext("Other"),"new_row"=>true,
				"attributes"=>array(
					array("packageFlags",			gettext("Package Flags"),			"generic24.png"),
					array("packageType",			gettext("Package Type"),			"generic24.png")
					)
				)
			));

		$ldap_server->add_display_layout("msWMI-Som",array(
			array("section_name"=>gettext("WMI Filter Information"),"new_row"=>true,
				"attributes"=>array(
					array("msWMI-Name",			gettext("Name"),				"generic24.png"),
					array("msWMI-ID",			gettext("Instance ID"),				"generic24.png"),
					array("msWMI-Author",			gettext("Author"),				"generic24.png"),
					array("msWMI-CreationDate",		gettext("Creation Date"),			"date-time.png"),
					array("msWMI-ChangeDate",		gettext("Last Modified"),			"date-time.png")
					)
				),
			array("section_name"=>gettext("Parameters"),"new_row"=>true,
				"attributes"=>array(
					array("msWMI-Parm1",			gettext("Parameter 1"),				"generic24.png"), // description
					array("msWMI-Parm2",			gettext("Parameter 2"),				"generic24.png"), // queries
					array("msWMI-Parm3",			gettext("Parameter 3"),				"generic24.png"),
					array("msWMI-Parm4",			gettext("Parameter 4"),				"generic24.png")
					)
				)
			));

		$ldap_server->add_display_layout("nTDSConnection",array(
			array("section_name"=>gettext("Replication Connection Settings"),
				"attributes"=>array(
					array("cn",				gettext("Object Name"),				"id.png"),
					array("description",			gettext("Description"),				"description.png"),
					array("transportType",			gettext("Transport"),				"alias.png"),
					array("schedule",			gettext("Schedule"),				"date.png"),
					array("fromServer",			gettext("Replicate From"),			"alias.png"),
					array("enabledConnection",		gettext("Available for Use"),			"generic24.png"),
					array("options",			gettext("Options"),				"generic24.png"),
					array("mS-DS-ReplicatesNCReason",	gettext("Usefulness to Replication Topology"),	"generic24.png")
					)
				)
			));

		$ldap_server->add_display_layout("nTDSDSA",array(
			array("section_name"=>gettext("Domain Controller Settings"),
				"attributes"=>array(
					array("cn",				gettext("Object Name"),				"id.png"),
					array("description",			gettext("Description"),				"description.png"),
					array("queryPolicyObject",		gettext("Query Policy"),			"alias.png"),
					array("options",			gettext("Global Catalog"),			"generic24.png"),
					)
				),
			array("section_name"=>gettext("Directory Database"),"new_row"=>true,
				"attributes"=>array(
					array("msDS-Behavior-Version",		gettext("Domain/Forest Behavior Version"),	"generic24.png"),
					array("invocationId",			gettext("Invocation ID Data"),			"generic24.png"),
					array("hasMasterNCs",			gettext("Naming Contexts Served"),		"alias.png"),
						// replaced by msDS-hasMasterNCs on newer DCs; maintained for backwards compatibility

					array("msDS-HasDomainNCs",		gettext("Domain Naming Contexts"),		"alias.png"),
					array("dMDLocation",			gettext("Schema Partition"),			"alias.png"),
					)
				),
			array("section_name"=>gettext("Naming Context Replication Status"),"new_row"=>true,
				"attributes"=>array(
					array("msDS-HasInstantiatedNCs"),
					)
				),
			array("section_name"=>gettext("Connections"),"new_row"=>true,
				"attributes"=>array(
					array("__CHILD_OBJECTS__")
					)
				)
			));

		$ldap_server->add_display_layout("server",array(
			array("section_name"=>gettext("Server Object"),
				"attributes"=>array(
					array("cn",				gettext("Object Name"),				"id.png"),
					array("description",			gettext("Description"),				"description.png"),
					array("dNSHostName",			gettext("DNS Host Name"),			"generic24.png"),
					array("serverReference",		gettext("Computer Object"),			"alias.png"),
					array("bridgeheadTransportList",	gettext("Bridgehead Transports"),		"alias.png"),
					)
				),
			array("section_name"=>gettext("Child Objects"),"new_row"=>true,
				"attributes"=>array(
					array("__CHILD_OBJECTS__")
					)
				)
			));

		$ldap_server->add_display_layout("user,inetOrgPerson",array(
			array("section_name"=>gettext("Personal"),
				"attributes"=>array(
					array("givenName",			gettext("Given Name"),				"contact24.png","allow_view"=>false),
			//		array("initials",			gettext("Initials"),				"contact24.png","allow_view"=>false),
					array("sn",				gettext("Surname"),				"contact24.png","allow_view"=>false),
					array("cn",				gettext("Full Name"),				"contact24.png","allow_view"=>false),
					array("displayName",			gettext("Preferred Name"),			"contact24.png"),
					array("mail",				gettext("E-mail"),				"mail.png"),
					array("homePhone",			gettext("Home Phone"),				"landline-phone.png"),
			//		array("pager",				gettext("Pager"),				"generic24.png"),
					array("mobile",				gettext("Mobile Phone"),			"cell-phone.png"),
			//		array("preferredLanguage",		gettext("Preferred Language"),			"chat.png"),
			//		array("preferredDeliveryMethod",	gettext("Preferred Delivery Method"),		"generic24.png"),
					array("wWWHomePage",			gettext("Web Page"),				"internet.png"),

					// Address of user or Internet/Intranet Organizational Person
					// Several potential address layouts are possible, depending on country-specific
					// convention and individual preference.
					// If used, the postalCode attribute can control a link to a web-based map
					// service which shows the organizational unit's location.

					// Component-based address representation:
					array("streetAddress:l:st:postalCode",	gettext("Postal Address"),			"address.png"),

					// Component-based address representation including a field for PO Box
					// array("postOfficeBox:street:l:st:postalCode",gettext("Postal Address"),		"address.png"),

					// Addresses combined into a single attribute:
					// array("homePostalAddress",		gettext("Home Address"),			"address.png"),
					// array("postalAddress",		gettext("Postal Address"),			"address.png"),

					array("c",				gettext("Country"),				"country.png"),
			//		array("carLicense",			gettext("Car License Plate"),			"generic24.png"),
					array("jpegPhoto",			gettext("Photo"),				"photo24.png")
					)
				),
			array("section_name"=>gettext("Business/Work"),"width"=>"50%",
				"attributes"=>array(
					array("company",			gettext("Company"),				"company.png"),
					// array("businessCategory",		gettext("Business Category"),			"company.png"),
					array("url",				gettext("Web Page"),				"internet.png"),
					array("thumbnailLogo",			gettext("Logo"),				"logo24.png"),
					array("telephoneNumber",		gettext("Office Phone"),			"landline-phone.png"),
					array("facsimileTelephoneNumber",	gettext("Office Fax"),				"fax.png"),
					// array("internationaliSDNNumber",	gettext("ISDN"),				"landline-phone.png"),
					array("title",				gettext("Job Title"),				"org-role.png"),
			//		array("employeeNumber",			gettext("Employee Number"),			"id.png"),
			//		array("employeeType",			gettext("Employee Type"),			"generic24.png"),
			//		array("roomNumber",			gettext("Room Number"),				"room.png"),
			//		array("manager",			gettext("Manager"),				"org-role.png"),
			//		array("secretary",			gettext("Secretary"),				"org-role.png"),
					array("department",			gettext("Department"),				"org.png"),

					// If your directory's address fields contain a business address rather than a
					// home address then you may want to move the address from the "Personal" section
					// of the display layout (above) to here. Another possible setup is:
					//
					//	homePostalAddress - Used for personal/home address
					//	postalAddress - Used for business/work address

					// array("postalAddress",		gettext("Postal Address"),			"address.png"),

			//		array("departmentNumber",		gettext("Department Number"),			"org.png"),
					array("physicalDeliveryOfficeName",	gettext("Office"),				"office.png")
					)
				),

			array("section_name"=>gettext("Login Details"),"new_row"=>true,"colspan"=>2,
				"attributes"=>array(
					array("sAMAccountName",			gettext("User ID"),				"id.png"),
			//		array("unicodePwd",			gettext("Password"),				"password.png"),
					array("userPrincipalNameName",		gettext("User Principal Name"),			"id.png"),
					)
				),

			array("section_name"=>gettext("Group Membership"),"new_row"=>true,"colspan"=>2,
				"attributes"=>array(
					array("memberOf",			null,						null,"allow_edit"=>false)
					)
				),
			// Uncomment this section in order to display certificate data.
			// (You may wish to remove/comment out those fields which are
			// not used in your directory.)

			/*
			array("section_name"=>gettext("Certificate Data"),"new_row"=>true,"colspan"=>2,
				"attributes"=>array(
					array("userCertificate;binary",		gettext("User Certificate"),			"cert.png"),
					array("userSMIMECertificate;binary",	gettext("S/MIME Certificate"),			"cert.png"),
					array("userPKCS12;binary",		gettext("PKCS #12 PFX Data"),			"key-material.png")
					)
				),
			*/
			array("section_name"=>gettext("Additional Notes"),"new_row"=>true,"colspan"=>2,
				"attributes"=>array(
					array("info")
					)
				),

			/*
			array("section_name"=>gettext("See Also"),"new_row"=>true,"colspan"=>2,
				"attributes"=>array(
					array("seeAlso")
					)
				)
			*/
			));

		$ldap_server->add_display_layout("contact",array(
			array("section_name"=>gettext("Personal"),
				"attributes"=>array(
					array("givenName",			gettext("Given Name"),				"contact24.png","allow_view"=>false),
			//		array("initials",			gettext("Initials"),				"contact24.png","allow_view"=>false),
					array("sn",				gettext("Surname"),				"contact24.png","allow_view"=>false),
					array("cn",				gettext("Full Name"),				"contact24.png","allow_view"=>false),
					array("displayName",			gettext("Preferred Name"),			"contact24.png"),
					array("mail",				gettext("E-mail"),				"mail.png"),
					array("homePhone",			gettext("Home Phone"),				"landline-phone.png"),
			//		array("pager",				gettext("Pager"),				"generic24.png"),
					array("mobile",				gettext("Mobile Phone"),			"cell-phone.png"),
			//		array("preferredDeliveryMethod",	gettext("Preferred Delivery Method"),		"generic24.png"),
					array("wWWHomePage",			gettext("Web Page"),				"internet.png"),

					// Address of contact record
					// Several potential address layouts are possible, depending on country-specific
					// convention and individual preference.
					// If used, the postalCode attribute can control a link to a web-based map
					// service which shows the organizational unit's location.

					// Component-based address representation:
					array("streetAddress:l:st:postalCode",	gettext("Postal Address"),			"address.png"),

					// Component-based address representation including a field for PO Box
					// array("postOfficeBox:street:l:st:postalCode",gettext("Postal Address"),		"address.png"),

					// Addresses combined into a single attribute:
					// array("homePostalAddress",		gettext("Home Address"),			"address.png"),
					// array("postalAddress",		gettext("Postal Address"),			"address.png"),

					array("c",				gettext("Country"),				"country.png"),
					array("jpegPhoto",			gettext("Photo"),				"photo24.png")
					)
				),
			array("section_name"=>gettext("Business/Work"),"width"=>"50%",
				"attributes"=>array(
					array("company",			gettext("Company"),				"company.png"),
					array("url",				gettext("Web Page"),				"internet.png"),
					array("thumbnailLogo",			gettext("Logo"),				"logo24.png"),
					array("telephoneNumber",		gettext("Office Phone"),			"landline-phone.png"),
					array("facsimileTelephoneNumber",	gettext("Office Fax"),				"fax.png"),
					// array("internationaliSDNNumber",	gettext("ISDN"),				"landline-phone.png"),
					array("title",				gettext("Job Title"),				"org-role.png"),
			//		array("manager",			gettext("Manager"),				"org-role.png"),
			//		array("secretary",			gettext("Secretary"),				"org-role.png"),
					array("department",			gettext("Department"),				"org.png"),

					// If your directory's address fields contain a business address rather than a
					// home address then you may want to move the address from the "Personal" section
					// of the display layout (above) to here. Another possible setup is:
					//
					//	homePostalAddress - Used for personal/home address
					//	postalAddress - Used for business/work address

					// array("postalAddress",		gettext("Postal Address"),			"address.png"),

					array("physicalDeliveryOfficeName",	gettext("Office"),				"office.png")
					)
				),

			// Uncomment this section in order to display group membership information

			/*
			array("section_name"=>gettext("Group Membership"),"new_row"=>true,"colspan"=>2,
				"attributes"=>array(
					array("memberOf",			null,						null,"allow_edit"=>false)
					)
				),
			*/

			// Uncomment this section in order to display certificate data.
			// (You may wish to remove/comment out those fields which are
			// not used in your directory.)

			/*
			array("section_name"=>gettext("Certificate Data"),"new_row"=>true,"colspan"=>2,
				"attributes"=>array(
					array("userCertificate;binary",		gettext("User Certificate"),			"cert.png"),
					array("userSMIMECertificate;binary",	gettext("S/MIME Certificate"),			"cert.png"),
					)
				),
			*/

			array("section_name"=>gettext("Additional Notes"),"new_row"=>true,"colspan"=>2,
				"attributes"=>array(
					array("info")
					)
				),

			/*
			array("section_name"=>gettext("See Also"),"new_row"=>true,"colspan"=>2,
				"attributes"=>array(
					array("seeAlso")
					)
				)
			*/
			));

		$ldap_server->add_display_layout("infrastructureUpdate",array(
			array("section_name"=>gettext("Infrastructure Operations Master Role Holder"),
				"attributes"=>array(
					array("fSMORoleOwner",				gettext("Operations Master DSA for this Domain"),				"alias.png","allow_edit"=>false),
					)
				)
			));

		$ldap_server->add_display_layout("rIDManager",array(
			array("section_name"=>gettext("RID Operations Master Role Holder"),
				"attributes"=>array(
					array("fSMORoleOwner",				gettext("Operations Master DSA for this Domain"),				"alias.png","allow_edit"=>false)
					)
				),
			array("section_name"=>gettext("Available RID Pool"),"new_row"=>true,
				"attributes"=>array(
					array("rIDAvailablePool")
					)
				)
			));

		$ldap_server->add_display_layout("trustedDomain",array(
			array("section_name"=>gettext("Trusted Domain"),
				"attributes"=>array(
					array("trustPartner",			gettext("DNS Name"),				"generic24.png"),
					array("flatName",			gettext("Flat (NetBIOS) Name"),			"generic24.png"),
					array("trustPosixOffset",		gettext("POSIX UID/GID Offset"),		"id.png")
					)
				),
			array("section_name"=>gettext("Trust Settings"),"new_row"=>true,
				"attributes"=>array(
					array("trustType",			gettext("Trust Type"),				"generic24.png"),
					array("trustDirection",			gettext("Direction"),				"generic24.png"),
					array("trustAttributes",		gettext("Attributes"),				"generic24.png"),
					array("msDS-TrustForestTrustInfo",	gettext("Forest Trust Data"),			"generic24.png")
					)
				),
			array("section_name"=>gettext("Supported Encryption/Authentication Types"),"new_row"=>true,
				"attributes"=>array(
					array("msDs-supportedEncryptionTypes")
					)
				)
			));

		// component schema
		$ldap_server->add_schema("microsoft/exchange");
		$ldap_server->add_schema("microsoft/laps");
		$ldap_server->add_schema("microsoft/sms");
		$ldap_server->add_schema("microsoft/std");
		$ldap_server->add_schema("microsoft/system");

		parent::__construct($ldap_server);
	}

	/** Assign default values for new printQueue objects */

        function populate_for_create_printQueue(&$ldap_server,&$entry)
        {
                $this->add_attrib_value($ldap_server,$entry,"printColor","FALSE");
                $this->add_attrib_value($ldap_server,$entry,"printDuplexSupported","FALSE");
                $this->add_attrib_value($ldap_server,$entry,"printStaplingSupported","FALSE");
        }
}
?>
