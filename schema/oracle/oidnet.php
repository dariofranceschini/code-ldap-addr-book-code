<?php
/** Oracle Net Services Schema (oidnet.schema) */

class oracle_oidnet_schema extends ldap_schema
{
        function __construct(&$ldap_server)
        {
		$this->attribute_schema = array(
			// binary data types not yet supported:
			//
			// array("name"=>"orclNetSourceRoute",		"data_type"=>"download"),
			// array("name"=>"orclNetLoadBalance",		"data_type"=>"download"),
			// array("name"=>"orclNetFailover",		"data_type"=>"download"),

			array("name"=>"orclNetSdu",			"data_type"=>"text"),
			array("name"=>"orclNetServer",			"data_type"=>"text"),
			array("name"=>"orclNetServiceName",		"data_type"=>"text"),
			array("name"=>"orclNetInstanceName",		"data_type"=>"text"),
			array("name"=>"orclNetHandlerName",		"data_type"=>"text"),
			array("name"=>"orclNetParamList",		"data_type"=>"text"),
			array("name"=>"orclNetAuthenticationType",	"data_type"=>"text"),
			array("name"=>"orclNetAuthParams",		"data_type"=>"text"),
			array("name"=>"orclNetAddressString",		"data_type"=>"text"),
			array("name"=>"orclNetProtocol",		"data_type"=>"text"),
			array("name"=>"orclNetShared",			"data_type"=>"text"),
			array("name"=>"orclNetAddrList",		"data_type"=>"text"),
			array("name"=>"orclNetProtocolStack",		"data_type"=>"text"),
			array("name"=>"orclNetDescList",		"data_type"=>"text"),
			array("name"=>"orclNetConnParamList",		"data_type"=>"text"),
			array("name"=>"orclNetAuthenticationService",	"data_type"=>"text"),
			);

		// Object classes
		$this->object_schema = array(
			// Oracle schema for Active Directory specifier (incorrectly) defines orclNetService as container object
			array("name"=>"orclNetService",			"icon"=>"oracle/oracle-netservice.png",		"is_folder"=>false,"display_name"=>gettext("Oracle NetService"),"can_create"=>true),
			array("name"=>"orclNetDescriptionList",		"icon"=>"generic24.png",			"is_folder"=>false),
			array("name"=>"orclNetDescription",		"icon"=>"generic24.png",			"is_folder"=>false),
			array("name"=>"orclNetAddressList",		"icon"=>"generic24.png",			"is_folder"=>false),
			array("name"=>"orclNetAddress",			"icon"=>"generic24.png",			"is_folder"=>false),
			);

		// Display layouts
		$ldap_server->add_display_layout("orclNetService",array(
			array("section_name"=>gettext("Oracle NetService"),
				"attributes"=>array(
					array("cn",			gettext("NetService Name"),			"oracle/oracle-netservice.png"),
					)
				),
			array("section_name"=>gettext("Description"),"new_row"=>true,
				"attributes"=>array(
					array("description")
					)
				),
			array("section_name"=>gettext("Connect Descriptor String"),"new_row"=>true,
				"attributes"=>array(
					array("orclNetDescString")
					)
				)
			));

		parent::__construct($ldap_server);
	}
}
?>
