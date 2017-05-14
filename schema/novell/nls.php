<?php
/** Novell Licensing Services (NLS) schema */

class novell_nls_schema extends ldap_schema
{
	var $data_version = 50;

	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"nLSACLCheck",			"data_type"=>"text",		"display_name"=>gettext("ACL Check")),
			array("name"=>"nLSAssignedCertificates",	"data_type"=>"dn_list",		"display_name"=>gettext("Assigned Certificates")),
			array("name"=>"nLSBroadcastNotify",		"data_type"=>"dn_list",		"display_name"=>gettext("Broadcast Notify")),
			array("name"=>"nLSCertCurrentInstalled",	"data_type"=>"download",	"display_name"=>gettext("Cert Current Installed")),
			array("name"=>"nLSCertCurrentUsed",		"data_type"=>"download",	"display_name"=>gettext("Cert Current Used")),
			array("name"=>"nLSCertCurrentPeakInstalled",	"data_type"=>"download",	"display_name"=>gettext("Cert Peak Installed")),
			array("name"=>"nLSCertCurrentPeakUsed",		"data_type"=>"download",	"display_name"=>gettext("Cert Peak Used")),
			array("name"=>"nLSCertLastPeakInstalled",	"data_type"=>"download",	"display_name"=>gettext("Cert Last Peak Installed")),
			array("name"=>"nLSCertLastPeakUsed",		"data_type"=>"download",	"display_name"=>gettext("Cert Last Peak Used")),
			array("name"=>"nLSCertPeakInstalledPool",	"data_type"=>"download",	"display_name"=>gettext("Cert Peak Installed Pool")),
			array("name"=>"nLSCertPeakUsedPool",		"data_type"=>"download",	"display_name"=>gettext("Cert Peak Used Pool")),
			array("name"=>"nLSCommonCertificate",		"data_type"=>"download",	"display_name"=>gettext("Common Certificate")),
			array("name"=>"nLSCurrentInstalled",		"data_type"=>"text",		"display_name"=>gettext("Number of License Units Installed")),
			array("name"=>"nLSCurrentPeakInstalled",	"data_type"=>"text",		"display_name"=>gettext("Peak Number of License Units Installed")),
			array("name"=>"nLSCurrentPeakUsed",		"data_type"=>"text",		"display_name"=>gettext("Peak Number of License Units In Use")),
			array("name"=>"nLSCurrentUsed",			"data_type"=>"text",		"display_name"=>gettext("Number of License Units In Use")),
			array("name"=>"nLSDeleted",			"data_type"=>"text",		"display_name"=>gettext("Deleted")),
			array("name"=>"nLSEmailNotify",			"data_type"=>"text",		"display_name"=>gettext("E-mail Notify")),
			array("name"=>"nLSEmailServerName",		"data_type"=>"text",		"display_name"=>gettext("E-mail Server Name")),
			array("name"=>"nLSEvalStart",			"data_type"=>"date_time",	"display_name"=>gettext("Start of Evaluation Period")),
			array("name"=>"nLSHourlyDataSize",		"data_type"=>"text",		"display_name"=>gettext("Maximum License History Size (bytes)")),
			array("name"=>"nLSInstaller",			"data_type"=>"text",		"display_name"=>gettext("Installer")),
			array("name"=>"nLSLastInstall",			"data_type"=>"date_time",	"display_name"=>gettext("Last Installation Time")),
			array("name"=>"nLSLicenseDatabase",		"data_type"=>"yes_no",		"display_name"=>gettext("License Database")),
			array("name"=>"nLSLicenseID",			"data_type"=>"text",		"display_name"=>gettext("License ID")),
			array("name"=>"nLSLicenseServiceProvider",	"data_type"=>"dn",		"display_name"=>gettext("License Service Provider")),
			array("name"=>"nLSLicensesUsed",		"data_type"=>"text",		"display_name"=>gettext("Licenses Used")),
			array("name"=>"nLSListOfHandles",		"data_type"=>"download",	"display_name"=>gettext("List of Handles")),
			array("name"=>"nLSLSPAssignment",		"data_type"=>"dn",		"display_name"=>gettext("License Service Provider Assignment")),
			array("name"=>"nLSLSPRevision",			"data_type"=>"text",		"display_name"=>gettext("LSP Revision")),
			array("name"=>"nLSOwner",			"data_type"=>"text",		"display_name"=>gettext("Owner")),
			array("name"=>"nLSNotificationEnabled",		"data_type"=>"text",		"display_name"=>gettext("Notification Enabled")),
			array("name"=>"nLSPeakInstalledData",		"data_type"=>"download",	"display_name"=>gettext("Peak Installed License Units History")),
			array("name"=>"nLSPeakUsedData",		"data_type"=>"download",	"display_name"=>gettext("Peak License Units in Use History")),
			array("name"=>"nLSProduct",			"data_type"=>"text",		"display_name"=>gettext("Product Name")),
			array("name"=>"nLSPublisher",			"data_type"=>"text",		"display_name"=>gettext("Publisher Name")),
			array("name"=>"nLSRevision",			"data_type"=>"text",		"display_name"=>gettext("Data Version")),
			array("name"=>"nLSSearchType",			"data_type"=>"test",		"display_name"=>gettext("Search Type")),
			array("name"=>"nLSSubContainerList",		"data_type"=>"dn_list",		"display_name"=>gettext("Subcontainer List")),
			array("name"=>"nLSSummaryUpdateTime",		"data_type"=>"date_time",	"display_name"=>gettext("License Summary Update Time")),
			array("name"=>"nLSSummaryVersion",		"data_type"=>"text",		"display_name"=>gettext("License Summary Data Version")),
			array("name"=>"nLSTransactionDatabase",		"data_type"=>"yes_no",		"display_name"=>gettext("Transaction Logging Enabled")),
			array("name"=>"nLSTransactionExecutable",	"data_type"=>"text",		"display_name"=>gettext("Transaction Executable")),
			array("name"=>"nLSTransactionLogName",		"data_type"=>"text",		"display_name"=>gettext("Transaction Log File Name")),
			array("name"=>"nLSTransactionLogSize",		"data_type"=>"text",		"display_name"=>gettext("Maximum Transaction Log File Size")),
			array("name"=>"nLSVersion",			"data_type"=>"text",		"display_name"=>gettext("Product Version Number"))
			);

		// Structural object classes

		$this->object_schema = array(
			array("name"=>"nLSLicenseCertificate",		"icon"=>"novell/license-cert.png",	"is_folder"=>false,"rdn_attrib"=>"nLSLicenseID","required_attribs"=>"nLSLicenseID,nLSCommonCertificate,nLSRevision","display_name"=>gettext("License Certificate")),
			array("name"=>"nLSLicenseServer",		"icon"=>"novell/license-server.png",	"is_folder"=>false,"required_attribs"=>"nLSLicenseDatabase,nLSTransactionDatabase,hostServer,nLSLSPRevision","display_name"=>gettext("License Service Provider")),
			array("name"=>"nLSProductContainer",		"icon"=>"novell/license-container.png",	"is_folder"=>false,"rdn_attrib"=>"nLSPublisher,nLSProduct,nLSVersion","required_attribs"=>"nLSRevision","display_name"=>gettext("License Product Container"))
			);

		// Display layouts

		$ldap_server->add_display_layout("nLSProductContainer",array(
			array("section_name"=>gettext("Product Information"),
				"attributes"=>array(
					array("nLSPublisher",		gettext("Publisher"),			"generic24.png"),
					array("nLSProduct",		gettext("Product"),			"generic24.png"),
					array("nLSVersion",		gettext("Version"),			"generic24.png"),
					)
				),
			array("section_name"=>gettext("License Summary"),"new_row"=>true,
				"attributes"=>array(
					array("nLSCurrentInstalled",	gettext("Units Installed"),		"generic24.png"),
					array("nLSCurrentUsed",		gettext("Units In Use"),		"generic24.png"),
					array("nLSCurrentPeakInstalled",gettext("Units Available"),		"generic24.png"),
					array("nLSCurrentPeakUsed",	gettext("Highest Units Used"),		"generic24.png"),
					array("nLSSummaryUpdateTime",	" - " . gettext("As Of"),		"generic24.png"),
					array("nLSNotificationEnabled",	gettext("Notifications Enabled"),	"generic24.png"),
					array("nLSSummaryVersion",	gettext("Data Version"),		"generic24.png")
					)
				),
                        array("section_name"=>gettext("License Certificates"),"new_row"=>true,
                                "attributes"=>array(
                                        array("__CHILD_OBJECTS__")
                                        )
                                ),
			array("section_name"=>gettext("Data Version"),"new_row"=>true,
				"attributes"=>array(
					array("nLSRevision")
					)
				),
			));

		parent::__construct($ldap_server);
	}

        /** Assign default values for nLSProductContainer attributes */

        function populate_for_create_nLSProductContainer(&$ldap_server,&$entry)
        {
                $this->add_attrib_value($ldap_server,$entry,"nlsRevision",$this->data_version);
                $this->add_attrib_value($ldap_server,$entry,"nlsSummaryVersion",$this->data_version);
        }
}
?>
