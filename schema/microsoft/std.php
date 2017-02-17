<?php
/** Microsoft Standards-Based Schema Definitions (microsoft.std.schema)

    This schema contains elements of existing/third party standards which
    were adopted as the basis of the Active Directory schema. Beware that
    several of these definitions deviate from the RFCs and other standards
    from which they originate.

    @see:
	https://tools.ietf.org/html/draft-ietf-asid-schema-pilot-00
*/

class microsoft_std_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			// LDAP Core Schema for User Applications
			array("name"=>"businessCategory",		"data_type"=>"text",		"display_name"=>gettext("Business Category")),
			array("name"=>"c",				"data_type"=>"country_code",	"display_name"=>gettext("Country Code")),
			array("name"=>"cn",				"data_type"=>"text",		"display_name"=>gettext("Common Name/Full Name")),
			array("name"=>"dc",				"data_type"=>"text",		"display_name"=>gettext("Domain Component Name")),
			array("name"=>"description",			"data_type"=>"text",		"display_name"=>gettext("Description")),
			array("name"=>"destinationIndicator",		"data_type"=>"text",		"display_name"=>gettext("Telegram Destination Indicator")),
			array("name"=>"distinguishedName",		"data_type"=>"dn",		"display_name"=>gettext("Distinguished Name")),
			array("name"=>"facsimileTelephoneNumber",	"data_type"=>"text",		"display_name"=>gettext("Fax Number")),
			array("name"=>"generationQualifier",		"data_type"=>"text",		"display_name"=>gettext("Generation Qualifier")),
			array("name"=>"givenName",			"data_type"=>"text",		"display_name"=>gettext("Given Name")),
			array("name"=>"initials",			"data_type"=>"text",		"display_name"=>gettext("Initials")),
			array("name"=>"internationaliSDNNumber",	"data_type"=>"text",		"display_name"=>gettext("ISDN Number")),
			array("name"=>"l",				"data_type"=>"text",		"display_name"=>gettext("Locality (e.g. Town/City)")),
			array("name"=>"mail",				"data_type"=>"text",		"display_name"=>gettext("E-mail Address")),
			array("name"=>"member",				"data_type"=>"dn_list",		"display_name"=>gettext("Members")),
			array("name"=>"o",				"data_type"=>"text",		"display_name"=>gettext("Organization Name")),
			array("name"=>"objectClass",			"data_type"=>"text",		"display_name"=>gettext("Object Class")),
			array("name"=>"ou",				"data_type"=>"text",		"display_name"=>gettext("Organizational Unit Name")),
			array("name"=>"owner",				"data_type"=>"dn_list",		"display_name"=>gettext("Owner")),
			array("name"=>"physicalDeliveryOfficeName",	"data_type"=>"text",		"display_name"=>gettext("Office")),
			array("name"=>"postalAddress",			"data_type"=>"text_area",	"display_name"=>gettext("Postal Address")),
			array("name"=>"postalCode",			"data_type"=>"postcode",	"display_name"=>gettext("Postal Code")),
			array("name"=>"postOfficeBox",			"data_type"=>"text",		"display_name"=>gettext("Post Office Box")),
			array("name"=>"preferredDeliveryMethod",	"data_type"=>"text",		"display_name"=>gettext("Preferred Delivery Method")),
			array("name"=>"registeredAddress",		"data_type"=>"text_area",	"display_name"=>gettext("Registered Address")),
			array("name"=>"roleOccupant",			"data_type"=>"dn_list",		"display_name"=>gettext("Role Occupant")),
			array("name"=>"searchGuide",			"data_type"=>"text_list",	"display_name"=>gettext("Search Guide")),
			array("name"=>"seeAlso",			"data_type"=>"dn_list",		"display_name"=>gettext("See Also")),
			array("name"=>"serialNumber",			"data_type"=>"text",		"display_name"=>gettext("Serial Number")),
			array("name"=>"sn",				"data_type"=>"text",		"display_name"=>gettext("Surname")),
			array("name"=>"st",				"data_type"=>"text",		"display_name"=>gettext("State (or Province/County)")),

			// Standard definition of "street" has "streetAddress" as an alias. Microsoft
			// define proprietary "streetAddress" as a separate class.
			array("name"=>"street",				"data_type"=>"text_area",	"display_name"=>gettext("Street Address")),

			array("name"=>"telephoneNumber",		"data_type"=>"phone_number",	"display_name"=>gettext("Telephone Number")),
			array("name"=>"teletexTerminalIdentifier",	"data_type"=>"text",		"display_name"=>gettext("Teletex Terminal Identifier")),
			array("name"=>"telexNumber",			"data_type"=>"text",		"display_name"=>gettext("Telex Number")),
			array("name"=>"title",				"data_type"=>"text",		"display_name"=>gettext("Job Title")),
			array("name"=>"userPassword",			"data_type"=>"text",		"display_name"=>gettext("User Password")),
			array("name"=>"x121Address",			"data_type"=>"text",		"display_name"=>gettext("X.121 Network Address")),

			// LDAP PKI (RFC 4523)
			array("name"=>"authorityRevocationList;binary",	"data_type"=>"download_list",	"display_name"=>gettext("Certification Authority Revocation List")),
			array("name"=>"cACertificate;binary",		"data_type"=>"download_list",	"display_name"=>gettext("Certification Authority Certificate")),
			array("name"=>"certificateRevocationList;binary","data_type"=>"download_list",	"display_name"=>gettext("Certificate Revocation List")),
			array("name"=>"crossCertificatePair;binary",	"data_type"=>"download",	"display_name"=>gettext("Cross Certificate Pair")),
			array("name"=>"deltaRevocationList;binary",	"data_type"=>"download_list",	"display_name"=>gettext("Delta Revocation List")),
			array("name"=>"userCertificate;binary",		"data_type"=>"download_list",	"display_name"=>gettext("User Certificate")),

			// Other legacy attributes not carried forward from predecessors of RFC 4519
			array("name"=>"knowledgeInformation",		"data_type"=>"text",		"display_name"=>gettext("Knowledge Information")),
			array("name"=>"presentationAddress",		"data_type"=>"text",		"display_name"=>gettext("OSI Presentation Address")),
			array("name"=>"supportedApplicationContext",	"data_type"=>"oid_list",	"display_name"=>gettext("Supported OSI Application Contexts")),

			// COSINE and Internet X.500 Schema
			array("name"=>"homePhone",			"data_type"=>"phone_number",	"display_name"=>gettext("Home Telephone Number")),
			array("name"=>"manager",			"data_type"=>"dn_list",		"display_name"=>gettext("Manager")),
			array("name"=>"mobile",				"data_type"=>"phone_number",	"display_name"=>gettext("Mobile/Cell Telephone Number")),
			array("name"=>"pager",				"data_type"=>"text",		"display_name"=>gettext("Pager Telephone Number")),

			// Other legacy attributes not carried forward from predecessors of RFC 4524
			array("name"=>"textEncodedORAddress",		"data_type"=>"text",		"display_name"=>gettext("Text Encoded O/R Address")),

			// Directory Information Models (RFC 4512)
			array("name"=>"attributeTypes",			"data_type"=>"ldap_schema",	"display_name"=>gettext("Attribute Types")),
			array("name"=>"dITContentRules",		"data_type"=>"ldap_schema",	"display_name"=>gettext("DIT Content Rules")),
			array("name"=>"objectClasses",			"data_type"=>"ldap_schema",	"display_name"=>gettext("Object Classes")),
			array("name"=>"subschemaSubentry",		"data_type"=>"dn",		"display_name"=>gettext("Subschema Subentry")),

			// Operational attributes
			array("name"=>"createTimestamp",		"data_type"=>"date_time",	"display_name"=>gettext("Created")),
			array("name"=>"modifyTimestamp",		"data_type"=>"date_time",	"display_name"=>gettext("Last Modified")),

			// Active Directory uses non-standard OIDs for these attributes instead
			// of those referenced by the ASID working group. Microsoft use OIDs from
			// ranges that have been registered to Netscape. The comment after each
			// definition shows the conflicting Netscape attribute with the same OID.

			array("name"=>"middleName",			"data_type"=>"text",		"display_name"=>gettext("Middle Name")),		// ref
			array("name"=>"thumbnailLogo",			"data_type"=>"image",		"display_name"=>gettext("Thumbnail Logo")),		// nsLicensedFor
			array("name"=>"thumbnailPhoto",			"data_type"=>"image",		"display_name"=>gettext("Thumbnail Photograph")),	// changeLog

			// Active Directory uses a non-standard OID for userSMIMECertificate
			// instead of the OID referenced by RFC 2798 (and others). The OID is
			// from a range that has been registered to Netscape. Additionally, the
			// last two digits appear to be a typo - "3.140" instead of "3.1.40".

			array("name"=>"userSMIMECertificate;binary",	"data_type"=>"download_list",	"display_name"=>gettext("S/MIME Certificate")),

			// Added for Windows Server 2003 (or inetOrgPerson kit for Windows 2000 Server)
			array("name"=>"jpegPhoto",			"data_type"=>"image",		"display_name"=>gettext("Photograph")),
			);

		// Structural object classes
		$this->object_schema = array(
			// Non-standard implementation of InetOrgPerson (RFC 2798):
			//	- Subclass of proprietary "user"
			//	- Attribute "sn" is not mandatory
			array("name"=>"inetOrgPerson",			"icon"=>"user24.png",		"is_folder"=>false,"display_name"=>gettext("InetOrgPerson"),"can_create"=>true,"parent_class"=>"user"),

			// LDAP Core Schema for User Applications (RFC 2256)
			array("name"=>"applicationEntity",		"icon"=>"generic24.png",	"is_folder"=>false,"display_name"=>gettext("Application Entity")),
			array("name"=>"applicationProcess",		"icon"=>"generic24.png",	"is_folder"=>false,"display_name"=>gettext("Application Process")), // not user creatable
			array("name"=>"country",			"icon"=>"folder.png",		"is_folder"=>true,"rdn_attrib"=>"c","display_name"=>gettext("Country")), // type 88 class
			array("name"=>"device",				"icon"=>"generic24.png",	"is_folder"=>false,"display_name"=>gettext("Device")), // type 88 class
			array("name"=>"dSA",				"icon"=>"generic24.png",	"is_folder"=>false,"display_name"=>gettext("Directory System Agent"),"parent_class"=>"applicationEntity"),
			array("name"=>"groupOfNames",			"icon"=>"generic24.png",	"is_folder"=>false,"display_name"=>gettext("Group"),"required_attribs"=>"member"), // type 88 class
			array("name"=>"locality",			"icon"=>"folder.png",		"is_folder"=>true,"rdn_attrib"=>"l","display_name"=>gettext("Locality")),
			array("name"=>"organization",			"icon"=>"folder.png",		"is_folder"=>true,"rdn_attrib"=>"o","display_name"=>gettext("Organization")),
			array("name"=>"organizationalPerson",		"icon"=>"user24.png",		"is_folder"=>false,"display_name"=>gettext("Organizational Person"),"parent_class"=>"person"), // type 88 class
			array("name"=>"organizationalRole",		"icon"=>"generic24.png",	"is_folder"=>false,"display_name"=>gettext("Organizational Role")),
			array("name"=>"organizationalUnit",		"icon"=>"folder.png",		"is_folder"=>true,"rdn_attrib"=>"ou","display_name"=>gettext("Organizational Unit"),"can_create"=>true),
			array("name"=>"person",				"icon"=>"user24.png",		"is_folder"=>false,"display_name"=>gettext("Person")), // type 88 class
			array("name"=>"residentialPerson",		"icon"=>"generic24.png",	"is_folder"=>false,"display_name"=>gettext("Residential Person"),"parent_class"=>"person"),

			// LDAP PKI (RFC 4523)
			array("name"=>"certificationAuthority",		"icon"=>"cert-authority.png",	"is_folder"=>false), // type 88 class
			array("name"=>"cRLDistributionPoint",		"icon"=>"crl-distrib-point.png","is_folder"=>false),

			// Directory Information Models (RFC 4512)
			array("name"=>"subSchema",			"icon"=>"schema.png",		"is_folder"=>false,"display_name"=>gettext("Schema"))
			);

		// abstract class 'top' is also defined in this schema

		// Display layouts
		$ldap_server->add_display_layout("organizationalUnit",array(
			array("section_name"=>"Folder Details",
				"attributes"=>array(
					array("ou",			gettext("OU Name"),		"folder.png"),
					array("description",		gettext("Description"),		"description.png"),
					array("street:l:st:postalCode",	gettext("Postal Address"),	"address.png"),
					// Country attribute of OU class is Microsoft-specific addition
					array("c",			gettext("Country"),		"country.png")
					)
				)
			));

		parent::__construct($ldap_server);
	}
}
?>
