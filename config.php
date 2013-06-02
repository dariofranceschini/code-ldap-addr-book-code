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

$ldap_base_dn = "OU=Home,DC=turnersoft,DC=co,DC=uk";

// --------------------------------------------------------------------
// User name and password for logging on to the LDAP server
// --------------------------------------------------------------------

$ldap_user = "";
$ldap_password = "";

// --------------------------------------------------------------------
// Low-Level Settings
// --------------------------------------------------------------------

// List of object attributes used for searches
$ldap_search_attrib = array(
	"cn","mail","sn","displayName","company","title");
?>
