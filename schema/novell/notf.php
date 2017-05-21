<?php
/** Novell SMTP/eMail Notifications schema (partial) */

class novell_notf_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		// Object classes
		$this->object_schema = array(
			array("name"=>"notfTemplateCollection",			"icon"=>"novell/notification-template-collection.png",	"is_folder"=>true),
			array("name"=>"notfMergeTemplate",			"icon"=>"document-edit.png",				"is_folder"=>false),
			);

		parent::__construct($ldap_server);
	}
}
?>
