<?php
/** Novell WAN Traffic Manager schema (partial) */

class novell_wanman_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"wANMANWANPolicy","data_type"=>"text_list","display_name"=>gettext("WAN Traffic Manager Policy List")),
			);

		// Structural object classes
		$this->object_schema = array(
			array("name"=>"wANMANLANArea",		"icon"=>"novell/wanman-lan-area.png",	"is_folder"=>false,"display_name"=>gettext("WAN Traffic Manager LAN Area")),
			);

		parent::__construct($ldap_server);
	}
}
?>
