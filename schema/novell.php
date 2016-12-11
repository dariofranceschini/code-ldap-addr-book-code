<?php
/** Novell eDirectory base schema (partial) */

class novell_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			// matches core.schema
			array("name"=>"c",				"data_type"=>"country_code",	"display_name"=>gettext("Country Code")),
			array("name"=>"cn",				"data_type"=>"text",		"display_name"=>gettext("Common Name/Full Name")),
			array("name"=>"description",			"data_type"=>"text",		"display_name"=>gettext("Description")),
			array("name"=>"facsimileTelephoneNumber",	"data_type"=>"text",		"display_name"=>gettext("Fax Number")),
			array("name"=>"givenName",			"data_type"=>"text",		"display_name"=>gettext("Given Name")),
			array("name"=>"l",				"data_type"=>"text",		"display_name"=>gettext("Locality (e.g. Town/City)")),
			array("name"=>"mail",				"data_type"=>"text",		"display_name"=>gettext("E-mail Address")),
			array("name"=>"member",				"data_type"=>"dn_list",		"display_name"=>gettext("Member")),
			array("name"=>"o",				"data_type"=>"text",		"display_name"=>gettext("Organization Name")),
			array("name"=>"ou",				"data_type"=>"text",		"display_name"=>gettext("Organizational Unit Name")),
			array("name"=>"physicalDeliveryOfficeName",	"data_type"=>"text",		"display_name"=>gettext("Office")),
			array("name"=>"postalCode",			"data_type"=>"postcode",	"display_name"=>gettext("Postal Code")),
			array("name"=>"postOfficeBox",			"data_type"=>"text",		"display_name"=>gettext("Postal Office Box")),
			array("name"=>"roleOccupant",			"data_type"=>"dn_list",		"display_name"=>gettext("Role Occupant")),
			array("name"=>"sn",				"data_type"=>"text",		"display_name"=>gettext("Surname")),
			array("name"=>"st",				"data_type"=>"text",		"display_name"=>gettext("State (or Province/County)")),
			array("name"=>"street",				"data_type"=>"text_area",	"display_name"=>gettext("Street Address")),	// a.k.a. streetAddress
			array("name"=>"telephoneNumber",		"data_type"=>"phone_number",	"display_name"=>gettext("Telephone Number")),
			array("name"=>"title",				"data_type"=>"text",		"display_name"=>gettext("Job Title")),

			// Novell proprietary classes
			array("name"=>"groupMembership",		"data_type"=>"dn_list",		"display_name"=>gettext("Group Membership")),
			array("name"=>"hostServer",			"data_type"=>"dn",		"display_name"=>gettext("Host Server")),
			array("name"=>"loginScript",			"data_type"=>"text_area",	"display_name"=>gettext("Login Script")),
			array("name"=>"ndsCrossCertificatePair",	"data_type"=>"download",	"display_name"=>gettext("Cross Certificate Pair (NDS)")),
			);

		// Structural object classes
		$this->object_schema = array(
			// matches core.schema
			// (Class names for organization, country, locality are capitalised in their Novell versions)
			array("name"=>"organizationalUnit",		"icon"=>"folder.png",			"is_folder"=>true,"rdn_attrib"=>"ou","display_name"=>gettext("Organizational Unit"),"can_create"=>true),
			array("name"=>"Organization",			"icon"=>"org.png",			"is_folder"=>true,"rdn_attrib"=>"o","display_name"=>gettext("Organization"),"can_create"=>true),
			array("name"=>"Country",			"icon"=>"country.png",			"is_folder"=>true,"rdn_attrib"=>"c","display_name"=>gettext("Country"),"can_create"=>true),
			array("name"=>"Locality",			"icon"=>"locality.png",			"is_folder"=>true,"rdn_attrib"=>"l","display_name"=>gettext("Locality"),"can_create"=>true),
			array("name"=>"groupOfNames",			"icon"=>"group24.png",			"is_folder"=>false,"display_name"=>gettext("Group"),"can_create"=>true),
			array("name"=>"organizationalRole",		"icon"=>"org-role.png",			"is_folder"=>false,"display_name"=>gettext("Organizational Role"),"can_create"=>true),

			// matches inetorgperson.schema other than addition of display_name "User"
			// (inetOrgPerson LDAP class maps to eDirectory User class)
			array("name"=>"inetOrgPerson",			"icon"=>"user24.png",			"is_folder"=>false,"display_name"=>gettext("User"),"required_attribs"=>"sn","can_create"=>true),

			// base schema - equivalents to core.schema classes
			//
			// (Person should be listed after inetOrgPerson but before externalEntity
			// for correct inheritence behaviour)
			array("name"=>"Person",				"icon"=>"contact24.png",		"is_folder"=>false,"display_name"=>gettext("Person"),"required_attribs"=>"sn","can_create"=>true),

			// Novell proprietary classes
			array("name"=>"externalEntity",			"icon"=>"novell/external-entity24.png",	"is_folder"=>false,"display_name"=>gettext("External Entity"),"can_create"=>true),
			array("name"=>"Profile",			"icon"=>"novell/profile.png",		"is_folder"=>false,"display_name"=>gettext("Profile")),
			array("name"=>"Volume",				"icon"=>"novell/volume.png",		"is_folder"=>false,"display_name"=>gettext("Volume")),
			array("name"=>"Queue",				"icon"=>"novell/queue.png",		"is_folder"=>false,"display_name"=>gettext("Queue")),
			array("name"=>"directoryMap",			"icon"=>"novell/directory-map.png",	"is_folder"=>false,"display_name"=>gettext("Directory Map")),
			array("name"=>"ncpServer",			"icon"=>"server.png",			"is_folder"=>false,"display_name"=>gettext("NCP Server")),
			array("name"=>"aliasObject",			"icon"=>"alias.png",			"is_folder"=>false,"display_name"=>gettext("Alias"),"required_attribs"=>"aliasedObjectName"),
			);

		// Display layouts
		$ldap_server->add_display_layout("directoryMap",array(
			array("section_name"=>gettext("Directory Map Target"),
				"attributes"=>array(
					array("hostServer",			gettext("Host Server"),			"generic24.png"),
					array("path",				gettext("Volume and Path"),		"generic24.png"),
					)
				),
			array("section_name"=>gettext("Other Information"),"new_row"=>true,
				"attributes"=>array(
					array("l",				gettext("Location"),			"generic24.png"),
					array("ou",				gettext("Department"),			"generic24.png"),
					array("o",				gettext("Organization"),		"generic24.png"),
					array("description",			gettext("Description"),			"generic24.png"),
					array("seeAlso",			gettext("See Also"),			"generic24.png")
					)
				),
			));

		$ldap_server->add_display_layout("groupOfNames",array(
			array("colspan"=>2,"new_row"=>true,
				"attributes"=>array(
					array("cn",				gettext("Group Name"),			"group24.png",true),
					)
				),
			array("section_name"=>gettext("Group Members"),"colspan"=>2,"new_row"=>true,
				"attributes"=>array(
					array("member")
					)
				),
			array("section_name"=>gettext("Additional Notes"),"new_row"=>true,"colspan"=>2,
				"attributes"=>array(
					array("info")
					)
				)
			));

		$ldap_server->add_display_layout("Organization",array(
			array("section_name"=>gettext("Folder Details"),
				"attributes"=>array(
					array("o",				gettext("Organization Name"),		"folder.png"),
					)
				)
			));

		$ldap_server->add_display_layout("organizationalUnit",array(
			array("section_name"=>gettext("Folder Details"),
				"attributes"=>array(
					array("ou",				gettext("OU Name"),			"folder.png"),
					array("description",			gettext("Description"),			"description.png"),
					array("street:physicalDeliveryOfficeName:st:postalCode",
										gettext("Postal Address"),		"address.png"),
					)
				)
			));

		$ldap_server->add_display_layout("Country",array(
			array("section_name"=>gettext("Folder Details"),
				"attributes"=>array(
					array("c",				gettext("Country Code"),		"country.png"),
					array("description",			gettext("Description"),			"description.png"),
					)
				)
			));

		$ldap_server->add_display_layout("Profile",array(
			array("section_name"=>gettext("Login Script"),
				"attributes"=>array(
					array("loginScript"),
					)
				),
			array("section_name"=>gettext("Other Information"),"new_row"=>true,
				"attributes"=>array(
					array("l",				gettext("Location"),			"generic24.png"),
					array("ou",				gettext("Department"),			"generic24.png"),
					array("o",				gettext("Organization"),		"generic24.png"),
					array("description",			gettext("Description"),			"generic24.png"),
					array("seeAlso",			gettext("See Also"),			"generic24.png")
					)
				),
			));

		$ldap_server->add_display_layout("ncpServer",array(
			array("section_name"=>gettext("Server Information"),"new_row"=>true,
				"attributes"=>array(
					array("cn",				gettext("Server Name"),			"generic24.png",true),
		//			array("networkAddress",			gettext("Network Address"),		"generic24.png"),
					array("status",				gettext("Status"),			"generic24.png"),
					array("version",			gettext("Version"),			"generic24.png"),
					array("languageId",			gettext("Language ID"),			"generic24.png"),
					array("ncpKeyMaterialName",		gettext("Server Certificate"),		"generic24.png"),
					array("dnipDNSServerVersion",		gettext("DNS Server Version"),		"generic24.png"),
					array("dsRevision",			gettext("DS Revision"),			"generic24.png"),
					array("seeAlso",			gettext("See Also"),			"generic24.png"),
					)
				),
			array("section_name"=>gettext("Associated Service Objects"),"width"=>"50%",
				"attributes"=>array(
					array("dnipLocatorPtr",			gettext("DNS/DHCP Locator"),		"generic24.png"),
					array("snmpGroupDN",			gettext("SNMP Group"),			"generic24.png"),
					array("sasServiceDN",			gettext("SAS Service"),			"generic24.png"),
					array("httpServerDN",			gettext("HTTP Server (iMonitor)"),	"generic24.png"),
					array("ldapServerDN",			gettext("LDAP Server"),			"generic24.png"),
					array("ndsPredicateStatsDN",		gettext("Predicate Stats"),		"generic24.png"),
					array("encryptionPolicyDN",		gettext("Encryption Policy"),		"generic24.png"),
					)
				),
			array("section_name"=>gettext("Directory Index Definitions"),"new_row"=>true,"colspan"=>2,
				"attributes"=>array(
					array("indexDefinition")
					)
				),
			array("section_name"=>gettext("NMAS Cached Attributes on External References (CAER)"),"new_row"=>true,"colspan"=>2,
				"attributes"=>array(
					array("CachedAttrsOnExtRefs"),
					)
				),
			array("section_name"=>gettext("CIFS Native File Access Package"),"new_row"=>true,"colspan"=>2,
				"attributes"=>array(
					array("nfapCIFSServerName",		gettext("CIFS Server Name")),
					array("nfapCIFSWorkGroup",		gettext("Workgroup")),
					array("nfapCIFSComment",		gettext("Comment")),
					array("nfapCIFSShares",			gettext("Shares")),
					array("nfapCIFSShareVolsByDefault",	gettext("Share Volumes by Default")),
					array("nfapCIFSDFS",			gettext("DFS")),
					array("nfapCIFSDialect",		gettext("SMB/CIFS Dialect")),
					array("nfapCIFSUnicode",		gettext("Unicode Support")),
					array("nfapCIFSOpLocks",		gettext("OpLocks Support")),
					array("nfapCIFSAuthent",		gettext("Authentication")),
					array("nfapCIFSSignatures",		gettext("Signatures")),
					array("nfapCIFSPDCEnable",		gettext("PDC Enabled")),
					array("nfapCIFSPDCName",		gettext("PDC Name")),
					array("nfapCIFSPDCAddr",		gettext("PDC Address")),
					array("nfapCIFSUserContext",		gettext("User Context")),
					array("nfapCIFSWINSAddr",		gettext("WINS Address")),
					)
				),
			array("section_name"=>gettext("eDirectory Management Toolbox (eMBox) Configuration"),"new_row"=>true,"colspan"=>2,
				"attributes"=>array(
					array("emboxConfig"),
					)
				)
			));

		$ldap_server->add_display_layout("aliasObject",array(
			array("section_name"=>gettext("Alias"),
				"attributes"=>array(
					array("cn",				gettext("Name"),			"generic24.png"),
					array("aliasedObjectName",		gettext("Aliased Object"),		"generic24.png")
					)
				)
			));

		$ldap_server->add_display_layout("Volume",array(
			array("section_name"=>gettext("Volume Information"),"new_row"=>true,
				"attributes"=>array(
					array("hostServer",			gettext("Host Server"),			"generic24.png"),
					/* TODO: revision number */
					array("hostResourceName",		gettext("Host Resource Name"),		"generic24.png"),
					array("l",				gettext("Location"),			"generic24.png"),
					array("ou",				gettext("Department"),			"generic24.png"),
					array("o",				gettext("Organization"),		"generic24.png"),
					array("description",			gettext("Description"),			"generic24.png"),
					array("linuxNCPMountPoint",		gettext("Linux NCP Mount Point"),	"generic24.png"),
					)
				)
			));

		$ldap_server->add_display_layout("organizationalRole",array(
			array("section_name"=>gettext("Organizational Role"),
				"attributes"=>array(
					array("cn",				gettext("Role Name"),			"org-role.png"),
					array("l",				gettext("Location"),			"locality.png"),
					array("ou",				gettext("Department"),			"org.png"),
					array("telephoneNumber",		gettext("Phone"),			"landline-phone.png"),
					array("facsimileTelephoneNumber",	gettext("Fax"),				"fax.png"),
					array("description",			gettext("Description"),			"description.png"),

					array("street:postOfficeBox:physicalDeliveryOfficeName:st:postalCode",
										gettext("Postal Address"),		"address.png"),

					// The address book does not yet support the stuctured mail
					// information format used in eDirectory
					//
					// array("postalAddress",		gettext("Mailing Label Information"),	"address.png"),

					array("seeAlso",			gettext("See Also"),			"alias.png"),
					)
				),
			array("section_name"=>gettext("Role Occupants"),"new_row"=>true,
				"attributes"=>array(
					array("roleOccupant")
					)
				)
			));

		parent::__construct($ldap_server);

		// component schema
		$ldap_server->add_schema("dhcp");		// ISC DHCP (OES Linux)
		$ldap_server->add_schema("novell/apache");
		$ldap_server->add_schema("novell/dnip");	// Novell DHCP (legacy NetWare) and DNS
		$ldap_server->add_schema("novell/embox");
		$ldap_server->add_schema("novell/encrypt");
		$ldap_server->add_schema("novell/httpstk");
		$ldap_server->add_schema("novell/ldap");
		$ldap_server->add_schema("novell/masv");
		$ldap_server->add_schema("novell/ncs");
		$ldap_server->add_schema("novell/nds");
		$ldap_server->add_schema("novell/ndscomm");
		$ldap_server->add_schema("novell/ndspki");
		$ldap_server->add_schema("novell/nfap");
		$ldap_server->add_schema("novell/nis");
		$ldap_server->add_schema("novell/nls");
		$ldap_server->add_schema("novell/nmas");
		$ldap_server->add_schema("novell/notf");
		$ldap_server->add_schema("novell/nov_inet");
		$ldap_server->add_schema("novell/nps");
		$ldap_server->add_schema("novell/nspm");
		$ldap_server->add_schema("novell/nssfs");
		$ldap_server->add_schema("novell/nwadmin");
		$ldap_server->add_schema("novell/pki");
		$ldap_server->add_schema("novell/sas");
		$ldap_server->add_schema("novell/slp");
		$ldap_server->add_schema("novell/snmp");
		$ldap_server->add_schema("novell/sshd");
		$ldap_server->add_schema("novell/sss");
		$ldap_server->add_schema("novell/uam");
		$ldap_server->add_schema("novell/wanman");
		$ldap_server->add_schema("novell/xtier");

		// DirXML
		$ldap_server->add_schema("novell/vrschema");
	}
}
?>
