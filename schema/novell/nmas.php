<?php
/** Novell Modular Authentication Service (NMAS) schema (partial) */

class novell_nmas_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"sasLoginSequence","data_type"=>"text_list","display_name"=>gettext("SAS Login Sequence")),
			);

		// Structural object classes
		$this->object_schema = array(
			array("name"=>"sASLoginPolicy",			"icon"=>"novell/login-policy.png",	"is_folder"=>false,"display_name"=>gettext("Login Policy")),
			);

		parent::__construct($ldap_server);
	}
}
?>
