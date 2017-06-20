<?php
/** OpenLDAP Project's Directory Schema (openldap.schema) */

class openldap_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		// Object classes
		$this->object_schema = array(
			array("name"=>"OpenLDAPdisplayableObject",		"icon"=>"generic24.png",	"class_type"=>"auxiliary","display_name"=>gettext("OpenLDAP Displayable Object")),
			array("name"=>"OpenLDAPorg",				"icon"=>"org.png",		"is_folder"=>true,"display_name"=>gettext("OpenLDAP Organization"),"parent_class"=>"organization"),
			array("name"=>"OpenLDAPou",				"icon"=>"folder.png",		"is_folder"=>true,"display_name"=>gettext("OpenLDAP Organizational Unit"),"parent_class"=>"organizationalUnit"),
			array("name"=>"OpenLDAPperson",				"icon"=>"user24.png",		"is_folder"=>false,"display_name"=>gettext("OpenLDAP Person"),"parent_class"=>"pilotPerson,inetOrgPerson")
			);

		parent::__construct($ldap_server);
	}
}
?>
