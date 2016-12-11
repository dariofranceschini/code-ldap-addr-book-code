<?php
/** Novell DirXML schema (partial) */

class novell_vrschema_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		// Structural object classes
		$this->object_schema = array(
			array("name"=>"DirXML-Driver",				"icon"=>"novell/dirxml-driver.png",	"is_folder"=>true),
			array("name"=>"DirXML-DriverSet",			"icon"=>"novell/dirxml-driverset.png",	"is_folder"=>true),
			array("name"=>"DirXML-Job",				"icon"=>"novell/dirxml-job.png",	"is_folder"=>true),
			array("name"=>"DirXML-Publisher",			"icon"=>"novell/dirxml-publisher.png",	"is_folder"=>true),
			array("name"=>"DirXML-Resource",			"icon"=>"novell/dirxml-resource.png",	"is_folder"=>false),
			array("name"=>"DirXML-Subscriber",			"icon"=>"novell/dirxml-subscriber.png",	"is_folder"=>true)
			);

		parent::__construct($ldap_server);
	}
}
?>
