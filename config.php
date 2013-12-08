<?php
// --------------------------------------------------------------------
// Address book name (for page title and browser search plugin)
// --------------------------------------------------------------------

$site_name = "Address Book";

// --------------------------------------------------------------------
// Links displayed in page footer
//
// This application's software license requires you to provide users
// with a means of obtaining the source code (i.e. the link below).
// You may however choose to provide the source from a different
// location, e.g. to a copy containing your own local changes, or if
// users have only limited connectivity to the external Internet that
// would prevent them from accessing the default location. Please see
// the license agreement (file "license.html" in "doc" folder) for
// full information.
// --------------------------------------------------------------------

$site_footer_links = array(
	array("url"=>"doc/","text"=>"User Guide"),
	array("url"=>"https://sourceforge.net/projects/ldap-addr-book/",
		"text"=>"Get Source Code")
	);

// --------------------------------------------------------------------
// Type of LDAP server, chosen from:
//	ad		Microsoft Active Directory
//	edir		Novell eDirectory
//	openldap	OpenLDAP
// --------------------------------------------------------------------

$ldap_server_type = "ad";

// --------------------------------------------------------------------
// LDAP server to connect to.
//
// For Active Directory, providing just the domain name will
// load balance across available domain controllers.
// --------------------------------------------------------------------

$ldap_link = ldap_connect("dc1.turnersoft.co.uk");

// --------------------------------------------------------------------
// Directory location (e.g. OU) of the address book records
// --------------------------------------------------------------------

$ldap_base_dn = "ou=Home,dc=turnersoft,dc=co,dc=uk";

// --------------------------------------------------------------------
// User names and passwords for logging on to the LDAP server
// --------------------------------------------------------------------

// default credentials used to browse the directory when no user has
// explicitly logged in ("anonymous" access)
$ldap_default_user = "";
$ldap_default_password = "";

// whether to enable different users to log into the directory
$ldap_login_enabled = false;

// Defines how address book logins tranlate to LDAP logins and
// permissions on the back-end directory. (See "configuring users and
// permissions" in the manual for more info.)

$ldap_user_map = array(
	array("login_name"=>"__ANONYMOUS__","ldap_name"=>$ldap_default_user,
		"ldap_password"=>$ldap_default_password,
		"allow_browse"=>true,
		"allow_search"=>true,
		"allow_view"=>true
		),
	array("login_name"=>"__DEFAULT__","ldap_name"=>"__USERNAME__@turnersoft.co.uk",
		"allow_browse"=>true,
		"allow_search"=>true,
		"allow_view"=>true
		)
	);

// --------------------------------------------------------------------
// Search/Browse Directory Settings
// --------------------------------------------------------------------

// List of object attributes used for searches
$search_ldap_attrib = array(
	"cn","mail","sn","displayName","company","title");

// Column layout used for search results
// Note special attribute "sortableName" - uses syntax "surname,
// firstname" where both of these are defined

$search_result_columns = array(
	array("caption"=>"Name",		"attrib"=>"sortableName",	"link_type"=>"object"),
	array("caption"=>"E-Mail",		"attrib"=>"mail",		"link_type"=>"mailto"),
	array("caption"=>"Home Phone",		"attrib"=>"homePhone",		"link_type"=>"none"),
	array("caption"=>"Office Phone",	"attrib"=>"telephoneNumber",	"link_type"=>"none"),
	array("caption"=>"Mobile Phone",	"attrib"=>"mobile",		"link_type"=>"none"),
	array("caption"=>"Office Fax",	"attrib"=>"facsimileTelephoneNumber",	"link_type"=>"none")
//	array("caption"=>"Organisation",	"attrib"=>"company",		"link_type"=>"none")
	);

// Default sort order until user selects another by clicking a column header
$search_result_default_sort_order = "sortableName";

// --------------------------------------------------------------------
// Directory Entry Detail/Info Settings
// --------------------------------------------------------------------

// Layout of the detail/info view
//
// Configurable attributes of a section - all optional:
//
//	section_name - Name/title of section (default blank/not shown)
//	width - Width of section (default unset - let browser decide)
//	new_row - Start section on a new row (default false)
//	colspan - Number of table columns to span (default 1)

$entry_layout = array(
	array("section_name"=>"Personal",
		"attributes"=>array(
			array("displayName",			"Preferred Name",	"contact24.png"),
			array("mail",				"E-mail",		"mail.png"),
			array("homePhone",			"Home Phone",		"landline-phone.png"),
	//		array("pager",				"Pager",		""),
			array("mobile",				"Mobile Phone",		"cell-phone.png"),
			array("wWWHomePage",			"Web Page",		"internet.png"),
			array("streetAddress:l:st:postalCode",	"Postal Address",	"address.png"),
			array("c",				"Country",		"country.png")
			)
		),

	array("section_name"=>"Business/Work","width"=>"50%",
		"attributes"=>array(
			array("company",			"Company",		"company.png"),
			array("url",				"Web Page",		"internet.png"),
			array("telephoneNumber",		"Office Phone",		"landline-phone.png"),
			array("facsimileTelephoneNumber",	"Office Fax",		"fax.png"),
			array("title",				"Job Title",		"id.png"),
			array("department",			"Department",		"org.png"),
			array("physicalDeliveryOfficeName",	"Office",		"office.png")
			)
		),

	array("section_name"=>"Additional Notes","new_row"=>true,"colspan"=>2,
		"attributes"=>array(
			array("info")
			)
		)
	);
?>
