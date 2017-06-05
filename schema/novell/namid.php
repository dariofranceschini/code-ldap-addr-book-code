<?php
/** Novell Access Manager (NAM) ID schema (partial) */

class novell_namid_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"uamPosixGidNumberReuse",		"data_type"=>"yes_no",		"display_name"=>gettext("Reuse GID Numbers")),
			array("name"=>"uamPosixUidNumberReuse",		"data_type"=>"yes_no",		"display_name"=>gettext("Reuse UID Numbers")),
			);

		parent::__construct($ldap_server);
	}
}
?>
