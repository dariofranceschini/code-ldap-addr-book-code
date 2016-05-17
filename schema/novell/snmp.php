<?php
/** Novell SNMP schema (partial) */

class novell_snmp_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"snmpServerList",		"data_type"=>"dn_list",		"display_name"=>gettext("SNMP Server List")),
			array("name"=>"snmpTrapDisable",	"data_type"=>"yes_no",		"display_name"=>gettext("SNMP Traps Disabled")),
			array("name"=>"snmpGroupDN",		"data_type"=>"dn_list",		"display_name"=>gettext("SNMP Group DN")),
			);

		// Structural object classes
		$this->object_schema = array(
			// SNMP
			array("name"=>"snmpGroup",				"icon"=>"novell/snmp-group.png",	"is_folder"=>false),
			);

		parent::__construct($ldap_server);
	}
}
?>
