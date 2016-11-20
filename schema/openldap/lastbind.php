<?php
/** OpenLDAP Last Bind Overlay On-Line Configuration (OLC) schema */

class openldap_lastbind_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"olcLastBindPrecision",		"data_type"=>"text",		"display_name"=>gettext("Authentication Timestamp Precision")),

			array("name"=>"authTimestamp",			"data_type"=>"text",		"display_name"=>gettext("Date/Time of Last Successful Authentication"))
			);

		// Structural object classes
		$this->object_schema = array(
			array("name"=>"olcLastBindConfig",			"icon"=>"openldap/overlay.png",		"is_folder"=>false,"rdn_attrib"=>"olcOverlay","display_name"=>gettext("Last Bind Overlay"),"parent_class"=>"olcOverlayConfig")
			);

		parent::__construct($ldap_server);
	}
}
?>
