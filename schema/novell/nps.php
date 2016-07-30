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

		// Display layouts
		$ldap_server->add_display_layout("bhGadget",array(
			array("section_name"=>gettext("Portal Gadget"),
				"attributes"=>array(
					array("cn",			gettext("Name"),			"generic24.png"),
					array("bhLocation",		gettext("Location"),			"generic24.png"),
					array("bhVersion",		gettext("Version"),			"generic24.png"),
					array("bhObjectGUID",		gettext("Object GUID"),			"generic24.png"),
					array("bhInstanceClassName",	gettext("Instance Class Name"),		"generic24.png")
					)
				),
			array("section_name"=>gettext("Configuration"),"new_row"=>true,
				"attributes"=>array(
					array("bhConfig")
					)
				),
			array("section_name"=>gettext("Other Objects"),"new_row"=>true,
				"attributes"=>array(
					array("bhObjectList")
					)
				)
			));

		$ldap_server->add_display_layout("bhModule",array(
			array("section_name"=>gettext("Portal Gadget"),
				"attributes"=>array(
					array("cn",			gettext("Name"),			"generic24.png"),
					array("bhLocation",		gettext("Location"),			"generic24.png"),
					array("bhVersion",		gettext("Version"),			"generic24.png"),
					array("bhObjectGUID",		gettext("Object GUID"),			"generic24.png"),
					array("bhPortalDN",		gettext("Portal DN"),			"generic24.png")
					)
				),
			array("section_name"=>gettext("Configuration"),"new_row"=>true,
				"attributes"=>array(
					array("bhConfig")
					)
				),
			array("section_name"=>gettext("Gadgets"),"new_row"=>true,
				"attributes"=>array(
					array("bhGadgetList")
					)
				),
			array("section_name"=>gettext("Page Sets"),"new_row"=>true,
				"attributes"=>array(
					array("bhPageSetList")
					)
				),
			array("section_name"=>gettext("Themes"),"new_row"=>true,
				"attributes"=>array(
					array("bhThemeList")
					)
				)
			));

		$ldap_server->add_display_layout("bhPortal",array(
			array("section_name"=>gettext("Portal"),
				"attributes"=>array(
					array("cn",			gettext("Name"),			"generic24.png"),
					array("bhLocation",		gettext("Location"),			"generic24.png"),
					array("bhVersion",		gettext("Version"),			"generic24.png"),
					array("bhObjectGUID",		gettext("Object GUID"),			"generic24.png"),
					array("bhCommunityContainer",	gettext("Community Container"),		"generic24.png")
					)
				),
			array("section_name"=>gettext("Configuration"),"new_row"=>true,
				"attributes"=>array(
					array("bhConfig")
					)
				),
			array("section_name"=>gettext("Modules"),"new_row"=>true,
				"attributes"=>array(
					array("bhModuleList")
					)
				),
			array("section_name"=>gettext("Pages"),"new_row"=>true,
				"attributes"=>array(
					array("bhPageList")
					)
				),
			array("section_name"=>gettext("Page Sets"),"new_row"=>true,
				"attributes"=>array(
					array("bhPageSetList")
					)
				),
			array("section_name"=>gettext("Themes"),"new_row"=>true,
				"attributes"=>array(
					array("bhThemeList")
					)
				),
			array("section_name"=>gettext("Other Objects"),"new_row"=>true,
				"attributes"=>array(
					array("bhObjectList")
					)
				)
			));

		$ldap_server->add_display_layout("bhPage",array(
			array("section_name"=>gettext("Portal Page"),
				"attributes"=>array(
					array("cn",			gettext("Name"),			"generic24.png"),
					array("bhObjectGUID",		gettext("Object GUID"),			"generic24.png"),
					array("bhPortalDN",		gettext("Portal DN"),			"generic24.png")
					)
				),
			array("section_name"=>gettext("Configuration"),"new_row"=>true,
				"attributes"=>array(
					array("bhConfig")
					)
				),
			array("section_name"=>gettext("Parent Modules"),"new_row"=>true,
				"attributes"=>array(
					array("bhParentModuleList")
					)
				),
			array("section_name"=>gettext("Other Objects"),"new_row"=>true,
				"attributes"=>array(
					array("bhObjectList")
					)
				)
			));

		$ldap_server->add_display_layout("bhTheme",array(
			array("section_name"=>gettext("Portal Theme"),
				"attributes"=>array(
					array("cn",			gettext("Name"),			"generic24.png"),
					array("bhObjectGUID",		gettext("Object GUID"),			"generic24.png"),
					array("bhPortalDN",		gettext("Portal DN"),			"generic24.png")
					)
				),
			array("section_name"=>gettext("Configuration"),"new_row"=>true,
				"attributes"=>array(
					array("bhConfig")
					)
				),
			array("section_name"=>gettext("Parent Modules"),"new_row"=>true,
				"attributes"=>array(
					array("bhParentModuleList")
					)
				),
			array("section_name"=>gettext("Other Objects"),"new_row"=>true,
				"attributes"=>array(
					array("bhObjectList")
					)
				)
			));

		$ldap_server->add_display_layout("bhPageSet",array(
			array("section_name"=>gettext("Portal Page Set"),
				"attributes"=>array(
					array("cn",			gettext("Name"),			"generic24.png"),
					array("bhObjectGUID",		gettext("Object GUID"),			"generic24.png"),
					array("bhPortalDN",		gettext("Portal DN"),			"generic24.png")
					)
				),
			array("section_name"=>gettext("Configuration"),"new_row"=>true,
				"attributes"=>array(
					array("bhConfig")
					)
				),
			array("section_name"=>gettext("Parent Modules"),"new_row"=>true,
				"attributes"=>array(
					array("bhParentModuleList")
					)
				),
			array("section_name"=>gettext("GUIDs"),"new_row"=>true,
				"attributes"=>array(
					array("bhGUIDList")
					)
				)
			));

		parent::__construct($ldap_server);
	}
}
?>
