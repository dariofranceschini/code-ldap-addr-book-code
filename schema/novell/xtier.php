<?php
/** Novell xTier schema (partial) */

class novell_xtier_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		// Structural object classes
		$this->object_schema = array(
			array("name"=>"xTier-StorageLocation",		"icon"=>"novell/xtier-storage-loc.png","is_folder"=>false),
			);

		// Display layouts
		$ldap_server->add_display_layout("xTier-StorageLocation",array(
			array("section_name"=>gettext("xTier Storage Location"),
				"attributes"=>array(
					array("xtier-url",				gettext("URL"),			"generic24.png")
					)
				)
			));

		parent::__construct($ldap_server);
	}
}
?>
