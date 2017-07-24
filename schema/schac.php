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

                // Object classes
                $this->object_schema = array(
			array("name"=>"schacContactLocation",		"icon"=>"locality.png",		"class_type"=>"auxiliary","display_name"=>gettext("Contact Location"),"can_create"=>true),
			array("name"=>"schacEmployeeInfo",		"icon"=>"org-role.png",		"class_type"=>"auxiliary","display_name"=>gettext("Position Within Institution"),"can_create"=>true),
			array("name"=>"schacEntryConfidentiality",	"icon"=>"generic24.png",	"class_type"=>"auxiliary","display_name"=>gettext("Private Attributes List"),"can_create"=>true),
			array("name"=>"schacEntryMetadata",		"icon"=>"date-time.png",	"class_type"=>"auxiliary","display_name"=>gettext("Authorization Expiry Date/Time"),"can_create"=>true),
			array("name"=>"schacExperimentalOC",		"icon"=>"generic24.png",	"class_type"=>"auxiliary","display_name"=>gettext("SCHAC Experimental Attributes")),
			array("name"=>"schacGroupMembership",		"icon"=>"group24.png",		"class_type"=>"auxiliary","display_name"=>gettext("Project Group Membership"),"can_create"=>true),
			array("name"=>"schacLinkageIdentifiers",	"icon"=>"user-alias24.png",	"class_type"=>"auxiliary","display_name"=>gettext("External Identifiers"),"can_create"=>true),
			array("name"=>"schacPersonalCharacteristics",	"icon"=>"generic24.png",	"class_type"=>"auxiliary","display_name"=>gettext("Personal Characteristics"),"can_create"=>true),
			array("name"=>"schacUserEntitlements",		"icon"=>"generic24.png",	"class_type"=>"auxiliary","display_name"=>gettext("Authorization for Services"),"can_create"=>true)
			);

                // Auxiliary class display layouts

		$ldap_server->add_display_layout("schacPersonalCharacteristics",array(
			array("section_name"=>gettext("Personal Characteristics"),
				"attributes"=>array(
					array("schacPersonalTitle",		gettext("Personal Title"),			"generic24.png"),
					array("schacSn1",			gettext("First Surname"),			"generic24.png"),
					array("schacSn2",			gettext("Second Surname"),			"generic24.png"),
					array("schacGender",			gettext("Gender"),				"generic24.png"),
					array("schacDateOfBirth",		gettext("Date of Birth"),			"date.png"),
					array("schacPlaceOfBirth",		gettext("Place of Birth"),			"locality.png"),
					array("schacCountryOfCitizenship",	gettext("Country of Citizenship"),		"country.png"),
					array("schacMotherTongue",		gettext("Preferred Language"),			"chat.png"),
					)
				)
			));

		$ldap_server->add_display_layout("schacContactLocation",array(
			array("section_name"=>gettext("Contact Location"),
				"attributes"=>array(
					array("schacHomeOrganization",		gettext("Home Organization Domain Name"),	"domain24.png"),
					array("schacHomeOrganizationType",	gettext("Home Organization Type"),		"generic24.png"),
					array("schacCountryOfResidence",	gettext("Country of Residence"),		"country.png"),
					array("schacUserPresenceID",		gettext("Presence Service URL"),		"labeled-uri.png")
					)
				)
			));

		$ldap_server->add_display_layout("schacEmployeeInfo",array(
			array("section_name"=>gettext("Position Within Institution"),
				"attributes"=>array(
					array("schacPersonalPosition")
					)
				)
			));

		$ldap_server->add_display_layout("schacLinkageIdentifiers",array(
			array("section_name"=>gettext("External Identifiers"),
				"attributes"=>array(
					array("schacPersonalUniqueCode",	gettext("Personal Unique Code"),		"id.png"),
					array("schacPersonalUniqueID",		gettext("Personal Unique ID"),			"id.png")
					)
				)
			));

		$ldap_server->add_display_layout("schacEntryMetadata",array(
			array("section_name"=>gettext("Authorization Expiry Date/Time"),
				"attributes"=>array(
					array("schacExpiryDate")
					)
				)
			));

		$ldap_server->add_display_layout("schacEntryConfidentiality",array(
			array("section_name"=>gettext("Private Attributes"),
				"attributes"=>array(
					array("schacUserPrivateAttribute")
					)
				)
			));

		$ldap_server->add_display_layout("schacUserEntitlements",array(
			array("section_name"=>gettext("Authorization for Services"),
				"attributes"=>array(
					array("schacUserStatus")
					)
				)
			));

		$ldap_server->add_display_layout("schacGroupMembership",array(
			array("section_name"=>gettext("Project Group Membership"),
				"attributes"=>array(
					array("schacProjectMembership",		gettext("Project Name"),			"generic24.png"),
					array("schacProjectSpecificRole",	gettext("Roles Within Project"),		"org-role.png")
					)
				)
			));

		$ldap_server->add_display_layout("schacExperimentalOC",array(
			array("section_name"=>gettext("SCHAC Experimental Attributes"),
				"attributes"=>array(
					array("schacYearOfBirth",		gettext("Year of Birth"),			"date.png")
					)
				)
			));

		parent::__construct($ldap_server);
	}

	function populate_for_create_schacGroupMembership(&$ldap_server,&$entry)
	{
		$this->add_attrib_value($ldap_server,$entry,"schacProjectSpecificRole",
			"urn:mace:terena.org:schac:projectSpecificRole:<project-name>:<iNSS>");
	}

	// note: country code can be replaced with <institution-code>.<country-code>
	// where <institution-code> is typically 3 letters

	function populate_for_create_schacUserEntitlements(&$ldap_server,&$entry)
	{
		$this->add_attrib_value($ldap_server,$entry,"schacUserStatus",
			"urn:mace:terena.org:schac:userStatus:<country-code>:<domain>:<iNSS>");
	}

	function populate_for_create_schacLinkageIdentifiers(&$ldap_server,&$entry)
	{
		$this->add_attrib_value($ldap_server,$entry,"schacPersonalUniqueCode",
			"urn:mace:terena.org:schac:personalUniqueCode:<country-code>:<iNSS>");
		$this->add_attrib_value($ldap_server,$entry,"schacPersonalUniqueID",
			"urn:mace:terena.org:schac:personalUniqueID:<country-code>:<idType>:<idValue>");
	}

	function populate_for_create_schacEmployeeInfo(&$ldap_server,&$entry)
	{
		$this->add_attrib_value($ldap_server,$entry,"schacPersonalPosition",
			"urn:mace:terena.org:schac:personalPosition:<country-code>:<domain>:<iNSS>");
	}

	function populate_for_create_schacContactLocation(&$ldap_server,&$entry)
	{
		$this->add_attrib_value($ldap_server,$entry,"schacHomeOrganizationType",
			"urn:mace:terena.org:schac:homeOrganizationType:<country-code>:<string>");
	}
}
?>
