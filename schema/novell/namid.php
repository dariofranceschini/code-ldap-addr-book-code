<?php
/** Novell Access Manager (NAM) ID schema */

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

		// Object classes

		$this->object_schema = array(
			array("name"=>"uamPosixGidNumberInfo",		"icon"=>"generic24.png",	"class_type"=>"auxiliary","display_name"=>gettext("Group ID Mapping Information")),
			array("name"=>"uamPosixUidNumberInfo",		"icon"=>"generic24.png",	"class_type"=>"auxiliary","display_name"=>gettext("User ID Mapping Information"))
			);

		// Auxiliary class display layouts

		$ldap_server->add_display_layout("uamPosixGidNumberInfo",array(
			array("section_name"=>gettext("Group ID Mapping"),
				"attributes"=>array(
					array("uamPosixGidNumberStart",		gettext("Starting GID"),			"generic24.png"),
					array("uamPosixGidNumberEnd",		gettext("End GID"),				"generic24.png"),
					array("uamPosixGidNumberLastAssigned",	gettext("Last Assigned GID"),			"generic24.png"),
					array("uamPosixGidNumberReuse",		gettext("Reuse GID Numbers"),			"generic24.png"),
					array("uamPosixGidNumberDeletedMap",	gettext("Deleted GID Map"),			"generic24.png")
					)
				)
			));

		$ldap_server->add_display_layout("uamPosixUidNumberInfo",array(
			array("section_name"=>gettext("User ID Mapping"),
				"attributes"=>array(
					array("uamPosixUidNumberStart",		gettext("Starting UID"),			"generic24.png"),
					array("uamPosixUidNumberEnd",		gettext("End UID"),				"generic24.png"),
					array("uamPosixUidNumberLastAssigned",	gettext("Last Assigned UID"),			"generic24.png"),
					array("uamPosixUidNumberReuse",		gettext("Reuse UID Numbers"),			"generic24.png"),
					array("uamPosixUidNumberDeletedMap",	gettext("Deleted UID Map"),			"generic24.png")
					)
				)
			));

		parent::__construct($ldap_server);
	}
}
?>
