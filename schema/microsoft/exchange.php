<?php
/** Microsoft Exchange schema (partial) */

class microsoft_exchange_schema extends ldap_schema
{
        function __construct(&$ldap_server)
        {
		// Object classes
		$this->object_schema = array(
			array("name"=>"msExchDynamicDistributionList",		"icon"=>"microsoft/dynamic-group24.png",	"is_folder"=>false,"display_name"=>gettext("Query-based Distribution Group"),"can_create"=>true),
			array("name"=>"msExchOrganizationContainer",		"icon"=>"microsoft/exchange-org.png",		"is_folder"=>true,"display_name"=>gettext("Exchange Organization")),
			array("name"=>"msExchOAB",				"icon"=>"microsoft/offline-addr-book.png",	"is_folder"=>false,"display_name"=>gettext("Offline Address List")),
			array("name"=>"msExchSystemObjectsContainer",		"icon"=>"folder.png",				"is_folder"=>true),
			array("name"=>"msExchAdminGroupContainer",		"icon"=>"folder.png",				"is_folder"=>true),
			array("name"=>"msExchApprovalApplicationContainer",	"icon"=>"folder.png",				"is_folder"=>true),
			array("name"=>"msExchAuthAuthConfig",			"icon"=>"folder.png",				"is_folder"=>true),
			array("name"=>"msExchAvailabilityConfig",		"icon"=>"folder.png",				"is_folder"=>true),
			array("name"=>"msExchExchangeAssistance",		"icon"=>"folder.png",				"is_folder"=>true),
			array("name"=>"msExchFedOrgId",				"icon"=>"folder.png",				"is_folder"=>true),
			array("name"=>"msExchMobileMailboxSettings",		"icon"=>"folder.png",				"is_folder"=>true),
			array("name"=>"msExchRecipientPolicyContainer",		"icon"=>"folder.png",				"is_folder"=>true),
			array("name"=>"msExchSystemPolicyContainer",		"icon"=>"folder.png",				"is_folder"=>true),
			array("name"=>"msExchTransportSettings",		"icon"=>"folder.png",				"is_folder"=>true),
			array("name"=>"msExchContainer",			"icon"=>"folder.png",				"is_folder"=>true),
			array("name"=>"msExchThrottlingPolicy",			"icon"=>"folder.png",				"is_folder"=>true),
			array("name"=>"msExchAdminAuditLogConfig",		"icon"=>"folder.png",				"is_folder"=>true),
			array("name"=>"msExchResourceSchema",			"icon"=>"folder.png",				"is_folder"=>true),
			array("name"=>"msExchMDBAvailabilityGroupContainer",	"icon"=>"folder.png",				"is_folder"=>true),
			array("name"=>"msExchMDBContainer",			"icon"=>"folder.png",				"is_folder"=>true),
			array("name"=>"msExchRoutingGroupContainer",		"icon"=>"folder.png",				"is_folder"=>true),
			array("name"=>"msExchServersContainer",			"icon"=>"folder.png",				"is_folder"=>true),
			array("name"=>"msExchConnectors",			"icon"=>"folder.png",				"is_folder"=>true),
			array("name"=>"msExchSMTPTurfList",			"icon"=>"folder.png",				"is_folder"=>true),
			array("name"=>"msExchProtocolCfgHTTPContainer",		"icon"=>"folder.png",				"is_folder"=>true),
			array("name"=>"msExchProtocolCfgIMAPContainer",		"icon"=>"folder.png",				"is_folder"=>true),
			array("name"=>"msExchProtocolCfgPOPContainer",		"icon"=>"folder.png",				"is_folder"=>true),
			array("name"=>"msExchProtocolCfgSIPContainer",		"icon"=>"folder.png",				"is_folder"=>true),
			array("name"=>"msExchProtocolCfgSMTPContainer",		"icon"=>"folder.png",				"is_folder"=>true),
			array("name"=>"msExchExchangeTransportCfgContainer",	"icon"=>"folder.png",				"is_folder"=>true),
			array("name"=>"protocolCfgSharedServer",		"icon"=>"folder.png",				"is_folder"=>true),
			array("name"=>"publicFolder",				"icon"=>"microsoft/public-folder.png",		"is_folder"=>false,"display_name"=>gettext("Public Folder")),
			array("name"=>"addrType",				"icon"=>"microsoft/address-type.png",		"is_folder"=>false,"display_name"=>gettext("Address Type")),
			array("name"=>"msExchAdminGroup",			"icon"=>"microsoft/exchange-admin-group.png",	"is_folder"=>true,"display_name"=>gettext("Administrative Group")),
			array("name"=>"msExchRoutingGroup",			"icon"=>"microsoft/routing-group.png",		"is_folder"=>true,"display_name"=>gettext("Routing Group")),
			array("name"=>"msExchRoutingSMTPConnector",		"icon"=>"microsoft/smtp-connector.png",		"is_folder"=>false,"display_name"=>gettext("SMTP Connector")),
			array("name"=>"msExchContentConfigContainer",		"icon"=>"microsoft/internet-msg-formats.png",	"is_folder"=>true,"display_name"=>gettext("Internet Message Formats")),
			array("name"=>"msExchOmaConfigurationContainer",	"icon"=>"microsoft/wireless-carriers.png",	"is_folder"=>true,"display_name"=>gettext("Wireless Carriers")),
			array("name"=>"msExchMessageDeliveryConfig",		"icon"=>"microsoft/smtp-virtual-server.png",	"is_folder"=>true,"display_name"=>gettext("Message Delivery Configuration")),
			array("name"=>"msExchIMGlobalSettingsContainer",	"icon"=>"microsoft/im-global-settings.png",	"is_folder"=>true,"display_name"=>gettext("Instant Messaging Global Settings")),
			array("name"=>"msExchAdvancedSecurityContainer",	"icon"=>"microsoft/advanced-security.png",	"is_folder"=>true,"display_name"=>gettext("Advanced Security")),
			array("name"=>"msExchExchangeTransportServer",		"icon"=>"microsoft/exchange-server.png",	"is_folder"=>true),
			array("name"=>"msExchExchangeServer",			"icon"=>"microsoft/exchange-server.png",	"is_folder"=>true,"display_name"=>gettext("Exchange Server")),
			array("name"=>"msExchRecipientPolicy",			"icon"=>"microsoft/recipient-policy.png",	"is_folder"=>false,"display_name"=>gettext("Recipient Policy")),
			array("name"=>"msExchSystemPolicy",			"icon"=>"microsoft/policy.png",			"is_folder"=>false,"display_name"=>gettext("System Policy")),
			array("name"=>"addressTemplate",			"icon"=>"microsoft/addr-template.png",		"is_folder"=>false,"display_name"=>gettext("Address Template")),
			array("name"=>"displayTemplate",			"icon"=>"config-file.png",			"is_folder"=>false,"display_name"=>gettext("Display Template")),
			array("name"=>"mTA",					"icon"=>"microsoft/mta.png",			"is_folder"=>false,"display_name"=>gettext("Message Transfer Agent")),
			array("name"=>"exchangeAdminService",			"icon"=>"microsoft/exchange-admin-service.png",	"is_folder"=>false,"display_name"=>gettext("System Attendant")),
			array("name"=>"siteAddressing",				"icon"=>"microsoft/policy.png",			"is_folder"=>false,"display_name"=>gettext("Site Addressing")),
			array("name"=>"msExchPFTree",				"icon"=>"microsoft/public-folder-hierarchy.png","is_folder"=>false,"display_name"=>gettext("Public Folder Top Level Hierarchy")),
			array("name"=>"protocolCfgIMAPServer",			"icon"=>"microsoft/imap-virtual-server.png",	"is_folder"=>false,"display_name"=>gettext("IMAP Virtual Server")),
			array("name"=>"protocolCfgPOPServer",			"icon"=>"microsoft/pop-virtual-server.png",	"is_folder"=>false,"display_name"=>gettext("POP Virtual Server")),
			array("name"=>"protocolCfgSMTPServer",			"icon"=>"microsoft/smtp-virtual-server.png",	"is_folder"=>true,"display_name"=>gettext("SMTP Virtual Server")),
			array("name"=>"protocolCfgSMTPDomainContainer",		"icon"=>"microsoft/smtp-domain.png",		"is_folder"=>true,"display_name"=>gettext("SMTP Domains")),
			array("name"=>"protocolCfgSMTPRoutingSources",		"icon"=>"microsoft/smtp-domain.png",		"is_folder"=>false,"display_name"=>gettext("SMTP Routing Sources")),
			array("name"=>"protocolCfgSMTPSessions",		"icon"=>"user24.png",				"is_folder"=>false,"display_name"=>gettext("SMTP Sessions")),
			array("name"=>"encryptionCfg",				"icon"=>"microsoft/encryption-config.png",	"is_folder"=>false,"display_name"=>gettext("Encryption Configuration")),
			array("name"=>"msExchInformationStore",			"icon"=>"microsoft/information-store.png",	"is_folder"=>true,"display_name"=>gettext("Information Store")),
			array("name"=>"msExchPrivateMDB",			"icon"=>"microsoft/private-mdb.png",		"is_folder"=>true,"display_name"=>gettext("Private Information Store")),
			array("name"=>"msExchPublicMDB",			"icon"=>"microsoft/public-mdb.png",		"is_folder"=>false,"display_name"=>gettext("Public Information Store")),
			array("name"=>"msExchStorageGroup",			"icon"=>"microsoft/storage-group.png",		"is_folder"=>true,"display_name"=>gettext("Storage Group")),
			array("name"=>"msExchDomainContentConfig",		"icon"=>"microsoft/domain-content-config.png",	"is_folder"=>false)
			);

		parent::__construct($ldap_server);
	}
}
?>
