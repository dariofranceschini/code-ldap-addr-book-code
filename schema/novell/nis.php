<?php
/** Novell Network Information Service (NIS) schema (partial) */

class novell_nis_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		// Structural object classes

		$this->object_schema = array(
			array("name"=>"nisServer",		"icon"=>"novell/nis-server.png",		"is_folder"=>false),
			);

		// Display layouts

		$ldap_server->add_display_layout("nisServer",array(
			array("section_name"=>gettext("NIS Server"),
				"attributes"=>array(
					array("ipHostNumber",		gettext("IP Address"),			"generic24.png")
					)
				)
			));

		parent::__construct($ldap_server);
	}
}
?>
