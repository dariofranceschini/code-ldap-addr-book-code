<?php
/** Microsoft Systems Management Server (SMS) schema (partial)
    (now rebranded as Microsoft System Center Configuration Manager/SCCM)
*/

class microsoft_sms_schema extends ldap_schema
{
        function __construct(&$ldap_server)
        {
		// Structural object classes
		$this->object_schema = array(
			array("name"=>"mSSMSManagementPoint",		"icon"=>"microsoft/sms-mgmt-point.png",		"is_folder"=>false),
			array("name"=>"mSSMSSite",			"icon"=>"microsoft/sms-site.png",		"is_folder"=>false),
			array("name"=>"mSSMSServerLocatorPoint",	"icon"=>"generic24.png",			"is_folder"=>false),
			array("name"=>"mSSMSRoamingBoundaryRange",	"icon"=>"microsoft/sms-boundary-range.png",	"is_folder"=>false)
			);

		parent::__construct($ldap_server);
	}
}
?>
