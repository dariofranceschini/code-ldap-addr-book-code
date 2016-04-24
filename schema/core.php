<?php
/** core.schema (partial) */

class core_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"c",				"data_type"=>"country_code",	"display_name"=>gettext("Country Code"),		"alias_names"=>"countryName"),
			array("name"=>"cn",				"data_type"=>"text",		"display_name"=>gettext("Common Name/Full Name"),	"alias_names"=>"commonName"),
			array("name"=>"description",			"data_type"=>"text",		"display_name"=>gettext("Description")),
			array("name"=>"facsimileTelephoneNumber",	"data_type"=>"text",		"display_name"=>gettext("Fax Number"),			"alias_names"=>"fax"),
			array("name"=>"givenName",			"data_type"=>"text",		"display_name"=>gettext("Given Name"),			"alias_names"=>"gn"),
			array("name"=>"l",				"data_type"=>"text",		"display_name"=>gettext("Locality (e.g. Town/City)"),	"alias_names"=>"localityName"),
			array("name"=>"mail",				"data_type"=>"text",		"display_name"=>gettext("E-mail Address"),		"alias_names"=>"rfc822Mailbox"),
			array("name"=>"member",				"data_type"=>"dn_list",		"display_name"=>gettext("Member")),
			array("name"=>"physicalDeliveryOfficeName",	"data_type"=>"text",		"display_name"=>gettext("Office")),
			array("name"=>"postalCode",			"data_type"=>"postcode",	"display_name"=>gettext("Postal Code")),
			array("name"=>"sn",				"data_type"=>"text",		"display_name"=>gettext("Surname"),			"alias_names"=>"surname"),
			array("name"=>"st",				"data_type"=>"text",		"display_name"=>gettext("State (or Province/County)"),	"alias_names"=>"stateOrProvinceName"),
			array("name"=>"street",				"data_type"=>"text_area",	"display_name"=>gettext("Street Address"),		"alias_names"=>"streetAddress"),
			array("name"=>"telephoneNumber",		"data_type"=>"phone_number",	"display_name"=>gettext("Telephone Number")),
			array("name"=>"title",				"data_type"=>"text",		"display_name"=>gettext("Job Title")),
			array("name"=>"uid",				"data_type"=>"text",		"display_name"=>gettext("User ID"),			"alias_names"=>"userid"),

			// used with memberof overlay
			array("name"=>"memberOf",			"data_type"=>"dn_list",		"display_name"=>gettext("Member Of"))
			);

		// Structural object classes
		$this->object_schema = array(
			array("name"=>"organizationalUnit",		"icon"=>"folder.png",		"is_folder"=>true,"rdn_attrib"=>"ou","display_name"=>gettext("Organizational Unit"),"can_create"=>true),
			array("name"=>"groupOfNames",			"icon"=>"group24.png",		"is_folder"=>false,"required_attribs"=>"member")
			);

		parent::__construct($ldap_server);
	}
}
?>
