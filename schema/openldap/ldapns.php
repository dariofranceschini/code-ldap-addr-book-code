<?php
/** OpenLDAP Name Service Additional Schema (ldapns.schema)

    For use with NSS/PAM Lookup Overlay (nssov)

    @see http://www.iana.org/assignments/gssapi-service-names
*/

class openldap_ldapns_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		// Attributes
		$this->attribute_schema = array(
			array("name"=>"authorizedService",		"data_type"=>"text",		"display_name"=>gettext("Authorised Service Name")),
			array("name"=>"loginStatus",			"data_type"=>"text_list",	"display_name"=>gettext("Currently Logged in Sessions")),
			);

		// auxiliary class 'authorizedServiceObject' is also defined in this schema
		// auxiliary class 'hostObject' is also defined in this schema
		// auxiliary class 'loginStatusObject' is also defined in this schema
	}
}
