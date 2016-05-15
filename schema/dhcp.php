<?php
/** Internet Software Consortium DHCP Server schema (partial) */

class dhcp_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"dhcpLocatorDN",			"data_type"=>"dn",		"display_name"=>gettext("DHCP Locator DN")),
			array("name"=>"dhcpServiceDN",			"data_type"=>"dn_list",		"display_name"=>gettext("DHCP Service DN")),
			array("name"=>"dhcpServerDN",			"data_type"=>"dn_list",		"display_name"=>gettext("DHCP Server DN")),
			array("name"=>"dhcpStatements",			"data_type"=>"text_list",	"display_name"=>gettext("DHCP Configuration Statements")),
			array("name"=>"dhcpOption",			"data_type"=>"text_list",	"display_name"=>gettext("DHCP Option List")),
			array("name"=>"dhcpKeyDN",			"data_type"=>"dn_list",		"display_name"=>gettext("DHCP TSIG Key DN"))
			);

		// Structural object classes
		$this->object_schema = array(
			array("name"=>"dhcpFailOverPeer",		"icon"=>"dhcp/dhcp-failover-peer.png",	"is_folder"=>false,"display_name"=>gettext("DHCP Failover Peer")),
			array("name"=>"dhcpDnsZone",			"icon"=>"dhcp/dhcp-dns-zone.png",	"is_folder"=>false,"display_name"=>gettext("DHCP DNS Zone")),
			array("name"=>"dhcpClass",			"icon"=>"dhcp/dhcp-class.png",		"is_folder"=>false,"display_name"=>gettext("DHCP Class")),
			array("name"=>"dhcpService",			"icon"=>"dhcp/dhcp-service.png",	"is_folder"=>true,"display_name"=>gettext("DHCP Service")),
			array("name"=>"dhcpLocator",			"icon"=>"dhcp/dhcp-locator.png",	"is_folder"=>false,"display_name"=>gettext("DHCP Locator")),
			array("name"=>"dhcpHost",			"icon"=>"dhcp/dhcp-host.png",		"is_folder"=>false,"display_name"=>gettext("DHCP Host/IP Address")),
			array("name"=>"dhcpServer",			"icon"=>"dhcp/dhcp-server.png",		"is_folder"=>false,"display_name"=>gettext("DHCP Server")),
			array("name"=>"dhcpSubnet",			"icon"=>"dhcp/dhcp-subnet.png",		"is_folder"=>false,"display_name"=>gettext("DHCP Subnet")),
			array("name"=>"dhcpSharedNetwork",		"icon"=>"dhcp/dhcp-shared-network.png",	"is_folder"=>true,"display_name"=>gettext("DHCP Shared Network")),
			array("name"=>"dhcpTSigKey",			"icon"=>"dhcp/dhcp-dns-key.png",	"is_folder"=>false,"display_name"=>gettext("DHCP TSIG Key"))
			);

		parent::__construct($ldap_server);
	}
}
?>
