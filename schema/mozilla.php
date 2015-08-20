<?php
/** MozillaAbPersonAlpha.schema */

class mozilla_schema extends ldap_schema
{
	var $attribute_schema = array(
		// mozillaAbPersonAlpha attributes:

		array("name"=>"mozillaCustom1",			"data_type"=>"text",		"display_name"=>"Custom 1"),
		array("name"=>"mozillaCustom2",			"data_type"=>"text",		"display_name"=>"Custom 2"),
		array("name"=>"mozillaCustom3",			"data_type"=>"text",		"display_name"=>"Custom 3"),
		array("name"=>"mozillaCustom4",			"data_type"=>"text",		"display_name"=>"Custom 4"),
		array("name"=>"mozillaHomeStreet",		"data_type"=>"text",		"display_name"=>"Street Address 1"),
		array("name"=>"mozillaHomeStreet2",		"data_type"=>"text",		"display_name"=>"Street Address 2"),
		array("name"=>"mozillaHomeLocalityName",	"data_type"=>"text",		"display_name"=>"City"),
		array("name"=>"mozillaHomeState",		"data_type"=>"text",		"display_name"=>"County"),
		array("name"=>"mozillaHomePostalCode",		"data_type"=>"text",		"display_name"=>"Post Code"),
		array("name"=>"mozillaHomeCountryName",		"data_type"=>"country_code",	"display_name"=>"Country"),
		array("name"=>"mozillaHomeUrl",			"data_type"=>"text",		"display_name"=>"Web Page"),
		array("name"=>"mozillaWorkStreet2",		"data_type"=>"text",		"display_name"=>"Street Address 2"),
		array("name"=>"mozillaWorkUrl",			"data_type"=>"text",		"display_name"=>"Web Page"),
		array("name"=>"mozillaNickname",		"data_type"=>"text",		"display_name"=>"Nickname"),
		array("name"=>"mozillaSecondEmail",		"data_type"=>"text",		"display_name"=>"Additional E-Mail"),
		array("name"=>"mozillaUseHtmlMail",		"data_type"=>"use_html_mail",	"display_name"=>"Preferred Message Format"),
		array("name"=>"nsAIMid",			"data_type"=>"text",		"display_name"=>"Chat Name (AIM)"),

		// mozillaOrgPerson attributes (additional to mozillaAbPersonAlpha):

		array("name"=>"mozillaPostalAddress2",		"data_type"=>"text",		"display_name"=>"Street Address 2"),
		array("name"=>"mozillaHomePostalAddress2",	"data_type"=>"text",		"display_name"=>"Street Address 2"),
		array("name"=>"mozillaHomeFriendlyCountryName",	"data_type"=>"text",		"display_name"=>"Country")
		);

	// Structural object classes
	var $object_schema = array(
		array("name"=>"mozillaAbPersonAlpha",		"icon"=>"contact24.png",	"is_folder"=>false,"can_create"=>true),
		array("name"=>"mozillaOrgPerson",		"icon"=>"contact24.png",	"is_folder"=>false,"can_create"=>true)
		);
}
?>
