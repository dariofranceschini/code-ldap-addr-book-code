<?php
/** Oracle Net Services Alias Schema (alias.schema) */

class oracle_alias_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"orclNetServiceAlias",		"data_type"=>"text"),
			);

		// Object classes
		$this->object_schema = array(
			array("name"=>"orclNetServiceAlias",		"icon"=>"oracle/oracle-netservice-alias.png",		"is_folder"=>false),
			);

		// Display layouts
		$ldap_server->add_display_layout("orclNetServiceAlias",array(
			array(
				"attributes"=>array(
					array("cn",			gettext("Alias Name"),					"generic24.png"),
					array("aliasedObjectName",	gettext("Aliased Net Service Object"),			"generic24.png"),
					)
				)
			));

		// orclNetServiceAlias class is not supported in Active Directory
		if($ldap_server->server_type == "ad")
			$ldap_server->delete_object_class("orclNetServiceAlias");

		parent::__construct($ldap_server);
	}
}
?>
