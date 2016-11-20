<?php
/** OpenLDAP SQL (ODBC) Backend On-Line Configuration (OLC) schema (partial) */

class openldap_back_sql_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			/** @todo: some attributes are shared with back_ndb - move to common back_config */
			array("name"=>"olcDbName",			"data_type"=>"text",		"display_name"=>gettext("ODBC Data Source Name")),
			array("name"=>"olcSqlAllowOrphans",		"data_type"=>"yes_no",		"display_name"=>gettext("Allow Orphan Entries to be Added")),
			array("name"=>"olcSqlAliasingQuote",		"data_type"=>"text",		"display_name"=>gettext("Quote Character Surrounding Alias")),
			array("name"=>"olcSqlAutocommit",		"data_type"=>"yes_no",		"display_name"=>gettext("Automatically Commit Data")),
			array("name"=>"olcSqlBaseObject",		"data_type"=>"text",		"display_name"=>gettext("Base Object LDIF File Name")),
			array("name"=>"olcSqlCheckSchema",		"data_type"=>"yes_no",		"display_name"=>gettext("Check Adherence to LDAP Schema after Modifications")),
			array("name"=>"olcSqlCreateNeedsSelect",	"data_type"=>"yes_no",		"display_name"=>gettext("Get Unique ID of Newly Created Entries using a Query instead of a Stored Procedure")),
			array("name"=>"olcSqlFailIfNoMapping",		"data_type"=>"yes_no",		"display_name"=>gettext("Report Failure when Writing Attributes with no Mapping")),
			array("name"=>"olcSqlFetchAllAttrs",		"data_type"=>"yes_no",		"display_name"=>gettext("Force All Attributes to be Returned")),
			array("name"=>"olcSqlFetchAttrs",		"data_type"=>"text",		"display_name"=>gettext("List of Attributes Always Fetched")),
			array("name"=>"olcSqlHasLDAPinfoDnRu",		"data_type"=>"yes_no",		"display_name"=>gettext("Store Reversed, Upper Case Version of Each LDAP Entry DN")),
			array("name"=>"olcSqlLayer",			"data_type"=>"text_list",	"display_name"=>gettext("SQL Helper Layers")),		// experimental - don't use yet
			array("name"=>"olcSqlStrcastFunc",		"data_type"=>"text",		"display_name"=>gettext("Cast-to-String Function")),
			array("name"=>"olcSqlUpperNeedsCast",		"data_type"=>"yes_no",		"display_name"=>gettext("Values to be Upper Cased Should be Cast as VARCHAR(<max DN length>)")),
			array("name"=>"olcSqlUseSubtreeShortcut",	"data_type"=>"yes_no",		"display_name"=>gettext("Don't Use for Subtree Search Scoping from Naming Context Root"))
			);

		// Structural object classes
		$this->object_schema = array(
			array("name"=>"olcSqlConfig",		"icon"=>"openldap/db.png",		"is_folder"=>false,"rdn_attrib"=>"olcDatabase","display_name"=>gettext("SQL (ODBC) Database"),"required_attribs"=>"olcSuffix,olcDbName","parent_class"=>"olcDatabaseConfig")
			);

		parent::__construct($ldap_server);
	}
}
?>
