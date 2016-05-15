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
			array("name"=>"dnipLocatorPtr",			"data_type"=>"dn_list",		"display_name"=>gettext("DNS/DHCP Locator DN")),
			array("name"=>"dnipSecondaryZone",		"data_type"=>"yes_no",		"display_name"=>gettext("DNS Secondary Zone")),
			);

		// Structural object classes
		$this->object_schema = array(
			array("name"=>"dNIPLocator",			"icon"=>"dhcp/dhcp-locator.png",		"is_folder"=>false,"display_name"=>gettext("Locator")),
			array("name"=>"dNIPDNSZone",			"icon"=>"novell/dnip-dns-zone.png",		"is_folder"=>true,"display_name"=>gettext("DNS Zone")),
			array("name"=>"dNIPDNSRRset",			"icon"=>"document-series.png",			"is_folder"=>false,"display_name"=>gettext("DNS RRset")),
			array("name"=>"dNIPSubnet",			"icon"=>"dhcp/dhcp-subnet.png",			"is_folder"=>true,"display_name"=>gettext("Subnet")),
			array("name"=>"dNIPSubnetPool",			"icon"=>"dhcp/dhcp-shared-network.png",		"is_folder"=>false,"display_name"=>gettext("Subnet Pool")),
			array("name"=>"dNIPSubnetAddressRange",		"icon"=>"novell/dnip-subnet-address-range.png",	"is_folder"=>false,"display_name"=>gettext("Subnet Address Range")),
			array("name"=>"dNIPIPAddressConfiguration",	"icon"=>"dhcp/dhcp-host.png",			"is_folder"=>false,"display_name"=>gettext("IP Address Configuration")),
			array("name"=>"dNIPDNSKey",			"icon"=>"dhcp/dhcp-dns-key.png",		"is_folder"=>false,"display_name"=>gettext("DNIP:DNS Key")),
			array("name"=>"dNIPDHCPServer",			"icon"=>"dhcp/dhcp-server.png",			"is_folder"=>false,"display_name"=>gettext("DHCP Server")),
			);

		parent::__construct($ldap_server);
	}
}
?>
