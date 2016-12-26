<?php
/** Microsoft Standards-Based Schema Definitions (microsoft.std.schema) (partial)

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
			array("name"=>"c",				"data_type"=>"country_code",	"display_name"=>gettext("Country Code")),
			array("name"=>"cn",				"data_type"=>"text",		"display_name"=>gettext("Common Name/Full Name")),
			array("name"=>"description",			"data_type"=>"text",		"display_name"=>gettext("Description")),
			array("name"=>"facsimileTelephoneNumber",	"data_type"=>"text",		"display_name"=>gettext("Fax Number")),
			array("name"=>"givenName",			"data_type"=>"text",		"display_name"=>gettext("Given Name")),
			array("name"=>"l",				"data_type"=>"text",		"display_name"=>gettext("Locality (e.g. Town/City)")),
			array("name"=>"mail",				"data_type"=>"text",		"display_name"=>gettext("E-mail Address")),
			array("name"=>"member",				"data_type"=>"dn_list",		"display_name"=>gettext("Members")),
			array("name"=>"ou",				"data_type"=>"text",		"display_name"=>gettext("Organizational Unit Name")),
			array("name"=>"physicalDeliveryOfficeName",	"data_type"=>"text",		"display_name"=>gettext("Office")),
			array("name"=>"postalCode",			"data_type"=>"postcode",	"display_name"=>gettext("Postal Code")),
			array("name"=>"sn",				"data_type"=>"text",		"display_name"=>gettext("Surname")),
			array("name"=>"st",				"data_type"=>"text",		"display_name"=>gettext("State (or Province/County)")),
			array("name"=>"street",				"data_type"=>"text_area",	"display_name"=>gettext("Street Address")),
			array("name"=>"telephoneNumber",		"data_type"=>"phone_number",	"display_name"=>gettext("Telephone Number")),
			array("name"=>"title",				"data_type"=>"text",		"display_name"=>gettext("Job Title")),

			// COSINE and Internet X.500 Schema
			array("name"=>"homePhone",			"data_type"=>"phone_number",	"display_name"=>gettext("Home Telephone Number")),
			array("name"=>"mobile",				"data_type"=>"phone_number",	"display_name"=>gettext("Mobile/Cell Telephone Number")),
			array("name"=>"pager",				"data_type"=>"text",		"display_name"=>gettext("Pager Telephone Number")),

			// Directory Information Models (RFC 4512)
			array("name"=>"attributeTypes",			"data_type"=>"ldap_schema",	"display_name"=>gettext("Attribute Types")),
			array("name"=>"dITContentRules",                "data_type"=>"text_list",       "display_name"=>gettext("DIT Content Rules")),
			array("name"=>"objectClasses",			"data_type"=>"ldap_schema",	"display_name"=>gettext("Object Classes")),
			array("name"=>"subschemaSubentry",              "data_type"=>"dn",              "display_name"=>gettext("Subschema Subentry")),

			// Active Directory uses non-standard OIDs for these attributes instead
			// of those referenced by the ASID working group. Microsoft use OIDs from
			// ranges that have been registered to Netscape. The comment after each
			// definition shows the conflicting Netscape attribute with the same OID.

			array("name"=>"thumbnailLogo",			"data_type"=>"image",		"display_name"=>gettext("Thumbnail Logo")),		// nsLicensedFor
			array("name"=>"thumbnailPhoto",			"data_type"=>"image",		"display_name"=>gettext("Thumbnail Photograph")),	// changeLog

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
			array("name"=>"organizationalUnit",		"icon"=>"folder.png",		"is_folder"=>true,"rdn_attrib"=>"ou","display_name"=>gettext("Organizational Unit"),"can_create"=>true),

			// LDAP PKI (RFC 4523)
			array("name"=>"certificationAuthority",		"icon"=>"cert-authority.png",	"is_folder"=>false), // type 88 class
			array("name"=>"cRLDistributionPoint",		"icon"=>"crl-distrib-point.png","is_folder"=>false),

			// System Schema
			array("name"=>"subSchema",			"icon"=>"schema.png",		"is_folder"=>false,"display_name"=>gettext("Schema"))
			);

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
