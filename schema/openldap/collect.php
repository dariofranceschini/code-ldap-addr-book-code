<?php
/** OpenLDAP Collective Attributes Overlay On-Line Configuration (OLC) schema */

class openldap_collect_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"olcCollectInfo",			"data_type"=>"text",		"display_name"=>gettext("Ancestor DN/Collective Attributes"))
			);

		// Structural object classes
		$this->object_schema = array(
			array("name"=>"olcCollectConfig",		"icon"=>"openldap/overlay.png",		"is_folder"=>false,"rdn_attrib"=>"olcOverlay","display_name"=>gettext("Collective Attributes Overlay"),"parent_class"=>"olcOverlayConfig")
			);

		parent::__construct($ldap_server);
	}
}
?>
