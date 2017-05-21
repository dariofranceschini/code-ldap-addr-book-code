<?php
/** Novell xTier schema (partial) */

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
			array("name"=>"xTier-StorageLocation",		"icon"=>"novell/xtier-storage-loc.png","is_folder"=>false),
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

		parent::__construct($ldap_server);
	}
}
?>
