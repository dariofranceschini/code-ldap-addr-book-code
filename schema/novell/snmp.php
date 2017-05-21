<?php
/** Novell SNMP schema (partial) */

class novell_snmp_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"snmpServerList",		"data_type"=>"dn_list",		"display_name"=>gettext("SNMP Server List")),
			array("name"=>"snmpTrapDisable",	"data_type"=>"yes_no",		"display_name"=>gettext("SNMP Traps Disabled")),
			array("name"=>"snmpGroupDN",		"data_type"=>"dn",		"display_name"=>gettext("SNMP Group DN")),
			);

		// Object classes
		$this->object_schema = array(
			array("name"=>"snmpGroup",				"icon"=>"novell/snmp-group.png",	"is_folder"=>false),
			);

		// Display layouts
		$ldap_server->add_display_layout("snmpGroup",array(
			array("section_name"=>gettext("SNMP Group"),"new_row"=>true,
				"attributes"=>array(
					array("snmpServerList",			gettext("SNMP Servers"),			"generic24.png"),
					array("snmpTrapInterval",		gettext("Default SNMP Time Interval (s)"),	"generic24.png"),
					array("snmpTrapDisable",		gettext("Disable all SNMP Traps"),		"generic24.png"),

				/*	TODO: decode SNMP trap configuration data
					array("snmpTrapDescription",		gettext("Trap Descriptions"),			"generic24.png"),
					array("snmpTrapConfig",			gettext("Trap Configuration"),			"generic24.png"), */

					array("version",			gettext("Version"),				"generic24.png"),
					)
				)
			));

		parent::__construct($ldap_server);
	}
}
?>
