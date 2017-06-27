<?php
/** MozillaAbPersonAlpha.schema */

class mozilla_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			// mozillaAbPersonAlpha attributes:

			array("name"=>"mozillaCustom1",			"data_type"=>"text",		"display_name"=>gettext("Custom 1")),
			array("name"=>"mozillaCustom2",			"data_type"=>"text",		"display_name"=>gettext("Custom 2")),
			array("name"=>"mozillaCustom3",			"data_type"=>"text",		"display_name"=>gettext("Custom 3")),
			array("name"=>"mozillaCustom4",			"data_type"=>"text",		"display_name"=>gettext("Custom 4")),
			array("name"=>"mozillaHomeStreet",		"data_type"=>"text",		"display_name"=>gettext("Street Address 1")),
			array("name"=>"mozillaHomeStreet2",		"data_type"=>"text",		"display_name"=>gettext("Street Address 2")),
			array("name"=>"mozillaHomeLocalityName",	"data_type"=>"text",		"display_name"=>gettext("City")),
			array("name"=>"mozillaHomeState",		"data_type"=>"text",		"display_name"=>gettext("County")),
			array("name"=>"mozillaHomePostalCode",		"data_type"=>"text",		"display_name"=>gettext("Post Code")),
			array("name"=>"mozillaHomeCountryName",		"data_type"=>"country_code",	"display_name"=>gettext("Country")),
			array("name"=>"mozillaHomeUrl",			"data_type"=>"text",		"display_name"=>gettext("Web Page")),
			array("name"=>"mozillaWorkStreet2",		"data_type"=>"text",		"display_name"=>gettext("Street Address 2")),
			array("name"=>"mozillaWorkUrl",			"data_type"=>"text",		"display_name"=>gettext("Web Page")),
			array("name"=>"mozillaNickname",		"data_type"=>"text",		"display_name"=>gettext("Nickname")),
			array("name"=>"mozillaSecondEmail",		"data_type"=>"text",		"display_name"=>gettext("Additional E-Mail")),
			array("name"=>"mozillaUseHtmlMail",		"data_type"=>"use_html_mail",	"display_name"=>gettext("Preferred Message Format")),
			array("name"=>"nsAIMid",			"data_type"=>"text",		"display_name"=>gettext("Chat Name (AIM)")),

			// mozillaOrgPerson attributes (additional to mozillaAbPersonAlpha):

			array("name"=>"mozillaPostalAddress2",		"data_type"=>"text_area",	"display_name"=>gettext("Street Address 2")),
			array("name"=>"mozillaHomePostalAddress2",	"data_type"=>"text_area",	"display_name"=>gettext("Street Address 2")),
			array("name"=>"mozillaHomeFriendlyCountryName",	"data_type"=>"text",		"display_name"=>gettext("Country"))
			);

		// Object classes
		$this->object_schema = array(
			array("name"=>"mozillaAbPersonAlpha",		"icon"=>"contact24.png",	"class_type"=>"auxiliary","required_attribs"=>"cn","can_create"=>true),
			array("name"=>"mozillaOrgPerson",		"icon"=>"contact24.png",	"class_type"=>"auxiliary")
			);

		// Auxiliary class display layouts

		$ldap_server->add_display_layout("mozillaAbPersonAlpha,mozillaOrgPerson",array(
			array("section_name"=>gettext("Contact"),
				"attributes"=>array(
					array("givenName",				gettext("First"),			"contact24.png"),
					array("sn",					gettext("Last"),			"contact24.png"),
					array("displayName",				gettext("Display"),			"contact24.png"),
					// Not stored? - "always prefer display name over message header"
                                        array("mozillaNickname",			gettext("Nickname"),			"contact24.png"),
					array("mail",					gettext("E-Mail"),			"mail.png"),
					array("mozillaSecondEmail",			gettext("Additional E-Mail"),		"mail.png"),
					array("mozillaUseHtmlMail",			gettext("Preferred Message Format"),	"document.png"),
					)
				),
			array("section_name"=>gettext("Phone Numbers"),
				"attributes"=>array(
					array("telephoneNumber",			gettext("Work"),			"landline-phone.png"),
					array("homePhone",				gettext("Home"),			"landline-phone.png"),
					array("facsimileTelephoneNumber",		gettext("Fax"),				"fax.png"),
					array("pager",					gettext("Pager"),			"generic24.png"),
					array("mobile",					gettext("Mobile"),			"cell-phone.png"),
					)
				),
			array("section_name"=>gettext("Private"),"new_row"=>true,
				"attributes"=>array(
					array("mozillaHomeStreet:mozillaHomeStreet2",	gettext("Address"),			"address.png"),
					array("mozillaHomeLocalityName",		gettext("City"),			"generic24.png"),
					array("mozillaHomeState",			gettext("County"),			"generic24.png"),
					array("mozillaHomePostalCode",			gettext("Post Code"),			"generic24.png"),
					array("mozillaHomeCountryName",			gettext("Country"),			"country.png"),

					// Alternate representation for country (mozillaOrgPerson)
					// array("mozillaHomeFriendlyCountryName",	gettext("Country"),			"country.png"),

					array("mozillaHomeUrl",				gettext("Web Page"),			"labeled-uri.png"),

					// not stored? - birthday/age

					// Additional field in mozillaOrgPerson only - address in single field?:
					// mozillaHomePostalAddress2
					)
				),
			array("section_name"=>gettext("Work"),"new_row"=>true,
				"attributes"=>array(
					array("title",					gettext("Title"),			"id.png"),
					array("ou",					gettext("Department"),			"org.png"),
					array("o",					gettext("Organization"),		"company.png"),
					array("street:mozillaWorkStreet2",		gettext("Address"),			"address.png"),
					array("l",					gettext("City"),			"generic24.png"),
					array("st",					gettext("County"),			"generic24.png"),
					array("postalCode",				gettext("Post Code"),			"generic24.png"),
					array("c",					gettext("Country"),			"country.png"),

					// Alternate representation for country
					//array("co",					gettext("Country"),			"country.png"),

					array("mozillaWorkUrl",				gettext("Web Page"),			"labeled-uri.png"),

					// Additional field in mozillaOrgPerson only - address in single field?:
					// mozillaPostalAddress2
					)
				),
			array("section_name"=>gettext("Other"),"new_row"=>true,
				"attributes"=>array(
					array("mozillaCustom1",				gettext("Custom 1"),			"description.png"),
					array("mozillaCustom2",				gettext("Custom 2"),			"description.png"),
					array("mozillaCustom3",				gettext("Custom 3"),			"description.png"),
					array("mozillaCustom4",				gettext("Custom 4"),			"description.png"),
					array("description",				gettext("Notes"),			"description.png"),
					)
				),
			array("section_name"=>gettext("Chat"),"new_row"=>true,
				"attributes"=>array(
					// not stored? - Google Talk
					array("nsAIMid",				gettext("AIM"),				"chat.png"),
					// not stored? - Yahoo
					// not stored? - Skype
					// not stored? - QQ
					// not stored? - MSN
					// not stored? - ICQ
					// not stored? - Jabber ID
					// not stored? - IRC Nick
					)
				),
			// not stored? - photo
			//array("section_name"=>gettext("Photo"),
			//	"attributes"=>array(
			//		array("???",					gettext("Photo"),			"generic24.png"),
			//		)
			//	)
			));

		parent::__construct($ldap_server);
	}
}
?>
