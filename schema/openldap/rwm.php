<?php
/** OpenLDAP Rewrite/Remap Overlay On-Line Configuration (OLC) schema */

class openldap_rwm_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"olcRwmDropUnrequested",		"data_type"=>"yes_no",		"display_name"=>gettext("Drop Attributes Not Explicitly Requested")),
			array("name"=>"olcRwmMap",			"data_type"=>"text_list",	"display_name"=>gettext("Attribute/Object Class Mapping Rules")),
			array("name"=>"olcRwmNormalizeMapped",		"data_type"=>"yes_no",		"display_name"=>gettext("Normalise Mapped Attribute/Object Classes")),
			array("name"=>"olcRwmRewrite",			"data_type"=>"text_list",	"display_name"=>gettext("String Rewriting Rule")),
			array("name"=>"olcRwmTFSupport",		"data_type"=>"text",		"display_name"=>gettext("True/False Filter Support"))		// rfc4526
			);

		// Structural object classes
		$this->object_schema = array(
			array("name"=>"olcRwmConfig",			"icon"=>"openldap/overlay.png",		"is_folder"=>false,"rdn_attrib"=>"olcOverlay","display_name"=>gettext("Rewrite/Remap Overlay"),"parent_class"=>"olcOverlayConfig")
			);

		// Display layouts
		$ldap_server->add_display_layout("olcRwmConfig",array(
			array("section_name"=>gettext("Rewrite/Remap Overlay Settings"),"new_row"=>true,
				"attributes"=>array(
					array("olcOverlay",			gettext("Overlay Object Name"),				"openldap/overlay.png"),
					array("olcRwmTFSupport",		gettext("True/False Filter Support"),			"generic24.png"),
					array("olcRwmNormalizeMapped",		gettext("Normalise Mapped Attribute/Object Classes"),	"generic24.png"),
					array("olcRwmDropUnrequested",		gettext("Drop Attributes Not Explicitly Requested"),	"generic24.png")
					)
				),
			array("section_name"=>gettext("Attribute/Object Class Mapping Rules"),"new_row"=>true,
				"attributes"=>array(
					array("olcRwmMap")
					)
				),
			array("section_name"=>gettext("String Rewriting Rules"),"new_row"=>true,
				"attributes"=>array(
					array("olcRwmRewrite")
					)
				)
			));

		parent::__construct($ldap_server);
	}

	function populate_for_create_olcRwmConfig(&$ldap_server,&$entry)
	{
		$ldap_server->assign_ordered_sequence_rdn($entry,"olcOverlayConfig","rwm");
		$this->add_attrib_value($ldap_server,$entry,"olcRwmNormalizeMapped","FALSE");
		$this->add_attrib_value($ldap_server,$entry,"olcRwmDropUnrequested","TRUE");

		// Supported by OpenLDAP, but not necessarily supported by remote proxy/metadirectory
		// backends.
		$this->add_attrib_value($ldap_server,$entry,"olcRwmTFSupport","FALSE");
	}

	function before_create_olcRwmConfig(&$ldap_server,&$entry)
	{
		$ldap_server->ensure_openldap_module_loaded("rwm");
	}
}
?>
