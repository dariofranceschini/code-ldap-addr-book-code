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

		// Object classes
		$this->object_schema = array(
			array("name"=>"dhcpClass",			"icon"=>"dhcp/dhcp-class.png",		"is_folder"=>false,"display_name"=>gettext("DHCP Class")),
			array("name"=>"dhcpDnsZone",			"icon"=>"dhcp/dhcp-dns-zone.png",	"is_folder"=>false,"display_name"=>gettext("DHCP DNS Zone")),
			array("name"=>"dhcpFailOverPeer",		"icon"=>"dhcp/dhcp-failover-peer.png",	"is_folder"=>false,"display_name"=>gettext("DHCP Failover Peer")),
			array("name"=>"dhcpHost",			"icon"=>"dhcp/dhcp-host.png",		"is_folder"=>false,"display_name"=>gettext("DHCP Host/IP Address")),
			array("name"=>"dhcpLocator",			"icon"=>"dhcp/dhcp-locator.png",	"is_folder"=>false,"display_name"=>gettext("DHCP Locator")),
			array("name"=>"dhcpOptions",			"icon"=>"dhcp/dhcp-options.png",	"class_type"=>"auxiliary","display_name"=>gettext("DHCP Options"),"required_attribs"=>"cn"),
			array("name"=>"dhcpServer",			"icon"=>"dhcp/dhcp-server.png",		"is_folder"=>false,"display_name"=>gettext("DHCP Server")),
			array("name"=>"dhcpService",			"icon"=>"dhcp/dhcp-service.png",	"is_folder"=>true,"display_name"=>gettext("DHCP Service")),
			array("name"=>"dhcpSharedNetwork",		"icon"=>"dhcp/dhcp-shared-network.png",	"is_folder"=>true,"display_name"=>gettext("DHCP Shared Network")),
			array("name"=>"dhcpSubnet",			"icon"=>"dhcp/dhcp-subnet.png",		"is_folder"=>false,"display_name"=>gettext("DHCP Subnet")),
			array("name"=>"dhcpTSigKey",			"icon"=>"dhcp/dhcp-dns-key.png",	"is_folder"=>false,"display_name"=>gettext("DHCP TSIG Key"))
			);

		// Display layouts
		$ldap_server->add_display_layout("dhcpClass",array(
			array("section_name"=>gettext("DHCP Class Configuration"),"new_row"=>true,
				"attributes"=>array(
					array("dhcpStatements")
					)
				),
			array("section_name"=>gettext("DHCP Options"),"new_row"=>true,
				"attributes"=>array(
					array("dhcpOption")
					)
				),
			array("section_name"=>gettext("Comments"),"new_row"=>true,
				"attributes"=>array(
					array("dhcpComments")
					)
				)
			));

		$ldap_server->add_display_layout("dhcpDnsZone",array(
			array("section_name"=>gettext("DHCP Dynamic DNS Update Settings"),"new_row"=>true,
				"attributes"=>array(
					array("dhcpDNSZoneServer",		gettext("DNS Server"),			"generic24.png"),
					array("dhcpKeyDN",			gettext("TSIG Key"),			"generic24.png")
					)
				),
			array("section_name"=>gettext("Comments"),"new_row"=>true,
				"attributes"=>array(
					array("dhcpComments")
					)
				)
			));

		$ldap_server->add_display_layout("dhcpFailOverPeer",array(
			array("section_name"=>gettext("DHCP Failover Peer"),"colspan"=>2,
				"attributes"=>array(
					array("dhcpFailOverLoadBalanceTime",	gettext("Load Balance Time (s)"),	"time.png"),
					array("dhcpFailOverSplit",		gettext("Split"),			"generic24.png"),
					array("dhcpMaxClientLeadTime",		gettext("Maximum Client Lead Time (s)"),"time.png"),
					array("dhcpFailOverResponseDelay",	gettext("Response Delay (s)"),		"time.png"),
					array("dhcpFailOverUnackedUpdates",	gettext("Unacknowledged Updates"),	"generic24.png")
					)
				),
			array("section_name"=>gettext("Primary"),"new_row"=>true,
				"attributes"=>array(
					array("dhcpFailOverPrimaryServer",	gettext("Server"),			"generic24.png"),
					array("dhcpFailOverPrimaryPort",	gettext("Port"),			"generic24.png"),
					)
				),
			array("section_name"=>gettext("Secondary"),"width"=>"50%",
				"attributes"=>array(
					array("dhcpFailOverSecondaryServer",	gettext("Server"),			"generic24.png"),
					array("dhcpFailOverSecondaryPort",	gettext("Port"),			"generic24.png"),
					)
				),
			array("section_name"=>gettext("Comments"),"new_row"=>true,"colspan"=>2,
				"attributes"=>array(
					array("dhcpComments")
					)
				)
			));

		$ldap_server->add_display_layout("dhcpHost",array(
			array("section_name"=>gettext("Host Information"),
				"attributes"=>array(
					array("dhcpHWAddress",			gettext("Hardware Address"),		"generic24.png"),
					)
				),
			array("section_name"=>gettext("DHCP Server Configuration"),"new_row"=>true,
				"attributes"=>array(
					array("dhcpStatements")
					)
				)
			));

		$ldap_server->add_display_layout("dhcpLocator",array(
			array("section_name"=>gettext("DHCP Object Locations"),
				"attributes"=>array(
					array("dhcpServerDN",			gettext("Server Objects"),		"server-alias.png"),
					array("dhcpServiceDN",			gettext("Service Objects"),		"alias.png")
					)
				),
			));

		$ldap_server->add_display_layout("dhcpServer",array(
			array("section_name"=>("Associated Objects"),
				"attributes"=>array(
					array("dhcpLocatorDN",			gettext("DHCP Locator Object"),		"generic24.png"),
					array("dhcpServiceDN",			gettext("DHCP Service Object"),		"generic24.png")
					)
				),
			array("section_name"=>gettext("DHCP Server Configuration"),"new_row"=>true,
				"attributes"=>array(
					array("dhcpStatements")
					)
				)
			));

		$ldap_server->add_display_layout("dhcpSharedNetwork",array(
			array("section_name"=>gettext("Shared Network Configuration"),"new_row"=>true,
				"attributes"=>array(
					array("dhcpStatements")
					)
				),
			array("section_name"=>gettext("DHCP Options"),"new_row"=>true,
				"attributes"=>array(
					array("dhcpOption")
					)
				),
			array("section_name"=>gettext("Comments"),"new_row"=>true,
				"attributes"=>array(
					array("dhcpComments")
					)
				)
			));

		$ldap_server->add_display_layout("dhcpSubnet",array(
			array("section_name"=>gettext("Subnet Information"),
				"attributes"=>array(
					array("dhcpNetmask",			gettext("Netmask"),			"generic24.png"),
					)
				),
			array("section_name"=>gettext("DHCP Server Configuration"),"new_row"=>true,
				"attributes"=>array(
					array("dhcpStatements")
					)
				),
			array("section_name"=>gettext("Comments"),"new_row"=>true,
				"attributes"=>array(
					array("dhcpComments")
					)
				)
			));

		$ldap_server->add_display_layout("dhcpTSigKey",array(
			array("section_name"=>gettext("DHCP Transaction Signature (TSIG) Key"),"new_row"=>true,
				"attributes"=>array(

					array("dhcpKeyAlgorithm",		gettext("Algorithm"),			"generic24.png"),
					array("dhcpKeySecret",			gettext("Secret"),			"generic24.png"),
					)
				),
			array("section_name"=>gettext("Comments"),"new_row"=>true,
				"attributes"=>array(
					array("dhcpComments")
					)
				)
			));

		// Auxiliary class display layouts

		$ldap_server->add_display_layout("dhcpOptions",array(
			array("section_name"=>gettext("DHCP Options"),
				"attributes"=>array(
					array("cn",				gettext("Object Name"),			"generic24.png"),
					array("dhcpOption",			gettext("DHCP Options"),		"dhcp/dhcp-options.png"),
					array("dhcpComments",			gettext("Comments"),			"description.png")
					)
				)
			));

		parent::__construct($ldap_server);
	}
}
?>
