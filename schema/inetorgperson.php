<?php
/** Internet/Intranet Organizational Person Schema (inetorgperson.schema)

    The inetOrgPerson object class extends the X.521 standard's
    organizationalPerson class with additional attributes to better
    meet the requirements of Internet/Intranet directory service
    deployments.

    (Partial implementation only)

    @see https://www.ietf.org/rfc/rfc2798.txt
*/

class inetorgperson_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"displayName",			"data_type"=>"text",		"display_name"=>gettext("Display/Preferred Name")),
			array("name"=>"jpegPhoto",			"data_type"=>"image",		"display_name"=>gettext("Photograph"))
			);

		// Structural object classes
		$this->object_schema = array(
			array("name"=>"inetOrgPerson",			"icon"=>"user24.png",		"is_folder"=>false,"required_attribs"=>"sn","can_create"=>true)
			);

		parent::__construct($ldap_server);
	}
}
?>
