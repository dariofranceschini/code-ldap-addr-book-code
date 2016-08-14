<?php
/** Oracle Base Schema (oidbase.schema) */

class oracle_oidbase_schema extends ldap_schema
{
        function __construct(&$ldap_server)
        {
		$this->attribute_schema = array(
			//oidbase
                        array("name"=>"orclVersion",			"data_type"=>"text"),
                        array("name"=>"orclOracleHome",			"data_type"=>"text"),
                        array("name"=>"orclSystemName",			"data_type"=>"text"),
                        array("name"=>"orclServiceType",		"data_type"=>"text"),
                        array("name"=>"orclSid",			"data_type"=>"text"),
                        array("name"=>"orclProductVersion",		"data_type"=>"text"),
                        array("name"=>"orclNetDescName",		"data_type"=>"dn"),
                        array("name"=>"orclNetDescString",		"data_type"=>"text_area"),
			);

                // Structural object classes
                $this->object_schema = array(
			// oidbase
			array("name"=>"orclContainer",			"icon"=>"oracle/oracle-container.png",		"is_folder"=>true,"display_name"=>gettext("Oracle Container")),
			array("name"=>"orclContext",			"icon"=>"oracle/oracle-container.png",		"is_folder"=>true,"display_name"=>gettext("Oracle Context")),
			array("name"=>"orclSchemaVersion",		"icon"=>"generic24.png",			"is_folder"=>false),
			array("name"=>"orclService",			"icon"=>"generic24.png",			"is_folder"=>false,"display_name"=>gettext("Oracle Service")),
                        );

		// Display layouts
		$ldap_server->add_display_layout("orclContext,orclContainer",array(
			array("section_name"=>gettext("Oracle Container Details"),
				"attributes"=>array(
					array("cn",			gettext("Object Name"),				"oracle/oracle-container.png")
					)
				)
			));

		parent::__construct($ldap_server);
        }
}
?>
