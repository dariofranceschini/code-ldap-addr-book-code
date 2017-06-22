<?php
/** Novell Network Information Service (NIS) schema (object classes only)

    The auxiliary object classes "posixAccount" and "shadowAccount" in RFC2307
    are defined in Novell's User Account Management (UAM) schema rather than
    the NIS schema.
*/

class novell_nis_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		// Object classes

		$this->object_schema = array(
			// standard object classes defined in RFC2307
			array("name"=>"ipNetwork",		"icon"=>"ip-network.png",		"is_folder"=>false,"display_name"=>gettext("IP Network")),
			array("name"=>"ipProtocol",		"icon"=>"ip-protocol.png",		"is_folder"=>false,"display_name"=>gettext("IP Protocol")),
			array("name"=>"ipService",		"icon"=>"ip-service.png",		"is_folder"=>false,"display_name"=>gettext("IP Service")),
			array("name"=>"nisMap",			"icon"=>"nis-map.png",			"is_folder"=>true,"display_name"=>gettext("NIS Map")),
			array("name"=>"nisNetgroup",		"icon"=>"nis-netgroup.png",		"is_folder"=>false,"display_name"=>gettext("Netgroup")),
			array("name"=>"nisObject",		"icon"=>"nis-object.png",		"is_folder"=>false,"display_name"=>gettext("NIS Map Entry")),
			array("name"=>"oncRpc",			"icon"=>"onc-rpc.png",			"is_folder"=>false,"display_name"=>gettext("ONC RPC Binding")),
			// "posixGroup" (from RFC2307) is defined in UAM schema instead

			// Auxiliary classes in RFC2307 which Novell implements as structural classes instead
			array("name"=>"bootableDevice",		"icon"=>"bootable-device.png",		"is_folder"=>false,"parent_class"=>"device"),
			array("name"=>"ieee802Device",		"icon"=>"novell/ieee802-device.png",	"is_folder"=>false,"parent_class"=>"device"),
			array("name"=>"ipHost",			"icon"=>"novell/ip-host.png",		"is_folder"=>false,"parent_class"=>"device"),

			// Novell proprietary object classes
			array("name"=>"nisDomain",		"icon"=>"novell/nis-domain.png",	"is_folder"=>true),
			array("name"=>"nisServer",		"icon"=>"novell/nis-server.png",	"is_folder"=>false)
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
