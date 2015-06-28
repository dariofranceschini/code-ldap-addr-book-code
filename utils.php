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
	global $site_name,$enable_search_suggestions;

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
		echo "  <link rel=\"stylesheet\" href=\"lib/jquery-ui-themes-1.11.4/smoothness.css\" type=\"text/css\">\n";
		echo "  <script src=\"lib/jquery-1.11.2/jquery-1.11.2.js\"></script>\n";
		echo "  <script src=\"lib/jquery-ui-1.11.4/jquery-ui-1.11.4.js\"></script>\n";
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
	global $site_name,$ldap_server,$ldap_base_dn;

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
	if($ldap_server->per_user_login_enabled())
	{
		// Resume existing session (if any exists) in order to get
		// currently logged in user
		if(!isset($_SESSION)) session_start();

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

	/** Display a viewer for editing a record (true/false) */
	var $edit = false;

	/** Display a viewer for creating a new record (true/false) */
	var $create = false;

	/** Constructor

	    @param object $ldap_server
		LDAP server from which a record is to be displayed
	    @param array $ldap_entry
		Array containing LDAP object entry which is to
		be displayed
	*/

	function __construct($ldap_server,$ldap_entry)
	{
		$this->ldap_entry = $ldap_entry;

		$entry_viewer_layout = $ldap_server->get_display_layout(
			$ldap_server->get_object_class($ldap_entry[0]));

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
		global $ldap_base_dn,$ldap_server,$thumbnail_image_size,
			$enable_ldap_path_thumbnail;

		$dn = $this->ldap_entry[0]["dn"];

		if($this->create)
			show_ldap_path("CN=New "
				. $ldap_server->get_object_schema_setting(
				$ldap_server->get_object_class($this->ldap_entry[0]),
				"display_name") .  "," . $dn,$ldap_base_dn,
				$ldap_server->get_icon_for_ldap_entry($this->ldap_entry[0]));
		else
			show_ldap_path($dn,$ldap_base_dn,
				$ldap_server->get_icon_for_ldap_entry($this->ldap_entry[0]));

		if(get_user_setting("allow_search"))
			show_search_box("");
		else
			echo "<br>";

		if(get_user_setting("allow_view"))
		{
			if($this->edit && get_user_setting("allow_edit"))
				echo "<form method=\"post\" action=\"update.php?dn="
					. urlencode($dn) . "\" style=\"display:inline\" enctype=\"multipart/form-data\">";

			echo "<table class=\"ldap_entry_viewer\">\n";

			foreach($this->section as $section)
				$section->show($this->edit);

			echo "</table>\n\n";

			if(get_user_setting("allow_edit"))
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

			if(get_user_setting("allow_delete") && !$this->edit)
				echo "<a href=\"delete.php?page=info&dn="
					. urlencode($dn)
					. "\"><button>Delete</button></a>\n";

			if(get_user_setting("allow_export") && !$this->edit)
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

	function __construct($ldap_entry,$attribute,$caption="",$icon="",$hide_unless_editing=false)
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
		global $ldap_server;

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

					$display_name = $ldap_server->get_attribute_schema_setting(
						$attribute,"display_name",$attribute);

					if($display_name!=$attribute)
						$display_name .= " (" . $attribute . ")";

					// determine whether this is a required attribute

					$required = $ldap_server->check_object_requires_attribute(
						$ldap_server->get_object_class($this->ldap_entry[0]),$attribute);

					// display the attribute
					switch($ldap_server->get_attribute_schema_setting($attribute,"data_type","text"))
					{
						case "dn_list":
							$this->show_dn_list($attribute,$display_name,$required); break;
						case "date":
							$this->show_date($attribute,$display_name,$required); break;
						case "date_time":
							$this->show_date_time($attribute,$display_name,$required); break;
						case "gender":
							$this->show_gender($attribute,$display_name,$required); break;
						case "ad_group_type":
							$this->show_ad_group_type($attribute,$display_name,$required); break;
						case "postcode":
							$this->show_postcode($attribute,$display_name,$required); break;
						case "country_code":
							$this->show_country_code($attribute,$display_name,$required); break;
						case "image":
							$this->show_image($attribute,$display_name,$required); break;
						case "yes_no":
							$this->show_boolean_yes_no($attribute,$display_name,$required); break;
						case "use_html_mail":
							$this->show_use_html_mail($attribute,$display_name,$required); break;
						case "text":
							$this->show_text($attribute,$display_name,$required); break;
						case "text_list":
							$this->show_text_list($attribute,$display_name,$required); break;
						case "text_area":
							$this->show_text_area($attribute,$display_name,$required); break;
						case "phone_number":
							$this->show_phone_number($attribute,$display_name,$required); break;
						default:
							echo "** Unsupported data type: <code>"
								. $ldap_server->get_attribute_schema_setting($attribute,"data_type","text")
								. "</code> **";
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

	/** Show Active Directory groupType attribute (data type "ad_group_type")

	    This attribute contains a bit pattern which describes scope/type
	    characteristics of an Active Directory "group" object.

	    The attribute is defined in the "microsoft" schema.

	    @see
		https://msdn.microsoft.com/en-us/library/ms675935%28v=vs.85%29.aspx

	    @todo
		Editing support for this data type

	    @param string $attribute
		Attribute to display
	    @param string $display_name
		"Friendly" display name of attribute (typically
		rendered as "tooltip")
	    @param bool $required
		Whether attribute is mandatory (either marked as such or the RDN)
	*/

	function show_ad_group_type($attribute,$display_name,$required)
	{
		$attrib_value = get_ldap_attribute(
			$this->ldap_entry,$attribute);

		echo "<ul style=\"margin:0px;list-style-type:none;padding:0px\">";
		if($attrib_value & 0x80000000) echo "<li>Security group"; else echo "<li>Distribution group";
		if($attrib_value & 0x01) echo " (System generated)";
		echo "</li>";

		if($attrib_value & 0x10) echo "<li>Windows Authorization Manager APP_BASIC group</li>";
		if($attrib_value & 0x20) echo "<li>Windows Authorization Manager APP_QUERY group</li>";
		if($attrib_value & 0x02) echo "<li>Global scope</li>";
		if($attrib_value & 0x04) echo "<li>Domain local scope</li>";
		if($attrib_value & 0x08) echo "<li>Universal scope</li>";
		echo "</ul>";
	}

	/** Show mozillaUseHtmlMail attribute (data type "use_html_mail")

	    This attribute indicates whether or not the user prefers to receive
	    HTML-formatted e-mails.

		- TRUE - User prefers HTML
		- FALSE - User prefers plain text (does not prefer HTML)

	    The attribute is defined in the "mozilla" schema.

	    @param string $attribute
		Attribute to display
	    @param string $display_name
		"Friendly" display name of attribute (typically
		rendered as "tooltip")
	    @param bool $required
		Whether attribute is mandatory (either marked as such or the RDN)
	*/

	function show_use_html_mail($attribute,$display_name,$required)
	{
		$this->show_enum($attribute,$display_name,$required,
			array(
				array("value"=>"FALSE","display_name"=>"Plain Text"),
				array("value"=>"TRUE","display_name"=>"HTML")
				)
			);
	}

	/** Show boolean attribute, displayed as yes/no (data type "yes_no")

	    @param string $attribute
		Attribute to display
	    @param string $display_name
		"Friendly" display name of attribute (typically
		rendered as "tooltip")
	    @param bool $required
		Whether attribute is mandatory (either marked as such or the RDN)
	*/

	function show_boolean_yes_no($attribute,$display_name,$required)
	{
		$this->show_enum($attribute,$display_name,$required,
			array(
				array("value"=>"TRUE","display_name"=>"Yes"),
				array("value"=>"FALSE","display_name"=>"No")
				)
			);
	}

	/** Show ISO 5218 gender code attribute (data type "gender")

		- 0 Not known
		- 1 Male
		- 2 Female
		- 9 Not specified

	    @param string $attribute
		Attribute to display
	    @param string $display_name
		"Friendly" display name of attribute (typically
		rendered as "tooltip")
	    @param bool $required
		Whether attribute is mandatory (either marked as such or the RDN)
	*/

	function show_gender($attribute,$display_name,$required)
	{
		$this->show_enum($attribute,$display_name,$required,
			array(
				array("value"=>"0","display_name"=>"Not known"),
				array("value"=>"1","display_name"=>"Male"),
				array("value"=>"2","display_name"=>"Female"),
				array("value"=>"9","display_name"=>"Not specified")
				)
			);
	}

	/** Show a general enumerated data type attribute

	    Called from other "show_" functions which implement specific
	    enumerations (boolean, gender, country code, etc)

	    @todo
		Style this better.. should be 100% less a fixed number of pixels?

	    @param string $attribute
		Attribute to display
	    @param string $display_name
		"Friendly" display name of attribute (typically
		rendered as "tooltip")
	    @param array $enum
		Array describing the enumerated data type
	    @param bool $required
		Whether attribute is mandatory (either marked as such or the RDN)
	*/

	function show_enum($attribute,$display_name,$required,$enum)
	{
		$attrib_value = get_ldap_attribute(
			$this->ldap_entry,$attribute);

		if($this->edit)
		{
			if($required)
				$style = "width:98%;border-color:red;border-style:solid";
			else
				$style = "width:98%;";

                        echo "<select name=\"ldap_attribute_" . $attribute
                                . "\" title=\"" . $display_name . "\" style=\"" . $style . "\">\n";

			foreach($enum as $enum_entry)
			{
				if($enum_entry["display_name"] == "")
					$enum_entry["display_name"] = "(blank)";

				echo "              <option value=\"" . $enum_entry["value"] . "\""
					. ($attrib_value == $enum_entry["value"] ? " selected" : "")
					. ">" . $enum_entry["display_name"] . "</option>\n";
			}

			echo "</select>";
		}
		else
		{
			$found = false;
			foreach($enum as $enum_entry)
				if($attrib_value == $enum_entry["value"])
				{
					$found = true;
					echo $enum_entry["display_name"];
				}

			if(!$found)
				echo "Unrecognised value: " . $attrib_value;
		}
	}

	/** Show ISO 8601 short date attribute (data type "date")

	    Dates are encoded as: YYYYMMDD
		- YYYY - year
		- MM - month, with leading zero
		- DD - Day, with leading zero

	    Non-numeric characters are stripped from the date, e.g. so that
	    "YYYY-MM-DD" will be parsed as "YYYYMMDD".
	    Any subsequent characters after the year, month and day
	    (e.g. representing a time and time zone are ignored.

	    This function may be used to display just the date portion of an
	    ASN.1 "generalised time" value.

	    @todo
		More user friendly date editing
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

	function show_date($attribute,$display_name,$required)
	{
		$attrib_value = get_ldap_attribute(
			$this->ldap_entry,$attribute);

		// Remove all non-numerics
		$attrib_value = preg_replace("/\D/","",$attrib_value);

		if($attrib_value == "")
			$formatted_date = "";
		else
		{
			/** @todo: check string length - support legacy 2-digit years? */
			$date = mktime(0,0,0,
				substr($attrib_value,4,2),	// month
				substr($attrib_value,6,2),	// day
				substr($attrib_value,0,4)	// year
				);

			$formatted_date = date("l jS F Y",$date);
		}

		if($this->edit)
		{
			if($required)
				$style = "width:98%;border-color:red;border-style:solid";
			else
				$style = "width:98%;";

			echo "<input style=\"" . $style . "\" type=\"text\" id=\"ldap_attribute_"
				. $attribute . "\" name=\"ldap_attribute_"
				. $attribute . "\" value=\""
				. htmlentities($attrib_value,ENT_COMPAT,"UTF-8")
				. "\" title=\"" . $display_name . "\" placeholder=\""
				. $display_name . "\">";
		}
		else
			echo htmlentities($formatted_date,ENT_COMPAT,"UTF-8");
	}

	/** Show ISO 8601 date/time attribute (data type "date_time")

	    @todo
		Escape "nasty values" in $attrib_value, e.g. "
	    @todo
		Style this better.. should be 100% less a fixed number of pixels?
	    @todo
		Support displaying fractional seconds (where present)
	    @todo
		Support displaying the time zone

	    @param string $attribute
		Attribute to display
	    @param string $display_name
		"Friendly" display name of attribute (typically
		rendered as "tooltip")
	    @param bool $required
		Whether attribute is mandatory (either marked as such or the RDN)
	*/

	function show_date_time($attribute,$display_name,$required)
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
		{
	                if($attrib_value == "")
				$formatted_date = "";
			else
			{
				$date = mktime(
					substr($attrib_value,8,2),	// hour
					substr($attrib_value,10,2),	// minute
					substr($attrib_value,12,2),	// second
					substr($attrib_value,4,2),	// month
					substr($attrib_value,6,2),	// day
					substr($attrib_value,0,4)	// year
					);

				$formatted_date = date("l jS F Y H:i:s",$date);
			}

			echo $formatted_date;
		}
	}

	/** Show multi-value textual attribute (data type "text_list")

	    @todo
		Escape "nasty values" in $attrib_value, e.g. "
	    @todo
		Style this better.. should be 100% less a fixed number of pixels?
	    @todo
		Support editing

	    @param string $attribute
		Attribute to display
	    @param string $display_name
		"Friendly" display name of attribute (typically
		rendered as "tooltip")
	    @param bool $required
		Whether attribute is mandatory (either marked as such or the RDN)
	*/

	function show_text_list($attribute,$display_name,$required)
	{
		if(!empty($this->ldap_entry[0][strtolower($attribute)]))
		{
			echo "<ul style=\"margin:0px;list-style-type:none;padding:0px\">";

			foreach($this->ldap_entry[0][strtolower($attribute)] as $key=>$value)
				if(empty($key) || $key != "count")
					echo "<li>" . urls_to_links(htmlentities($value,ENT_COMPAT,"UTF-8")) . "</li>";
			echo "</ul>";
		}
	}

	/** Show list of LDAP DN attribute (data type "dn_list")

	    @todo
		Escape "nasty values" in $attrib_value, e.g. "
	    @todo
		Style this better.. should be 100% less a fixed number of pixels?
	    @todo
		Support editing

	    @param string $attribute
		Attribute to display
	    @param string $display_name
		"Friendly" display name of attribute (typically
		rendered as "tooltip")
	    @param bool $required
		Whether attribute is mandatory (either marked as such or the RDN)
	*/

	function show_dn_list($attribute,$display_name,$required)
	{
		global $ldap_server,$ldap_base_dn;

		if(!empty($this->ldap_entry[0][strtolower($attribute)]))
		{
			foreach($this->ldap_entry[0][strtolower($attribute)] as $key=>$value)
				if(empty($key) || $key != "count")
				{
					// retrieve object class icon
					$search_resource = @ldap_read($ldap_server->connection,$value,"(objectclass=*)");

					if($search_resource)
					{
						$entry = ldap_get_entries($ldap_server->connection,$search_resource);
						$icon = $ldap_server->get_icon_for_ldap_entry($entry[0]);
					}
					else
						$icon = "schema/generic24.png";

					$rdn_list = ldap_explode_dn2($value);

					if($ldap_server->compare_dn_to_base($value,$ldap_base_dn))
						echo "<img src=\"" . $icon . "\"> <a href=\"info.php?dn=" . $value . "\">" . $rdn_list["0"]["value"] . "</a><br>";
					else
						echo "<img src=\"" . $icon . "\">" . $rdn_list["0"]["value"] . "<br>";
				}
		}
			else echo "(none)";
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

		$countries = array(
			array("value"=>"","display_name"=>"")
			);

		foreach($country_name as $code => $name)
			$countries[] = array("value"=>$code,"display_name"=>$name);

		$this->show_enum($attribute,$display_name,$required,$countries);
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
	global $ldap_server;

	if($ldap_server->per_user_login_enabled())
	{
		if(isset($_SESSION["LOGIN_USER"]))
			echo "<p>You do not have permission to log in to"
				. " the address book directory.</p>\n"
				. "<p><a href=\"user.php\">Log in as a"
				. " different user</a></p>";
		else
			echo "<p><a href=\"user.php\">Please log in to"
				. " use the address book.</a></p>";
	}
	else
	{
		echo "<p>Unable to connect to address book directory."
			. " (LDAP bind failed)</p>\n";

		// don't show this line if the user has already configured
		// a non-blank default user
		if(get_user_setting("ldap_name","__ANONYMOUS__")=="")
			echo "<p>If you have not already done so, please
				<a href=\"doc/\">read the manual</a> for how
				instructions on to configure directory
				access.</p>";
	}
}

/** Get LDAP bind password of current user

    The password for user "__ANONYMOUS__" is stored in its "ldap_password"
    setting. For all other users the password typed into the login
    dialogue is retrieved from the "LOGIN_PASSWORD" session variable.

    @return
	LDAP bind password of current user
*/

function get_ldap_bind_password()
{
	if(get_user_setting("login_name") == "__ANONYMOUS__")
		return get_user_setting("ldap_password","__ANONYMOUS__");
	else
	{
		// Resume existing session (if any exists) in order to get
		// currently logged in user
		if(!isset($_SESSION)) session_start();

		return $_SESSION["LOGIN_PASSWORD"];
	}
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
	$user = get_user_setting("ldap_name");

	if($user == "__DENY__")
		return 1;
	else
		return @ldap_bind_log($ldap_link,$user,
			get_ldap_bind_password()) ? 1 : 0;
}

/** Return value of a user setting

    @param string $attrib
	Attribute to be returned
    @param string $user_name
	Name of user whose setting is to be returned. If this is omitted
	then the current logged in user will be used.
    @return
	Value of requested user setting
*/

function get_user_setting($attrib,$user_name = "")
{
	global $ldap_server;

	// use current user name if no user name passed as a parameter

	if(empty($user_name))
	{
		// Resume existing session (if any exists) in order to get
		// currently logged in user
		if(!isset($_SESSION)) session_start();

		if(isset($_SESSION["LOGIN_USER"]))
			$user_name = $_SESSION["LOGIN_USER"];
		else
			$user_name = "__ANONYMOUS__";
	}

	// retrieve the user's info from user_map array

	$user_info = array();	// default if no match at all
	$found=false;
	foreach($ldap_server->user_map as $map_user)
		if(!$found && ($map_user["login_name"] == $user_name
			|| ($map_user["login_name"] == "__DEFAULT__"
			&& $user_name != "__ANONYMOUS__")))
		{
			$user_info = $map_user;
			if($map_user["login_name"] != "__DEFAULT__")
				$found = true;
		}

	if(isset($user_info["ldap_name"]))
		$user_info["ldap_name"]=str_replace("__USERNAME__",
			$user_name,$user_info["ldap_name"]);

	// return the value of the requested attribute

	if(isset($user_info[$attrib]))
		return $user_info[$attrib];
	else
	{
		// default values to return if setting is undefined
		// for the specified user
		switch($attrib)
		{
			case "ldap_user":
				return "__DENY__"; break;
			case "allow_browse":
			case "allow_search":
			case "allow_view":
			case "allow_create":
			case "allow_edit":
			case "allow_delete":
			case "allow_export":
			case "allow_export_bulk":
				return false; break;
			default:
				return null;
		}
	}
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

	/** LDAP server containing the entries to be displayed */
	var $ldap_server;

	/** Constructor

	    @param object $ldap_server
		LDAP server containing the entries to be displayed
	    @param resource $ldap_entries
		LDAP search resource containing the LDAP object entries which
		are to be displayed
	    @param array $search_result_columns
		Search result column layout to use for display
	    @param string $sort_order
		LDAP attribute that the list should be sorted by
	*/

	function __construct($ldap_server,$ldap_entries,$search_result_columns,$sort_order)
	{
		$this->ldap_server = $ldap_server;
		$this->ldap_entries = $ldap_entries;
		$this->search_result_columns = $search_result_columns;
		$this->sort_order = $sort_order;
	}

	/** Output address book contents as vCard */

	function save_vcard()
	{
		// Fetch and sort records

		$ldap_data = ldap_sort_entries(
			ldap_get_entries($this->ldap_server->connection,$this->ldap_entries),
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
		echo "<table class=\"search_results_viewer\">\n  <tr>\n";

		$this->show_column_headings();

		// Fetch and sort records

		$ldap_data = ldap_sort_entries(
			ldap_get_entries($this->ldap_server->connection,$this->ldap_entries),
			$this->sort_order == "sortableName"
			? array("sn","givenName","ou","cn")
			: array($this->sort_order),
			LDAP_SORT_ASCENDING);

		// Display records

		for($i=0;$i < $ldap_data["count"]; $i++)
			$this->show_ldap_entry($ldap_data[$i]);

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

	    @param arary $ldap_entry
		LDAP entry to display
	*/

	function show_ldap_entry($ldap_entry)
	{
		global $enable_search_browse_thumbnail,$thumbnail_image_size,$ldap_server;
		echo "  <tr>\n";

		// Fetch object schema details for this record

		$item_object_class = $ldap_server->get_object_class($ldap_entry);

		$dn = $ldap_entry["dn"];

		$icon = $ldap_server->get_icon_for_ldap_entry($ldap_entry);

		$item_is_folder = $ldap_server->get_object_schema_setting(
			$item_object_class,"is_folder");
		$object_rdn_attrib = $ldap_server->get_object_schema_setting(
			$item_object_class,"rdn_attrib");

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
						&& !get_user_setting("allow_view"))
					$column["link_type"] = "none";

				$this->show_attrib($object_dn,
					$column["attrib"],
					$attrib_value,$column["link_type"]);
			}
		}

		if(get_user_setting("allow_delete"))
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
		global $thumbnail_image_size,$ldap_server;

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
		switch($ldap_server->get_attribute_schema_setting($attrib_name,"data_type","text"))
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

/** Return the specified LDAP entry in vCard format

    @param array $entry
	LDAP entry which is to be converted to vCard
    @return
	String containing a vCard representation of the LDAP entry
*/

function vcard($entry)
{
	global $exclude_logo_if_photo_present,$ldap_server;

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
	$rdn_attrib = $ldap_server->get_object_schema_setting(
		$ldap_server->get_object_class($entry)
		,"rdn_attrib");

	$vcard .= "FN:" . $entry[strtolower($rdn_attrib)][0] . "\n";

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

	if(isset($entry["mozillaUseHtmlMail"]))
		$vcard .= "x-mozilla-html:" . $entry["mozillaUseHtmlMail"] . "\n";

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

/** LDAP server

    Implements the following features relating to an LDAP server:
	- Connect and log on (bind) to the server
	- Return information about the directory schema
	- Update attributes of directory entries
	- Check whether a DN is within the directory's address book area
*/

class ldap_server
{
	/** Return LDAP server/schema type

	    Supported server/schema types:

		- ad - Microsoft Active Directory
		- edir - Novell eDirectory
		- openldap - OpenLDAP
	*/
	var $server_type;

	/** Return information about the LDAP server's object class schema

	    Returns an array holding information about the LDAP object classes used by
	    the addressbook. Where objects can potentially match more than one class,
	    those classes should be listed with most "specific" first (e.g.
	    Person/inetOrgPerson in edir schema).

		- name - Name of object class to which settings relate
		- icon - Icon image representing the object class
		- is_folder - Present as a folder rather than a "leaf" object
		- display_name - Display name of the object
		- required_attribs - Lists any required attributes (in addition to
			the RDN attribute, which is implicitly required)
		- can_create - Allow users to create records of this object class

	*/
	var $object_schema = array();

	/** Return information about the LDAP server's attribute class schema

	    Returns an array associating LDAP attribute classes with schema
	    setting used by the addressbook (currently data type for use when
	    editing/displaying and "friendly" display name).

		- name - Name of attribute class to which settings relate
		- data_type - Data type of the attribute
		- display_name - Display name of the attribute
	*/
	var $attribute_schema = array();

	/** Return the default LDAP object class to use when creating new records

	    The default class for new objects depends on the LDAP server type.
	*/
	var $default_create_class = "person";

	/** Return connection resource for communicating with the LDAP server */
	var $connection;

	/** Whether to follow LDAP referrals */
	var $follow_referrals = false;

	/** Permissions and user name mappings between address book and LDAP server */
	var $user_map = array();

	/** Display layouts */
	var $display_layouts = array();

	/** Supported server types */
	var $server_types = array(
		array("name"=>"ad",		"default_create_class"=>"contact",	"schema_list"=>"microsoft"),
		array("name"=>"edir",		"default_create_class"=>"inetOrgPerson","schema_list"=>"novell"),
		array("name"=>"openldap",	"default_create_class"=>"inetOrgPerson","schema_list"=>"core,cosine,inetorgperson"),
		array("name"=>"custom",		"default_create_class"=>"person",	"schema_list"=>"")
		);

	/** Constructor

	    Member variables $object_schema, $attribute_schema and
	    $default_create_class are populated appropriately to the LDAP
	    server type. A connection to the server is opened; connection
	    resource available in $connection.

	    @param string $ldap_server_type
		Indicates LDAP server type/schema type to create
		("ad", "edir" or "openldap")
	    @param string $ldap_server_host_or_url
		Host name, IP address or URL of the LDAP server to connect
		to. OpenLDAP 2.0 or later is required to use URL syntax
		(e.g. "ldap://server/")
	    @param int  $ldap_server_port
		Port number on LDAP server to connect to
	*/

        function __construct($ldap_server_type,$ldap_server_host_or_url,$ldap_server_port = null)
        {
		if(is_null($ldap_server_port))
			$this->connection = ldap_connect($ldap_server_host_or_url);
		else
			$this->connection = ldap_connect($ldap_server_host_or_url,
				$ldap_server_port);

                $this->server_type = $ldap_server_type;

		$schema_list  = "";
		foreach($this->server_types as $server_type)
		{
			if($server_type["name"] == $this->server_type)
			{
				$this->default_create_class = $server_type["default_create_class"];
				$schema_list = $server_type["schema_list"];
			}
		}
		$schema_list = explode(",",$schema_list);

		foreach($schema_list as $schema)
			$this->add_schema($schema);
        }

	/** Return a schema setting for the specified LDAP attribute

	    @param string $class
		Attribute class for which the setting value is required
	    @param string $setting_name
		Setting for which the value is required
	    @param string $setting_default
		Value to be returned instead if schema setting not defined
	    @return
		Value of the requested setting (otherwise $setting_default)
	*/

	function get_attribute_schema_setting($class,$setting_name,
		$setting_default="")
	{
		$setting_value = $setting_default;

		foreach($this->attribute_schema as $schema_entry)
			if($schema_entry["name"] == $class)
				$setting_value = $schema_entry[$setting_name];

	        return $setting_value;
	}

	/** Return the value of a setting for the specified object class

	    @param string $class
		Object class for which the setting value is required
	    @param string $setting_name
		Setting for which the value is required
	    @param string $setting_default
		Value to be returned instead if schema setting not defined
	    @return
		Value of the requested setting (otherwise $setting_default)
	*/

	function get_object_schema_setting($class,$setting_name,$setting_default="")
	{
		$setting_value = $setting_default;
		$object_data_found = false;

		foreach($this->object_schema as $object_class)
			if($object_class["name"] == $class && isset($object_class[$setting_name]))
			{
				$setting_value = $object_class[$setting_name];
				$object_data_found = true;
			}

		// return useful defaults if setting not found in schema
		if(!$object_data_found)
		{
			if($setting_name == "icon") $setting_value = "generic24.png";
			if($setting_name == "is_folder") $setting_value = false;
			if($setting_name == "rdn_attrib") $setting_value = "cn";
			if($setting_name == "can_create") $setting_value = false;
			if($setting_name == "display_name") $setting_value = $class;
		}
		return $setting_value;
	}

	/** Return matching schema object class for the specified LDAP entry

	    @param string $entry
		Entry for which matching class name is to be returned
	    @return
		Most specific class name for the object, or the
		string "(unrecognised)" if none of its object class
		values appears in the schema
	*/

	function get_object_class($entry)
	{
		$object_data_found = false;
		$item_object_class = "(unrecognised)";

		foreach($this->object_schema as $object_class)
			if(in_array($object_class["name"],
				$entry["objectclass"])
				&& $object_data_found == false)
			{
				$item_object_class = $object_class["name"];
				$object_data_found = true;
			}

		return $item_object_class;
	}

	/** Return whether the specified attribute is mandatory

	    Return 'true' if the specified attribute must always
	    have a non-empty value in the specified object class.

	    @param string $object_class
		Object class to be queried
	    @param string $attribute_name
		Attribute to return whether mandatory or not
	    @return
		Whether the attribute is mandatory or not (true/false)
	*/

	function check_object_requires_attribute($object_class,$attribute_name)
	{
		// is it required due to being the class's RDN?

		$required = ($this->get_object_schema_setting($object_class,
			"rdn_attrib")==$attribute_name);

		// if not required due to being the class's RDN attribute,
		// check whether it is listed in required_attribs

		if(!$required)
		{
			$required_attribs = explode(",",
				$this->get_object_schema_setting($object_class,
					"required_attribs"));

			foreach($required_attribs as $attrib)
				if($attrib == $attribute_name)
					$required = true;
		}

		return $required;
	}

	/** Retrieve icon/photo thumbnail URL for the specified LDAP entry.

	    The first available image will be used from the following list
	    (i.e. image is present and thumbnails enabled in the config)

		- jpegPhoto attribute
		- thumbnailPhoto attribute
		- thumbnailLogo attribute
		- icon representing object class

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
		global $enable_ldap_path_thumbnail,$thumbnail_image_size;

		// Resume existing session (if any exists) in order to get
		// currently logged in user
		if(!isset($_SESSION)) session_start();

		if(get_user_setting("allow_browse") || get_user_setting("allow_search")
			|| $get_user_setting("allow_view"))
		{
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
				$icon = "schema/" . $this->get_object_schema_setting(
					$this->get_object_class($entry),"icon");
				switch($this->server_type)
				{
					case "ad":
						// Microsoft AD - show disabled user icon
						$object_class = $this->get_object_class($entry);
						if($object_class == "user" && isset($entry["useraccountcontrol"]) &&
							($entry["useraccountcontrol"][0] & 2))
						$icon = "schema/user-disabled24.png";
						break;
				}
				return $icon;
			}
		}
		else
			return "schema/generic24.png";
	}

	/** Log on to LDAP directory

	    Attempts LDAP bind (login) with user (or config file) specified
	    credentials

	    @return
		Whether log on was successful (true/false)
	*/

	function log_on()
	{
		$user = get_user_setting("ldap_name");

		$result=false;
		if($user != "__DENY__")
		{
			ldap_set_option($this->connection,
				LDAP_OPT_PROTOCOL_VERSION,3);

			ldap_set_option($this->connection,
				LDAP_OPT_REFERRALS,$this->follow_referrals);

			$result=@ldap_bind_log($this->connection,$user,
				get_ldap_bind_password());

			if($this->follow_referrals)
				ldap_set_rebind_proc($this->connection,
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

	/** Return whether a DN is within the specified base of the DIT

	    The comparision is done server-side in order to apply
	    the correct matching rule for each attribute (e.g. whether
	    it is case sensitive or not).

	    @param string $dn
		DN to test
	    @param string $base_dn
		Base DN that $dn is going to be tested against
	    @return
		Whether $dn falls within $base_dn in the DIT (true/false)
	*/

	function compare_dn_to_base($dn,$base_dn)
	{
		$base_rdn_list = ldap_explode_dn($base_dn,0);
		$base_rdn_count = $base_rdn_list["count"];
		$dn_base_section = implode(array_slice(ldap_explode_dn($dn,0),
			-$base_rdn_count),",");

		return @ldap_compare($this->connection,
			$base_dn,"DN",$dn_base_section);
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

	function update_attribute($entry,$attrib,$is_rdn=false)
	{
		$dn = $entry[0]["dn"];

		$new_val_set = isset($_POST["ldap_attribute_" . $attrib]);

		// For image attributes, the above is set if the "clear image" box was ticked.
		// Further checks to see if an image was uploaded:
		if($this->get_attribute_schema_setting($attrib,"data_type","text") == "image")
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

			if($this->get_attribute_schema_setting($attrib,"data_type","text") == "image")
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
					$result = @ldap_mod_del($this->connection,$dn,$changes);
				else
					if($is_rdn)
						$result = @ldap_rename($this->connection,$dn,
							$attrib . "=" . $new_val,null,true);
					else
						$result = @ldap_mod_replace($this->connection,$dn,$changes);

				if($result)
				{
					if($this->get_attribute_schema_setting($attrib,"data_type","text") == "image")
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
						. $attrib . "': " . ldap_error($this->connection) . "<br>";
			}
		}
	}

	/** Add a mapping between an address book login and the LDAP logins

	    Defines the permissions that should be given to the user

	    @param string $login_name
		Address book login name
		* __ANONYMOUS__ - settings used when no user logged in by name
		* __DEFAULT__ - settings used for log in by name but not explicit config
	    @param string $ldap_name
		Corresponding LDAP server name
	    @param array $settings
		Array of permissions/settings for the user

	    @see
		"configuring users and permissions" in the manual
	*/
	function add_user_mapping($login_name,$ldap_name,$settings)
	{
		$this->user_map[] = array_merge(
			array("login_name"=>$login_name,"ldap_name"=>$ldap_name),
			$settings);
	}

	/** Return whether per-user logins are enabled

	    Indicated by either > 1 entry in user map
	    or a single entry which is not for __ANONYMOUS__

	    @return
		Whether per-user logins are enabled
	*/
	function per_user_login_enabled()
	{
		if(count($this->user_map) == 1
                        && $this->user_map[0]["login_name"] == "__ANONYMOUS__")

			return false;
		else
			return (count($this->user_map)>1);
	}

	/** Add/enable a schema

	    @param string $name
		Schema name
	*/

	function add_schema($name)
	{
		include_once("schema/" . $name . ".php");
		$schema_class_name = $name . "_schema";

		$schema_class = new $schema_class_name($this);
	}

	/** Add an object class to the schema

	    If the object class is already defined then its previous definition
	    will be replaced. The index position of the object in the schema
	    (used to indicate inheritance) will be unchanged.

	    Remove and re-add classes in the correct order if you need to change
	    the inheritance relationship of existing classes.

	    @param string $name
		Class name
	    @param array $settings
		Array of schema settings
	*/

	function add_object_class($name,$settings = array())
	{
		$object_class_index = $this->get_object_class_index($name);

		if(is_null($object_class_index))
		{
			$new_object_class = array_merge(array("name"=>$name),$settings);
			$this->object_schema[] = $new_object_class;
		}
		else
		{
			$this->object_schema[$object_class_index] = array("name"=>$name);
			foreach($settings as $setting=>$value)
				$this->object_schema[$object_class_index][$setting] = $value;
		}
	}

	/** Delete the specified object class from the schema

	    This function has no effect if the object class doesn't exist.

	    @param string $name
		Name of the object class name to be deleted
	*/

	function delete_object_class($name)
	{
		$object_class_index = $this->get_object_class_index($name);

		if(!is_null($object_class_index))
			unset($this->object_schema[$object_class_index]);
	}

	/** Return index of an object class schema entry

	    @param string $class_name
		Class name for which index is to be returned
	    @return
		Index of object class schema entry, or null if not found
	*/

	function get_object_class_index($class_name)
	{
		$class_index = null;
		foreach($this->object_schema as $index=>$object_class)
			if($object_class["name"] == $class_name)
				$class_index = $index;
		return $class_index;
	}

	/** Add an attribute class to the schema

	    If the attribute class is already defined then its previous definition
	    will be replaced. The index position of the attribute in the schema
	    (used to indicate inheritance) will be unchanged.

	    Remove and re-add classes in the correct order if you need to change
	    the inheritance relationship of existing classes.

	    @param string $name
		Class name
	    @param array $settings
		Array of schema settings

	    @todo
		behaviour if attribute already present (should update existing)
	*/

	function add_attribute_class($name,$settings = array())
	{
		$attribute_class_index = $this->get_attribute_class_index($name);

		if(is_null($attribute_class_index))
		{
			$new_attribute_class = array_merge(array("name"=>$name),$settings);
			$this->attribute_schema[] = $new_attribute_class;
		}
		else
		{
			$this->attribute_schema[$attribute_class_index] = array("name"=>$name);
			foreach($settings as $setting=>$value)
				$this->attribute_schema[$attribute_class_index][$setting] = $value;
		}
	}

	/** Delete the specified attribute class from the schema

	    This function has no effect if the attribute class doesn't exist.

	    @param string $name
		Name of the attribute class name to be deleted
	*/

	function delete_attribute_class($name)
	{
		$attribute_class_index = $this->get_attribute_class_index($name);

		if(!is_null($attribute_class_index))
			unset($this->attribute_schema[$attribute_class_index]);
	}

	/** Return index of an attribute class schema entry

	    @param string $class_name
		Class name for which index is to be returned
	    @return
		Index of attribute class schema entry, or null if not found
	*/

	function get_attribute_class_index($class_name)
	{
		$class_index = null;
		foreach($this->attribute_schema as $index=>$attribute_class)
			if($attribute_class["name"] == $class_name)
				$class_index = $index;
		return $class_index;
	}

	/** Add a display layout

	    @param string $object_classes
		Comma separated list of object classes which should use this layout
	    @param array $layout
		Array representing the display layout
	*/

	function add_display_layout($object_classes,$layout)
	{
		$object_class_list = explode(",",$object_classes);
		$this->display_layouts[] = array("object_classes"=>$object_class_list,"layout"=>$layout);
	}

	/** Return the display layout to be used for the specified object class

	    @param string $object_class
		Class name
	    @return
		Array representing the display layout
	*/

	function get_display_layout($object_class)
	{
		$found = false;
		$selected_layout = array();

		foreach($this->display_layouts as $layout)
		{
			if(!$found)
			{
				if(in_array($object_class,$layout["object_classes"])
					|| in_array("*",$layout["object_classes"]))
				{
					$found = true;
					$selected_layout = $layout["layout"];
				}
				if(in_array("*",$layout["object_classes"]))
					$selected_layout = $layout["layout"];
			}
		}
		return $selected_layout;
	}
}

/** Bind to LDAP directory, recording failures to server error log

    @param resource $ldap_link
        LDAP connection handle to bind/authenticate against
    @param string $user
	LDAP bind DN/login name of current user
    @param string $password
	LDAP bind password of current user
    @return
	True on success or false on failure
*/
function ldap_bind_log($ldap_link,$user=null,$password=null)
{
	$result=@ldap_bind($ldap_link,$user,$password);
	if(!$result)
		error_log("[" . $_SERVER["REMOTE_ADDR"]
			. "] Authentication to LDAP addressbook as '"
			. preg_replace("/[^[:print:]]/","",$user)
			. "' failed");
	return $result;
}

/** LDAP schema */

abstract class ldap_schema
{
	var $object_schema = array();
	var $attribute_schema = array();

	function __construct(&$ldap_server)
	{
		// Export object schema settings to LDAP server object
		foreach($this->object_schema as $settings)
		{
			$name = $settings["name"];
			unset($settings["name"]);

			$ldap_server->add_object_class($name,$settings);
		}
		// Export attribute schema settings to LDAP server object
		foreach($this->attribute_schema as $settings)
		{
			$name = $settings["name"];
			unset($settings["name"]);

			$ldap_server->add_attribute_class($name,$settings);
		}
	}
}
?>
