<?php
/** OpenLDAP MemberOf Overlay Configuration schema (partial) */

class openldap_memberof_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"memberOf",			"data_type"=>"dn_list",		"display_name"=>gettext("Member Of"))
			);

		parent::__construct($ldap_server);
	}
}
?>
