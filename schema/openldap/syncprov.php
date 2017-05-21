<?php
/** OpenLDAP Configuration (OLC) Schema for Sync Provider Overlay */

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

		// Object classes
		$this->object_schema = array(
			array("name"=>"olcSyncProvConfig",			"icon"=>"openldap/overlay.png",		"is_folder"=>false,"rdn_attrib"=>"olcOverlay","display_name"=>gettext("Sync Provider Overlay"),"parent_class"=>"olcOverlayConfig","can_create"=>true,"create_method"=>"atomic")
			);

		// Display layouts
		$ldap_server->add_display_layout("olcSyncProvConfig",array(
			array("section_name"=>gettext("Sync Provider Overlay Settings"),"new_row"=>true,
				"attributes"=>array(
					array("olcOverlay",			gettext("Overlay Object Name"),				"openldap/overlay.png"),
					array("olcSpCheckpoint",		gettext("Checkpoint Interval (Operations, Minutes)"),	"generic24.png"),
					array("olcSpSessionlog",		gettext("In-Memory Session Log Size (Operations)"),	"generic24.png"),
					array("olcSpNoPresent",			gettext("Skip 'Present' Phase of Sync Refresh"),	"generic24.png"),
					array("olcSpReloadHint",		gettext("Observe Reload Hint in Request Control"),	"generic24.png")
					)
				)
			));

		parent::__construct($ldap_server);
	}

	function populate_for_create_olcSyncProvConfig(&$ldap_server,&$entry)
	{
		$ldap_server->assign_ordered_sequence_rdn($entry,"olcOverlayConfig","syncprov");
		$this->add_attrib_value($ldap_server,$entry,"olcSpNoPresent","FALSE");
		$this->add_attrib_value($ldap_server,$entry,"olcSpReloadHint","FALSE");
	}

	function before_create_olcSyncProvConfig(&$ldap_server,&$entry)
	{
		$ldap_server->ensure_openldap_module_loaded("syncprov");
	}
}
?>
