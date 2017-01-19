<?php
/** OpenLDAP AutoGroup Overlay On-Line Configuration (OLC) schema */

class openldap_autogroup_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"olcAGattrSet",			"data_type"=>"text_list",	"display_name"=>gettext("Attribute Set")),
			array("name"=>"olcAGmemberOfAd",		"data_type"=>"text",		"display_name"=>gettext("Member Of Attribute"))
			);

		// Structural object classes
		$this->object_schema = array(
			array("name"=>"olcAutomaticGroups",		"icon"=>"openldap/overlay.png",		"is_folder"=>false,"rdn_attrib"=>"olcOverlay","display_name"=>gettext("AutoGroup Overlay"),"parent_class"=>"olcOverlayConfig")
			);

		$ldap_server->add_display_layout("olcAutomaticGroups",array(
			array("section_name"=>gettext("AutoGroup Overlay Settings"),"new_row"=>true,
				"attributes"=>array(
					array("olcOverlay",			gettext("Overlay Object Name"),			"openldap/overlay.png"),
					array("olcAGmemberOfAd",		gettext("MemberOf Attribute"),			"generic24.png"),
					),
				),
			array("section_name"=>gettext("Attribute Sets"),"new_row"=>true,
				"attributes"=>array(
					array("olcAGattrSet"),
					),
				)
			));

		parent::__construct($ldap_server);
	}

	function populate_for_create_olcAutomaticGroups(&$ldap_server,&$entry)
	{
		$ldap_server->assign_ordered_sequence_rdn($entry,"olcOverlayConfig","autogroup");
		// TODO: default attribute set value: "groupOfURLs memberURL member"
		$this->add_attrib_value($ldap_server,$entry,"olcAGmemberOfAd","memberOf");
	}

	function before_create_olcAutomaticGroups(&$ldap_server,&$entry)
	{
		$ldap_server->ensure_openldap_module_loaded("autogroup");
	}
}
?>
