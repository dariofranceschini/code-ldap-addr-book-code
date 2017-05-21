<?php
/** Novell Storage Services schema (partial) */

class novell_nssfs_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		// Object classes
		$this->object_schema = array(
			array("name"=>"nssfsPool",			"icon"=>"novell/nssfs-pool.png",	"is_folder"=>false)
			);

		// Display layouts
		$ldap_server->add_display_layout("nssfsPool",array(
			array("section_name"=>gettext("Storage Pool"),
				"attributes"=>array(
					array("hostServer",			gettext("Host Server"),					"generic24.png"),
					array("hostResourceName",		gettext("Host Resource Name"),				"generic24.png"),
					array("nssfsPoolID",			gettext("Pool ID"),					"generic24.png")
					)
				)
			));

		parent::__construct($ldap_server);
	}
}
?>
