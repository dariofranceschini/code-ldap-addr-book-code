<?php
/** Novell Mandatory Access Control Service (MASV) schema (partial) */

class novell_masv_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		// Object classes
		$this->object_schema = array(
			array("name"=>"mASVSecurityPolicy","icon"=>"novell/security-policy.png","is_folder"=>false,"display_name"=>gettext("Security Policy")),
			);

		parent::__construct($ldap_server);
	}
}
?>
