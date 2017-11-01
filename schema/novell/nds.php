<?php
/** Novell Directory Services (NDS) schema (partial) */

class novell_nds_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			// Equivalents to core.schema classes
			array("name"=>"dc",			"data_type"=>"text",		"display_name"=>gettext("Domain Component Name"),	"alias_names"=>"domainComponent"),

			// Novell proprietary classes
			array("name"=>"ndsPredicateStatsDN",	"data_type"=>"dn_list",		"display_name"=>gettext("NDS Predicate Stats DN")),
			array("name"=>"ndsPredicateFlush",	"data_type"=>"yes_no",		"display_name"=>gettext("NDS Predicate Flush")),
			array("name"=>"ndsPredicateState",	"data_type"=>"yes_no",		"display_name"=>gettext("NDS Predicate State")),
			array("name"=>"ndsPredicateUseValues",	"data_type"=>"yes_no",		"display_name"=>gettext("NDS Predicate Use Values")),

			array("name"=>"CachedAttrsOnExtRefs",	"data_type"=>"text_list",	"display_name"=>gettext("Cached Attributes On External References")),
			array("name"=>"excludedMember",		"data_type"=>"dn_list",		"display_name"=>gettext("Excluded Member")),
			array("name"=>"indexDefinition",	"data_type"=>"text_list",	"display_name"=>gettext("Index Definition List"))
			);

		// Object classes
		$this->object_schema = array(
			// Equivalents to cosine.schema classes
			array("name"=>"domain",				"icon"=>"novell/domain.png",		"is_folder"=>true,"display_name"=>gettext("Domain"),"rdn_attrib"=>"dc"),

			// Novell proprietary classes
			array("name"=>"ndsPredicateStats",		"icon"=>"novell/stats.png",		"is_folder"=>false),

			// rdn_attrib is "t" in legacy NetWare 5 version of this class
			array("name"=>"treeRoot",			"icon"=>"novell/tree-root.png",		"is_folder"=>true,"display_name"=>gettext("Tree Root"),"rdn_attrib"=>"directoryTreeName")
			);

		// abstract class 'ndsContainerLoginProperties' is also defined in this schema
		// abstract class 'ndsLoginProperties' is also defined in this schema

		// Display layouts
		$ldap_server->add_display_layout("ndsPredicateStats",array(
			array("section_name"=>gettext("NDS Predicate Statistics"),"new_row"=>true,
				"attributes"=>array(
					array("ndsPredicate",			gettext("Predicate"),			"generic24.png"),
					array("ndsPredicateFlush",		gettext("Flush"),			"generic24.png"),
					array("ndsPredicateState",		gettext("State"),			"generic24.png"),
					array("ndsPredicateTimeout",		gettext("Timeout"),			"time.png"),
					array("ndsPredicateUseValues",		gettext("Use Values"),			"generic24.png"),
					)
				),
			));

		$ldap_server->add_display_layout("treeRoot",array(
			array("section_name"=>gettext("eDirectory Tree"),"new_row"=>true,"colspan"=>6,
				"attributes"=>array(
					array("directoryTreeName",		null,					"novell/tree-root.png")
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
			array("section_name"=>gettext("Supported Extended Operations"),"new_row"=>true,"colspan"=>6,
				"attributes"=>array(
					array("supportedExtension")
					)
				)
			));

		parent::__construct($ldap_server);
	}
}
?>
