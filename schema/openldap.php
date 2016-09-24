<?php
/** OpenLDAP Project's Directory Schema (openldap.schema) */

class openldap_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
                // Structural object classes
                $this->object_schema = array(
                        array("name"=>"OpenLDAPorg",				"icon"=>"org.png",		"is_folder"=>true,"display_name"=>gettext("OpenLDAP Organization")),
                        array("name"=>"OpenLDAPou",				"icon"=>"folder.png",		"is_folder"=>true,"display_name"=>gettext("OpenLDAP Organizational Unit")),
                        array("name"=>"OpenLDAPperson",				"icon"=>"user24.png",		"is_folder"=>false,"display_name"=>gettext("OpenLDAP Person")),
			);

		// auxiliary class 'OpenLDAPdisplayableObject' is also defined in this schema

		parent::__construct($ldap_server);
	}
}
?>
