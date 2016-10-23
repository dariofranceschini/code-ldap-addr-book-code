<?php
/** microsoft.std.schema (partial) */

class microsoft_std_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			// microsoft.std.schema entries (partial)
			array("name"=>"c",				"data_type"=>"country_code",	"display_name"=>gettext("Country Code")),
			array("name"=>"cn",				"data_type"=>"text",		"display_name"=>gettext("Common Name/Full Name")),
			array("name"=>"description",			"data_type"=>"text",		"display_name"=>gettext("Description")),
			array("name"=>"facsimileTelephoneNumber",	"data_type"=>"text",		"display_name"=>gettext("Fax Number")),
			array("name"=>"givenName",			"data_type"=>"text",		"display_name"=>gettext("Given Name")),
			array("name"=>"homePhone",			"data_type"=>"phone_number",	"display_name"=>gettext("Home Telephone Number")),
			array("name"=>"l",				"data_type"=>"text",		"display_name"=>gettext("Locality (e.g. Town/City)")),
			array("name"=>"member",				"data_type"=>"dn_list",		"display_name"=>gettext("Members")),
			array("name"=>"mail",				"data_type"=>"text",		"display_name"=>gettext("E-mail Address")),
			array("name"=>"mobile",				"data_type"=>"phone_number",	"display_name"=>gettext("Mobile/Cell Telephone Number")),
			array("name"=>"ou",				"data_type"=>"text",		"display_name"=>gettext("Organizational Unit Name")),
			array("name"=>"pager",				"data_type"=>"text",		"display_name"=>gettext("Pager Telephone Number")),
			array("name"=>"physicalDeliveryOfficeName",	"data_type"=>"text",		"display_name"=>gettext("Office")),
			array("name"=>"postalCode",			"data_type"=>"postcode",	"display_name"=>gettext("Postal Code")),
			array("name"=>"sn",				"data_type"=>"text",		"display_name"=>gettext("Surname")),
			array("name"=>"st",				"data_type"=>"text",		"display_name"=>gettext("State (or Province/County)")),
			array("name"=>"street",				"data_type"=>"text_area",	"display_name"=>gettext("Street Address")),
			array("name"=>"telephoneNumber",		"data_type"=>"phone_number",	"display_name"=>gettext("Telephone Number")),
			array("name"=>"thumbnailLogo",			"data_type"=>"image",		"display_name"=>gettext("Thumbnail Logo")),		// non-standard
			array("name"=>"thumbnailPhoto",			"data_type"=>"image",		"display_name"=>gettext("Thumbnail Photograph")),	// non-standard
			array("name"=>"title",				"data_type"=>"text",		"display_name"=>gettext("Job Title")),

			// Added for Windows Server 2003 (or inetOrgPerson kit for Windows 2000 Server)
			array("name"=>"jpegPhoto",			"data_type"=>"image",		"display_name"=>gettext("Photograph")),
			);

		// Structural object classes
		$this->object_schema = array(
			array("name"=>"organizationalUnit",		"icon"=>"folder.png",		"is_folder"=>true,"rdn_attrib"=>"ou","display_name"=>gettext("Organizational Unit"),"can_create"=>true),
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
