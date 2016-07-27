<?php
/** Novell LDAP Agent for Novell eDirectory schema (partial) */

class novell_ldap_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"ldapServerList",				"data_type"=>"dn_list","display_name"=>gettext("LDAP Server List")),
			array("name"=>"ldapAnonymousIdentity",			"data_type"=>"dn_list","display_name"=>gettext("LDAP Anonymous Identity")),
			array("name"=>"ldapAllowClearTextPassword",		"data_type"=>"yes_no","display_name"=>gettext("LDAP Allow Clear Text Password")),
			array("name"=>"ldapClassList",				"data_type"=>"text_list","display_name"=>gettext("LDAP Class List")),
			array("name"=>"ldapAttributeList",			"data_type"=>"text_list","display_name"=>gettext("LDAP Attribute List")),
			array("name"=>"ldapGroupDN",				"data_type"=>"dn_list","display_name"=>gettext("LDAP Group DN")),
			array("name"=>"ldapHostServer",				"data_type"=>"dn_list","display_name"=>gettext("LDAP Server Host")),
			array("name"=>"ldapDerefAliasOnAuth",			"data_type"=>"yes_no","display_name"=>gettext("Dereference Aliases on Authentication")),
			array("name"=>"ldapDerefAlias",				"data_type"=>"yes_no","display_name"=>gettext("Dereference Aliases")),
			array("name"=>"ldapTLSRequired",			"data_type"=>"yes_no","display_name"=>gettext("TLS Required")),
			array("name"=>"ldapEnableSSL",				"data_type"=>"yes_no","display_name"=>gettext("Enable TLS")),
			array("name"=>"ldapEnableTCP",				"data_type"=>"yes_no","display_name"=>gettext("Enable TCP (non-TLS)")),
			array("name"=>"ldapEnablePSearch",			"data_type"=>"yes_no","display_name"=>gettext("Enable Persistent Search")),
			array("name"=>"ldapIgnorePSearchLimitsForEvents",	"data_type"=>"yes_no","display_name"=>gettext("Ignore Persistent Search Limits for Events")),
			array("name"=>"ldapNonStdAllUserAttrsMode",		"data_type"=>"yes_no","display_name"=>gettext("Non-Standard All User Attributes Mode")),
			array("name"=>"ldapEnableMonitorEvents",		"data_type"=>"yes_no","display_name"=>gettext("Enable Monitor Events")),
			array("name"=>"nonStdClientSchemaCompatMode",		"data_type"=>"yes_no","display_name"=>gettext("Non-Standard Client Schema Compatibility Mode")),
			array("name"=>"ldapServerDN",				"data_type"=>"dn_list","display_name"=>gettext("LDAP Server DN")),

			// Approximate data types only - specific decoders needed
			array("name"=>"extensionInfo",				"data_type"=>"text_list","display_name"=>gettext("LDAP Extension List")),
			array("name"=>"filteredReplicaUsage",			"data_type"=>"text","display_name"=>gettext("Filtered Replica Usage")),
			);

		// Structural object classes
		$this->object_schema = array(
			array("name"=>"ldapServer",			"icon"=>"ldap-server.png",		"is_folder"=>false,"display_name"=>gettext("LDAP Server"),"can_create"=>true),
			array("name"=>"ldapGroup",			"icon"=>"novell/ldap-group.png",	"is_folder"=>false,"display_name"=>gettext("LDAP Group"),"can_create"=>true),
			);

		// Display layouts
		$ldap_server->add_display_layout("ldapServer",array(
			array("section_name"=>gettext("LDAP Server Details"),"new_row"=>true,
				"attributes"=>array(
					array("version",				gettext("Product Version"),				"generic24.png"),
					array("ldapHostServer",				gettext("Host"),					"generic24.png"),
					array("ldapGroupDN",				gettext("LDAP Group"),					"generic24.png"),
					)
				),
			array("section_name"=>gettext("Non-Standard Behaviors"),"new_row"=>true,
				"attributes"=>array(
					array("ldapDerefAliasOnAuth",			gettext("Dereference Aliases when Resolving Names for Authentication"),"generic24.png"),
					array("ldapDerefAlias",				gettext("Dereference Aliases when Resolving Names on All Operations"),"generic24.png"),
					)
				),
			array("section_name"=>gettext("Transport Layer Security (TLS/SSL)"),"new_row"=>true,
				"attributes"=>array(
					array("ldapKeyMaterialName",			gettext("Server Certificate"),				"generic24.png"),
					array("ldapTLSVerifyClientCertificate",		gettext("Client Certificate"),				"generic24.png"),
					array("ldapTLSRequired",			gettext("Require TLS for All Operations"),		"generic24.png"),
					)
				),
			array("section_name"=>gettext("Ports"),"new_row"=>true,
				"attributes"=>array(
					array("ldapEnableSSL",				gettext("Enable Encrypted Port"),			"generic24.png"),
					array("ldapSSLPort",				gettext("Encrypted Port"),				"generic24.png"),
					array("ldapEnableTCP",				gettext("Enable Non-Encrypted Port"),			"generic24.png"),
					array("ldapTCPPort",				gettext("Non-Encrypted Port"),				"generic24.png"),
					)
				),
			array("section_name"=>gettext("LDAP Bulk Update/Replication Protocol (LBURP)"),"new_row"=>true,
				"attributes"=>array(
					array("ldapLBURPNumWriterThreads",		gettext("Number of Writer Threads"),			"generic24.png"),
					)
				),
			array("section_name"=>gettext("LDAP Server"),"new_row"=>true,
				"attributes"=>array(
					array("ldapInterfaces",				gettext("LDAP Interfaces"),				"generic24.png"),
					array("ldapServerBindLimit",			gettext("Concurrent Bind Limit"),			"generic24.png"),
					array("ldapServerIdleTimeout",			gettext("Idle Timeout (s)"),				"generic24.png"),
					array("ldapBindRestrictions",			gettext("Bind Restrictions"),				"generic24.png"),
					)
				),
			array("section_name"=>gettext("Searches"),"new_row"=>true,
				"attributes"=>array(
					array("ldapEnablePSearch",			gettext("Enable Persistent Search"),			"generic24.png"),
					array("ldapMaximumPSearchOperations",		gettext("Maximum Concurrent Persistent Searches"),	"generic24.png"),
					array("ldapIgnorePSearchLimitsForEvents",	gettext("Ignore Size and Time Limits when Monitoring Persistent Search Events"),"generic24.png"),
					array("searchTimeLimit",			gettext("Search Time Limit"),				"generic24.png"),
					array("searchSizeLimit",			gettext("Result Size Limit (Number of Entries)"),	"generic24.png"),
					array("ldapNonStdAllUserAttrsMode",		gettext("Return Operational Attributes when all User Attributes are Requested"),"generic24.png"),
					array("nonStdClientSchemaCompatMode",		gettext("Enable old ADSI and Netscape Schema Output"),	"generic24.png"),
					)
				),
			array("section_name"=>gettext("Event Monitoring and Tracing"),"new_row"=>true,
				"attributes"=>array(
					array("ldapEnableMonitorEvents",		gettext("Enable Event Monitoring"),			"generic24.png"),
					array("ldapMaximumMonitorEventsLoad",		gettext("Maximum Event Monitoring Load (operations)"),	"generic24.png"),
					array("ldapTraceLevel",				gettext("Tracing Options"),				"generic24.png"),
					)
				),
			array("section_name"=>gettext("Referrals"),"new_row"=>true,
				"attributes"=>array(
					array("ldapReferral",				gettext("Default Referral URL"),			"generic24.png"),
					array("ldapDefaultReferralBehavior",		gettext("Conditions Which Return Default Referral"),	"generic24.png"),
					array("ldapSearchReferralUsage",		gettext("Use Referrals for Searches"),			"generic24.png"),
					array("ldapOtherReferralUsage",			gettext("Use Referrals for Other eDirectory Operations"),"generic24.png")
					)
				),

			/** @todo Decode extensionInfo array - list of OIDs */

			/*
			array("section_name"=>gettext("LDAP Extensions List"),"new_row"=>true,
				"attributes"=>array(
					array("extensionInfo"),
					)
				),
			*/

			/** @todo Decode filteredReplicaUsage */

			/*
			array("section_name"=>gettext("Filtered Replica Usage"),"new_row"=>true,
				"attributes"=>array(
					array("filteredReplicaUsage"),
					)
				),
			*/

			array("section_name"=>gettext("Config Version"),"new_row"=>true,
				"attributes"=>array(
					array("ldapConfigVersion")
					)
				)
			));

		$ldap_server->add_display_layout("ldapGroup",array(
			array("section_name"=>gettext("LDAP Server List"),"new_row"=>true,
				"attributes"=>array(
					array("ldapServerList"),
					)
				),
			array("section_name"=>gettext("Authentication Options"),"new_row"=>true,
				"attributes"=>array(
					array("ldapAnonymousIdentity",		gettext("Proxy User (Anonymous Identity)"),			"generic24.png"),
					array("ldapAllowClearTextPassword",	gettext("Require TLS for Simple Binds with Password"),		"generic24.png")
					)
				),
			array("section_name"=>gettext("Referrals"),"new_row"=>true,
				"attributes"=>array(
					array("ldapReferral",			gettext("Default Referral URL"),				"generic24.png"),
					array("ldapDefaultReferralBehavior",	gettext("Conditions Which Return Default Referral"),		"generic24.png"),
					array("ldapSearchReferralUsage",	gettext("Use Referrals for Searches"),				"generic24.png"),
					array("ldapOtherReferralUsage",		gettext("Use Referrals for Other eDirectory Operations"),	"generic24.png")
					)
				),
			array("section_name"=>gettext("NDS to LDAP Object Mappings"),"new_row"=>true,
				"attributes"=>array(
					array("ldapClassList")
					)
				),
			array("section_name"=>gettext("NDS to LDAP Attribute Mappings"),"new_row"=>true,
				"attributes"=>array(
					array("ldapAttributeList")
					)
				),
			array("section_name"=>gettext("Config Version"),"new_row"=>true,
				"attributes"=>array(
					array("ldapConfigVersion")
					)
				)
			));

		parent::__construct($ldap_server);
	}
}
