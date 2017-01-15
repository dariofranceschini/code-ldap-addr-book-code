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
			array("name"=>"olcSqlCheckSchema",		"data_type"=>"yes_no",		"display_name"=>gettext("Check Adherence to LDAP Schema After Modifications")),
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

		// Display layouts
		$ldap_server->add_display_layout("olcSqlConfig",array(
			array("section_name"=>gettext("SQL Database Settings"),"new_row"=>true,
				"attributes"=>array(
					array("olcSuffix",			gettext("Naming Context"),							"alias.png"),
					array("olcDbName",			gettext("ODBC Data Source"),							"generic24.png"),
					array("olcLastMod",			gettext("Maintain Last Modification Info"),					"generic24.png"),
					array("olcRootDN",			gettext("Root User"),								"generic24.png"),
				//	array("olcRootPW",			gettext("Root Password"),							"generic24.png"),
					)
				),
			array("section_name"=>gettext("Connection Info"),"new_row"=>true,
				"attributes"=>array(
					array("olcDbHost",			gettext("Host Name"),								"generic24.png"),
					array("olcDbUser",			gettext("User Name"),								"generic24.png"),
					array("olcDbPass",			gettext("Password"),								"generic24.png"),
					)
				),
			array("section_name"=>gettext("Subtree Search Scoping"),"new_row"=>true,
				"attributes"=>array(
					array("olcSqlSubtreeCond",		gettext("Subtree Search Condition"),						"generic24.png"),
					array("olcSqlUseSubtreeShortcut",	gettext("Don't Use for Subtree Searches from Naming Context Root"),		"generic24.png"),
					)
				),
			array("section_name"=>gettext("Child Search Scoping"),"new_row"=>true,
				"attributes"=>array(
					array("olcSqlChildrenCond",		gettext("Child Search Condition"),						"generic24.png"),
					)
				),
			array("section_name"=>gettext("SQL Statements"),"new_row"=>true,
				"attributes"=>array(
					array("olcSqlOcQuery",			gettext("Get Object Class Mappings"),						"generic24.png"),
					array("olcSqlAtQuery",			gettext("Get Attribute Type Mappings"),						"generic24.png"),
					array("olcSqlFailIfNoMapping",		gettext("Report Failure when Writing Attributes with no Mapping"),		"generic24.png"),
					// there seems to be no OLC equivalent to id_query setting in traditional config file
					array("olcSqlInsEntryStmt",		gettext("Insert LDAP Entry"),							"generic24.png"),
					array("olcSqlDelEntryStmt",		gettext("Delete LDAP Entry"),							"generic24.png"),
					array("olcSqlDelObjclassesStmt",	gettext("Delete LDAP Entry-to-objectClass Links"),				"generic24.png"),
					)
				),
			array("section_name"=>gettext("SQL Syntax"),"new_row"=>true,
				"attributes"=>array(
					array("olcSqlUpperFunc",		gettext("Function to Convert Value to Upper Case"),				"generic24.png"),
					array("olcSqlUpperNeedsCast",		gettext("Values to be Upper Cased Should be Cast as VARCHAR(<max DN length>)"),	"generic24.png"),
					array("olcSqlStrcastFunc",		gettext("Function to Convert Value to String"),					"generic24.png"),
					array("olcSqlConcatPattern",		gettext("String Concatenation Syntax"),						"generic24.png"),
					array("olcSqlAliasingKeyword",		gettext("Keyword for Assigning Table/Field Aliases"),				"generic24.png"),
					array("olcSqlAliasingQuote",		gettext("Quote Character to Use when Assigning Table/Field Aliases"),		"generic24.png"),
					)
				),
			array("section_name"=>gettext("Database Structure/Implementation Details"),"new_row"=>true,
				"attributes"=>array(
					array("olcSqlHasLDAPinfoDnRu",		gettext("Store Reversed, Upper Case Version of Each LDAP Entry DN"),		"generic24.png"),
					array("olcSqlAllowOrphans",		gettext("Allow Entries to be Added which have no Parent Object"),		"generic24.png"),
					array("olcSqlBaseObject",		gettext("LDIF File Describing the Database's Base Object"),			"generic24.png"),
					array("olcSqlCreateNeedsSelect",	gettext("Get Unique ID of Newly Created Entries using a Query instead of a Stored Procedure"),	"generic24.png"),
					array("olcSqlAutocommit",		gettext("Automatically Commit Data"),						"generic24.png"),
					)
				),
			array("section_name"=>gettext("Attribute Retrival and Update"),"new_row"=>true,
				"attributes"=>array(
					array("olcSqlFetchAttrs",		gettext("Always Fetch the Following Attributes"),				"generic24.png"),
					array("olcSqlFetchAllAttrs",		gettext("Force All Attributes to be Returned"),					"generic24.png"),
					array("olcSqlCheckSchema",		gettext("Check Adherence to LDAP Schema After Modifications"),			"generic24.png"),
					)
				),

			/* Experimental/under development. Forthcoming in a future version of OpenLDAP.

			array("section_name"=>gettext("SQL Helper Layers"),"new_row"=>true,
				"attributes"=>array(
					array("olcSqlLayer")
					)
				),
			*/

			array("section_name"=>gettext("Access Controls"),"new_row"=>true,
				"attributes"=>array(
					array("olcAccess")
					)
				),
			array("section_name"=>gettext("Overlays"),"new_row"=>true,
				"attributes"=>array(
					array("__CHILD_OBJECTS__")
					)
				)
			));

		parent::__construct($ldap_server);
	}

	function populate_for_create_olcSqlConfig(&$ldap_server,&$entry)
	{
		// override the schema-defined data type that the new database's DN can be typed in
		$ldap_server->modify_attribute_schema("olcSuffix","data_type","text");

		$this->add_attrib_value($ldap_server,$entry,"olcSqlOcQuery",		"SELECT id, name, keytbl, keycol, create_proc, delete_proc, expect_return FROM ldap_oc_mappings");
		$this->add_attrib_value($ldap_server,$entry,"olcSqlAtQuery",		"SELECT name, sel_expr, from_tbls, join_where, add_proc, delete_proc, param_order, expect_return FROM ldap_attr_mappings WHERE oc_map_id=?");
		$this->add_attrib_value($ldap_server,$entry,"olcSqlInsEntryStmt",	"INSERT INTO ldap_entries (dn, oc_map_id, parent, keyval) VALUES (?, ?, ?, ?)");
		$this->add_attrib_value($ldap_server,$entry,"olcSqlDelEntryStmt",	"DELETE FROM ldap_entries WHERE id=?");
		$this->add_attrib_value($ldap_server,$entry,"olcSqlDelObjclassesStmt",	"DELETE FROM ldap_entry_objclasses WHERE entry_id=?");
		$this->add_attrib_value($ldap_server,$entry,"olcSqlFailIfNoMapping",	"FALSE");
		$this->add_attrib_value($ldap_server,$entry,"olcSqlUpperFunc",		"UPPER");
		$this->add_attrib_value($ldap_server,$entry,"olcSqlUpperNeedsCast",	"FALSE");
		$this->add_attrib_value($ldap_server,$entry,"olcSqlHasLDAPinfoDnRu",	"FALSE");
		$this->add_attrib_value($ldap_server,$entry,"olcSqlAllowOrphans",	"FALSE");
		$this->add_attrib_value($ldap_server,$entry,"olcSqlCreateNeedsSelect",	"FALSE");
		$this->add_attrib_value($ldap_server,$entry,"olcSqlFetchAllAttrs",	"FALSE");
		$this->add_attrib_value($ldap_server,$entry,"olcSqlAutocommit",		"FALSE");
		$this->add_attrib_value($ldap_server,$entry,"olcSqlCheckSchema",	"TRUE");
		$this->add_attrib_value($ldap_server,$entry,"olcSqlConcatPattern",	"CONCAT(?,?)");
		$this->add_attrib_value($ldap_server,$entry,"olcSqlAliasingKeyword",	"AS");
	}

	function before_create_olcSqlConfig(&$ldap_server,&$entry)
	{
		$ldap_server->ensure_openldap_module_loaded("back_sock");

		$ldap_server->assign_ordered_sequence_rdn($entry,"olcDatabaseConfig","sql",-1);

		$this->add_attrib_single_value($ldap_server,$entry,"olcAccess",array(
			"{0}to * by dn.exact=gidNumber=0+uidNumber=0,cn=peercred,cn=external,cn=auth manage by * break",
			"{1}to * by dn.base=\"" . $_SESSION["LOGIN_BIND_DN"] . "\" manage",
			"{2}to attrs=userPassword by self write by anonymous auth by * none",
			"{3}to attrs=shadowLastChange by self write by * read",
			"{4}to * by * read")
			);
	}
}
?>
