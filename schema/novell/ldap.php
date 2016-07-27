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

		parent::__construct($ldap_server);
	}
}
