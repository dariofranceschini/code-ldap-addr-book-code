<?php
/** Novell eDirectory base schema (partial) */

class novell_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			// Standard user attributes
			array("name"=>"c",				"data_type"=>"country_code",	"display_name"=>gettext("Country Code")),
			array("name"=>"cn",				"data_type"=>"text",		"display_name"=>gettext("Common Name/Full Name")),
			array("name"=>"description",			"data_type"=>"text",		"display_name"=>gettext("Description")),
			array("name"=>"facsimileTelephoneNumber",	"data_type"=>"text",		"display_name"=>gettext("Fax Number")),
			array("name"=>"givenName",			"data_type"=>"text",		"display_name"=>gettext("Given Name")),
			array("name"=>"generationQualifier",		"data_type"=>"text",		"display_name"=>gettext("Generation Qualifier")),
			array("name"=>"initials",			"data_type"=>"text",		"display_name"=>gettext("Initials")),
			array("name"=>"l",				"data_type"=>"text",		"display_name"=>gettext("Locality (e.g. Town/City)")),
			array("name"=>"mail",				"data_type"=>"text",		"display_name"=>gettext("E-mail Address")),
			array("name"=>"member",				"data_type"=>"dn_list",		"display_name"=>gettext("Member")),
			array("name"=>"o",				"data_type"=>"text",		"display_name"=>gettext("Organization Name")),
			array("name"=>"ou",				"data_type"=>"text",		"display_name"=>gettext("Organizational Unit Name")),
			array("name"=>"physicalDeliveryOfficeName",	"data_type"=>"text",		"display_name"=>gettext("Office")),
			array("name"=>"postalCode",			"data_type"=>"postcode",	"display_name"=>gettext("Postal Code")),
			array("name"=>"postOfficeBox",			"data_type"=>"text",		"display_name"=>gettext("Postal Office Box")),
			array("name"=>"roleOccupant",			"data_type"=>"dn_list",		"display_name"=>gettext("Role Occupant")),
			array("name"=>"seeAlso",			"data_type"=>"dn_list",		"display_name"=>gettext("See Also")),
			array("name"=>"sn",				"data_type"=>"text",		"display_name"=>gettext("Surname")),
			array("name"=>"st",				"data_type"=>"text",		"display_name"=>gettext("State (or Province/County)")),
			array("name"=>"street",				"data_type"=>"text_area",	"display_name"=>gettext("Street Address")),	// a.k.a. streetAddress
			array("name"=>"telephoneNumber",		"data_type"=>"phone_number",	"display_name"=>gettext("Telephone Number")),
			array("name"=>"title",				"data_type"=>"text",		"display_name"=>gettext("Job Title")),
			array("name"=>"uid",				"data_type"=>"text",		"display_name"=>gettext("User ID"),			"alias_names"=>"userid"),

			// Novell proprietary attributes
			array("name"=>"fullName",			"data_type"=>"text",		"display_name"=>gettext("Full Name")),
			array("name"=>"groupMembership",		"data_type"=>"dn_list",		"display_name"=>gettext("Group Membership")),
			array("name"=>"hostServer",			"data_type"=>"dn",		"display_name"=>gettext("Host Server")),
			array("name"=>"Language",			"data_type"=>"text",		"display_name"=>gettext("Language")),
			array("name"=>"loginGraceLimit",		"data_type"=>"text",		"display_name"=>gettext("Number of Grace Logins Allowed for Expired Passwords")),
			array("name"=>"loginGraceRemaining",		"data_type"=>"text",		"display_name"=>gettext("Number of Grace Logins Remaining")),
			array("name"=>"loginScript",			"data_type"=>"text_area",	"display_name"=>gettext("Login Script")),
			array("name"=>"messageServer",			"data_type"=>"dn",		"display_name"=>gettext("Message Server")),
			array("name"=>"ndsCrossCertificatePair",	"data_type"=>"download",	"display_name"=>gettext("Cross Certificate Pair (NDS)")),
			array("name"=>"ndsHomeDirectory",		"data_type"=>"text",		"display_name"=>gettext("Home Directory (NDS)")),
			array("name"=>"passwordAllowChange",		"data_type"=>"yes_no",		"display_name"=>gettext("Allow User To Change Their Password")),
			array("name"=>"passwordExpirationInterval",	"data_type"=>"text",		"display_name"=>gettext("Password Expiration Interval")),
			array("name"=>"passwordExpirationTime",		"data_type"=>"date_time",	"display_name"=>gettext("End of Password Validity Period")),
			array("name"=>"passwordMinimumLength",		"data_type"=>"text",		"display_name"=>gettext("Minimum Accepted Password Length")),
			array("name"=>"passwordRequired",		"data_type"=>"yes_no",		"display_name"=>gettext("User Must Have A Password Set")),
			array("name"=>"passwordUniqueRequired",		"data_type"=>"yes_no",		"display_name"=>gettext("Password Must Be Unique")),

			// Root DSE attributes
			array("name"=>"altServer",			"data_type"=>"text_list",	"display_name"=>gettext("Alternative Servers")),
			array("name"=>"dSAName",			"data_type"=>"dn",		"display_name"=>gettext("DSA Name")),
			array("name"=>"namingContexts",			"data_type"=>"dn_list",		"display_name"=>gettext("Naming Contexts")),
			array("name"=>"subschemaSubentry",		"data_type"=>"dn",		"display_name"=>gettext("Subschema Subentry")),
			array("name"=>"supportedControl",		"data_type"=>"oid_list",	"display_name"=>gettext("Supported Controls")),
			array("name"=>"supportedExtension",		"data_type"=>"oid_list",	"display_name"=>gettext("Supported Extended Operations")),
			array("name"=>"supportedFeatures",		"data_type"=>"oid_list",	"display_name"=>gettext("Supported Features")),
			// See: draft-zeilenga-ldap-grouping-06
			array("name"=>"supportedGroupingTypes",		"data_type"=>"oid_list",	"display_name"=>gettext("Supported Grouping Types")),
			array("name"=>"supportedLDAPVersion",		"data_type"=>"ldap_version",	"display_name"=>gettext("Supported LDAP Versions")),
			array("name"=>"supportedSASLMechanisms",	"data_type"=>"text_list",	"display_name"=>gettext("Supported SASL Mechanisms")),

			// Schema definition attributes
			array("name"=>"attributeTypes",			"data_type"=>"ldap_schema",	"display_name"=>gettext("Attribute Types")),
			array("name"=>"ldapSyntaxes",			"data_type"=>"ldap_schema",	"display_name"=>gettext("LDAP Syntaxes")),
			array("name"=>"matchingRules",			"data_type"=>"ldap_schema",	"display_name"=>gettext("Matching Rules")),
			array("name"=>"matchingRuleUse",		"data_type"=>"ldap_schema",	"display_name"=>gettext("Matching Rule Use")),
			array("name"=>"objectClasses",			"data_type"=>"ldap_schema",	"display_name"=>gettext("Object Classes")),

			// Operational attributes
			array("name"=>"creatorsName",			"data_type"=>"dn",		"display_name"=>gettext("Created By")),
			array("name"=>"createTimestamp",		"data_type"=>"date_time",	"display_name"=>gettext("Created")),
			array("name"=>"modifiersName",			"data_type"=>"dn",		"display_name"=>gettext("Last Modified By")),
			array("name"=>"modifyTimestamp",		"data_type"=>"date_time",	"display_name"=>gettext("Last Modified"))
			);

		// Object classes
		$this->object_schema = array(
			// Standard object classes
			// (Class names for organization, country, locality are capitalised in their Novell versions)
			// (inetOrgPerson LDAP class maps to eDirectory User class and has display_name "User")
			array("name"=>"Country",			"icon"=>"country.png",			"is_folder"=>true,"rdn_attrib"=>"c","display_name"=>gettext("Country"),"can_create"=>true),
			array("name"=>"groupOfNames",			"icon"=>"group24.png",			"is_folder"=>false,"display_name"=>gettext("Group"),"can_create"=>true,"parent_class"=>"ndsLoginProperties"),
			array("name"=>"inetOrgPerson",			"icon"=>"user24.png",			"is_folder"=>false,"display_name"=>gettext("User"),"required_attribs"=>"sn","can_create"=>true,"parent_class"=>"organizationalPerson,ndsLoginProperties"),
			array("name"=>"Locality",			"icon"=>"locality.png",			"is_folder"=>true,"rdn_attrib"=>"l","display_name"=>gettext("Locality"),"can_create"=>true),
			array("name"=>"Organization",			"icon"=>"org.png",			"is_folder"=>true,"rdn_attrib"=>"o","display_name"=>gettext("Organization"),"can_create"=>true,"parent_class"=>"ndsLoginProperties,ndsContainerLoginProperties"),
			array("name"=>"organizationalRole",		"icon"=>"org-role.png",			"is_folder"=>false,"display_name"=>gettext("Organizational Role"),"can_create"=>true),
			array("name"=>"organizationalUnit",		"icon"=>"folder.png",			"is_folder"=>true,"rdn_attrib"=>"ou","display_name"=>gettext("Organizational Unit"),"can_create"=>true,"parent_class"=>"ndsLoginProperties,ndsContainerLoginProperties"),
			array("name"=>"Person",				"icon"=>"contact24.png",		"is_folder"=>false,"display_name"=>gettext("Person"),"required_attribs"=>"sn","can_create"=>true,"parent_class"=>"ndsLoginProperties"),
			array("name"=>"subschema",			"icon"=>"schema.png",			"is_folder"=>false,"display_name"=>gettext("Schema")),

			// Novell proprietary classes
			array("name"=>"aliasObject",			"icon"=>"alias.png",			"is_folder"=>false,"display_name"=>gettext("Alias"),"required_attribs"=>"aliasedObjectName"),
			array("name"=>"directoryMap",			"icon"=>"novell/directory-map.png",	"is_folder"=>false,"display_name"=>gettext("Directory Map"),"parent_class"=>"Resource"),
			array("name"=>"externalEntity",			"icon"=>"novell/external-entity24.png",	"is_folder"=>false,"display_name"=>gettext("External Entity"),"can_create"=>true),
			array("name"=>"ncpServer",			"icon"=>"server.png",			"is_folder"=>false,"display_name"=>gettext("NCP Server"),"parent_class"=>"Server"),
			array("name"=>"Profile",			"icon"=>"novell/profile.png",		"is_folder"=>false,"display_name"=>gettext("Profile")),
			array("name"=>"Queue",				"icon"=>"novell/queue.png",		"is_folder"=>false,"display_name"=>gettext("Queue"),"parent_class"=>"Resource"),
			array("name"=>"Volume",				"icon"=>"novell/volume.png",		"is_folder"=>false,"display_name"=>gettext("Volume"),"parent_class"=>"Resource"),
			);

		// abstract class 'Resource' is also defined in this schema
		// abstract class 'Server' is also defined in this schema

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
			array("section_name"=>gettext("NCP Volume"),"new_row"=>true,
				"attributes"=>array(
					array("hostServer",			gettext("Host Server"),			"server-alias.png"),
					array("hostResourceName",		gettext("Resource Name"),		"novell/volume.png"),
					array("linuxNCPMountPoint",		gettext("Linux Mount Point"),		"folder.png")
					/* TODO: revision number */
					)
				),
			array("section_name"=>gettext("Additional Information"),"new_row"=>true,
				"attributes"=>array(
					array("l",				gettext("Location"),			"locality.png"),
					array("ou",				gettext("Department"),			"org.png"),
					array("o",				gettext("Organization"),		"company.png"),
					array("description",			gettext("Description"),			"description.png")
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

		$ldap_server->add_display_layout("inetOrgPerson",array(
			array("section_name"=>gettext("Personal"),
				"attributes"=>array(
					array("cn",				gettext("Object Name"),			"contact24.png","allow_view"=>false),
					array("givenName",			gettext("First Name"),			"contact24.png","allow_view"=>false),
					array("initials",			gettext("Initials"),			"contact24.png","allow_view"=>false),
					array("sn",				gettext("Last Name"),			"contact24.png","allow_view"=>false),
					array("generationQualifier",		gettext("Generation Qualifier"),	"contact24.png","allow_view"=>false),
					array("fullName",			gettext("Full Name"),			"contact24.png","allow_view"=>false),
					array("displayName",			gettext("Preferred Name"),		"contact24.png"),
					array("mail",				gettext("E-mail"),			"mail.png"),
					array("homePhone",			gettext("Home Phone"),			"landline-phone.png"),
			//		array("pager",				gettext("Pager"),			"generic24.png"),
					array("mobile",				gettext("Mobile Phone"),		"cell-phone.png"),
			//		array("language",			gettext("Preferred Language"),		"chat.png"),
					array("street:physicalDeliveryOfficeName:st:postalCode",gettext("Postal Address"),"address.png"),
			//		array("jpegPhoto",			gettext("Photo"),			"photo24.png")
					)
				),
			array("section_name"=>gettext("Business/Work"),"width"=>"50%",
				"attributes"=>array(
					array("company",			gettext("Company"),			"company.png"),
					array("telephoneNumber",		gettext("Office Phone"),		"landline-phone.png"),
					array("facsimileTelephoneNumber",	gettext("Office Fax"),			"fax.png"),
					array("title",				gettext("Job Title"),			"org-role.png"),
					array("ou",				gettext("Department"),			"org.png"),
					array("l",				gettext("Office Location"),		"office.png")
					)
				),

			// Uncomment this section in order to display login details

			/*
			array("section_name"=>gettext("Login Details"),"new_row"=>true,"colspan"=>2,
				"attributes"=>array(
					array("uid",				gettext("User ID"),			"id.png"),
					array("messageServer",			gettext("Default Server"),		"alias.png"),
					array("ndsHomeDirectory",		gettext("Home Directory"),		"folder.png"),
					array("loginScript",			gettext("Login Script"),		"generic24.png")
					)
				),
			*/

			// Uncomment this section in order to display password restriction options

			/*
			array("section_name"=>gettext("Password Restrictions"),"new_row"=>true,"colspan"=>2,
				"attributes"=>array(
					array("passwordAllowChange",		gettext("Allow User to Change Password"),"generic24.png"),
					array("passwordRequired",		gettext("Require a Password"),		"password-policy.png"),
					array("passwordMinimumLength",		gettext("Minimum Password Length"),	"password-checker.png"),
					array("passwordExpirationInterval",	gettext("Password Expiration Interval (s)"),"time.png"),
					array("passwordExpirationTime",		gettext("Password Expiry Date"),	"date-time.png"),
					array("passwordUniqueRequired",		gettext("Require Unique Passwords"),	"generic24.png"),
					array("loginGraceLimit",		gettext("Grace Logins Allowed"),	"generic24.png"),
					array("loginGraceRemaining",		gettext("Remaining Grace Logins"),	"generic24.png")
					)
				),
			*/

			array("section_name"=>gettext("Group Membership"),"new_row"=>true,"colspan"=>2,
				"attributes"=>array(
					array("groupMembership")
					)
				),
			array("section_name"=>gettext("Additional Notes"),"new_row"=>true,"colspan"=>2,
				"attributes"=>array(
					array("description")
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

		$ldap_server->add_display_layout("subschema",array(
			array("section_name"=>gettext("Object Class Definitions"),"new_row"=>true,"colspan"=>2,
				"attributes"=>array(
					array("objectClasses")
					)
				),
			array("section_name"=>gettext("Attribute Type Definitions"),"new_row"=>true,"colspan"=>2,
				"attributes"=>array(
					array("attributeTypes")
					)
				),
			array("section_name"=>gettext("LDAP Syntax Definitions"),"new_row"=>true,"colspan"=>2,
				"attributes"=>array(
					array("ldapSyntaxes")
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
		$ldap_server->add_schema("novell/namid");
		$ldap_server->add_schema("novell/ncs");
		$ldap_server->add_schema("novell/nds");
		$ldap_server->add_schema("novell/ndscomm");
		$ldap_server->add_schema("novell/ndspki");
		$ldap_server->add_schema("novell/nestgrp");
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
