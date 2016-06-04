<?php
/** Microsoft Exchange schema (partial) */

class microsoft_exchange_schema extends ldap_schema
{
        function __construct(&$ldap_server)
        {
		// Structural object classes
		$this->object_schema = array(
			array("name"=>"msExchDynamicDistributionList",		"icon"=>"microsoft/dynamic-group24.png",	"is_folder"=>false,"display_name"=>gettext("Query-based Distribution Group")),
			);

		parent::__construct($ldap_server);
	}
}
?>
