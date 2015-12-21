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

			array("name"=>"mozillaPostalAddress2",		"data_type"=>"text",		"display_name"=>gettext("Street Address 2")),
			array("name"=>"mozillaHomePostalAddress2",	"data_type"=>"text",		"display_name"=>gettext("Street Address 2")),
			array("name"=>"mozillaHomeFriendlyCountryName",	"data_type"=>"text",		"display_name"=>gettext("Country"))
			);

		// Structural object classes
		$this->object_schema = array(
			array("name"=>"mozillaAbPersonAlpha",		"icon"=>"contact24.png",	"is_folder"=>false,"can_create"=>true),
			array("name"=>"mozillaOrgPerson",		"icon"=>"contact24.png",	"is_folder"=>false,"can_create"=>true)
			);

		parent::__construct($ldap_server);
	}
}
?>
