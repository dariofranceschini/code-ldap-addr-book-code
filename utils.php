<?php
/* ************************************************************************

   This program is free software: you can redistribute it and/or modify
   it under the terms of the GNU Affero General Public License as published
   by the Free Software Foundation, either version 3 of the License, or
   (at your option) any later version.

   This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU Affero General Public License for more details.

   You should have received a copy of the GNU Affero General Public License
   along with this program.  If not, see <http://www.gnu.org/licenses/>.

   ************************************************************************ */

define("LDAP_SORT_ASCENDING",1);
define("LDAP_SORT_DESCENDING",2);
define("LDAP_ATTRIBUTE_IS_RDN",true);

define("MAX_DN_LENGTH",1000);
define("MAX_IMAGE_UPLOAD",1048576);		// 1 MiB

/** Output the site's HTML header elements */

function show_site_header()
{
	global $site_name,$ldap_login_enabled,$enable_search_suggestions;

	// Resume existing session (if any exists) in order to get
	// currently logged in user
	if(!isset($_SESSION)) session_start();

	echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 5.0//EN\">\n";
	echo "<html>\n";
	echo "<head>\n";
	echo "  <title>" . $site_name . "</title>\n";
	// HTML 5.0 syntax:
	// echo "<meta charset=\"UTF-8\">";

	// equivalent syntax, backwardly compatible with HTML 4.01
	echo "  <meta http-equiv=\"content-type\" content=\"text/html; charset=UTF-8\">\n";

	echo "  <meta name=\"viewport\" content=\"width=device-width,initial-scale=1,user-scalable=0\">\n";
	echo "  <link rel=\"search\" type=\"application/opensearchdescription+xml\" title=\""
		. $site_name . "\" href=\"search-plugin.php\">\n";
	echo "  <link rel=\"icon\" type=\"image/png\" href=\"addressbook24.png\">\n";
	echo "  <link rel=\"stylesheet\" href=\"styles.css\" type=\"text/css\">\n";
	if(file_exists("styles_local.css"))
		echo "  <link rel=\"stylesheet\" href=\"styles_local.css\" type=\"text/css\">\n";

	if(isset($enable_search_suggestions) && $enable_search_suggestions)
	{
		echo "  <link rel=\"stylesheet\" href=\"lib/jquery-ui-themes-1.11.2/smoothness.css\" type=\"text/css\">\n";
		echo "  <script src=\"lib/jquery-1.11.2/jquery-1.11.2.js\"></script>\n";
		echo "  <script src=\"lib/jquery-ui-1.11.2/jquery-ui-1.11.2.js\"></script>\n";
		echo "  <script src=\"suggest.js\"></script>\n";
	}

	echo "</head>\n\n";
	echo "<body>\n\n";
}

/** Output the site's HTML footer elements */

function show_site_footer()
{
	global $site_footer_links;

	echo "<hr>\n"
		. "<div class=\"page_footer\">\n  This Address Book uses Free and "
		. "Open Source Software, licensed under the\n  terms of the "
		. "<a href=\"doc/license.html\">GNU Affero GPL version 3</a>\n"
		. "  <ul>\n";

	$first_link = true;
	foreach($site_footer_links as $link)
	{
		echo "    <li><a href=\"" . $link["url"] . "\">"
			. $link["text"] . "</a></li>\n";

		$first_link = false;
	}

	echo "  </ul>\n</div>\n\n</body>\n</html>\n";
}

/** Output the HTML to display the search box

    @param string $initial_value
	Text to display in search box when page first loaded;
	typically the previous text the user searched on.
*/

function show_search_box($initial_value)
{
	echo "<form action=\"" . current_page_folder_url() . "\" method=\"get\">\n";

	echo "  <table class=\"search\">\n";
	echo "    <tr>\n";
	echo "      <th>Search for:</th>\n";
	echo "      <td><input type=\"text\" id=\"filter\" name=\"filter\"";
	if(!empty($initial_value))
		echo " value=\"" . htmlentities($initial_value,
			ENT_COMPAT,"UTF-8") . "\"";
	echo "></td>\n";
	echo "      <td><input type=\"submit\" value=\"Search\"></td>\n";
	echo "    </tr>\n";
	echo "  </table>\n";
	echo "</form>\n\n";
}

/** Generate a page showing the specified error message

    @param string $message
	Message to display
*/

function show_error_message($message)
{
	global $ldap_base_dn;
	show_ldap_path($ldap_base_dn,$ldap_base_dn,"schema/folder.png");
	show_search_box("");
	echo "<p>  \n" . $message . "\n</p>"
		. "<p>\n  <a href=\"" . current_page_folder_url()
		. "\">Return to the Address Book</a>\n</p>";
}

/** Show "breadcrumb navigation" version of specified LDAP path

    Each level in the DIT appears with a folder icon; final item
    is displayed with $leaf_icon next to it

    Also shows "login" button to right (if editing enabled)

    @param string $base
	The DN for which the breadcrumb navigation is to be
	displayed
    @param string $default_base
	The address book's base DN. Elements of this DN
	are not to be displayed as breadcrumb elements.
    @param string $leaf_icon
	Icon image to use for the last item in the path.
	Typically either the icon for the object class or
	the object's photo attribute.
*/

function show_ldap_path($base,$default_base,$leaf_icon)
{
	global $site_name,$ldap_login_enabled,$ldap_base_dn;

	echo "<table class=\"ldap_navigation_path_frame\">\n  <tr>\n"
		. "    <td>\n      <ul class=\"ldap_navigation_path\">\n"
		. "        <li><a href=\"" . current_page_folder_url() . "\">"
		. "<img alt=\"Address Book\" src=\"addressbook24.png\"> "
		. $site_name . "</a></li>\n";

	$folder_list = substr($base,0,-strlen($default_base)-1);
	if($folder_list != "")
	{
		$folder_list = ldap_explode_dn2($folder_list);

		for($i=$folder_list["count"];$i>0;$i--)
		{
			echo "        <li>";

			if($i>1)
			{
				$folder_alt_text="Folder";
				$folder_icon="schema/folder.png";
				if($folder_list[$i-1]["dn"] == $ldap_base_dn)
					$folder_dn = $ldap_base_dn;
				else
					$folder_dn = $folder_list[$i-1]["dn"]
						. "," . $ldap_base_dn;

				echo "<a href=\"" . current_page_folder_url()
					. "?dn=" . urlencode($folder_dn) . "\">";
			}
			else
			{
				$folder_alt_text="Address Book Entry";
				$folder_icon=$leaf_icon;
			}

			echo "<img alt=\""
				. $folder_alt_text . "\" src=\""
				. $folder_icon . "\"> "
				. $folder_list[$i-1]["value"];

			if($i>1) echo "</a>";

			echo "</li>";

			echo "\n";
		}
	}

	echo "      </ul>\n";
	echo "    </td>\n";

	echo "    <td class=\"login_info\">";
	if($ldap_login_enabled)
	{
		// display user name if set, etc, etc
		echo "<a href=\"user.php\">";

		if(isset($_SESSION["LOGIN_USER"]))
			echo "Log out " . ucwords(strtolower($_SESSION["LOGIN_USER"]));
		else
			echo "Log In";
		echo "</a>";
	}
	else
		echo "<!-- per-user logins not enabled -->";
	echo "</td>\n";
	echo "  </tr>\n</table>\n\n";
}

/** Convert an LDAP DN into an associative array of RDNs

    Decodes the elements of the specified DN into an associative array.
    Correctly handles accented characters - in contrast to the
    built-in PHP function ldap_explode_dn(), however does not currently
    support:

	- multi-valued RDNs
	- DNs which have commas within any of their values.

    @todo
	Support for commas in RDN values could do with being
	added; limitation may prove to be unacceptably limiting.

    @param string $dn
	DN which is to be converted into an array
    @return
	An array representing the DN, consisting of integer
	value "count" indicating the number of RDNs,
	followed by each RDN as an "attrib"+"value" pair
*/

function ldap_explode_dn2($dn)
{
	$dn = explode(",",$dn);

	for($i=0;$i<count($dn);$i++)
		$dn[$i] = array(
			"attrib"=>substr($dn[$i],0,strpos($dn[$i],"=")),
			"value"=>substr($dn[$i],strpos($dn[$i],"=")+1)
			);

	// add the DN of each array element
	$previous_element_dn="";
	for($i=count($dn)-1;$i>=0;$i--)
		$dn[$i]["dn"] = $previous_element_dn = $dn[$i]["attrib"] . "=" . $dn[$i]["value"]
			. ($previous_element_dn == "" ? "" : ",")
			. $previous_element_dn;

	$dn = array("count"=>count($dn)) + $dn;

	return $dn;
}

/** Return URL of folder containing the currently running script

    @return
	Folder part of URL of currently running script
*/

function current_page_folder_url()
{
	$scheme = empty($_SERVER['HTTPS']) ? "http" : "https";

	$path = substr($_SERVER["REQUEST_URI"],-1) == "/"
		? $_SERVER["REQUEST_URI"]
		: dirname($_SERVER["REQUEST_URI"]);

	// add trailing slash (missing from non-root folders)
	if(substr($path,-1) != "/") $path .= "/";

	return $scheme . "://" . $_SERVER["SERVER_NAME"] . $path;
}

/** Return an array representing the LDAP object class schema

    Return an array associating LDAP object classes with attributes used by
    the addressbook (currently icon graphic, whether it should be presented
    as a folder or a "leaf" object and which attribute holds the RDN). Where
    objects can potentially match more than one class those classes should
    be listed with most "specific" first (e.g. Person/inetOrgPerson in edir
    schema).

    @param string $ldap_server_type
	Indicates LDAP server type/schema to return
	("ad", "edir" or "openldap")
    @return
	Array of information about the LDAP server's object class schema
*/

function get_object_class_schema($ldap_server_type = "ad")
{
	switch($ldap_server_type)
	{
		case "edir";
			// Object class data - these items specific to Novell eDirectory
			return array(
				array("name"=>"organizationalUnit",	"icon"=>"folder.png",	"is_folder"=>true,"rdn_attrib"=>"ou","display_name"=>"Organizational Unit","can_create"=>true),
				array("name"=>"groupOfNames",		"icon"=>"group24.png",			  "is_folder"=>false,"display_name"=>"Group","can_create"=>true),
				array("name"=>"ncpServer",		"icon"=>"novell-edirectory/server24.png", "is_folder"=>false,"display_name"=>"NCP Server"),
				array("name"=>"ldapServer",		"icon"=>"novell-edirectory/directory-server.png","is_folder"=>false,"display_name"=>"LDAP Server","can_create"=>true),
				array("name"=>"inetOrgPerson",		"icon"=>"user24.png",			  "is_folder"=>false,"display_name"=>"User","required_attribs"=>"sn","can_create"=>true),
				array("name"=>"Person",			"icon"=>"contact24.png",		  "is_folder"=>false,"required_attribs"=>"sn","can_create"=>true),
				array("name"=>"externalEntity",		"icon"=>"novell-edirectory/external-entity24.png","is_folder"=>false,"display_name"=>"External Entity","can_create"=>true),
				array("name"=>"nDSPKIKeyMaterial",	"icon"=>"novell-edirectory/key-material.png","is_folder"=>false,"display_name"=>"NDSPKI:Key Material","can_create"=>true),
				array("name"=>"Volume",			"icon"=>"novell-edirectory/volume.png",   "is_folder"=>false),
				array("name"=>"sASService",		"icon"=>"novell-edirectory/security.png", "is_folder"=>false,"display_name"=>"SAS:Service","can_create"=>true),
				array("name"=>"ndsPredicateStats",	"icon"=>"novell-edirectory/stats.png",    "is_folder"=>false),
				array("name"=>"Queue",			"icon"=>"novell-edirectory/queue.png",    "is_folder"=>false),
				array("name"=>"nLSLicenseServer",	"icon"=>"novell-edirectory/lic_srv.png",  "is_folder"=>false),
				array("name"=>"ldapGroup",		"icon"=>"novell-edirectory/ldapgroup24.png","is_folder"=>false,"display_name"=>"LDAP Group","can_create"=>true),
				array("name"=>"nssfsPool",		"icon"=>"novell-edirectory/raid.png",	  "is_folder"=>false)
				);
			break;
		case "openldap":
			// Object class data - these items specific to OpenLDAP
			return array(
				// core.schema (partial)
				array("name"=>"organizationalUnit",	"icon"=>"folder.png",	"is_folder"=>true,"rdn_attrib"=>"ou","display_name"=>"Organizational Unit","can_create"=>true),
				array("name"=>"groupOfNames",		"icon"=>"group24.png",			  "is_folder"=>false,"can_create"=>true),
				// inetorgperson.schema
				array("name"=>"inetOrgPerson",		"icon"=>"user24.png",			  "is_folder"=>false,"required_attribs"=>"sn","can_create"=>true)
				);
			break;
		case "ad":
		default:
			// Object class data - these items specific to Active Directory
			return array(
				array("name"=>"organizationalUnit",	"icon"=>"folder.png",	"is_folder"=>true,"rdn_attrib"=>"ou","display_name"=>"Organizational Unit","can_create"=>true),
				array("name"=>"container",		"icon"=>"folder.png",	"is_folder"=>true,"display_name"=>"Container","can_create"=>true),
				array("name"=>"builtinDomain",		"icon"=>"folder.png",	"is_folder"=>true),
				array("name"=>"lostAndFound",		"icon"=>"folder.png",	"is_folder"=>true),
				array("name"=>"msDS-QuotaContainer",	"icon"=>"folder.png",	"is_folder"=>true),
				array("name"=>"group",			"icon"=>"group24.png",	"is_folder"=>false,"display_name"=>"Group","can_create"=>true),
				array("name"=>"contact",		"icon"=>"contact24.png","is_folder"=>false,"display_name"=>"Contact","can_create"=>true),
				array("name"=>"computer",		"icon"=>"microsoft-active-directory/computer24.png","is_folder"=>false,"display_name"=>"Computer","can_create"=>true),
				array("name"=>"foreignSecurityPrincipal","icon"=>"user-alias24.png","is_folder"=>false),
				array("name"=>"user",			"icon"=>"user24.png",	"is_folder"=>false,"display_name"=>"User","can_create"=>true),
				array("name"=>"inetOrgPerson",		"icon"=>"user24.png",	"is_folder"=>false,"display_name"=>"InetOrgPerson","can_create"=>true)
				);
	}
}

/** Return an array representing the LDAP attribute class schema

    Return an array associating LDAP attribute classes with schema
    setting used by the addressbook (currently data type for use when
    editing/displaying and "friendly" display name).

    @param string $ldap_server_type
	Indicates LDAP server type/schema to return
	("ad", "edir" or "openldap")
    @return
	Array of information about the LDAP server's attribute class schema
*/

function get_attribute_class_schema($ldap_server_type = "ad")
{
	// Generic schema used as basis of LDAP server-specific schemas

	return array(
		array("name"=>"c",			"data_type"=>"country_code",	"display_name"=>"Country Code"),
		array("name"=>"cn",			"data_type"=>"text",		"display_name"=>"Common Name/Full Name"),
		array("name"=>"company",		"data_type"=>"text",		"display_name"=>"Company"),
		array("name"=>"department",		"data_type"=>"text",		"display_name"=>"Department"),
		array("name"=>"displayName",		"data_type"=>"text",		"display_name"=>"Display/Preferred Name"),
		array("name"=>"facsimileTelephoneNumber","data_type"=>"text",		"display_name"=>"Fax Number"),
		array("name"=>"postalCode",		"data_type"=>"postcode",	"display_name"=>"Postal Code"),
		array("name"=>"givenName",		"data_type"=>"text",		"display_name"=>"Given Name"),
		array("name"=>"homePhone",		"data_type"=>"phone_number",	"display_name"=>"Home Telephone Number"),
		array("name"=>"info",			"data_type"=>"text_area",	"display_name"=>"Information"),
		array("name"=>"jpegPhoto",		"data_type"=>"image",		"display_name"=>"Photograph"),
		array("name"=>"l",			"data_type"=>"text",		"display_name"=>"Locality (e.g. Town/City)"),
		array("name"=>"mail",			"data_type"=>"text",		"display_name"=>"E-mail Address"),
		array("name"=>"mobile",			"data_type"=>"phone_number",	"display_name"=>"Mobile/Cell Telephone Number"),
		array("name"=>"pager",			"data_type"=>"text",		"display_name"=>"Pager Telephone Number"),
		array("name"=>"physicalDeliveryOfficeName","data_type"=>"text",		"display_name"=>"Office"),
		array("name"=>"sn",			"data_type"=>"text",		"display_name"=>"Surname"),
		array("name"=>"st",			"data_type"=>"text",		"display_name"=>"State (or Province/County)"),
		array("name"=>"streetAddress",		"data_type"=>"text_area",	"display_name"=>"Street Address"),
		array("name"=>"telephoneNumber",	"data_type"=>"phone_number",	"display_name"=>"Telephone Number"),
		array("name"=>"title",			"data_type"=>"text",		"display_name"=>"Job Title"),
		array("name"=>"thumbnailLogo",		"data_type"=>"image",		"display_name"=>"Thumbnail Logo"),
		array("name"=>"thumbnailPhoto",		"data_type"=>"image",		"display_name"=>"Thumbnail Photograph"),
		array("name"=>"url",			"data_type"=>"text",		"display_name"=>"URL (e.g. web page)"),
		array("name"=>"wWWHomePage",		"data_type"=>"text",		"display_name"=>"WWW Home Page")
		);
}

/** Return whether the specified attribute is mandatory

    Return 'true' if the specified attribute must always
    have a non-empty value in the specified object class.

    @param array $object_class_schema
	Object class schema to be queried
    @param string $object_class
	Object class to be queried
    @param string $attribute_name
	Attribute to return whether mandatory or not
    @return
	Whether the attribute is mandatory or not (true/false)
*/

function object_requires_attribute($object_class_schema,$object_class,$attribute_name)
{
	$required = (get_object_class_setting($object_class_schema,
		$object_class,"rdn_attrib")==$attribute_name);

	// if not required due to being the class's RDN attribute,
	// check whether it is listed in required_attribs

	if(!$required)
	{
		$required_attribs = explode(",",
			get_object_class_setting($object_class_schema,
				$object_class,"required_attribs"));

		foreach($required_attribs as $attrib)
			if($attrib == $attribute_name)
				$required = true;
	}

	return $required;
}

/** Return the value of a schema setting for the specificed LDAP attribute

    @param string $attribute_name
	Attribute for which schema setting is to be returned
    @param array $attribute_class_schema
	Attribute schema, as returned by get_attribute_class_schema()
    @param string $setting_name
	Schema setting to be returned
    @param string $setting_default
	Value to be returned if schema setting not defined
    @return
	Value of the attribute schema setting
*/

function get_attribute_setting($attribute_name,$attribute_class_schema,
	$setting_name,$setting_default)
{
        $setting_value=$setting_default;

        foreach($attribute_class_schema as $schema_entry)
                if($schema_entry["name"] == $attribute_name)
                        $setting_value = $schema_entry[$setting_name];

        return $setting_value;
}

/** Return the data type associated with the specified LDAP attribute

    @param string $attribute_name
	Attribute for which schema setting is to be returned
    @param array $attribute_class_schema
	Attribute schema, as returned by get_attribute_class_schema()
    @return
	Data type name for the specified LDAP attribute
*/

function get_attribute_data_type($attribute_name,$attribute_class_schema)
{
	return get_attribute_setting($attribute_name,$attribute_class_schema,
		"data_type","text");
}

/** Return the display name of the specified LDAP attribute

    @param string $attribute_name
	Attribute for which schema setting is to be returned
    @param array $attribute_class_schema
	Attribute schema, as returned by get_attribute_class_schema()
    @return
	Display name for the specified LDAP attribute
*/

function get_attribute_display_name($attribute_name,$attribute_class_schema)
{
	return get_attribute_setting($attribute_name,$attribute_class_schema,
		"display_name",$attribute_name);
}

/** Array of ISO 3166-1 alpha-2 country codes/names

    @see
	Code snippet from:
	http://coding-talk.com/forum/main-forums/coding-forum/snippets-functions-and-classes/12262-iso-country-codes-to-country-names-country-form-select-options
*/

$country_name=array(
	"AD" => "Andorra",
	"AE" => "United Arab Emirates",
	"AF" => "Afghanistan",
	"AG" => "Antigua and Barbuda",
	"AI" => "Anguilla",
	"AL" => "Albania",
	"AM" => "Armenia",
	"AN" => "Netherlands Antilles",
	"AO" => "Angola",
	"AQ" => "Antarctica",
	"AR" => "Argentina",
	"AS" => "American Samoa",
	"AT" => "Austria",
	"AU" => "Australia",
	"AW" => "Aruba",
	"AZ" => "Azerbaijan",
	"BA" => "Bosnia and Herzegovina",
	"BB" => "Barbados",
	"BD" => "Bangladesh",
	"BE" => "Belgium",
	"BF" => "Burkina Faso",
	"BG" => "Bulgaria",
	"BH" => "Bahrain",
	"BI" => "Burundi",
	"BJ" => "Benin",
	"BM" => "Bermuda",
	"BN" => "Brunei Darussalam",
	"BO" => "Bolivia",
	"BR" => "Brazil",
	"BS" => "Bahamas",
	"BT" => "Bhutan",
	"BV" =>	"Bouvet Island",
	"BW" => "Botswana",
	"BY" => "Belarus",
	"BZ" => "Belize",
	"CA" => "Canada",
	"CC" => "Cocos (Keeling) Islands",
	"CD" => "Congo, The Democratic Republic of the",
	"CF" => "Central African Republic",
	"CG" => "Congo",
	"CH" => "Switzerland",
	"CI" => "Cote D'Ivoire",
	"CK" => "Cook Islands",
	"CL" => "Chile",
	"CM" => "Cameroon",
	"CN" => "China",
	"CO" => "Colombia",
	"CR" => "Costa Rica",
	"CU" => "Cuba",
	"CV" => "Cape Verde",
	"CX" => "Christmas Island",
	"CY" => "Cyprus",
	"CZ" => "Czech Republic",
	"DE" => "Germany",
	"DJ" => "Djibouti",
	"DK" => "Denmark",
	"DM" => "Dominica",
	"DO" => "Dominican Republic",
	"DZ" => "Algeria",
	"EC" => "Ecuador",
	"EE" => "Estonia",
	"EG" => "Egypt",
	"EH" => "Western Sahara",
	"ER" => "Eritrea",
	"ES" => "Spain",
	"ET" => "Ethiopia",
	"FI" => "Finland",
	"FJ" => "Fiji",
	"FK" => "Falkland Islands (Malvinas)",
	"FM" => "Micronesia, Federated States of",
	"FO" => "Faroe Islands",
	"FR" => "France",
	"FX" => "France, Metropolitan",
	"GA" => "Gabon",
	"GB" => "United Kingdom",
	"GD" => "Grenada",
	"GE" => "Georgia",
	"GF" => "French Guiana",
	"GH" => "Ghana",
	"GI" => "Gibraltar",
	"GL" => "Greenland",
	"GM" => "Gambia",
	"GN" => "Guinea",
	"GP" => "Guadeloupe",
	"GQ" => "Equatorial Guinea",
	"GR" => "Greece",
	"GS" => "South Georgia and the South Sandwich Islands",
	"GT" => "Guatemala",
	"GU" => "Guam",
	"GW" => "Guinea-Bissau",
	"GY" => "Guyana",
	"HK" => "Hong Kong",
	"HM" => "Heard Island and McDonald Islands",
	"HN" => "Honduras",
	"HR" => "Croatia",
	"HT" => "Haiti",
	"HU" => "Hungary",
	"ID" => "Indonesia",
	"IE" => "Ireland",
	"IL" => "Israel",
	"IN" => "India",
	"IO" => "British Indian Ocean Territory",
	"IQ" => "Iraq",
	"IR" => "Iran, Islamic Republic of",
	"IS" => "Iceland",
	"IT" => "Italy",
	"JM" => "Jamaica",
	"JO" => "Jordan",
	"JP" => "Japan",
	"KE" => "Kenya",
	"KG" => "Kyrgyzstan",
	"KH" => "Cambodia",
	"KI" => "Kiribati",
	"KM" => "Comoros",
	"KN" => "Saint Kitts and Nevis",
	"KP" => "Korea, Democratic People's Republic of",
	"KR" => "Korea, Republic of",
	"KW" => "Kuwait",
	"KY" => "Cayman Islands",
	"KZ" => "Kazakstan",
	"LA" => "Lao People's Democratic Republic",
	"LB" => "Lebanon",
	"LC" => "Saint Lucia",
	"LI" => "Liechtenstein",
	"LK" => "Sri Lanka",
	"LR" => "Liberia",
	"LS" => "Lesotho",
	"LT" => "Lithuania",
	"LU" => "Luxembourg",
	"LV" => "Latvia",
	"LY" => "Libyan Arab Jamahiriya",
	"MA" => "Morocco",
	"MC" => "Monaco",
	"MD" => "Moldova, Republic of",
	"MG" => "Madagascar",
	"MH" => "Marshall Islands",
	"MK" => "Macedonia",
	"ML" => "Mali",
	"MM" => "Myanmar",
	"MN" => "Mongolia",
	"MO" => "Macau",
	"MP" => "Northern Mariana Islands",
	"MQ" => "Martinique",
	"MR" => "Mauritania",
	"MS" => "Montserrat",
	"MT" => "Malta",
	"MU" => "Mauritius",
	"MV" => "Maldives",
	"MW" => "Malawi",
	"MX" => "Mexico",
	"MY" => "Malaysia",
	"MZ" => "Mozambique",
	"NA" => "Namibia",
	"NC" => "New Caledonia",
	"NE" => "Niger",
	"NF" => "Norfolk Island",
	"NG" => "Nigeria",
	"NI" => "Nicaragua",
	"NL" => "Netherlands",
	"NO" => "Norway",
	"NP" => "Nepal",
	"NR" => "Nauru",
	"NU" => "Niue",
	"NZ" => "New Zealand",
	"OM" => "Oman",
	"PA" => "Panama",
	"PE" => "Peru",
	"PF" => "French Polynesia",
	"PG" => "Papua New Guinea",
	"PH" => "Philippines",
	"PK" => "Pakistan",
	"PL" => "Poland",
	"PM" => "Saint Pierre and Miquelon",
	"PN" => "Pitcairn Islands",
	"PR" => "Puerto Rico",
	"PS" => "Palestinian Territory",
	"PT" => "Portugal",
	"PW" => "Palau",
	"PY" => "Paraguay",
	"QA" => "Qatar",
	"RE" => "Reunion",
	"RO" => "Romania",
	"RU" => "Russian Federation",
	"RW" => "Rwanda",
	"SA" => "Saudi Arabia",
	"SB" => "Solomon Islands",
	"SC" => "Seychelles",
	"SD" => "Sudan",
	"SE" => "Sweden",
	"SG" => "Singapore",
	"SH" => "Saint Helena",
	"SI" => "Slovenia",
	"SJ" => "Svalbard and Jan Mayen",
	"SK" => "Slovakia",
	"SL" => "Sierra Leone",
	"SM" => "San Marino",
	"SN" => "Senegal",
	"SO" => "Somalia",
	"SR" => "Suriname",
	"ST" => "Sao Tome and Principe",
	"SV" => "El Salvador",
	"SY" => "Syrian Arab Republic",
	"SZ" => "Swaziland",
	"TC" => "Turks and Caicos Islands",
	"TD" => "Chad",
	"TF" => "French Southern Territories",
	"TG" => "Togo",
	"TH" => "Thailand",
	"TJ" => "Tajikistan",
	"TK" => "Tokelau",
	"TM" => "Turkmenistan",
	"TN" => "Tunisia",
	"TO" => "Tonga",
	"TL" => "Timor-Leste",
	"TR" => "Turkey",
	"TT" => "Trinidad and Tobago",
	"TV" => "Tuvalu",
	"TW" => "Taiwan",
	"TZ" => "Tanzania, United Republic of",
	"UA" => "Ukraine",
	"UG" => "Uganda",
	"UM" => "United States Minor Outlying Islands",
	"US" => "United States",
	"UY" => "Uruguay",
	"UZ" => "Uzbekistan",
	"VA" => "Holy See (Vatican City State)",
	"VC" => "Saint Vincent and the Grenadines",
	"VE" => "Venezuela",
	"VG" => "Virgin Islands, British",
	"VI" => "Virgin Islands, U.S.",
	"VN" => "Vietnam",
	"VU" => "Vanuatu",
	"WF" => "Wallis and Futuna",
	"WS" => "Samoa",
	"YE" => "Yemen",
	"YT" => "Mayotte",
	"RS" => "Serbia",
	"ZA" => "South Africa",
	"ZM" => "Zambia",
	"ME" => "Montenegro",
	"ZW" => "Zimbabwe",
	"AX" => "Aland Islands",
	"GG" => "Guernsey",
	"IM" => "Isle of Man",
	"JE" => "Jersey",
	"BL" => "Saint Barthelemy",
	"MF" => "Saint Martin");

/** Return full country name given ISO 3166-1 alpha-2 country code

    @param string $code
	Country code which is to be converted to country name
    @return
	Country name represented by the specified country code
*/

function get_country_name_from_code($code)
{
	global $country_name;
	if(isset($country_name[$code]))
		return $country_name[$code];
	else
		return "unknown";
}

/** View LDAP entry as HTML */

class ldap_entry_viewer
{
	/** Display layout (array of ldap_entry_viewer_section) */
	var $section = array();

	/** LDAP object entry which is to be displayed */
	var $ldap_entry;

	/** Name of last section to which an attribute was added

	    Used by add_to_section()
	*/
	var $last_section_added = "";

	/** Array of user information (e.g. allowed permissions) */
	var $user_info = "";

	/** Display a viewer for editing a record (true/false) */
	var $edit = false;

	/** Display a viewer for creating a new record (true/false) */
	var $create = false;

	/** Constructor

	    @param array $entry_viewer_layout
		LDAP attributes to be displayed and their layout
	    @param array $ldap_entry
		Array containing LDAP object entry which is to
		be displayed
	*/

	function ldap_entry_viewer($entry_viewer_layout,$ldap_entry)
	{
		$this->ldap_entry = $ldap_entry;
		$this->user_info = get_user_info();

		$first_section = true;
		foreach($entry_viewer_layout as $section)
		{
			$this->add_section(
				isset($section["section_name"]) ? $section["section_name"] : "",
				isset($section["new_row"]) ? $section["new_row"] : $first_section,
				isset($section["colspan"]) ? $section["colspan"] : 1,
				isset($section["width"]) ? $section["width"] : null
				);

			foreach($section["attributes"] as $attribute)
				$this->add_to_section(
					$attribute[0],			// LDAP attribute name
					isset($attribute[1]) ? $attribute[1] : null,	// caption
					isset($attribute[2]) ? $attribute[2] : null,	// icon
					isset($attribute[3]) ? $attribute[3] : null	// hide unless editing
					);

			$first_section = false;
		}
	}

	/** Add a section to the display

	    @param string $text
		Title text/section name
	    @param bool $newrow
		Should the section start on a new row?
	    @param integer $colspan
		Number of table columns to span
	    @param string $width
		Section width (defaults to evenly spaced/auto expand if missing)
	*/

	function add_section($text,$newrow=false,$colspan=1,$width="")
	{
		$heading = new ldap_entry_viewer_section();
		$heading->text = $text;
		$heading->colspan = $colspan;
		$heading->newrow = $newrow;
		$heading->width = $width;
		$heading->ldap_entry = $this->ldap_entry;

		$this->section[$text] = $heading;

		$this->last_section_added = $text;
	}

	/** Add an attribute and its value to the display

	    @param string $attribute
		LDAP attribute to be added to the layout section
	    @param string $caption
		"Friendly" caption to be used for the LDAP attribute
	    @param string $icon
		Icon image to display next to attribute
	    @param bool $hide_unless_editing
		Should the attribute be hidden except when editing
	*/

	function add_to_section($attribute,$caption="",$icon="",$hide_unless_editing=false)
	{
		$this->section[$this->last_section_added]->add_data(
			$attribute,$caption,$icon,$hide_unless_editing);
	}

	/** Output the object entry as vCard */

	function save_vcard()
	{
		echo vcard($this->ldap_entry[0]);
	}

	/** Output the object entry as HTML */

	function show()
	{
		global $ldap_base_dn,$ldap_server_type,$thumbnail_image_size,
			$enable_ldap_path_thumbnail;

		$object_class_schema = get_object_class_schema(
			$ldap_server_type);

		$dn = $this->ldap_entry[0]["dn"];

		if($this->create)
			show_ldap_path("CN=New "
				. get_object_class_setting($object_class_schema,
				get_object_class($object_class_schema,$this->ldap_entry[0])
				,"display_name") .  "," . $dn,$ldap_base_dn,
				get_icon_for_ldap_entry($this->ldap_entry[0]));
		else
			show_ldap_path($dn,$ldap_base_dn,
				get_icon_for_ldap_entry($this->ldap_entry[0]));

		if($this->user_info["allow_search"])
			show_search_box("");
		else
			echo "<br>";

		if($this->user_info["allow_view"])
		{
			if($this->edit && isset($this->user_info["allow_edit"]) && $this->user_info["allow_edit"])
				echo "<form method=\"post\" action=\"update.php?dn="
					. urlencode($dn) . "\" style=\"display:inline\" enctype=\"multipart/form-data\">";

			echo "<table class=\"ldap_entry_viewer\">\n";

			foreach($this->section as $section)
				$section->show($this->edit);

			echo "</table>\n\n";

			if(isset($this->user_info["allow_edit"]) && $this->user_info["allow_edit"])
				if($this->edit)
				{
					if($this->create)
						echo "<input type=\"hidden\" name=\"create\" value=\""
							. $this->ldap_entry[0]["objectclass"][0] . "\">"
							. "<input type=\"submit\" value=\"Create record\">"
							. "\n</form>\n";
					else
						echo "<input type=\"submit\" value=\"Save changes\">"
							. "\n</form>\n";

					echo "<a href=\"info.php?dn="
						. htmlentities($dn,ENT_COMPAT,"UTF-8")
						. "\"><button>Cancel</button></a>\n";
				}
				else
				{
					echo "<form method=\"get\" action=\"info.php\" style=\"display:inline\">\n"
						. "  <input type=\"hidden\" name=\"edit\" value=\"1\">\n"
						. "  <input type=\"hidden\" name=\"dn\" value=\""
						. htmlentities($dn,ENT_COMPAT,"UTF-8") . "\">\n"
						. "  <input type=\"submit\" value=\"Edit\">\n</form>\n";
				}

			if(isset($this->user_info["allow_delete"]) && $this->user_info["allow_delete"] && !$this->edit)
				echo "<a href=\"delete.php?page=info&dn="
					. urlencode($dn)
					. "\"><button>Delete</button></a>\n";

			if(isset($this->user_info["allow_export"]) && $this->user_info["allow_export"] && !$this->edit)
				echo "<a href=\"info.php?vcard=1&dn="
					. urlencode($dn)
					. "\"><button>Save as vCard</button></a>\n";
		}
		else
			echo "<p>You do not have permission to view this record</p>\n";
	}
}

/** Section of information within ldap_entry_viewer

    Contains a list of attributes to be displayed.
*/

class ldap_entry_viewer_section
{
	/** Title text/section name */
	var $text;

	/** Number of table columns to span */
	var $colspan=1;

	/** Should the section start on a new row?

	    - true: Display to the right of previous section (continuing same row)
	    - false: Display below previous section (starting a new row)

	    The user-defined section layout may be overridden (via CSS) on devices
	    with narrow screen widths, with all sections placed below each other
	    in their own rows. (equivalent of forcing 'true' for all sections)
	*/
	var $newrow=false;

	/** Attributes to display in section (array of ldap_entry_viewer_attribute) */
	var $attrib=array();

	/** Width of the section

	    Value to be assigned to CSS "width" style directive for the section.
	    Typically a percentage
	*/
	var $width="";

	/** LDAP object entry which is to be displayed */
	var $ldap_entry;

	/** Add an attribute and its value to the display

	    @param string $attribute
		LDAP attribute
	    @param string $caption
		Caption/label to be shown next to the LDAP attribute
	    @param string $icon
		Icon image to display next to attribute
	    @param bool $hide_unless_editing
		Whether the attribute should be hidden except when editing
	*/

	function add_data($attribute,$caption="",$icon="",$hide_unless_editing=false)
	{
		$this->attrib[] = new ldap_entry_viewer_attrib($this->ldap_entry,$attribute,
			$caption,$icon,$hide_unless_editing);
	}

	/** Output this section of the object entry as HTML

	    $param bool $edit
		Whether the section should be rendered with editing enabled
	*/

	function show($edit)
	{
		echo "\n<!-- Section: " . $this->text . " -->\n\n";

		if($this->newrow) echo "  <tr>\n";

		$cell_attrib = "";
		if($this->colspan != 1)
			$cell_attrib.=" colspan=" . $this->colspan;

		if($this->width != "")
			$cell_attrib.=" style=\"width:" . $this->width . "\"";

		echo "    <td class=\"ldap_entry_viewer_section_frame\" " . $cell_attrib
			. ">\n      <table class=\"ldap_entry_viewer_section\">\n";

		if(!empty($this->text))
			echo "        <tr>\n          <th colspan=3 class=\"column_header\">"
				. $this->text . "</th>\n        </tr>\n";

		foreach($this->attrib as $attrib)
			$attrib->show($edit);

		echo "      </table>\n";
		echo "    </td>\n";
	}
}

/** Individual LDAP object attribute displayed in ldap_entry_viewer_section */

class ldap_entry_viewer_attrib
{
	/** Caption/label to be shown next to the LDAP attribute */
	var $caption;

	/** LDAP attribute to display */
	var $ldap_attribute;

	/** Icon image to display next to attribute */
	var $icon;

	/** Whether the attribute should be rendered with editing enabled */
	var $edit = false;

	/** LDAP entry whose attribute is to be displayed */
	var $ldap_entry;

	/** Whether this attribute should be hidden except when editing */
	var $hide_unless_editing = false;

	/** Add an attribute and its value to the display

	    @param array $ldap_entry
		LDAP entry whose attribute is to be displayed
	    @param string $attribute
		LDAP attribute to display
	    @param string $caption
		Caption/label to be shown next to the LDAP attribute
	    @param string $icon
		Icon image to display next to attribute
	    @param bool $hide_unless_editing
		Whether this attribute should be hidden except when editing
	*/

	function ldap_entry_viewer_attrib($ldap_entry,$attribute,$caption="",$icon="",$hide_unless_editing=false)
	{
		$this->caption = $caption;
		$this->ldap_attribute = $attribute;
		$this->icon = $icon;
		$this->ldap_entry = $ldap_entry;
		$this->hide_unless_editing = $hide_unless_editing;
	}

	/** Output this object attribute as HTML

	    @param bool $edit
		Whether the attribute should be rendered with editing enabled
	*/

	function show($edit)
	{
		global $ldap_server_type;

		$this->edit = $edit;

		if($this->edit || !$this->hide_unless_editing)
		{
			echo "        <tr>\n";

			// Use full width if attribute has no icon or caption text
			if($this->icon == "" && $this->caption == "")
				echo "          <td colspan=3 class=\""
						. ldap_attribute_to_css_class($this->ldap_attribute)
						. "\">";
			else
			{
				echo "          <th>";
				if($this->icon != "")
					echo "<img alt=\"" . $this->ldap_attribute
						. "\" src=\"schema/"
						. $this->icon . "\">";
				echo "</th>\n          "
					. "<th>"
					. $this->caption . "</th>\n";

				echo "          <td class=\""
					. ldap_attribute_to_css_class($this->ldap_attribute)
					. "\">\n            ";
			}

			$attribute_class_schema = get_attribute_class_schema($ldap_server_type);

			$first_line = true;
			// look up values of attributes listed
			//   (: = line break, + = space between words)
			foreach(explode(":",$this->ldap_attribute) as $attribute_line)
			{
				if($first_line == false) echo "<br>\n";
				$first_line = false;

				$space_before_attribute = false;
				foreach(explode("+",$attribute_line) as $attribute)
				{
					if($space_before_attribute) echo " ";
					$space_before_attribute = true;

					$display_name=get_attribute_display_name($attribute,$attribute_class_schema);

					if($display_name!=$attribute)
						$display_name .= " (" . $attribute . ")";

					// determine whether this is a required attribute

					$object_class_schema = get_object_class_schema($ldap_server_type);
					$required = object_requires_attribute($object_class_schema,
						get_object_class($object_class_schema,$this->ldap_entry[0])
						,$attribute);

					// display the attribute
					switch(get_attribute_data_type($attribute,$attribute_class_schema))
					{
						case "postcode":
							$this->show_postcode($attribute,$display_name,$required); break;
						case "country_code":
							$this->show_country_code($attribute,$display_name,$required); break;
						case "image":
							$this->show_image($attribute,$display_name,$required); break;
						case "text":
							$this->show_text($attribute,$display_name,$required); break;
						case "text_area":
							$this->show_text_area($attribute,$display_name,$required); break;
						case "phone_number":
							$this->show_phone_number($attribute,$display_name,$required); break;
						default:
							echo "** unsupported data type **";
					}
				}
			}
			echo "\n          </td>\n        </tr>\n";
		}
	}

	/** Show single-line textual attribute (data type "text")

	    @todo
		Escape "nasty values" in $attrib_value, e.g. "
	    @todo
		Style this better.. should be 100% less a fixed number of pixels?

	    @param string $attribute
		Attribute to display
	    @param string $display_name
		"Friendly" display name of attribute (typically
		rendered as "tooltip")
	    @param bool $required
		Whether attribute is mandatory (either marked as such or the RDN)
	*/

	function show_text($attribute,$display_name,$required)
	{
		$attrib_value = get_ldap_attribute(
			$this->ldap_entry,$attribute);

		if($this->edit)
		{
			if($required)
				$style = "width:98%;border-color:red;border-style:solid";
			else
				$style = "width:98%;";

			echo "<input style=\"" . $style . "\" type=\"text\" name=\"ldap_attribute_"
				. $attribute . "\" value=\""
				. htmlentities($attrib_value,ENT_COMPAT,"UTF-8")
				. "\" title=\"" . $display_name . "\" placeholder=\""
				. $display_name . "\">";
		}
		else
			echo urls_to_links(htmlentities($attrib_value,ENT_COMPAT,"UTF-8"));
	}

	/** Show telephone number (data type "phone_number")

	    @todo
		Escape "nasty values" in $attrib_value, e.g. "
	    @todo
		Style this better.. should be 100% less a fixed number of pixels?

	    @param string $attribute
		Attribute to display
	    @param string $display_name
		"Friendly" display name of attribute (typically
		rendered as "tooltip")
	    @param bool $required
		Whether attribute is mandatory (either marked as such or the RDN)
	*/

	function show_phone_number($attribute,$display_name,$required)
	{
		$attrib_value = get_ldap_attribute(
			$this->ldap_entry,$attribute);

		if($this->edit)
		{
			if($required)
				$style = "width:98%;border-color:red;border-style:solid";
			else
				$style = "width:98%;";

			echo "<input style=\"" . $style . "\" type=\"text\" name=\"ldap_attribute_"
				. $attribute . "\" value=\""
				. htmlentities($attrib_value,ENT_COMPAT,"UTF-8")
				. "\" title=\"" . $display_name . "\" placeholder=\""
				. $display_name . "\">";
		}
		else
			show_phone_number_formatted($attrib_value);
	}

	/** Show multi-line textual attribute (data type "text_area")

	    @todo
		Escape "nasty values" in $attrib_value, e.g. "
	    @todo
		Style this better.. should be 100% less a fixed number of pixels?

	    @param string $attribute
		Attribute to display
	    @param string $display_name
		"Friendly" display name of attribute (typically
		rendered as "tooltip")
	    @param bool $required
		Whether attribute is mandatory (either marked as such or the RDN)
	*/

	function show_text_area($attribute,$display_name,$required)
	{
		$attrib_value = get_ldap_attribute(
			$this->ldap_entry,$attribute);

		if($this->edit)
		{
			if($required)
				$style = "width:98%;border-color:red;border-style:solid";
			else
				$style = "width:98%;";

			echo "\n            <textarea style=\"" . $style . "\" name=\"ldap_attribute_"
				. $attribute . "\" title=\"" . $display_name
				. "\" placeholder=\"" . $display_name . "\">"
				. htmlentities($attrib_value,ENT_COMPAT,"UTF-8")
				. "</textarea>";
		}
		else
			echo nl2br(urls_to_links(htmlentities($attrib_value,ENT_COMPAT,"UTF-8")),false);
	}

	/** Show ISO 3166-1 alpha-2 country code attribute (data type "country_code")

	    @todo
		Improve handling of unrecognised country codes

	    @param string $attribute
		Attribute to display
	    @param string $display_name
		"Friendly" display name of attribute (typically
		rendered as "tooltip")
	    @param bool $required
		Whether attribute is mandatory (either marked as such or the RDN)
	*/

	function show_country_code($attribute,$display_name,$required)
	{
		global $country_name;
		asort($country_name);

		$attrib_value = get_ldap_attribute(
			$this->ldap_entry,$attribute);

		if($this->edit)
		{
			if($required)
				$style = "border-color:red;border-style:solid";
			else
				$style = "";

			echo "<select name=\"ldap_attribute_" . $attribute
				. "\" title=\"" . $display_name . "\" style=\"" . $style . "\">\n";

			if($attrib_value == "")
				echo "              <option value=\"\" selected>(blank)</option>\n";
			else
				echo "              <option value=\"\">(blank)</option>\n";

			foreach($country_name as $code => $name)
				echo "              <option value=\"" . $code . "\""
					. ($attrib_value == $code ? " selected" : "")
					. ">" . $name . " (" . $code . ")</option>\n";

			echo "            </select>";
		}
		else
			if($attrib_value != "")
				echo get_country_name_from_code($attrib_value);
	}

	/** Show postcode attribute (data type "postcode")

	    Renders a poscode attribute - single line text, with an
	    adjacent button to display location in mapping service

	    @todo
		Escape "nasty values" in $attrib_value, e.g. "
	    @todo
		Style this better.. should be 100% less a fixed number of pixels?
	    @todo make mapping service configurable (not just Google)

	    @param string $attribute
		Attribute to display
	    @param string $display_name
		"Friendly" display name of attribute (typically
		rendered as "tooltip")
	    @param bool $required
		Whether attribute is mandatory (either marked as such or the RDN)
	*/

	function show_postcode($attribute,$display_name,$required)
	{
		$attrib_value = get_ldap_attribute(
			$this->ldap_entry,$attribute);

		if($this->edit)
		{
			if($required)
				$style = "width:98%;border-color:red;border-style:solid";
			else
				$style = "width:98%;";

			echo "<input style=\"" . $style . "\" type=\"text\" name=\"ldap_attribute_"
				. $attribute . "\" value=\""
				. htmlentities($attrib_value,ENT_COMPAT,"UTF-8")
				. "\" title=\"" . $display_name . "\" placeholder=\"" . $display_name . "\">";
		}
		else
			if($attrib_value != "")
				echo $attrib_value . "&nbsp;(<a href=\"https://maps.google.co.uk/?q="
					. urlencode($attrib_value) . "\" target=\"_blank\">View map</a>)";
	}

	/** Show image attribute (data type "image")

	    @param string $attribute
		Attribute to display
	    @param string $display_name
		"Friendly" display name of attribute (typically
		rendered as "tooltip")
	    @param bool $required
		Whether attribute is mandatory (either marked as such or the RDN)
	*/

	function show_image($attribute,$display_name,$required)
	{
		global $photo_image_size;

		$attrib_value = get_ldap_attribute(
			$this->ldap_entry,$attribute);

		/** @todo
			The method used here is not a very efficient
			way to determine  whether image attribute is empty
			(should avoid calling get_ldap_attribute above)
		*/
		if($attrib_value != "")
		{
			if(!empty($photo_image_size))
				$size = "&size="
					. $photo_image_size;
			else
				$size="";

			echo "<img src=\"image.php?dn="
				. urlencode($this->ldap_entry[0]["dn"])
				. "&attrib=" . $attribute . $size
				. "\" title=\"" . $display_name . "\">\n";
		}

		if($this->edit)
		{
			// Don't show "Clear Image" button if attribute is mandatory
			if($attrib_value == "" || $required)
				echo "            <input type=\"hidden\" name=\"ldap_attribute_"
					. $attribute . "\" value=\"\">";
			else
				echo "            <br>\n            <input type=\"checkbox\" name=\"ldap_attribute_"
					. $attribute . "\">Clear Image<br>\n";

			if($required)
				$style = "border-color:red;border-style:solid";
			else
				$style = "";

			echo "            <input style=\"" . $style . "\" type=\"file\" name=\"ldap_attribute_"
				. $attribute . "_file\" title=\"" . $display_name
				. "\" accept=\".jpg,.jpeg,.png,.gd2,.wbmp\">";
		}
	}
}

/** Return attribute-specific CSS class name for given LDAP attribute

    @param string $attrib
	Attribute for which CSS class name is to be returned.
    @return
	CSS class to be used when displaying the attribute.
*/

function ldap_attribute_to_css_class($attrib)
{
	// Remove characters not supported in CSS class names
	$attrib = str_replace(":","",$attrib);
	$attrib = str_replace("+","",$attrib);

	return "ldap_attribute_" . $attrib;
}

/** Return specified attribute from an LDAP object entry

    Return specified attribute from an LDAP object entry specified
    as an array, processing it for display as HTML (e.g. turn URLs
    and e-mail addresses, special characters into entities, etc)

    @param array $ldap_entry
	LDAP entry for which an attribute value is to be returned
    @param string $attribute
	Attribute for which value to be returned
    @return
	Value of the requested attribute
*/

function get_ldap_attribute($ldap_entry,$attribute)
{
	$attribute = strtolower($attribute);

	if(!empty($ldap_entry[0][$attribute][0]))
		/** @todo
			Currently only returns the first value of the
			attribute. Should iterate over multi-valued
			attributes.
		*/
		$attrib_value = $ldap_entry[0][$attribute][0];
	else
		$attrib_value = "";

	return $attrib_value;
}

/** Turn any URLs and e-mail addresses appearing in the text into HTML links.

    @param string $text
	Text which is to have its substrings that resemble
	URLs and e-mail addresses converted to links
    @return
	Processed version of the text, with URLs and e-mail
	addreses converted to HTML links
*/

function urls_to_links($text)
{
	// convert URLs to links
	$text = preg_replace(
		"/[[:alpha:]]+:\/\/[^<>[:space:]]+[[:alnum:]\/]/",
		"<a href=\"\\0\" rel=\"nofollow\">\\0</a>",$text);

	// convert e-mail addresses to links
	$text = preg_replace("/\b(\S+@\S+)\b/",
		'<a href="mailto:\1">\1</a>',$text);

	return $text;
}

/** Report an LDAP bind error (login failure)

    Uses wording appropriate to the specific situation
*/

function show_ldap_bind_error()
{
	global $ldap_login_enabled;

	if($ldap_login_enabled)
	{
		if(isset($_SESSION["LOGIN_USER"]))
			echo "<p>You do not have permission to log in to"
				. " the address book directory.</p>\n"
				. "<p><a href=\"user.php\">Log in as a"
				. " different user</a></p>";
		else
			echo "<p><a href=\"user.php\">Please log in to"
				. " access the address book"
				. " directory</a></p>";
	}
	else
	{
		echo "<p>Unable to connect to address book directory."
			. " (LDAP bind failed)</p>\n";

		// don't show this line if the user has already configured
		// a non-blank default user
		if(get_user_attrib("__ANONYMOUS__","ldap_name")=="")
			echo "<p>If you have not already done so, please
				<a href=\"doc/\">read the manual</a> for how
				instructions on to configure directory
				access.</p>";
	}
}

/** Log on to LDAP directory

    Attempts LDAP bind (login) with user (or config file) specified
    credentials

    @param resource $ldap_link
	LDAP connection handle to bind/authenticate against
    @return
	Whether log on was successful (true/false)
*/

function log_on_to_directory($ldap_link)
{
	global $follow_ldap_referrals;

	$user = get_ldap_bind_user();

	$result=false;
	if($user != "__DENY__")
	{
		ldap_set_option($ldap_link,LDAP_OPT_PROTOCOL_VERSION,3);

		if(!isset($follow_ldap_referrals))
			$follow_ldap_referrals = false;

		ldap_set_option($ldap_link,LDAP_OPT_REFERRALS,
			$follow_ldap_referrals);

		$result=@ldap_bind($ldap_link,$user,
			get_ldap_bind_password());

		if($follow_ldap_referrals)
			ldap_set_rebind_proc($ldap_link,
				"ldap_referral_rebind");	// callback

		// Timezone is not known to be used for anything in this
		// application, however various LDAP functions produce
		// warning messages in newer PHP versions (>=5.1.0) if it
		// is not set.
		if(!ini_get("date.timezone"))
			date_default_timezone_set("UTC");
	}

	return $result;
}

/** Get LDAP bind DN/login name of current user

    @return
	LDAP bind DN/login name of current user
*/

function get_ldap_bind_user()
{
	return isset($_SESSION["LOGIN_USER"])
		? get_user_attrib($_SESSION["LOGIN_USER"],"ldap_name")
		: get_user_attrib("__ANONYMOUS__","ldap_name");
}

/** Get LDAP bind password of current user

    @return
	LDAP bind password of current user
*/

function get_ldap_bind_password()
{
	return isset($_SESSION["LOGIN_USER"])
		? $_SESSION["LOGIN_PASSWORD"]
		: get_user_attrib("__ANONYMOUS__","ldap_password");
}

/** Callback function to reauthenticate following LDAP referral

    @param resource $ldap_link
        LDAP connection handle to bind/authenticate against
    @param string $referral_uri
	LDAP URI to be accessed following rebind (unused)
    @return
	1 for successful rebind, 0 for failure
*/

function ldap_referral_rebind($ldap_link,$referral_uri)
{
	$user = get_ldap_bind_user();

	if($user == "__DENY__")
		return 1;
	else
		return @ldap_bind($ldap_link,$user,
			get_ldap_bind_password()) ? 1 : 0;
}

/** Return value of named attribute for specified address book user name

    @param string $user_name
	Name of user whose information is required
    @param string $attrib
	Attribute to be returned
    @return
	Requested user attribute
*/

function get_user_attrib($user_name,$attrib)
{
	$user_info = get_user_info($user_name);
	return $user_info[$attrib];
}

/** Return array of attributes for specified address book user name

    @param string $user_name
	Name of user whose information is required (default to
	currently logged in user if omitted)
    @return
	Array of user information (e.g. allowed permissions)
*/

function get_user_info($user_name="")
{
	global $ldap_user_map;

	if(empty($user_name))
		if(isset($_SESSION["LOGIN_USER"]))
			$user_name = $_SESSION["LOGIN_USER"];
		else
			$user_name = "__ANONYMOUS__";

	$user_info = array();	// default if no match at all
	$found=false;
	foreach($ldap_user_map as $map_user)
		if(!$found && ($map_user["login_name"] == $user_name
			|| $map_user["login_name"] == "__DEFAULT__"))
		{
			$user_info = $map_user;
			$found = true;
		}
	$user_info["ldap_name"]=str_replace("__USERNAME__",
		$user_name,$user_info["ldap_name"]);
	return $user_info;
}

/** Return matching schema object class for the specified LDAP entry

    @param array $object_class_schema
	Schema object class definitions as returned by
	get_object_class_schema()
    @param string $entry
	Entry for which matching class name is to be returned
    @return
	Most specific class name for the object, or the
	string "(unrecognised)" if none of its object class
	values appears in the schema
*/

function get_object_class($object_class_schema,$entry)
{
	$object_data_found = false;
	$item_object_class = "(unrecognised)";

	foreach($object_class_schema as $object_class)
	{
		if(in_array($object_class["name"],
			$entry["objectclass"])
			&& $object_data_found == false)
		{
			$item_object_class = $object_class["name"];
			$object_data_found = true;
		}
	}
	return $item_object_class;
}

/** Return the value of a setting for the specified object class

    @param array $object_class_schema
	Schema object class definitions as returned by
	get_object_class_schema()
    @param string $class
	Schema class for which the setting value is required
    @param string $setting
	Schema class setting for which the value is required
    @return
	Value of the requested setting
*/

function get_object_class_setting($object_class_schema,$class,$setting)
{
	$setting_value = "";
	$object_data_found = false;

	foreach($object_class_schema as $object_class)
		if($object_class["name"] == $class && isset($object_class[$setting]))
		{
			$setting_value = $object_class[$setting];
			$object_data_found = true;
		}

	// return useful defaults if setting not found in schema
	if(!$object_data_found)
	{
		if($setting == "icon") $setting_value = "generic24.png";
		if($setting == "is_folder") $setting_value = false;
		if($setting == "rdn_attrib") $setting_value = "cn";
		if($setting == "can_create") $setting_value = false;
		if($setting == "display_name") $setting_value = $class;
	}
	return $setting_value;
}

/** Sort an array of LDAP entries against one or more attributes.

    @see
	Derived from code snippet at:
	http://www.php.net/manual/en/function.ldap-sort.php

    @param array $ldap_entries
	LDAP entries to be sorted
    @param array $attrib_list
	LDAP attributes to sort by, listed in order of priority
    @param integer $sort_direction
	Either LDAP_SORT_ASCENDING or LDAP_SORT_DESCENDING
*/

function ldap_sort_entries($ldap_entries,$attrib_list,$sort_direction)
{
	global $lc_collate;
	$collator = collator_create($lc_collate);

	for ($i=0;$i<$ldap_entries["count"];$i++)
		for ($j=$i;$j<$ldap_entries["count"];$j++)
		{
			$d = ldap_sort_entries_compare($collator,
				$ldap_entries[$i],$ldap_entries[$j],
				$attrib_list);

			if($sort_direction == LDAP_SORT_ASCENDING && $d>0)
				ldap_sort_entries_swap($ldap_entries,$i,$j);

			if($sort_direction == LDAP_SORT_DESCENDING && $d<0)
				ldap_sort_entries_swap($ldap_entries,$i,$j);
		}

	return $ldap_entries;
}

/** Compare two LDAP entries and return which of them occurs first alphabetically.

    Subfunction used for LDAP record sorting.

    @param object $collator
	Collator providing locale-sensitive comparison function
    @param array $entry1
	First entry to be compared
    @param array $entry2
	Second entry to be compared
    @param array $attrib_list
	Array of attributes to be used in the comparison, in
	order of priority
    @return
	Value indicating which occurs first alphabetically
*/

function ldap_sort_entries_compare($collator,$entry1,$entry2,$attrib_list)
{
	$d = 0;
	foreach($attrib_list as $fld_test)
	{
		$fld_test=strtolower($fld_test);
		if($d == 0 && ldap_sort_entries_getattrib($entry1,$fld_test)
				!= ldap_sort_entries_getattrib($entry2,$fld_test))

			$d = collator_compare($collator,
				ldap_sort_entries_getattrib($entry1,$fld_test),
				ldap_sort_entries_getattrib($entry2,$fld_test));
	}

	return $d;
}

/** Swap a pair of LDAP entries in an array.

    Subfunction used for LDAP record sorting - swaps a pair of
    indexed array elements

    @param array $ldap_entries
	Array whose elements are to be swapped
    @param mixed $entry1
	Index of first element
    @param mixed $entry2
	Index of second element
*/

function ldap_sort_entries_swap(&$ldap_entries,$entry1,$entry2)
{
	$temp = $ldap_entries[$entry1];
	$ldap_entries[$entry1] = $ldap_entries[$entry2];
	$ldap_entries[$entry2] = $temp;
}

/** Return the value of a specified entry and attribute.

    Deals with the following special cases so that the attribute
    is ready for use in sorting:
	- Returns an empty string if the attribute doesn't exist
	- Returns the first value of a multi-value attribute

    @param array $entry
	Entry for which the attribute is to be returned
    @param string $attrib
	Attribute whose value is to be returned (which must have
	a textual data type)
    @return
	The value requested
*/

function ldap_sort_entries_getattrib($entry,$attrib)
{
	if(!isset($entry[$attrib]))
		return "";
	else
	{
		if(isset($entry[$attrib][0]))
			return $entry[$attrib][0];
		else
			return $entry[$attrib];
	}
}

/** View LDAP entries (e.g. search results) as a HTML list */

class ldap_entry_list
{
	/** LDAP search resource containing the LDAP object entries which
            are to be displayed */
	var $ldap_entries;

	/** Column layout used for displaying search results */
	var $search_result_columns;

	/** LDAP attribute that the list should be sorted by */
	var $sort_order;

	/** Constructor

	    @param resource $ldap_entries
		LDAP search resource containing the LDAP object entries which
		are to be displayed
	    @param array $search_result_columns
		Search result column layout to use for display
	    @param string $sort_order
		LDAP attribute that the list should be sorted by
	*/

	function ldap_entry_list($ldap_entries,$search_result_columns,$sort_order)
	{
		$this->ldap_entries = $ldap_entries;
		$this->search_result_columns = $search_result_columns;
		$this->sort_order = $sort_order;
	}

	/** Output address book contents as vCard */

	function save_vcard()
	{
		global $ldap_link;

		// Fetch and sort records

		$ldap_data = ldap_sort_entries(
			ldap_get_entries($ldap_link,$this->ldap_entries),
			$this->sort_order == "sortableName"
			? array("sn","givenName","ou","cn")
			: array($this->sort_order),
			LDAP_SORT_ASCENDING);

		for($i=0;$i < $ldap_data["count"]; $i++)
			echo vcard($ldap_data[$i]) . "\n";
	}

	/** Output the object entry list as HTML, applying
	    user's chosen sort order */

	function show()
	{
		global $ldap_link,$ldap_server_type;

		$object_class_schema
			= get_object_class_schema($ldap_server_type);

		echo "<table class=\"search_results_viewer\">\n  <tr>\n";

		$this->show_column_headings();

		// Fetch and sort records

		$ldap_data = ldap_sort_entries(
			ldap_get_entries($ldap_link,$this->ldap_entries),
			$this->sort_order == "sortableName"
			? array("sn","givenName","ou","cn")
			: array($this->sort_order),
			LDAP_SORT_ASCENDING);

		// Display records

		for($i=0;$i < $ldap_data["count"]; $i++)
			$this->show_ldap_entry($object_class_schema,
				$ldap_data[$i]);

		echo "</table>\n";
	}

	/** Display column headings */

	function show_column_headings()
	{
		$colspan="colspan=2 ";
		foreach($this->search_result_columns as $column)
		{
			echo "    <th " . $colspan
				. "class=\"column_header search_results_attrib_"
				. $column["attrib"] . "\">"
				. "<a href=\"?sort=";

			echo urlencode($column["attrib"]);

			// Only the first item should have colspan=2 (so
			// that it spans both the icon and first attribute
			// column)
			$colspan="";

			if(!empty($_GET["dn"]))
				echo "&dn=" . urlencode($_GET["dn"]);

			if(!empty($_GET["filter"]))
				echo "&filter=" . urlencode($_GET["filter"]);

			echo "\">" . $column["caption"] . "</a></th>\n";
		}
		echo "  </tr>\n";
	}

	/** Display a single LDAP entry (row in table of search results)

	    @param array $object_class_schema
		Array of information about LDAP object
		classes, as returned by get_object_class_schema()
	    @param arary $ldap_entry
		LDAP entry to display
	*/

	function show_ldap_entry($object_class_schema,$ldap_entry)
	{
		global $enable_search_browse_thumbnail,$thumbnail_image_size;
		echo "  <tr>\n";

		// Fetch object schema details for this record

		$item_object_class = get_object_class(
			$object_class_schema,$ldap_entry);

		$dn = $ldap_entry["dn"];

		$icon = get_icon_for_ldap_entry($ldap_entry);

		$item_is_folder = get_object_class_setting(
			$object_class_schema,$item_object_class,"is_folder");
		$object_rdn_attrib = get_object_class_setting(
			$object_class_schema,$item_object_class,"rdn_attrib");

		// Item object class is displayed in the tooltip. All
		// inherited object classes should be listed where no
		// specific schema entry is recognised.
		if($item_object_class == "(unrecognised)")
		{
			$item_object_class="";
			// Subtract 1 is to take into account "count"
			// (which contains the number of class entries)
			for($j=0;$j<count($ldap_entry["objectclass"])-1;$j++)
			{
				if($j>0) $item_object_class .= ",";
				$item_object_class .=
					$ldap_entry["objectclass"][$j];
			}
		}

		// Display the record's icon (with tooltip/alt text as
		// described above)

		echo "    <td class=\"object_class_icon\"><img alt=\""
			. $item_object_class
			. "\" title=\"" . $item_object_class
			. "\" src=\"" . $icon . "\"></td>\n";

		$object_dn = $ldap_entry["dn"];

		$user_info = get_user_info();

		if($item_is_folder)
			// Display the folder name, and make it a link to
			// display the folder's contents.

			$this->show_attrib($object_dn,$object_rdn_attrib,
				$ldap_entry[$object_rdn_attrib][0],
				"object",$item_is_folder);
		else
		{
			// Display user's chosen set of columns (attributes)

			foreach($this->search_result_columns as $column)
			{
				// Get the attribute value for this column.
				$attrib_value = $this->get_attrib_value(
					$ldap_entry,$column["attrib"]);

				// Don't make the cell a link to the object
				// if the user doesn't have view permissions
				if($column["link_type"] == "object"
						&& !$user_info["allow_view"])
					$column["link_type"] = "none";

				$this->show_attrib($object_dn,
					$column["attrib"],
					$attrib_value,$column["link_type"]);
			}
		}

		if(isset($user_info["allow_delete"]) && $user_info["allow_delete"])
			echo "    <td style=\"width:1px;background-color:transparent\">\n      <a href=\"delete.php?dn="
				. urlencode($dn)
				. "\"><button>Delete</button></a>\n    </td>\n";

		echo "  </tr>\n";
	}

	/** Return the value of the specified LDAP entry and attribute

	    For multi-value attributes this function currently
	    returns only the first value.

	    @param array $ldap_entry
		LDAP entry for which value is to be returned
	    @param string $attrib
		Attribute of the LDAP entry to return
	    @return
		The requested attribute value
	*/

	function get_attrib_value($ldap_entry,$attrib)
	{
		/** @todo
			add support compound fields of multiple
			attributes
		*/

		// sortableName is an internal "synthesised"
		// attribute rather than retrieved from
		// the LDAP server itself.
		if($attrib == "sortableName")
		{
			if(!empty($ldap_entry["sn"][0]))
				$attrib_value = $ldap_entry["sn"][0];
			else if(!empty($ldap_entry["displayname"][0]))
				$attrib_value
					= $ldap_entry["displayname"][0];
			else
				$attrib_value = $ldap_entry["cn"][0];

			if(!empty($ldap_entry["givenname"][0]))
				$attrib_value .= ", "
					. $ldap_entry["givenname"][0];
		}
		else
			/** @todo
				this only supports getting the first
				value of a multi-value attribute.
			*/
			if(!empty($ldap_entry[strtolower($attrib)][0]))
				$attrib_value =
					$ldap_entry[strtolower($attrib)][0];
			else
				$attrib_value = "";

		return $attrib_value;
	}

	/** Display the specified attribute of an LDAP object.

	    This corresponds to an individual table cell in the search
	    results or OU being browsed

	    @todo
		support for compound attributes, e.g. address

	    @param string $dn
		LDAP distinguished name of object
	    @param string $attrib_name
		Name of attribute to be shown
	    @param string $attrib_value
		Value of attribute to be shown
	    @param string $link_type
		Specifies if and how the attribute should be
		shown as a HTML link:

		- object: Link to detailed info about the object
			(typically used with the sortableName attribute
			in the left-hand column, conceptually representing
			the object itself)
		- mailto: Link to the attribute's value as an e-mail address
		- url: Cell contains a URL which should be shown as a link
		- (none): Do not display attribute as a link
	    @param bool $is_folder
		Specifies whether the this record should be
		presented as a folder (clicking the link navigates to an
		OU browser for the destination) or a leaf object (clicking
		the link navigates to a page of detailed info about the
		object). Additional attributes (beyond the name) are also
		not shown for folders
	*/

	function show_attrib($dn,$attrib_name,$attrib_value,$link_type,
		$is_folder = false)
	{
		global $thumbnail_image_size,$ldap_server_type;

		if($is_folder)
			$colspan = " colspan=\""
				. count($this->search_result_columns)
				. "\"";
		else
			$colspan="";

		echo "    <td class=\""
			. ldap_attribute_to_css_class($attrib_name)
			. " search_results_attrib_" . $attrib_name . "\"" . $colspan . ">\n      ";

		if($link_type == "object")
			// Cell contains a link to the object
			echo "<a href=\"" . ($is_folder ? "" : "info.php")
				. "?dn=" . urlencode($dn) . "\">";
		else if($link_type == "mailto")
			// Cell contains a link to an e-mail address
			echo "<a href=\"mailto:"
				. urlencode($attrib_value) . "\">";
		else if($link_type == "url")
			// Cell contains a URL which should be shown as a link
			echo "<a href=\""
				. $attrib_value . "\">";

		// Display the attribute's value
		$attribute_class_schema = get_attribute_class_schema($ldap_server_type);
		switch(get_attribute_data_type($attrib_name,$attribute_class_schema))
		{
			case "image":
				if(!empty($attrib_value))
				{
					if(!empty($thumbnail_image_size))
						$size = "&size="
							. $thumbnail_image_size;
					else
						$size="";

					echo "<img src=\"image.php?dn="
						. urlencode($dn)
						. "&attrib="
						. $attrib_name
						. $size . "\">";
				}
				break;
			case "phone_number":
				if($link_type == "phone_number")
					show_phone_number_formatted($attrib_value);
				else
					echo htmlentities($attrib_value,
						ENT_COMPAT,"UTF-8");
				break;
			default:
				echo htmlentities($attrib_value,
					ENT_COMPAT,"UTF-8");
		}

		if($link_type != "none" && $link_type != "phone_number")
			echo "</a>";

		echo "\n    </td>\n";
	}
}

/** Prepare a string for use as a value in an LDAP search query

    Escapes otherwise invalid characters, e.g. to guard against
    "LDAP injection" attacks.

    @param string $ldap_value
	Text to be escaped for use as an LDAP value
    @return
	Sanitised version of string with invalid characters escaped
*/

function ldap_escape_search_value($ldap_value)
{
	return ldap_escape($ldap_value,false);
}

/** Prepare a string for use as a value in an LDAP DN

    Escapes otherwise invalid characters, e.g. guard against
    "LDAP injection" attacks.

    @param string $ldap_dn
	Text to be escaped for use as an LDAP DN
    @return
	Sanitised version of string with invalid characters escaped
*/

function ldap_escape_dn_value($ldap_dn)
{
	return ldap_escape($ldap_dn,true);
}

/** Prepare a string for use as an LDAP value or DN.

    Escapes otherwise invalid characters, e.g. guard against
    "LDAP injection" attacks.

    @see
    http://stackoverflow.com/questions/8560874/php-ldap-add-function-to-escape-ldap-special-characters-in-dn-syntax
    (ldap_escape 2.0 by Chris Wright)

    @param string $subject
	Text to be escaped for use as an LDAP value or DN
    @param bool $dn
	Whether to treat the string as a DN (assume no if not present)
    @param mixed $ignore
	Optional list of characters to leave untouched
	(set to null if no characters to be left untouched)
    @return
	Sanitised version of string with invalid characters escaped
*/

function ldap_escape($subject,$dn=false,$ignore=null)
{
	// The base array of characters to escape
	// Flip to keys for easy use of unset()
	$search = array_flip($dn ? array('\\',",","=","+","<",">",";","\"",
		"#") : array('\\',"*","(",")","\x00"));

	// Process characters to ignore
	if(is_array($ignore))
		$ignore = array_values($ignore);

	for($char=0;isset($ignore[$char]);$char++)
		unset($search[$ignore[$char]]);

	// Flip $search back to values and build $replace array
	$search = array_keys($search);
	$replace = array();
	foreach($search as $char)
		$replace[] = sprintf('\\%02x',ord($char));

	// Do the main replacement
	$result = str_replace($search,$replace,$subject);

	// Encode leading spaces in DN values
	if($dn && $result[0] == " ")
		$result = '\\20' . substr($result, 1);

	// Encode trailing spaces in DN values
	if($dn && $result[strlen($result)-1] == " ")
		$result = substr($result,0,-1) . '\\20';

	return $result;
}

/** Checks status of prerequisites to run the address book

    Returns whether or not all required prerequisite components
    (PHP modules) are installed and enabled. Displays details
    of any missing components.

    @return
	Whether all prequisites have been met (true/false)
*/

function prereq_components_ok()
{
	$php_extn_list = array(
		array("name"=>"gd","desc"=>"GD Support"),
		array("name"=>"intl","desc"=>"Internationalization Support"),
		array("name"=>"ldap","desc"=>"LDAP Support")
		);

	$missing_php_extn_list="";
	foreach($php_extn_list as $extn)
		if(!extension_loaded($extn["name"]))
			$missing_php_extn_list .= "<li>" . $extn["name"]
				. " (" . $extn["desc"] . ")";

	if(!empty($missing_php_extn_list))
	{
		show_ldap_path("","","folder.png");

		echo "<p>The following PHP extension modules must be "
			. "installed and enabled in order to use the "
			. "address book:</p>\n";

		echo "<ul>" . $missing_php_extn_list . "</ul>";

		echo "<p>Please see <em>Installation and Basic Setup</em> "
			. "in the User Guide for more information.</p>";
	}
	return empty($missing_php_extn_list);
}

/** Update an attribute of the specified LDAP entry from posted form data

    @param array $entry
	Entry to be updated
    @param string $attrib
	Name of attribute to be updated with new value from posted data
    @param bool $is_rdn
	Attribute to be updated is used for the object's RDN
    @return
	Textual description of the result (e.g. error if it failed)
*/

function update_ldap_attribute($entry,$attrib,$is_rdn=false)
{
	global $ldap_link,$ldap_server_type;

	$dn = $entry[0]["dn"];

	$attribute_class_schema = get_attribute_class_schema($ldap_server_type);

	$new_val_set = isset($_POST["ldap_attribute_" . $attrib]);

	// For image attributes, the above is set if the "clear image" box was ticked.
	// Further checks to see if an image was uploaded:
	if(get_attribute_data_type($attrib,$attribute_class_schema) == "image")
		$new_val_set = isset($_FILES["ldap_attribute_" . $attrib . "_file"]["tmp_name"])
			|| $new_val_set;

	if($new_val_set)
	{
		if(isset($entry[0][strtolower($attrib)][0]))
			$old_val = $entry[0][strtolower($attrib)][0];
		else
			$old_val = "";

		if(isset($_POST["ldap_attribute_" . $attrib]))
			$new_val = $_POST["ldap_attribute_" . $attrib];
		else
			$new_val = "";

		if(get_attribute_data_type($attrib,$attribute_class_schema) == "image")
		{
			if(isset($_POST["ldap_attribute_" . $attrib]) && $_POST["ldap_attribute_" . $attrib] != "")
				$new_val = "";		// clear image
			else
			{
				if(!empty($_FILES["ldap_attribute_" . $attrib . "_file"]["tmp_name"]))
				{
					// updated image uploaded
					$fd = fopen($_FILES["ldap_attribute_" . $attrib . "_file"]["tmp_name"],"r");
					$new_val =  fread($fd,MAX_IMAGE_UPLOAD);
					fclose($fd);
				}
				else
					$new_val = $old_val;	// re-use existing image
			}
		}

		if($new_val != $old_val)
		{
			/**
			    @todo Determine if multi-valued (currently always assume yes)
			*/
			if(1)
				// syntax for multi-valued attribute
				$changes[$attrib][0] = ($new_val == "" ? $old_val : $new_val);
			else
				// syntax for single-valued attribute
				$changes[$attrib] = ($new_val == "" ? $old_val : $new_val);

			if($new_val == "")
				$result = @ldap_mod_del($ldap_link,$dn,$changes);
			else
				if($is_rdn)
					$result = @ldap_rename($ldap_link,$dn,
						$attrib . "=" . $new_val,null,true);
				else
					$result = @ldap_mod_replace($ldap_link,$dn,$changes);

			if($result)
			{
				if(get_attribute_data_type($attrib,$attribute_class_schema) == "image")
					if(isset($_POST["ldap_attribute_" . $attrib])
							&& $_POST["ldap_attribute_" . $attrib] != "")
						return "Clear attribute '" . $attrib . "'";
					else
						return "Set attribute '" . $attrib
							. "' to contents of '" . $_FILES["ldap_attribute_"
							. $attrib . "_file"]["name"] . "'";
				else
					return "Set attribute '" . $attrib
						. "' to '" . htmlentities($new_val,ENT_COMPAT,"UTF-8") . "'";
			}
			else
				return "Error whilst setting attribute '"
					. $attrib . "': " . ldap_error($ldap_link) . "<br>";
		}
	}
}

/** Retrieve icon/photo thumbnail URL for the specified LDAP entry.

    @param array $entry
	Entry for which thumbnail URL is to be retrieved
    @return
	URL of image. This can be either retrieved from the record
	itself (if present, and image display is turned on) or an
	icon representing the record's object class (e.g. user
	or contact).
*/

function get_icon_for_ldap_entry($entry)
{
	global $ldap_server_type,$enable_ldap_path_thumbnail,
		$thumbnail_image_size;

	$dn = $entry["dn"];

	if(!empty($entry["jpegphoto"][0])
			&& $enable_ldap_path_thumbnail)
		return "image.php?dn=" . urlencode($dn)
			. "&attrib=jpegPhoto&size="
			. $thumbnail_image_size;
	else if(!empty($entry["thumbnailphoto"][0])
			&& $enable_ldap_path_thumbnail)
		return "image.php?dn=" . urlencode($dn)
			. "&attrib=thumbnailPhoto&size="
			. $thumbnail_image_size;
	else if(!empty($entry["thumbnaillogo"][0])
			&& $enable_ldap_path_thumbnail)
		return "image.php?dn=" . urlencode($dn)
			. "&attrib=thumbnailLogo&size="
			. $thumbnail_image_size;
	else
	{
		$object_class_schema = get_object_class_schema($ldap_server_type);

		return "schema/" . get_object_class_setting($object_class_schema,
			get_object_class($object_class_schema,$entry),
			"icon");
	}
}

/** Return the specified LDAP entry in vCard format

    @param array $entry
	LDAP entry which is to be converted to vCard
    @return
	String containing a vCard representation of the LDAP entry
*/

function vcard($entry)
{
	global $exclude_logo_if_photo_present;

	$vcard = "BEGIN:VCARD\nVERSION:2.1\n";

	// Family Name, Given Name, Additional Names, Honorific
	//   Prefixes, and Honorific Suffixes

	if(isset($entry["sn"][0])) $sn = $entry["sn"][0]; else $sn = "";
	if(isset($entry["givenname"][0])) $givenname = $entry["givenname"][0]; else $givenname = "";

	$vcard .= "N:"
		. $sn . ";"	// family name
		. $givenname . ";"	// given name
		. "" . ";"	// additional names
		. "" . ";"	// honorific prefixes
		. "" . "\n";	// honorific suffixes

	// formatted name
	$vcard .= "FN:" . $entry["cn"][0] . "\n";

	if(isset($entry["title"][0]))
		$vcard .= "TITLE:" . $entry["title"][0] . "\n";

	if(isset($entry["company"][0]))
	{
		$vcard .=  "ORG:" . $entry["company"][0];
		if(isset($entry["department"][0]))
			$vcard .=  ";" . $entry["department"][0];
		$vcard .= "\n";
	}

	if(isset($entry["streetaddress"][0])) $streetaddress = $entry["streetaddress"][0]; else $streetaddress = "";
	if(isset($entry["l"][0])) $l = $entry["l"][0]; else $l = "";
	if(isset($entry["st"][0])) $st = $entry["st"][0]; else $st = "";
	if(isset($entry["postalcode"][0])) $postalcode = $entry["postalcode"][0]; else $postalcode = "";

	$vcard .=  "ADR:;;" . $streetaddress
		. ";" . $l . ";" . $st . ";" . $postalcode . "\n";

	// Familiar/informal name of person
	if(isset($entry["displayname"][0]))
		$vcard .= "NICKNAME:" . $entry["displayname"][0] . "\n";
	if(isset($entry["homephone"][0]))
		$vcard .= "TEL;TYPE=HOME:" . str_replace(" ","",$entry["homephone"][0]) . "\n";
	if(isset($entry["telephonenumber"][0]))
		$vcard .= "TEL;TYPE=WORK:" . str_replace(" ","",$entry["telephonenumber"][0]) . "\n";
	if(isset($entry["mobile"][0]))
		$vcard .= "TEL;TYPE=CELL:" . str_replace(" ","",$entry["mobile"][0]) . "\n";
	if(isset($entry["facsimiletelephonenumber"][0]))
		$vcard .= "TEL;WORK;FAX:" . str_replace(" ","",$entry["facsimiletelephonenumber"][0]) . "\n";
	if(isset($entry["mail"][0]))
		$vcard .= "EMAIL;TYPE=INTERNET:" . $entry["mail"][0] . "\n";
	// Intepretted as "personal" URL in default layout
	if(isset($entry["wwwhomepage"][0]))
		$vcard .= "URL:" . $entry["wwwhomepage"][0] . "\n";
	// Intepretted as "business" URL in default layout
	if(isset($entry["url"][0]))
		$vcard .= "URL:" . $entry["url"][0] . "\n";

	if(isset($entry["jpegphoto"][0]))
		$vcard .= "PHOTO;ENCODING=BASE64;JPEG:"
			. chunk_split(base64_encode($entry["jpegphoto"][0]),76,"\n") . "\n";
	else if(isset($entry["thumbnailphoto"][0]))
		$vcard .= "PHOTO;ENCODING=BASE64;JPEG:"
			. chunk_split(base64_encode($entry["thumbnailphoto"][0]),76,"\n") . "\n";

	if(isset($entry["thumbnaillogo"][0]))
	{
		if(isset($exclude_logo_if_photo_present) && $exclude_logo_if_photo_present)
		{
			if(!isset($entry["jpegphoto"][0]) && !isset($entry["thumbnailphoto"][0]))
				$vcard .= "LOGO;ENCODING=BASE64;JPEG:"
					. chunk_split(base64_encode($entry["thumbnaillogo"][0]),76,"\n") . "\n";
		}
		else
			$vcard .= "LOGO;ENCODING=BASE64;JPEG:"
				. chunk_split(base64_encode($entry["thumbnaillogo"][0]),76,"\n") . "\n";
	}

	if(isset($entry["manager"][0]))
	{
		$manager = ldap_explode_dn2($entry["manager"][0]);

		$vcard .= "X-ANDROID-CUSTOM:vnd.android.cursor.item/relation;" . $manager["0"]["value"] . ";7;;;;;;;;;;;;;\n";
	}

	if(isset($entry["info"][0]))
	{
		$info="NOTE;ENCODING=QUOTED-PRINTABLE:";

		$count = strlen($info);
		foreach(str_split($entry["info"][0]) as $char)
		{
			$info .= "=" . (ord($char)<16 ? "0" : "") . dechex(ord($char));
			$count+=3;

			if($count>70)
			{
				$info .= "=\n";
				$count = 0;
			}
		}
		$vcard .= $info . "\n";
	}

	$vcard .= "END:VCARD\n";

	return $vcard;
}

/** Format and output a phone number of display

    Formatted either as plain body text or converted
    to a HTML link as per the substitution rule specified by
    $phone_number_link_template (defined in the config file) and with link
    target as per $phone_number_link_target (if defined)

    @param string $phone_number
	Phone number to be formatted
    @return
	Version of phone number formatted for display
*/

function show_phone_number_formatted($phone_number)
{
	global $phone_number_link_template,$phone_number_link_target;

	if(isset($phone_number_link_template) && !empty($phone_number_link_template))
	{
		$phone_number_link_url = str_replace("___phone_number___",
			urlencode($phone_number),$phone_number_link_template);

		if(isset($phone_number_link_target) && !empty($phone_number_link_target))
			$target = " target=\"" . $phone_number_link_target . "\"";
		else
			$target = "";

		echo "<a href=\"" . $phone_number_link_url . "\""
			. $target . ">"
			. htmlentities($phone_number,ENT_COMPAT,"UTF-8")
			. "</a>";
	}
	else
		echo htmlentities($phone_number,ENT_COMPAT,"UTF-8");
}

/** Return whether a DN is within the specified base of the DIT

    The comparision is done server-side in order to apply
    the correct matching rule for each attribute (e.g. whether
    it is case sensitive or not).

    @param resource $ldap_link
	LDAP connection handle to bind/authenticate against
    @param string $dn
	DN to test
    @param string $base_dn
	Base DN that $dn is going to be tested against
    @return
	Whether $dn falls within $base_dn in the DIT (true/false)
*/

function ldap_compare_dn_to_base($ldap_link,$dn,$base_dn)
{
	$base_rdn_list = ldap_explode_dn($base_dn,0);
	$base_rdn_count = $base_rdn_list["count"];
	$dn_base_section=implode(array_slice(ldap_explode_dn($dn,0),
		-$base_rdn_count),",");

	return ldap_compare($ldap_link,$base_dn,"DN",$dn_base_section);
}
?>
