<?
/** SCHema for ACademia (SCHAC)

    @see https://wiki.refeds.org/display/STAN/SCHAC+Releases
*/

class schac_schema extends ldap_schema
{
	var $attribute_schema = array(
		array("name"=>"schacMotherTongue",		"data_type"=>"text",		"display_name"=>"Preferred Language"),
		array("name"=>"schacGender",			"data_type"=>"gender",		"display_name"=>"Gender"),
		array("name"=>"schacDateOfBirth",		"data_type"=>"date",		"display_name"=>"Date of Birth"),
		array("name"=>"schacPlaceOfBirth",		"data_type"=>"text",		"display_name"=>"Place of Birth"),
		array("name"=>"schacCountryOfCitizenship",	"data_type"=>"country_code",	"display_name"=>"Country of Citizenship"),
		array("name"=>"schacSn1",			"data_type"=>"text",		"display_name"=>"First Surname"),
		array("name"=>"schacSn2",			"data_type"=>"text",		"display_name"=>"Second Surname"),
		array("name"=>"schacPersonalTitle",		"data_type"=>"text",		"display_name"=>"Personal Title"),
		array("name"=>"schacHomeOrganization",		"data_type"=>"text",		"display_name"=>"Home Organization Domain Name"),
		array("name"=>"schacHomeOrganizationType",	"data_type"=>"text",		"display_name"=>"Home Organization Type"),
		array("name"=>"schacCountryOfResidence",	"data_type"=>"country_code",	"display_name"=>"Country of Residence"),
		array("name"=>"schacUserPresenceID",		"data_type"=>"text",		"display_name"=>"Presence Service URL"),
		array("name"=>"schacPersonalPosition",		"data_type"=>"text",		"display_name"=>"Personal Position"),
		array("name"=>"schacPersonalUniqueCode",	"data_type"=>"text",		"display_name"=>"Personal Unique Code"),
		array("name"=>"schacPersonalUniqueID",		"data_type"=>"text",		"display_name"=>"Personal Unique ID"),
		array("name"=>"schacExpiryDate",		"data_type"=>"date_time",	"display_name"=>"Record Expiry Date"),
		array("name"=>"schacUserPrivateAttribute",	"data_type"=>"text_list",	"display_name"=>"Private Attributes"),
		array("name"=>"schacUserStatus",		"data_type"=>"text_list",	"display_name"=>"User Status"),
		array("name"=>"schacProjectMembership",		"data_type"=>"text",		"display_name"=>"Project Membership"),
		array("name"=>"schacProjectSpecificRole",	"data_type"=>"text",		"display_name"=>"Project Specific Role"),
		array("name"=>"schacYearOfBirth",		"data_type"=>"text",		"display_name"=>"Year of Birth")
		);

	// No structural object classes for this schema (auxillary classes only)
	var $object_schema = array();
}
?>
