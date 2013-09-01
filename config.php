<?
// --------------------------------------------------------------------
// Address book name (for page title and browser search plugin)
// --------------------------------------------------------------------

$site_name = "LDAP Address Book";

// --------------------------------------------------------------------
// Type of LDAP server, chosen from:
//	ad	Microsoft Active Directory
//	edir	Novell eDirectory
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
// User name and password for logging on to the LDAP server
// --------------------------------------------------------------------

$ldap_user = "";
$ldap_password = "";

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
?>
