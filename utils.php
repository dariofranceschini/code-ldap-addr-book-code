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

define("NEW_ROW",true);

// Output the site's HTML header elements

function show_site_header()
{
	global $site_name,$ldap_login_enabled;

	// Resume existing session (if any exists) in order to get
	// currently logged in user
	if(!isset($_SESSION)) session_start();

	echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 5.0//EN\">\n";
	echo "<html>\n";
	echo "<head>\n";
	echo "<title>" . $site_name . "</title>\n";
	echo "  <link rel=\"stylesheet\" href=\"styles.css\" type=\"text/css\">\n";
	echo "  <link rel=\"search\" type=\"application/opensearchdescription+xml\" title=\""
		. $site_name . "\" href=\"search-plugin.php\">\n";
	echo "  <link rel=\"icon\" type=\"image/png\" href=\"addressbook24.png\">\n";
	echo "</head>\n\n";
	echo "<body>\n\n";
}

// Output the HTML to display the search box
//
// $initial_value - text to display in search box when page first loaded; typically
// the previous text the user searched on.

function show_search_box($initial_value)
{
	echo "<form action=\"" . current_page_folder_url() . "\" method=\"get\">\n";

	echo "  <p>\n";
	echo "    Search for:\n";

	echo "    <input type=\"text\" name=\"filter\" size=50";
	if(!empty($initial_value))
		echo " value=\"" . $initial_value . "\"";

	echo ">\n    <input type=\"submit\" value=\"Search\">\n";
	echo "  </p>\n";
	echo "</form>\n\n";
}

// Show "breadcrumb navigation" version of specified LDAP path
// Each level in the DIT appears with a folder icon; final item
// is displayed with $leaf_icon next to it
//
// Also shows "login" button to right (if editing enabled)
//
// $base - The DN for whicht the breadcrumb navigation is to be
//           displayed
// $default_base - The address book's base DN. Elements of this DN
//           are not to be displayed as breadcrumb elements.

function show_ldap_path($base,$default_base,$leaf_icon)
{
	global $site_name,$ldap_login_enabled;

	echo "<table width=\"100%\">\n  <tr>\n"
		. "    <td><a href=\"" . current_page_folder_url() . "\">"
		. "<img border=0 align=\"top\" alt=\"Address Book\" src=\"addressbook24.png\">"
		. "</a></td>\n"
		. "    <td style=\"font-weight:bold;font-size:12pt;white-space:nowrap\">"
		. "<a href=\"" . current_page_folder_url() . "\">" . $site_name . "</a></td>\n";

	$folder_list = substr($base,0,-strlen($default_base)-1);
	if($folder_list != "")
	{
		$folder_list = ldap_explode_dn2($folder_list,true);

		for($i=count($folder_list);$i>0;$i--)
		{
			echo "\n    <td style=\"white-space:nowrap\">&nbsp;&nbsp;"
				. "&#x25B6;"	// Right-facing arrow head
				. "&nbsp;&nbsp;</td>\n\n    <td valign=\"middle\">";
			if($i==1)
				echo "<img align=\"top\" alt=\"Address Book Entry\" src=\"schema/" . $leaf_icon . "\">";
			else
				echo "<img align=\"top\" alt=\"Folder\" src=\"schema/folder.png\">";

			echo "</td>\n    <td style=\"white-space:nowrap\">" . $folder_list[$i-1]
				. "</td>\n";
		}
	}
	echo "    <td width=\"100%\" style=\"text-align:right\">";
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

// Returns the elemens of the specified DN as an array.
//
// (Partial) re-implementation standard PHP function ldap_explode_dn(),
// but correctly handling accented characters.
//
// Limitations compared to the "original":
// No support for multi-valued RDNs, no "count" attribute to
// indicate number of RDNs, no support for handling
// DNs which have commas within any of their values.
// (TODO: no commas could prove to be an unacceptable limitation!)
//
// $dn - DN which is to be converted into an array
// $with_attrib - Return associative array of attributes and values
//      if set to true

function ldap_explode_dn2($dn,$with_attrib)
{
	$dn = explode(",",$dn);

	if($with_attrib)
		for($i=0;$i<count($dn);$i++)
			$dn[$i] = substr($dn[$i],strpos($dn[$i],"=")+1);

	return $dn;
}

// Return URL of folder containing the currently running script

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

// Return an array associating LDAP object classes with attributes used by
// the addressbook (currently icon graphic, whether it should be presented
// as a folder or a "leaf" object and which attribute holds the RDN). Where
// objects can potentially match more than one class those classes should
// be listed with most "specific" first (e.g. Person/inetOrgPerson in edir
// schema).

function get_object_class_schema($ldap_server_type = "ad")
{
	switch($ldap_server_type)
	{
		case "edir";
			// Object class data - these items specific to Novell eDirectory
			return array(
				array("name"=>"organizationalUnit",	"icon"=>"folder.png",	"is_folder"=>true,"rdn_attrib"=>"ou"),
				array("name"=>"groupOfNames",		"icon"=>"group24.png",			  "is_folder"=>false),
				array("name"=>"ncpServer",		"icon"=>"novell-edirectory/server24.png", "is_folder"=>false),
				array("name"=>"ldapServer",		"icon"=>"novell-edirectory/directory-server.png","is_folder"=>false),
				array("name"=>"inetOrgPerson",		"icon"=>"user24.png",			  "is_folder"=>false),
				array("name"=>"Person",			"icon"=>"contact24.png",		  "is_folder"=>false),
				array("name"=>"externalEntity",		"icon"=>"novell-edirectory/external-entity24.png","is_folder"=>false),
				array("name"=>"nDSPKIKeyMaterial",	"icon"=>"novell-edirectory/key-material.png","is_folder"=>false),
				array("name"=>"Volume",			"icon"=>"novell-edirectory/volume.png",   "is_folder"=>false),
				array("name"=>"sASService",		"icon"=>"novell-edirectory/security.png", "is_folder"=>false),
				array("name"=>"ndsPredicateStats",	"icon"=>"novell-edirectory/stats.png",    "is_folder"=>false),
				array("name"=>"Queue",			"icon"=>"novell-edirectory/queue.png",    "is_folder"=>false),
				array("name"=>"nLSLicenseServer",	"icon"=>"novell-edirectory/lic_srv.gif",  "is_folder"=>false),
				array("name"=>"ldapGroup",		"icon"=>"novell-edirectory/ldapgroup24.png","is_folder"=>false),
				array("name"=>"nssfsPool",		"icon"=>"novell-edirectory/raid.png",	  "is_folder"=>false)
				);
			break;
		case "ad":
		default:
			// Object class data - these items specific to Active Directory
			return array(
				array("name"=>"organizationalUnit",	"icon"=>"folder.png",	"is_folder"=>true,"rdn_attrib"=>"ou"),
				array("name"=>"container",		"icon"=>"folder.png",	"is_folder"=>true),
				array("name"=>"builtinDomain",		"icon"=>"folder.png",	"is_folder"=>true),
				array("name"=>"lostAndFound",		"icon"=>"folder.png",	"is_folder"=>true),
				array("name"=>"msDS-QuotaContainer",	"icon"=>"folder.png",	"is_folder"=>true),
				array("name"=>"group",			"icon"=>"group24.png",	"is_folder"=>false),
				array("name"=>"contact",		"icon"=>"contact24.png","is_folder"=>false),
				array("name"=>"computer",		"icon"=>"microsoft-active-directory/computer24.png","is_folder"=>false),
				array("name"=>"foreignSecurityPrincipal","icon"=>"user-alias24.png",	"is_folder"=>false),
				array("name"=>"user",			"icon"=>"user24.png",	"is_folder"=>false),
				array("name"=>"inetOrgPerson",		"icon"=>"user24.png",	"is_folder"=>false)
				);
	}
}


// ISO 3166-1 alpha-2 country codes
//
// based on code snippet from:
// http://coding-talk.com/f46/iso-country-codes-to-country-names-country-form-select-options-16364/

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

// Return full country name given ISO 3166-1 alpha-2 country code
//
// $code - country code which is to be converted to country name

function get_country_name_from_code($code)
{
	global $country_name;
	if(isset($country_name[$code]))
		return $country_name[$code];
	else
		return "unknown";
}

// View LDAP entry as HTML

class ldap_entry_viewer
{
	var $section = array();
	var $ldap_entry;
	var $last_section_added = "";
	var $user_info = "";

	// Constructor.
	// $ldap_entry - Arary containing LDAP object entry which is to be displayed

	function ldap_entry_viewer($ldap_entry)
	{
		$this->ldap_entry = $ldap_entry;
		$this->user_info = get_user_info();
	}

	// Add a section to the display
	//
	// $text - title text/section name
	// $newrow - should the section start on a new row?
	// $colspan - number of table columns to span (default to 1 if missing)
	// $width - HTML/CSS column width (default to evenly spaced if missing)

	function add_section($text,$newrow=false,$colspan="",$width="")
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

	// Add an attribute and its value to the display
	//
	// $attribute - LDAP attribute
	// $caption - "friendly" caption to be used for LDAP attribute
	// $icon - icon image to display next to attribute

	function add_to_section($attribute,$caption="",$icon="")
	{
		$this->section[$this->last_section_added]->add_data(
			$attribute,$caption,$icon);
	}

	// Output the object entry as HTML, utilising chosen attributes
	// and layout

	function show()
	{
		global $ldap_base_dn;

		// TODO: replace with "user" icon/correct icon for class
		// and/or photo image if available?
		show_ldap_path(get_ldap_attribute($this->ldap_entry,
			"distinguishedName"),$ldap_base_dn,"contact24.png");

		if($this->user_info["allow_search"])
			show_search_box("");
		else
			echo "<br>";

		if($this->user_info["allow_view"])
		{
			echo "<table width=\"100%\" cellpadding=0>\n";

			foreach($this->section as $section)
				$section->show();

			echo "</table>";
		}
		else
			echo "<p>You do not have permission to view this record</p>\n";
	}
}

// Section of information (displayed list of attributes) within ldap_entry_viewer

class ldap_entry_viewer_section
{
	var $text;
	var $colspan="";
	var $newrow=0;
	var $attrib=array();
	var $width="";
	var $ldap_entry;

	// Add an attribute and its value to the display
	//
	// $attribute - LDAP attribute
	// $caption - "friendly" caption to be used for LDAP attribute
	// $icon - icon image to display next to attribute

	function add_data($attribute,$caption="",$icon="")
	{
		$this->attrib[] = new ldap_entry_viewer_attrib($attribute,
			$caption,$icon);
	}

	// Output this section of the object entry as HTML, utilising chosen attributes

	function show()
	{
		echo "\n<!-- Section: " . $this->text . " -->\n\n";

		if($this->newrow == true) echo "  <tr>\n";

		$cell_attrib = "";
		if($this->colspan != "")
			$cell_attrib.=" colspan=" . $this->colspan;

		if($this->width != "")
			$cell_attrib.=" width=\"" . $this->width . "\"";

		echo "    <td valign=\"top\" " . $cell_attrib
			. ">\n      <table collspan=3 cellpadding=0 width=\"100%\">"
			. "\n        <tr>\n          <th colspan=3 bgcolor=\"#e0e0e0\" style=\"font-size:12pt;font-weight:bold\">"
			. $this->text . "</th>\n        </tr>\n";

		foreach($this->attrib as $attrib)
			$attrib->show($this->ldap_entry);

		echo "      </table></td>\n";
	}
}

// Individual LDAP object attribute displayed in ldap_entry_viewer_section

class ldap_entry_viewer_attrib
{
	var $caption;
	var $ldap_attribute;
	var $icon;

	// Add an attribute and its value to the display
	//
	// $attribute - LDAP attribute
	// $caption - "friendly" caption to be used for LDAP attribute
	// $icon - icon image to display next to attribute

	function ldap_entry_viewer_attrib($attribute,$caption="",$icon="")
	{
		$this->caption = $caption;
		$this->ldap_attribute = $attribute;
		$this->icon = $icon;
	}

	// Output this object attribute as HTML

	function show($ldap_entry)
	{
		echo "        <tr>\n";

		// Use full width if attribute has no icon or caption text
		if($this->icon == "" && $this->caption == "")
			echo "          <td colspan=3 bgcolor=\"#f0f0f0\">";
		else
		{
			echo "          <td width=\"1px\">";
			if($this->icon != "")
				echo "<img alt=\"" . $this->ldap_attribute
					. "\" src=\"schema/"
					. $this->icon . "\">";
			echo "</td>\n          "
				. "<td width=\"1px\" style=\"white-space:nowrap\">"
				. $this->caption . "&nbsp;</td>\n";
			echo "          <td bgcolor=\"#f0f0f0\">"
				. "\n            ";
		}

		$first_line = true;
		// look up values of attributes listed
		//   (: = line break, + = space between words)
		foreach(explode(":",$this->ldap_attribute) as $attribute_line)
		{
			if($first_line == false) echo "<br>\n";
			$first_line = false;

			foreach(explode("+",$attribute_line) as $attribute)
			{
				$attrib_value = get_ldap_attribute(
					$ldap_entry,$attribute);

				// Handle specific LDAP attributes specially
				if($attrib_value != "")
					switch($attribute)
					{
						case "postalCode":
							echo $attrib_value . "&nbsp;(<a href=\"https://maps.google.co.uk/?q="
								. urlencode($attrib_value) . "\" target=\"_blank\">View map</a>)";
							break;
						case "c":
							echo get_country_name_from_code($attrib_value) . "&nbsp;";
							break;
						default:
							echo $attrib_value . "&nbsp;";
					}
			}

		}
		echo "\n          </td>\n        </tr>\n";
	}
}

// Return specified attribute from an LDAP object entry specified
// as an array, processing it for display as HTML (e.g. turn URLs
// and e-mail addresses, special characters into entities, etc)
//
// $ldap_entry - LDAP entry as an array
// $attribute - Attribute to be returned

function get_ldap_attribute($ldap_entry,$attribute)
{
	$attribute = strtolower($attribute);

	if(!empty($ldap_entry[0][$attribute][0]))
		// TODO: iterate over multi-valued attributes
		// (currently only returns the first value)
		$attrib_value = $ldap_entry[0][$attribute][0];
	else
		$attrib_value = "";

	// Convert UTF-8 characters into HTML entities
	$attrib_value = mb_convert_encoding($attrib_value,
		"HTML-ENTITIES","UTF-8");

	// convert URLs to links
	$attrib_value = ereg_replace(
		"[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]",
		"<a href=\"\\0\" rel=\"nofollow\">\\0</a>",$attrib_value);

	// convert e-mail addresses to links
	$attrib_value = preg_replace("/\b(\S+@\S+)\b/",
		'<a href="mailto:\1">\1</a>',$attrib_value);

	// convert line breaks to <br> tags
	$attrib_value = str_replace("\n","<br>\n",$attrib_value);

	return $attrib_value;
}

// Report an LDAP bind error (login failure), using wording appropriate
// to the specific situation

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

// Attempt LDAP bind (login) with user (or config file) specified
// credentials
//
// $ldap_link - LDAP connection handle to bind/authenticate against

function log_on_to_directory($ldap_link)
{
	if(isset($_SESSION["LOGIN_USER"]))
	{
		$user = get_user_attrib($_SESSION["LOGIN_USER"],"ldap_name");
		$pw = $_SESSION["LOGIN_PASSWORD"];
	}
	else
	{
		$user = get_user_attrib("__ANONYMOUS__","ldap_name");
		$pw = get_user_attrib("__ANONYMOUS__","ldap_password");
	}

	$old_error_reporting=error_reporting();
	error_reporting(0);
	$result=false;
	if($user != "__DENY__")
	{
		ldap_set_option($ldap_link,LDAP_OPT_PROTOCOL_VERSION,3);
		$result=ldap_bind($ldap_link,$user,$pw);

		// Timezone is not known to be used for anything in this
		// application, however various LDAP functions produce
		// warning messages in newer PHP versions (>=5.1.0) if it
		// is not set.
		if(!ini_get("date.timezone"))
			date_default_timezone_set("UTC");
	}
	error_reporting($old_error_reporting);

	return $result;
}

// Return value of named attribute for specified address book user name
//
// $user_name - Name of user whose information is required
// $attrib - Attribute to be returned

function get_user_attrib($user_name,$attrib)
{
	$user_info = get_user_info($user_name);
	return $user_info[$attrib];
}

// Return array of attributes for specified address book user name
//
// $user_name - Name of user whose information is required (default to
// currently logged in user if omitted)

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
?>
