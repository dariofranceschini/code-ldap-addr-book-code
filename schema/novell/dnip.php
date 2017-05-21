<?php
/** Novell Domain Name/IP Address Management (DNS, NetWare DHCP) schema (partial)

    Novell DHCP Server (for NetWare) was replaced by an LDAP-enabled ISC DHCP Server
    in OpenEnterprise Server (for Linux). The DHCP portion of DNIP is superceded by
    the ISC DHCP schema.

    The DNIP schema was submitted as an IETF Internet Draft (see below) but was
    not carried forward to become an RFC or Standard.

    @see https://tools.ietf.org/html/draft-miller-dhcp-ldap-schema-00
    @see https://tools.ietf.org/html/draft-miller-dns-ldap-schema-00
*/

class novell_dnip_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"dnipGroupReference",		"data_type"=>"dn_list",		"display_name"=>gettext("DNS/DHCP Group Object Name")),
			array("name"=>"dnipDNSZones",			"data_type"=>"dn_list",		"display_name"=>gettext("DNS Zone Object Names")),
			array("name"=>"dnipSubnetAttr",			"data_type"=>"dn_list",		"display_name"=>gettext("DHCP Subnet Attribute Object Names")),
			array("name"=>"dnipComment",			"data_type"=>"text_area",	"display_name"=>gettext("Comments")),
			array("name"=>"dnipAliasedObjectName",		"data_type"=>"dn_list",		"display_name"=>gettext("Associated NDS Object")),
			array("name"=>"dnipSubnetPoolReference",	"data_type"=>"dn_list",		"display_name"=>gettext("Subnet Pool Reference")),
			array("name"=>"dnipSubnetAttr",			"data_type"=>"dn_list",		"display_name"=>gettext("Subnet Objects")),
			array("name"=>"dnipLocatorPtr",			"data_type"=>"dn",		"display_name"=>gettext("DNS/DHCP Locator DN")),
			array("name"=>"dnipSecondaryZone",		"data_type"=>"yes_no",		"display_name"=>gettext("DNS Secondary Zone")),
			);

		// Object classes
		$this->object_schema = array(
			array("name"=>"dNIPLocator",			"icon"=>"dhcp/dhcp-locator.png",		"is_folder"=>false,"display_name"=>gettext("Locator")),
			array("name"=>"dNIPDNSZone",			"icon"=>"novell/dnip-dns-zone.png",		"is_folder"=>true,"display_name"=>gettext("DNS Zone")),
			array("name"=>"dNIPDNSRRset",			"icon"=>"document-series.png",			"is_folder"=>false,"display_name"=>gettext("DNS RRset")),
			array("name"=>"dNIPSubnet",			"icon"=>"dhcp/dhcp-subnet.png",			"is_folder"=>true,"display_name"=>gettext("Subnet")),
			array("name"=>"dNIPSubnetPool",			"icon"=>"dhcp/dhcp-shared-network.png",		"is_folder"=>false,"display_name"=>gettext("Subnet Pool")),
			array("name"=>"dNIPSubnetAddressRange",		"icon"=>"novell/dnip-subnet-address-range.png",	"is_folder"=>false,"display_name"=>gettext("Subnet Address Range")),
			array("name"=>"dNIPIPAddressConfiguration",	"icon"=>"dhcp/dhcp-host.png",			"is_folder"=>false,"display_name"=>gettext("IP Address Configuration")),
			array("name"=>"dNIPDNSKey",			"icon"=>"dhcp/dhcp-dns-key.png",		"is_folder"=>false,"display_name"=>gettext("DNIP:DNS Key")),
			array("name"=>"dNIPDHCPServer",			"icon"=>"dhcp/dhcp-server.png",			"is_folder"=>false,"display_name"=>gettext("DHCP Server (NetWare)")),
			);

		// Display layouts
		$ldap_server->add_display_layout("dNIPDHCPServer",array(
			array("section_name"=>gettext("NetWare DHCP Server Object"),
				"attributes"=>array(
					array("cn",				gettext("Name"),				"generic24.png"),
					array("dnipDHCPVersion",		gettext("Version"),				"generic24.png")
					)
				)
			));

		$ldap_server->add_display_layout("dNIPDNSZone",array(
			array("section_name"=>gettext("DNS Zone"),
				"attributes"=>array(
					array("cn",				gettext("Object Name"),				"generic24.png"),
					array("dnipSecondaryZone",		gettext("Secondary Zone"),			"generic24.png")
					)
				),
			array("section_name"=>gettext("Start of Authority Record"),"new_row"=>true,"width"=>"50%",
				"attributes"=>array(
					array("dnipZoneDomainName",		gettext("Fully Qualified Domain Name"),		"generic24.png"),
					array("dnipSOAAdminMailbox",		gettext("Admin Mailbox"),			"generic24.png"),
					array("dnipSOAZoneMaster",		gettext("Zone Master"),				"generic24.png"),
					array("dnipSOASerial",			gettext("Zone Serial Number"),			"generic24.png"),
					array("dnipSOARefresh",			gettext("Refresh Interval (s)"),		"generic24.png"),
					array("dnipSOARetry",			gettext("Refresh Retry Interval (s)"),		"generic24.png"),
					array("dnipSOAMinimum",			gettext("Minimum Record TTL for Zone (s)"),	"generic24.png"),
					array("dnipSOAExpire",			gettext("Authority Expiry Time (s)"),		"generic24.png")
					)
				)
			));

		$ldap_server->add_display_layout("dNIPDNSKey",array(
			array("section_name"=>gettext("DNS Transaction Signature (TSIG) Key"),"new_row"=>true,
				"attributes"=>array(
					array("dnipDNSKeyAlgorithm",		gettext("Algorithm"),				"generic24.png"),
					array("dnipDNSKeySecret",		gettext("Secret"),				"generic24.png"),
					)
				),
			array("section_name"=>gettext("Comments"),"new_row"=>true,
				"attributes"=>array(
					array("dnipComment")
					)
				)
			));

		$ldap_server->add_display_layout("dNIPDNSRRset",array(
			array("section_name"=>gettext("DNS Resource Record Set"),
				"attributes"=>array(
					array("dnipDNSDomainName",		gettext("Domain Name"),				"generic24.png"),
					array("dnipAliasedObjectName",		gettext("Associated NDS Object"),		"generic24.png"),
					array("dnipRRStatus",			gettext("Status"),				"generic24.png"),
					array("description",			gettext("Comments"),				"generic24.png"),
					)
				),

			// binary attribute data - could do with data type handler to display it properly
			array("section_name"=>gettext("Resource Data"),"new_row"=>true,
				"attributes"=>array(
					array("dnipRR"),
					)
				)
			));

		$ldap_server->add_display_layout("dNIPLocator",array(
			array("section_name"=>gettext("DNS/DHCP Group Object"),
				"attributes"=>array(
					array("dnipGroupReference"),
					)
				),
			array("section_name"=>gettext("DNS Objects"),"new_row"=>true,
				"attributes"=>array(
					array("dnipDNSZones",			gettext("Zones"),				"generic24.png")
					)
				),
			array("section_name"=>gettext("DHCP Objects"),"new_row"=>true,
				"attributes"=>array(
					array("dnipSubnetAttr",			gettext("Subnet Attributes"),			"generic24.png")
					)
				)
			));

		$ldap_server->add_display_layout("dNIPSubnet",array(
			array("section_name"=>gettext("NetWare DNS/DHCP Subnet"),
				"attributes"=>array(
					array("dnipSubnetPoolReference",	gettext("Subnet Pool Reference"),		"generic24.png"),
					array("dnipSubnetAddress",		gettext("Subnet Address"),			"generic24.png"),
					array("dnipSubnetMask",			gettext("Subnet Mask"),				"generic24.png"),
					array("dnipSubnetType",			gettext("Subnet Type"),				"generic24.png"),
					array("dnipLeaseTime",			gettext("IP Address Lease Time (s)"),		"generic24.png"),
					array("dnipDomainName",			gettext("Domain Name"),				"generic24.png")
				//	array("dnipBootParameter",		gettext("Boot Parameters"),			"generic24.png")
					)
				),
			array("section_name"=>gettext("Comments"),"new_row"=>true,
				"attributes"=>array(
					array("dnipComment")
					)
				),
			));

		$ldap_server->add_display_layout("dNIPSubnetPool",array(
			array("section_name"=>gettext("NetWare DNS/DHCP Subnet Pool"),
				"attributes"=>array(
					array("dnipSubnetType",			gettext("Type"),				"generic24.png"),
					)
				),
			array("section_name"=>gettext("Subnet Objects"),"new_row"=>true,
				"attributes"=>array(
					array("dnipSubnetAttr")
					)
				),
			));

		parent::__construct($ldap_server);
	}
}
?>
