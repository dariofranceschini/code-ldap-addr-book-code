<?php
/** Novell Portal Services (NPS) schema (partial) */

class novell_nps_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"bhGadgetList",				"data_type"=>"dn_list","display_name"=>gettext("Portal Gadget List")),
			array("name"=>"bhGUIDList",				"data_type"=>"text_list","display_name"=>gettext("Portal GUID List")),
			array("name"=>"bhModuleList",				"data_type"=>"dn_list","display_name"=>gettext("Portal Module List")),
			array("name"=>"bhParentModuleList",			"data_type"=>"dn_list","display_name"=>gettext("Parent Module List")),
			array("name"=>"bhPortalDN",				"data_type"=>"dn_list","display_name"=>gettext("Portal DN")),
			array("name"=>"bhObjectList",				"data_type"=>"dn_list","display_name"=>gettext("Portal Object List")),
			array("name"=>"bhPageList",				"data_type"=>"dn_list","display_name"=>gettext("Portal Page List")),
			array("name"=>"bhPageSetList",				"data_type"=>"dn_list","display_name"=>gettext("Portal Page Set List")),
			array("name"=>"bhThemeList",				"data_type"=>"dn_list","display_name"=>gettext("Portal Theme List")),
			array("name"=>"bhCommunityContainer",			"data_type"=>"dn_list","display_name"=>gettext("Community Container")),

			// should be list of text_area
			array("name"=>"bhConfig",				"data_type"=>"text_area","display_name"=>gettext("Portal Configuration")),
			);

		// Structural object classes
		$this->object_schema = array(
			array("name"=>"bhGadget",	"icon"=>"novell/bhgadget.png",	"is_folder"=>false),
			array("name"=>"bhModule",	"icon"=>"novell/bhmodule.png",	"is_folder"=>false),
			array("name"=>"bhPage",		"icon"=>"novell/bhpage.png",	"is_folder"=>false),
			array("name"=>"bhPageSet",	"icon"=>"novell/bhpageset.png",	"is_folder"=>false),
			array("name"=>"bhPortal",	"icon"=>"novell/bhportal.png",	"is_folder"=>false),
			array("name"=>"bhTheme",	"icon"=>"logo24.png",		"is_folder"=>false)
			);

		parent::__construct($ldap_server);
	}
}
?>
