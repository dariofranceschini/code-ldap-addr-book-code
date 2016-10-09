<?php
/** system schema - eDirectory specific items (partial) */

class system_edir_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"dSAName",			"data_type"=>"dn_list",		"display_name"=>gettext("DSA Name")),
			// See: draft-zeilenga-ldap-grouping-06
			array("name"=>"supportedGroupingTypes",		"data_type"=>"oid_list",	"display_name"=>gettext("Supported Grouping Types")),
			);

		// Root DSE object class (treeRoot) is defined in NDS schema - TODO: move it!

		$ldap_server->delete_object_class("alias");	// called 'aliasObject' instead

		// Display layouts
		$ldap_server->add_display_layout("treeRoot",array(
			array("section_name"=>gettext("eDirectory Tree"),"new_row"=>true,"colspan"=>6,
				"attributes"=>array(
					array("directoryTreeName",		null,				"novell/tree-root.png")
					)
				),
			array("section_name"=>gettext("Server Details"),"new_row"=>true,"colspan"=>6,
				"attributes"=>array(
					array("dSAName",			gettext("LDAP Server"),			"ldap-server.png"),
					array("vendorVersion",			gettext("Product Version"),		"generic24.png"),
					array("vendorName",			gettext("Vendor"),			"generic24.png"),
					array("supportedLDAPVersion",		gettext("Supported LDAP Versions"),	"generic24.png"),
					array("namingContexts",			gettext("Naming Contexts"),		"alias.png"),
					array("subschemaSubentry",		gettext("Subschema Subentry"),		"alias.png"),
					)
				),
			array("section_name"=>gettext("Replication"),"new_row"=>true,
				"attributes"=>array(
					array("repUpdatesOut",			gettext("Updates Out")),
					array("repUpdatesIn",			gettext("Updates In")),
					)
				),
			array("section_name"=>gettext("LDAP Binds"),
				"attributes"=>array(
					array("strongAuthBinds",		gettext("Strong Auth")),
					array("simpleAuthBinds",		gettext("Simple Auth")),
					array("unauthBinds",			gettext("Anonymous")),
					array("bindSecurityErrors",		gettext("Security Errors")),
					)
				),
			array("section_name"=>gettext("Data Transfer"),
				"attributes"=>array(
					array("outBytes",			gettext("Bytes Out")),
					array("inBytes",			gettext("Bytes In"))
					)
				),
			array("section_name"=>gettext("Ops Processed"),
				"attributes"=>array(
					array("extendedOps",			gettext("Extended")),
					array("abandonOps",			gettext("Abandoned")),
					array("wholeSubtreeSearchOps",		gettext("Subtree Search")),
					array("oneLevelSearchOps",		gettext("Single Level Search")),
					array("searchOps",			gettext("Search")),
					array("listOps",			gettext("List")),
					array("modifyRDNOps",			gettext("Modify RDN")),
					array("removeEntryOps",			gettext("Remove Entry")),
					array("addEntryOps",			gettext("Add Entry")),
					array("compareOps",			gettext("Compare")),
					array("readOps",			gettext("Read")),
					array("inOps",				gettext("Total In"))
					)
				),
			array("section_name"=>gettext("LDAP Referral Processed"),
				"attributes"=>array(
					array("chainings",			gettext("Outbound Referrals Sent")),
					array("referralsReturned",		gettext("Inbound Referrals Returned")),
					)
				),
			array("section_name"=>gettext("Miscellaneous"),
				"attributes"=>array(
					array("errors",				gettext("Errors")),
					array("securityErrors",			gettext("Security Errors")),
					)
				),
			array("section_name"=>gettext("Supported Grouping Types"),"new_row"=>true,"colspan"=>6,
				"attributes"=>array(
					array("supportedGroupingTypes")
					)
				),
			array("section_name"=>gettext("Supported Controls"),"new_row"=>true,"colspan"=>6,
				"attributes"=>array(
					array("supportedControl")
					)
				),
			array("section_name"=>gettext("Supported Features"),"new_row"=>true,"colspan"=>6,
				"attributes"=>array(
					array("supportedFeatures")
					)
				),
			array("section_name"=>gettext("Supported SASL Mechanisms"),"new_row"=>true,"colspan"=>6,
				"attributes"=>array(
					array("supportedSASLMechanisms")
					)
				),
			array("section_name"=>gettext("Supported Extensions"),"new_row"=>true,"colspan"=>6,
				"attributes"=>array(
					array("supportedExtension")
					)
				)
			));

		$ldap_server->add_display_layout("subschema",array(
			array("section_name"=>gettext("Object Class Definitions"),"new_row"=>true,"colspan"=>2,
				"attributes"=>array(
					array("objectClasses")
					)
				),
			array("section_name"=>gettext("Attribute Type Definitions"),"new_row"=>true,"colspan"=>2,
				"attributes"=>array(
					array("attributeTypes")
					)
				),
			array("section_name"=>gettext("LDAP Syntax Definitions"),"new_row"=>true,"colspan"=>2,
				"attributes"=>array(
					array("ldapSyntaxes")
					)
				)
			));

		parent::__construct($ldap_server);
	}
}
?>
