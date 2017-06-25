<?php
/** Novell xTier schema */

class novell_xtier_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		// Attributes
		$this->attribute_schema = array(
			array("name"=>"xTier-iFolderPassphrase",	"data_type"=>"text"),
			array("name"=>"xTier-LocationList",		"data_type"=>"dn_list"),
			array("name"=>"xTier-URL",			"data_type"=>"text")
			);

		// Object classes
		$this->object_schema = array(
			array("name"=>"xTier-StorageLocation",		"icon"=>"novell/xtier-storage-loc.png",		"is_folder"=>false),
			array("name"=>"xTier",				"icon"=>"generic24.png",			"class_type"=>"auxiliary"),
			array("name"=>"xTier-Locations",		"icon"=>"generic24.png",			"class_type"=>"auxiliary")
			);

		// Display layouts
		$ldap_server->add_display_layout("xTier-StorageLocation",array(
			array("section_name"=>gettext("xTier Storage Location"),
				"attributes"=>array(
					array("displayName",				gettext("Display Name"),	"generic24.png"),
					array("xTier-URL",				gettext("URL"),			"generic24.png")
					)
				)
			));

		// Auxiliary class display layouts

		$ldap_server->add_display_layout("xTier",array(
			array("section_name"=>gettext("xTier Settings"),
				"attributes"=>array(
					array("xTier-iFolderPassphrase")
					)
				)
			));

		$ldap_server->add_display_layout("xTier-Locations",array(
			array("section_name"=>gettext("xTier Storage Locations"),
				"attributes"=>array(
					array("xTier-LocationList")
					)
				)
			));

		parent::__construct($ldap_server);
	}
}
?>
