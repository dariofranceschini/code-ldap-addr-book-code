<?php
/** Microsoft Local Administrator Password Solution (LAPS) */

class microsoft_laps_schema extends ldap_schema
{
        function __construct(&$ldap_server)
        {
                $this->attribute_schema = array(
			array("name"=>"ms-Mcs-AdmPwd",			"data_type"=>"text"),
			array("name"=>"ms-Mcs-AdmPwdExpirationTime",	"data_type"=>"text"),
			);

		parent::__construct($ldap_server);
	}
}
?>
