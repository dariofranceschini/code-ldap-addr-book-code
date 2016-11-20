<?php
/** OpenLDAP Server Side Sorting and Virtual List View Overlay On-Line Configuration (OLC) schema (partial) */

class openldap_sssvlv_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		// Structural object classes
		$this->object_schema = array(
			array("name"=>"olcSssVlvConfig",			"icon"=>"openldap/overlay.png",		"is_folder"=>false,"rdn_attrib"=>"olcOverlay","display_name"=>gettext("Server Side Sorting and Virtual List View Overlay"),"parent_class"=>"olcOverlayConfig")
			);

		parent::__construct($ldap_server);
	}
}
?>
