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

		// Structural object classes
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
					array("ndsPredicateTimeout",		gettext("Timeout"),			"generic24.png"),
					array("ndsPredicateUseValues",		gettext("Use Values"),			"generic24.png"),
					)
				),
			));

		parent::__construct($ldap_server);
	}
}
?>
