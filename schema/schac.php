<?php
/** SCHema for ACademia (SCHAC)

    @see https://wiki.refeds.org/display/STAN/SCHAC+Releases
*/

class schac_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"schacMotherTongue",		"data_type"=>"text",		"display_name"=>gettext("Preferred Language")),
			array("name"=>"schacGender",			"data_type"=>"gender",		"display_name"=>gettext("Gender")),
			array("name"=>"schacDateOfBirth",		"data_type"=>"date",		"display_name"=>gettext("Date of Birth")),
			array("name"=>"schacPlaceOfBirth",		"data_type"=>"text",		"display_name"=>gettext("Place of Birth")),
			array("name"=>"schacCountryOfCitizenship",	"data_type"=>"country_code",	"display_name"=>gettext("Country of Citizenship")),
			array("name"=>"schacSn1",			"data_type"=>"text",		"display_name"=>gettext("First Surname")),
			array("name"=>"schacSn2",			"data_type"=>"text",		"display_name"=>gettext("Second Surname")),
			array("name"=>"schacPersonalTitle",		"data_type"=>"text",		"display_name"=>gettext("Personal Title")),
			array("name"=>"schacHomeOrganization",		"data_type"=>"text",		"display_name"=>gettext("Home Organization Domain Name")),
			array("name"=>"schacHomeOrganizationType",	"data_type"=>"text",		"display_name"=>gettext("Home Organization Type")),
			array("name"=>"schacCountryOfResidence",	"data_type"=>"country_code",	"display_name"=>gettext("Country of Residence")),
			array("name"=>"schacUserPresenceID",		"data_type"=>"text",		"display_name"=>gettext("Presence Service URL")),
			array("name"=>"schacPersonalPosition",		"data_type"=>"text",		"display_name"=>gettext("Personal Position")),
			array("name"=>"schacPersonalUniqueCode",	"data_type"=>"text",		"display_name"=>gettext("Personal Unique Code")),
			array("name"=>"schacPersonalUniqueID",		"data_type"=>"text",		"display_name"=>gettext("Personal Unique ID")),
			array("name"=>"schacExpiryDate",		"data_type"=>"date_time",	"display_name"=>gettext("Record Expiry Date")),
			array("name"=>"schacUserPrivateAttribute",	"data_type"=>"text_list",	"display_name"=>gettext("Private Attributes")),
			array("name"=>"schacUserStatus",		"data_type"=>"text_list",	"display_name"=>gettext("User Status")),
			array("name"=>"schacProjectMembership",		"data_type"=>"text",		"display_name"=>gettext("Project Membership")),
			array("name"=>"schacProjectSpecificRole",	"data_type"=>"text",		"display_name"=>gettext("Project Specific Role")),
			array("name"=>"schacYearOfBirth",		"data_type"=>"text",		"display_name"=>gettext("Year of Birth"))
			);

		// No structural object classes for this schema (auxillary classes only)

		parent::__construct($ldap_server);
	}
}
?>
