<?php
/** Novell eDirectory Management Toolbox (eMBox) schema (partial) */

class novell_embox_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"emboxConfig","data_type"=>"text_area","display_name"=>gettext("eMBox Configuration")),
			);

		parent::__construct($ldap_server);
	}
}
?>
