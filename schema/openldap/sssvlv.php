<?php
/** OpenLDAP Server Side Sorting and Virtual List View Overlay On-Line Configuration (OLC) schema */

class openldap_sssvlv_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		// Attributes
		$this->attribute_schema = array(
			array("name"=>"olcSssVlvMax",			"data_type"=>"text",		"display_name"=>gettext("Maximum Concurrent Sort Requests")),
			array("name"=>"olcSssVlvMaxKeys",		"data_type"=>"text",		"display_name"=>gettext("Maximum Keys per Sort Request")),
			array("name"=>"olcSssVlvMaxPerConn",		"data_type"=>"text",		"display_name"=>gettext("Maximum Concurrent Paged Search Requests per Connection"))
			);

		// Structural object classes
		$this->object_schema = array(
			array("name"=>"olcSssVlvConfig",			"icon"=>"openldap/overlay.png",		"is_folder"=>false,"rdn_attrib"=>"olcOverlay","display_name"=>gettext("Server Side Sorting and Virtual List View Overlay"),"parent_class"=>"olcOverlayConfig")
			);

		parent::__construct($ldap_server);
	}
}
?>
