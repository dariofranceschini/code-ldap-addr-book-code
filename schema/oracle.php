<?php
/** Schema container for Oracle */

class oracle_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		// component schema
		$ldap_server->add_schema("oracle/alias");
		$ldap_server->add_schema("oracle/oidbase");
		$ldap_server->add_schema("oracle/oidnet");
		$ldap_server->add_schema("oracle/oidrdbms");
	}
}
?>
