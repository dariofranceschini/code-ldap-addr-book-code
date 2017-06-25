<?php
/** Novell Access Manager (NAM) ID schema (partial) */

class novell_namid_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"uamPosixGidNumberDeletedMap",	"data_type"=>"download",	"display_name"=>gettext("Deleted GID Map")),
			array("name"=>"uamPosixGidNumberEnd",		"data_type"=>"text",		"display_name"=>gettext("End GID Number")),
			array("name"=>"uamPosixGidNumberLastAssigned",	"data_type"=>"text",		"display_name"=>gettext("Last Assigned GID")),
			array("name"=>"uamPosixGidNumberReuse",		"data_type"=>"yes_no",		"display_name"=>gettext("Reuse GID Numbers")),
			array("name"=>"uamPosixGidNumberStart",		"data_type"=>"text",		"display_name"=>gettext("Starting GID Number")),

			array("name"=>"uamPosixUidNumberDeletedMap",	"data_type"=>"download",	"display_name"=>gettext("Deleted UID Map")),
			array("name"=>"uamPosixUidNumberEnd",		"data_type"=>"text",		"display_name"=>gettext("End UID Number")),
			array("name"=>"uamPosixUidNumberLastAssigned",	"data_type"=>"text",		"display_name"=>gettext("Last Assigned UID")),
			array("name"=>"uamPosixUidNumberReuse",		"data_type"=>"yes_no",		"display_name"=>gettext("Reuse UID Numbers")),
			array("name"=>"uamPosixUidNumberStart",		"data_type"=>"text",		"display_name"=>gettext("Starting UID Number"))
			);

		parent::__construct($ldap_server);
	}
}
?>
