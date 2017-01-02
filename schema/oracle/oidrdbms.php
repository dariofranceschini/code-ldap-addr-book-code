<?php
/** Oracle RDBMS Enterprise Roles Schema (oidrdbms.schema) */

class oracle_oidrdbms_schema extends ldap_schema
{
        function __construct(&$ldap_server)
        {
		$this->attribute_schema = array(
                        array("name"=>"orclDBtrustedUser",		"data_type"=>"dn"),
                        array("name"=>"orclDBServerMember",		"data_type"=>"dn"),
                        array("name"=>"orclDBEntUser",			"data_type"=>"dn"),
                        array("name"=>"orclDBEntRoleAssigned",		"data_type"=>"dn"),
                        array("name"=>"orclDBServerRole",		"data_type"=>"text"),
                        array("name"=>"orclDBTrustedDomain",		"data_type"=>"text"),
                        array("name"=>"orclDBRoleOccupant",		"data_type"=>"dn"),
                        array("name"=>"orclDBDistinguishedName",	"data_type"=>"dn"),
                        array("name"=>"orclDBNativeUser",		"data_type"=>"text"),
                        array("name"=>"orclDBGlobalName",		"data_type"=>"text"),
			);

                // Structural object classes
                $this->object_schema = array(
			array("name"=>"orclDBServer",			"icon"=>"oracle/oracle-dbserver.png",		"is_folder"=>true,"display_name"=>gettext("Oracle Database"),"can_create"=>true),
			array("name"=>"orclDBEnterpriseDomain",		"icon"=>"oracle/oracle-enterprise-domain.png",	"is_folder"=>true,"display_name"=>gettext("Oracle Database Enterprise Domain"),"can_create"=>true),
			array("name"=>"orclDBEnterpriseRole",		"icon"=>"oracle/oracle-enterprise-role.png",	"is_folder"=>false,"display_name"=>gettext("Oracle Database Enterprise Role"),"can_create"=>true),
			array("name"=>"orclDBEntryLevelMapping",	"icon"=>"generic24.png",			"is_folder"=>false,"display_name"=>gettext("Oracle Database Entry Level Mapping")),
			array("name"=>"orclDBSubtreeLevelMapping",	"icon"=>"generic24.png",			"is_folder"=>false,"display_name"=>gettext("Oracle Database Subtree Level Mapping"))
                        );

                // Display layouts
                $ldap_server->add_display_layout("orclDBServer",array(
                        array(
                                "attributes"=>array(
                                        array("cn",                     gettext("Database Name"),		"oracle/oracle-dbserver.png"),
					array("orclSystemName",		gettext("Server Host Name"),		"generic24.png"),
					array("orclOracleHome",		gettext("Oracle Home"),			"generic24.png"),
					array("orclSid",		gettext("SID"),				"generic24.png"),
					array("orclDBGlobalName",	gettext("Global Name"),			"generic24.png"),
					array("orclNetDescName",	gettext("Net Service Name"),		"generic24.png"),
                                        )
                                ),
                        array("section_name"=>gettext("Connect Descriptor"),"new_row"=>true,
                                "attributes"=>array(
                                        array("orclNetDescString")
                                        )
                                )
                        ));

		parent::__construct($ldap_server);
        }

        function before_create_orclDBServer(&$ldap_server,&$entry)
        {
		$this->add_attrib_single_value($ldap_server,$entry,"orclServiceType","DB");	// indicates service is a database
		$this->add_attrib_single_value($ldap_server,$entry,"orclNetDescName","000:cn=DESCRIPTION_0");
        }
}
?>
