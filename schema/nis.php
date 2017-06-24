<?php
/** LDAP Network Information Service schema (nis.schema)

    NIS, also known as Yellow Pages (YP) is a directory service
    for system configuration data, originally developed by Sun
    Microsystems. The NIS LDAP schema, described in
    IETF RFC 2307, allows NIS data to be stored and retrived
    using LDAP.

    @see http://www.ietf.org/rfc/rfc2307.txt

    The following references contain background information on
    the types of data that can be represented in NIS.

    @see http://www.ietf.org/rfc/rfc791.txt
    @see http://www.ietf.org/rfc/rfc1057.txt
    @see http://www.ietf.org/rfc/rfc1831.txt
    @see http://www.ietf.org/rfc/rfc5531.txt

    Note: Description attributes of ipProtocol and oncRPC are
    listed as both optional AND required in RFC 2307 (corrected
    to just optional in RFC 2307bis). LDAP Address Book needs to
    handle them as required in order to create objects of these
    classes in OpenLDAP.
*/

class nis_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"bootFile",		"data_type"=>"text",		"display_name"=>gettext("Boot Image File Name")),
			array("name"=>"bootParameter",		"data_type"=>"text_list",	"display_name"=>gettext("Boot Parameter")),
			array("name"=>"gecos",			"data_type"=>"text",		"display_name"=>gettext("GECOS Field")),
			array("name"=>"gidNumber",		"data_type"=>"text",		"display_name"=>gettext("Group ID Number")),
			array("name"=>"homeDirectory",		"data_type"=>"text",		"display_name"=>gettext("Home Directory Path")),
			array("name"=>"ipHostNumber",		"data_type"=>"text",		"display_name"=>gettext("IPv4 Address")),
			array("name"=>"ipNetmaskNumber",	"data_type"=>"text",		"display_name"=>gettext("IPv4 Subnet Mask")),
			array("name"=>"ipNetworkNumber",	"data_type"=>"text",		"display_name"=>gettext("IPv4 Network Address")),
			array("name"=>"ipProtocolNumber",	"data_type"=>"text",		"display_name"=>gettext("IP Protocol Number")),
			array("name"=>"ipServicePort",		"data_type"=>"text",		"display_name"=>gettext("Port Number")),
			array("name"=>"ipServiceProtocol",	"data_type"=>"text_list",	"display_name"=>gettext("IP Protocol Name")),
			array("name"=>"loginShell",		"data_type"=>"text",		"display_name"=>gettext("Login Shell Path")),
			array("name"=>"macAddress",		"data_type"=>"text",		"display_name"=>gettext("MAC Address")),
			array("name"=>"memberNisNetgroup",	"data_type"=>"text",		"display_name"=>gettext("Member Netgroup")),
			array("name"=>"memberUid",		"data_type"=>"text_list",	"display_name"=>gettext("Member User ID")),
			array("name"=>"nisMapEntry",		"data_type"=>"text",		"display_name"=>gettext("NIS Map Entry")),
			array("name"=>"nisMapName",		"data_type"=>"text",		"display_name"=>gettext("NIS Map Name")),
			array("name"=>"nisNetgroupTriple",	"data_type"=>"text_list",	"display_name"=>gettext("Host/User/Domain Triple")),
			array("name"=>"oncRpcNumber",		"data_type"=>"text",		"display_name"=>gettext("ONC RPC Number")),
			array("name"=>"shadowExpire",		"data_type"=>"text",		"display_name"=>gettext("Expiry Date")),
			array("name"=>"shadowFlag",		"data_type"=>"text"),		// attribute reserved for future use
			array("name"=>"shadowInactive",		"data_type"=>"text",		"display_name"=>gettext("Time Until Account Inactive (Days)")),
			array("name"=>"shadowLastChange",	"data_type"=>"text",		"display_name"=>gettext("Date of Last Password Change")),
			array("name"=>"shadowMax",		"data_type"=>"text",		"display_name"=>gettext("Maximum Password Age (Days)")),
			array("name"=>"shadowMin",		"data_type"=>"text",		"display_name"=>gettext("Minimum Password Age (Days)")),
			array("name"=>"shadowWarning",		"data_type"=>"text",		"display_name"=>gettext("Warning Period Before Expiration (Days)")),
			array("name"=>"uidNumber",		"data_type"=>"text",		"display_name"=>gettext("User ID Number"))
			);

		// Object classes
		$this->object_schema = array(
			array("name"=>"ipNetwork",		"icon"=>"ip-network.png",		"is_folder"=>false,"display_name"=>gettext("IP Network"),"required_attribs"=>"ipNetworkNumber"),
			array("name"=>"ipProtocol",		"icon"=>"ip-protocol.png",		"is_folder"=>false,"display_name"=>gettext("IP Protocol"),"required_attribs"=>"ipProtocolNumber,description"),
			array("name"=>"ipService",		"icon"=>"ip-service.png",		"is_folder"=>false,"display_name"=>gettext("IP Service"),"required_attribs"=>"ipServicePort,ipServiceProtocol"),
			array("name"=>"nisMap",			"icon"=>"nis-map.png",			"is_folder"=>true,"display_name"=>gettext("NIS Map"),"rdn_attrib"=>"nisMapName"),
			array("name"=>"nisNetgroup",		"icon"=>"nis-netgroup.png",		"is_folder"=>false,"display_name"=>gettext("Netgroup")),
			array("name"=>"nisObject",		"icon"=>"nis-object.png",		"is_folder"=>false,"display_name"=>gettext("NIS Map Entry"),"required_attribs"=>"nisMapEntry,nisMapName"),
			array("name"=>"oncRpc",			"icon"=>"onc-rpc.png",			"is_folder"=>false,"display_name"=>gettext("ONC RPC Binding"),"required_attribs"=>"oncRpcNumber,description"),
			array("name"=>"posixGroup",		"icon"=>"group24.png",			"is_folder"=>false,"display_name"=>gettext("POSIX Group"),"required_attribs"=>"gidNumber")
			);

		// The auxiliary classes defined in this schema are:
		//
		//	bootableDevice
		//	ieee802Device
		//	ipHost
		//	posixAccount
		//	shadowAccount

		// Display layouts
		$ldap_server->add_display_layout("posixGroup",array(
			array("colspan"=>2,"new_row"=>true,
				"attributes"=>array(
					array("cn",				gettext("Group Name"),			"group24.png"),
					array("gidNumber",			gettext("Group ID Number"),		"id.png"),
					array("description",			gettext("Description"),			"description.png")

					// array("userPassword",		gettext("Password"),			"generic24.png")
					)
				),
			array("section_name"=>gettext("Group Members"),"new_row"=>true,
				"attributes"=>array(
					array("memberUid")
					)
				)
			));

		parent::__construct($ldap_server);
	}
}
?>
