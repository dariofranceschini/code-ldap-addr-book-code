<?php
/** OpenLDAP Sync Provider Overlay On-Line Configuration (OLC) schema */

class openldap_syncprov_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"olcSpCheckpoint",		"data_type"=>"text",		"display_name"=>gettext("Checkpoint Intervals")),
			array("name"=>"olcSpSessionlog",		"data_type"=>"text",		"display_name"=>gettext("In-memory Session Log Size")),
			array("name"=>"olcSpNoPresent",			"data_type"=>"yes_no",		"display_name"=>gettext("Skip 'Present' Phase of Sync Refresh")),
			array("name"=>"olcSpReloadHint",		"data_type"=>"yes_no",		"display_name"=>gettext("Observe Reload Hint in Request Control"))
			);

		// Structural object classes
		$this->object_schema = array(
			array("name"=>"olcSyncProvConfig",			"icon"=>"openldap/overlay.png",		"is_folder"=>false,"rdn_attrib"=>"olcOverlay","display_name"=>gettext("Sync Provider Overlay"),"parent_class"=>"olcOverlayConfig")
			);

		parent::__construct($ldap_server);
	}
}
?>
