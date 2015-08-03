<?
/** Novell eDirectory schema (partial) */

class novell_schema extends ldap_schema
{
        var $attribute_schema = array(
		// matches core.schema
		array("name"=>"c",				"data_type"=>"country_code",	"display_name"=>"Country Code"),
		array("name"=>"cn",				"data_type"=>"text",		"display_name"=>"Common Name/Full Name"),
		array("name"=>"description",			"data_type"=>"text",		"display_name"=>"Description"),
		array("name"=>"facsimileTelephoneNumber",	"data_type"=>"text",		"display_name"=>"Fax Number"),
		array("name"=>"givenName",			"data_type"=>"text",		"display_name"=>"Given Name"),
		array("name"=>"l",				"data_type"=>"text",		"display_name"=>"Locality (e.g. Town/City)"),
		array("name"=>"mail",				"data_type"=>"text",		"display_name"=>"E-mail Address"),
		array("name"=>"member",				"data_type"=>"dn_list",		"display_name"=>"Member"),
		array("name"=>"physicalDeliveryOfficeName",	"data_type"=>"text",		"display_name"=>"Office"),
		array("name"=>"postalCode",			"data_type"=>"postcode",	"display_name"=>"Postal Code"),
		array("name"=>"sn",				"data_type"=>"text",		"display_name"=>"Surname"),
		array("name"=>"st",				"data_type"=>"text",		"display_name"=>"State (or Province/County)"),
		array("name"=>"street",				"data_type"=>"text_area",	"display_name"=>"Street Address"),	// a.k.a. streetAddress
		array("name"=>"telephoneNumber",		"data_type"=>"phone_number",	"display_name"=>"Telephone Number"),
		array("name"=>"title",				"data_type"=>"text",		"display_name"=>"Job Title"),

		// matches cosine.schema
		array("name"=>"homePhone",			"data_type"=>"phone_number",	"display_name"=>"Home Telephone Number"),
		array("name"=>"mobile",				"data_type"=>"phone_number",	"display_name"=>"Mobile/Cell Telephone Number"),
		array("name"=>"pager",				"data_type"=>"text",		"display_name"=>"Pager Telephone Number"),

		// matches inetorgperson.schema
		array("name"=>"displayName",			"data_type"=>"text",		"display_name"=>"Display/Preferred Name"),
		array("name"=>"jpegPhoto",			"data_type"=>"image",		"display_name"=>"Photograph"),

		// Novell proprietary classes
		array("name"=>"company",			"data_type"=>"text",		"display_name"=>"Company"),
		array("name"=>"groupMembership",		"data_type"=>"dn_list",		"display_name"=>"Group Membership")
		);

	// Structural object classes
	var $object_schema = array(
		// matches core.schema
		array("name"=>"organizationalUnit",		"icon"=>"folder.png",			"is_folder"=>true,"rdn_attrib"=>"ou","display_name"=>"Organizational Unit","can_create"=>true),

		// matches core.schema other than addition of display_name "Group"
		array("name"=>"groupOfNames",			"icon"=>"group24.png",			"is_folder"=>false,"display_name"=>"Group","can_create"=>true),

		// matches inetorgperson.schema other than addition of display_name "User"
		// (inetOrgPerson LDAP class maps to eDirectory User class)
		array("name"=>"inetOrgPerson",			"icon"=>"user24.png",			"is_folder"=>false,"display_name"=>"User","required_attribs"=>"sn","can_create"=>true),

		// Novell proprietary classes

		array("name"=>"ncpServer",			"icon"=>"novell/server24.png", "is_folder"=>false,"display_name"=>"NCP Server"),
		array("name"=>"ldapServer",			"icon"=>"novell/directory-server.png","is_folder"=>false,"display_name"=>"LDAP Server","can_create"=>true),
		array("name"=>"Person",				"icon"=>"contact24.png",		  "is_folder"=>false,"required_attribs"=>"sn","can_create"=>true),
		array("name"=>"externalEntity",			"icon"=>"novell/external-entity24.png","is_folder"=>false,"display_name"=>"External Entity","can_create"=>true),
		array("name"=>"nDSPKIKeyMaterial",		"icon"=>"novell/key-material.png","is_folder"=>false,"display_name"=>"NDSPKI:Key Material","can_create"=>true),
		array("name"=>"sASService",			"icon"=>"novell/security.png", "is_folder"=>false,"display_name"=>"SAS:Service","can_create"=>true),
		array("name"=>"ldapGroup",			"icon"=>"novell/ldapgroup24.png","is_folder"=>false,"display_name"=>"LDAP Group","can_create"=>true),
		array("name"=>"Volume",				"icon"=>"novell/volume.png",   "is_folder"=>false),
		array("name"=>"ndsPredicateStats",		"icon"=>"novell/stats.png",    "is_folder"=>false),
		array("name"=>"Queue",				"icon"=>"novell/queue.png",    "is_folder"=>false),
		array("name"=>"nLSLicenseServer",		"icon"=>"novell/lic_srv.png",  "is_folder"=>false),
		array("name"=>"nssfsPool",			"icon"=>"novell/raid.png",	  "is_folder"=>false)
		);
}
?>
