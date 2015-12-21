<?php
/** Novell eDirectory schema (partial) */

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
			array("name"=>"physicalDeliveryOfficeName",	"data_type"=>"text",		"display_name"=>gettext("Office")),
			array("name"=>"postalCode",			"data_type"=>"postcode",	"display_name"=>gettext("Postal Code")),
			array("name"=>"sn",				"data_type"=>"text",		"display_name"=>gettext("Surname")),
			array("name"=>"st",				"data_type"=>"text",		"display_name"=>gettext("State (or Province/County)")),
			array("name"=>"street",				"data_type"=>"text_area",	"display_name"=>gettext("Street Address")),	// a.k.a. streetAddress
			array("name"=>"telephoneNumber",		"data_type"=>"phone_number",	"display_name"=>gettext("Telephone Number")),
			array("name"=>"title",				"data_type"=>"text",		"display_name"=>gettext("Job Title")),

			// matches cosine.schema
			array("name"=>"homePhone",			"data_type"=>"phone_number",	"display_name"=>gettext("Home Telephone Number")),
			array("name"=>"mobile",				"data_type"=>"phone_number",	"display_name"=>gettext("Mobile/Cell Telephone Number")),
			array("name"=>"pager",				"data_type"=>"text",		"display_name"=>gettext("Pager Telephone Number")),

			// matches inetorgperson.schema
			array("name"=>"displayName",			"data_type"=>"text",		"display_name"=>gettext("Display/Preferred Name")),
			array("name"=>"jpegPhoto",			"data_type"=>"image",		"display_name"=>gettext("Photograph")),

			// Novell proprietary classes
			array("name"=>"company",			"data_type"=>"text",		"display_name"=>gettext("Company")),
			array("name"=>"groupMembership",		"data_type"=>"dn_list",		"display_name"=>gettext("Group Membership"))
			);

		// Structural object classes
		$this->object_schema = array(
			// matches core.schema
			array("name"=>"organizationalUnit",		"icon"=>"folder.png",			"is_folder"=>true,"rdn_attrib"=>"ou","display_name"=>gettext("Organizational Unit"),"can_create"=>true),

			// matches core.schema other than addition of display_name "Group"
			array("name"=>"groupOfNames",			"icon"=>"group24.png",			"is_folder"=>false,"display_name"=>gettext("Group"),"can_create"=>true),

			// matches inetorgperson.schema other than addition of display_name "User"
			// (inetOrgPerson LDAP class maps to eDirectory User class)
			array("name"=>"inetOrgPerson",			"icon"=>"user24.png",			"is_folder"=>false,"display_name"=>gettext("User"),"required_attribs"=>"sn","can_create"=>true),

			// Novell proprietary classes

			array("name"=>"ncpServer",			"icon"=>"novell/server24.png", "is_folder"=>false,"display_name"=>gettext("NCP Server")),
			array("name"=>"ldapServer",			"icon"=>"novell/directory-server.png","is_folder"=>false,"display_name"=>gettext("LDAP Server"),"can_create"=>true),
			array("name"=>"Person",				"icon"=>"contact24.png",		  "is_folder"=>false,"display_name"=>gettext("Person"),"required_attribs"=>"sn","can_create"=>true),
			array("name"=>"externalEntity",			"icon"=>"novell/external-entity24.png","is_folder"=>false,"display_name"=>gettext("External Entity"),"can_create"=>true),
			array("name"=>"nDSPKIKeyMaterial",		"icon"=>"novell/key-material.png","is_folder"=>false,"display_name"=>gettext("NDSPKI:Key Material"),"can_create"=>true),
			array("name"=>"sASService",			"icon"=>"novell/security.png", "is_folder"=>false,"display_name"=>gettext("SAS:Service"),"can_create"=>true),
			array("name"=>"ldapGroup",			"icon"=>"novell/ldapgroup24.png","is_folder"=>false,"display_name"=>gettext("LDAP Group"),"can_create"=>true),
			array("name"=>"Volume",				"icon"=>"novell/volume.png",   "is_folder"=>false,"display_name"=>gettext("Volume")),
			array("name"=>"ndsPredicateStats",		"icon"=>"novell/stats.png",    "is_folder"=>false),
			array("name"=>"Queue",				"icon"=>"novell/queue.png",    "is_folder"=>false,"display_name"=>gettext("Queue")),
			array("name"=>"nLSLicenseServer",		"icon"=>"novell/lic_srv.png",  "is_folder"=>false),
			array("name"=>"nssfsPool",			"icon"=>"novell/raid.png",	  "is_folder"=>false)
			);

		parent::__construct($ldap_server);
	}
}
?>
