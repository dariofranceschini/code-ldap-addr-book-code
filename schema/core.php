<?
/** core.schema (partial) */

class core_schema extends ldap_schema
{
	var $attribute_schema = array(
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
		array("name"=>"streetAddress",			"data_type"=>"text_area",	"display_name"=>"Street Address"),
		array("name"=>"telephoneNumber",		"data_type"=>"phone_number",	"display_name"=>"Telephone Number"),
		array("name"=>"title",				"data_type"=>"text",		"display_name"=>"Job Title"),

		// used with memberof overlay
		array("name"=>"memberOf",			"data_type"=>"dn_list",		"display_name"=>"Member Of")
		);

	// Structural object classes
	var $object_schema = array(
		array("name"=>"organizationalUnit",		"icon"=>"folder.png",		"is_folder"=>true,"rdn_attrib"=>"ou","display_name"=>"Organizational Unit","can_create"=>true),
		array("name"=>"groupOfNames",			"icon"=>"group24.png",		"is_folder"=>false,"can_create"=>true)
		);
}
?>
