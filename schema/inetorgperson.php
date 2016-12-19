<?php
/** Internet/Intranet Organizational Person Schema (inetorgperson.schema)

    The inetOrgPerson object class extends the X.521 standard's
    organizationalPerson class with additional attributes to better
    meet the requirements of Internet/Intranet directory service
    deployments.

    @see https://www.ietf.org/rfc/rfc2798.txt
*/

class inetorgperson_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"carLicense",			"data_type"=>"text",		"display_name"=>gettext("Vehicle License/Registration")),
			array("name"=>"departmentNumber",		"data_type"=>"text",		"display_name"=>gettext("Department Number")),
			array("name"=>"displayName",			"data_type"=>"text",		"display_name"=>gettext("Display/Preferred Name")),
			array("name"=>"employeeNumber",			"data_type"=>"text",		"display_name"=>gettext("Employee Number")),
			array("name"=>"employeeType",			"data_type"=>"text",		"display_name"=>gettext("Employee Type")),
			array("name"=>"jpegPhoto",			"data_type"=>"image",		"display_name"=>gettext("Photograph")),
			array("name"=>"preferredLanguage",		"data_type"=>"text",		"display_name"=>gettext("Preferred Language")),
			array("name"=>"userPKCS12;binary",		"data_type"=>"download_list",	"display_name"=>gettext("PKCS #12 PFX Data")),
			array("name"=>"userSMIMECertificate;binary",	"data_type"=>"download_list",	"display_name"=>gettext("S/MIME Certificate"))
			);

		// Structural object classes
		$this->object_schema = array(
			array("name"=>"inetOrgPerson",			"icon"=>"user24.png",		"is_folder"=>false,"required_attribs"=>"sn","can_create"=>true,"parent_class"=>"organizationalPerson")
			);

		parent::__construct($ldap_server);
	}
}
?>
