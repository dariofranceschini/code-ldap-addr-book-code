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

include "lib/country.php";
include "lib/ldap_result.php";
include "lib/ldap_schema.php";
include "lib/oid.php";
include "lib/openldap_module.php";

define("LDAP_SORT_ASCENDING",1);
define("LDAP_SORT_DESCENDING",2);
define("LDAP_ATTRIBUTE_IS_RDN",true);

define("MAX_DN_LENGTH",1000);
define("MAX_IMAGE_UPLOAD",1048576);		// 1 MiB

define("ENTRY_LIST_SHOW_ALL_OBJECTS",1);
define("ENTRY_LIST_SHOW_FOLDER_OBJECTS",2);
define("ENTRY_LIST_SHOW_LEAF_OBJECTS",3);

if(!file_exists("config.php")) missing_config_error();
if(!isset($site_name)) $site_name = gettext("Address Book");

// provide ldap_escape function for PHP <5.6
if(!function_exists("ldap_escape")) include "lib/ldap_escape.php";

$linked_ldap_dn_list=array();

/** Set user interface display language

    @param string $language
	Language code to use
*/

function set_language($language)
{
	if($language == "__auto__")
		// get prefered language from user agent
		$language = substr(getenv("HTTP_ACCEPT_LANGUAGE"),0,2);
	else
	{
		setlocale(LC_ALL,$language);
		putenv("LANG=" . $language);
		putenv("LANGUAGE=" . $language);

		bindtextdomain("main","./locale");
		textdomain("main");
	}
}

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
	echo "  <link rel=\"stylesheet\" href=\"styles_print.css\" type=\"text/css\" media=\"print\">\n";
	if(file_exists("styles_local.css"))
		echo "  <link rel=\"stylesheet\" href=\"styles_local.css\" type=\"text/css\">\n";

	echo "  <link rel=\"stylesheet\" href=\"lib/jquery-ui-themes-1.11.4/smoothness.css\" type=\"text/css\">\n";
	echo "  <script src=\"lib/jquery-1.11.2/jquery-1.11.2.js\"></script>\n";
	echo "  <script src=\"lib/jquery-ui-1.11.4/jquery-ui-1.11.4.js\"></script>\n";
	echo "  <script src=\"select_object_class.js\"></script>\n";

	if(isset($enable_search_suggestions) && $enable_search_suggestions)
		echo "  <script src=\"suggest.js\"></script>\n";

	echo "</head>\n\n";
	echo "<body>\n\n";
}

/** Output the site's HTML footer elements */

function show_site_footer()
{
	global $site_footer_links;

	echo "<hr>\n<div class=\"page_footer\">\n  "
		. sprintf(gettext("This Address Book uses Free and "
		. "Open Source Software, licensed under the terms of the "
		. "%sGNU Affero GPL version 3%s"),
		"<a href=\"doc/license.html\">","</a>") . "\n  <ul>\n";

	$first_link = true;
	if(isset($site_footer_links))
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
	echo "<form id=\"search_form\" action=\"" . current_page_folder_url() . "\" method=\"GET\">\n";

	echo "  <table class=\"search\">\n";
	echo "    <tr>\n";
	echo "      <th>" . gettext("Search for:") . "</th>\n";
	echo "      <td><input type=\"text\" id=\"filter\" name=\"filter\"";
	if(!empty($initial_value))
		echo " value=\"" . htmlentities($initial_value,ENT_COMPAT,"UTF-8") . "\"";
	echo "></td>\n";
	echo "      <td><input type=\"submit\" value=\"" . gettext("Search") . "\"></td>\n";
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
	show_ldap_path(null,"");
	show_search_box("");
	echo "<p>  \n" . $message . "\n</p>"
		. "<p>\n  <a href=\"" . current_page_folder_url()
		. "\">" . gettext("Return to the Address Book") . "</a>\n</p>";

	show_site_footer();
	exit(0);
}

/** Show "breadcrumb navigation" version of specified LDAP path

    Also shows "server info" link (if the current user has system
    admin permissions) and "login" link (if per-user logins
    are enabled)

    @param object $ldap_server
	Server containing the record for which the breadcrumb
	navigation is to be displayed.
    @param string $dn
	DN of the record for which the breadcrumb navigation is
	to be displayed.
    @param string $leaf_icon
	Placeholder image to use for the last item in the path
	if the actual icon isn't available. Typically used when
	a creating a new object, before the object has gets
	written to the directory.
*/

function show_ldap_path($ldap_server,$dn,$leaf_icon = "")
{
	global $site_name,$show_ldap_path;

	if(!isset($site_name))
		$site_name = gettext("Address Book");

	echo "<table class=\"ldap_navigation_path_frame\">\n  <tr>\n"
		. "    <td>\n      <ul class=\"ldap_navigation_path\">\n"
		. "        <li><a href=\"" . current_page_folder_url() . "\">"
		. "<img alt=\"" . gettext("Address Book")
		. "\" title=\"" . gettext("Address Book") . "\" src=\"addressbook24.png\"> "
		. $site_name . "</a></li>\n";

	if(!is_object($ldap_server) || $ldap_server->base_dn == "" || !$ldap_server->compare_dn_to_base($dn,$ldap_server->base_dn))
		$rdn_list = $dn;
	else
		$rdn_list = substr($dn,0,-strlen($ldap_server->base_dn)-1);

	if($rdn_list != "")
	{
		$rdn_list = ldap_explode_dn2($rdn_list);

		if($ldap_server->get_user_setting("allow_ldap_path"))
			$starting_entry = $rdn_list["count"];
		else
			$starting_entry = 1;

		for($i=$starting_entry;$i>0;$i--)
		{
			echo "        <li>";

			if($rdn_list[$i-1]["dn"] == $ldap_server->base_dn)
				$object_dn = $ldap_server->base_dn;
			else if($ldap_server->base_dn == "" || !$ldap_server->compare_dn_to_base($dn,$ldap_server->base_dn))
				$object_dn = $rdn_list[$i-1]["dn"];
			else
				$object_dn = $rdn_list[$i-1]["dn"]
					. "," . $ldap_server->base_dn;

			// read LDAP entry to get object class/icon information

			$search_resource = @ldap_read($ldap_server->connection,
				$object_dn,"(objectclass=*)",array("objectclass",
				"jpegphoto","thumbnailphoto","thumbnaillogo"));

			if($search_resource)
			{
				$ldap_entry = ldap_get_entries($ldap_server->connection,$search_resource);
				$icon = $ldap_server->get_icon_for_ldap_entry($ldap_entry[0]);
				$object_class = $ldap_server->get_object_class($ldap_entry[0]);
			}
			else
			{
				// Use a generic icon if object's actual icon couldn't be retrieved

				if($i==1)
				{
					$icon = empty($leaf_icon) ? "schema/generic24.png" : $leaf_icon;
					$object_class=gettext("Address Book Entry");
				}
				else
				{
					$icon="schema/folder-unreadable.png";
					$object_class="__UNREADABLE_FOLDER__";
				}
			}
			if($object_class == "__UNREADABLE_FOLDER__")
				$alt_text = gettext("Unreadable Folder");
			else
				$alt_text = $object_class;

			if($i>1 && $object_class != "__UNREADABLE_FOLDER__"
				&& $ldap_server->get_user_setting("allow_browse"))
			{
				if($ldap_server->get_object_schema_setting($object_class,"is_folder"))
					echo "<a href=\"" . current_page_folder_url()
						. "?dn=";
				else
					echo "<a href=\"" . current_page_folder_url()
						. "info.php?dn=";

				echo urlencode($object_dn);

				echo "\">";
			}

			echo "<img alt=\""
				. $alt_text . "\" title=\"" . $alt_text . "\" src=\""
				. $icon . "\"> "
				. $rdn_list[$i-1]["value"];

			if($i>1 && $object_class != "__UNREADABLE_FOLDER__"
					&& $ldap_server->get_user_setting("allow_browse"))
				echo "</a>";

			echo "</li>";

			echo "\n";
		}
	}

	echo "      </ul>\n";
	echo "    </td>\n";

	echo "    <td class=\"server_info\">";
	if(is_object($ldap_server) && $ldap_server->get_user_setting("allow_system_admin"))
		echo "<a href=\"info.php?dn=\">" . gettext("Server Info") . "</a>";
	else
		echo "<!-- server info not enabled -->";

	if(is_object($ldap_server) && $ldap_server->per_user_login_enabled() && $ldap_server->get_user_setting("allow_system_admin"))
		echo " | ";
	echo "</td>\n";

	echo "    <td class=\"login_info\">";
	if(is_object($ldap_server) && $ldap_server->per_user_login_enabled())
	{
		// Resume existing session (if any exists) in order to get
		// currently logged in user
		if(!isset($_SESSION)) session_start();

		// display user name if set, etc, etc
		echo "<a href=\"user.php\">";

		if(isset($_SESSION["LOGIN_USER"]))
			echo gettext("Log out") . " " . ucwords(strtolower($_SESSION["LOGIN_USER"]));
		else
			echo gettext("Log in");
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
    built-in PHP function ldap_explode_dn().

    @param string $dn
	DN which is to be converted into an array
    @return
	An array representing the DN, consisting of integer
	value "count" indicating the number of RDNs,
	followed by each RDN as an "attrib"+"value" pair

    @see https://www.ietf.org/rfc/rfc4514.txt
*/

function ldap_explode_dn2($dn)
{
	// explode $dn on ",", except when escaped as "\,")
	$dn = preg_split("~(?<!\\\)" . preg_quote(",","~") . "~",$dn);

	for($i=0;$i<count($dn);$i++)
	{
		$component_dn = "";
		for($j=$i;$j<count($dn);$j++)
			$component_dn .= ($j>$i?",":"") . $dn[$j];

		// explode $dn[$i] on "+", except when escaped as "\+")
		$rdn = preg_split("~(?<!\\\)" . preg_quote("+","~") . "~",$dn[$i]);

		$dn[$i] = array(
			"attrib"=>"",
			"value"=>"",
			"dn"=>$component_dn);

		for($j=0;$j<count($rdn);$j++)
		{
			$dn[$i]["attrib"].=($j==0?"":"+") . substr($rdn[$j],0,strpos($rdn[$j],"="));
			$dn[$i]["value"].=($j==0?"":" + ") . substr($rdn[$j],strpos($rdn[$j],"=")+1);
		}

		$dn[$i]["value"]=ldap_unescape_dn_string($dn[$i]["value"]);
	}
	$dn = array("count"=>count($dn)) + $dn;

	return $dn;
}

/** Convert a DN string containing escaped characters back to its original form

    @param string $text
	Text which is to be converted

    @see https://www.ietf.org/rfc/rfc4514.txt
*/

function ldap_unescape_dn_string($text)
{
	$text_out="";
	for($i=0;$i<mb_strlen($text,"UTF-8");$i++)
	{
		if(mb_substr($text,$i,1,"UTF-8") == "\\")
		{
			if(in_array(mb_substr($text,$i+1,1,"UTF-8"),str_split(" \"#+,;<=>\\")))
			{
				$text_out.=mb_substr($text,$i+1,1,"UTF-8");
				$i++;
			}
			else if(ctype_xdigit(mb_substr($text,$i+1,2,"UTF-8")))
			{
				$text_out.=chr(hexdec(mb_substr($text,$i+1,2,"UTF-8")));
				$i+=2;
			}
			else
				$text_out.=mb_substr($text,$i,1,"UTF-8");
		}
		else
			$text_out.=mb_substr($text,$i,1,"UTF-8");
	}
	return $text_out;
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
		: str_replace(DIRECTORY_SEPARATOR,"/",
		dirname($_SERVER["REQUEST_URI"]));

	// add trailing slash (missing from non-root folders)
	if(substr($path,-1) != "/") $path .= "/";

	if(($scheme == "http" && $_SERVER["SERVER_PORT"] != 80)
			|| ($scheme == "https" && $_SERVER["SERVER_PORT"] != 443))
		$port = ":" . $_SERVER["SERVER_PORT"];
	else
		$port = "";

	return $scheme . "://" . $_SERVER["SERVER_NAME"] . $port . $path;
}

/** View LDAP entry as HTML */

class ldap_entry_viewer
{
	/** Display layout (array of ldap_entry_viewer_section) */
	var $section = array();

	/** LDAP server containing the entry to be displayed */
	var $ldap_server;

	/** LDAP object entry which is to be displayed */
	var $ldap_entry;

	/** Display a viewer for editing a record (true/false) */
	var $edit = false;

	/** Display a viewer for creating a new record (true/false) */
	var $create = false;

	/** Mequired/mandatory attributes for the record being viewed */
	var $required_attribs = array();

	/** Constructor

	    @param object $ldap_server
		LDAP server from which a record is to be displayed
	    @param array $ldap_entry
		Array containing LDAP object entry which is to
		be displayed
	*/

	function __construct($ldap_server,$ldap_entry)
	{
		$this->ldap_server = $ldap_server;
		$this->ldap_entry = $ldap_entry;

		$entry_viewer_layout = $ldap_server->get_display_layout(
			$ldap_server->get_object_class($ldap_entry[0]));

		// include newly-added auxiliary classes in the display layout
		// TODO: guard against nasties in add_aux_class
		if(isset($_GET["add_aux_class"]))
		{
			$new_aux_classes = explode(",",$_GET["add_aux_class"]);

			$ldap_entry[0]["objectclass"] = array_merge(
				$ldap_entry[0]["objectclass"],
				$new_aux_classes);
		}
		else
			$new_aux_classes = array();

		$this->ldap_server->add_auxiliary_layouts($ldap_entry[0],
			$entry_viewer_layout,$new_aux_classes);

		$this->required_attribs = get_required_attribs($ldap_entry[0]);

		$first_section = true;
		foreach($entry_viewer_layout as $section)
		{
			if(!isset($section["new_row"]))
				$section["new_row"] = $first_section;

			$this->add_section($section);

			$first_section = false;
		}
	}

	/** Add a section to the display

	    @param array $section
		Details of section to add
	*/

	function add_section($section)
	{
		$heading = new ldap_entry_viewer_section();

		$heading->text = isset($section["section_name"]) ? $section["section_name"] : "";
		$heading->colspan = isset($section["colspan"]) ? $section["colspan"] : 1;
		$heading->new_row = $section["new_row"];
		$heading->width = isset($section["width"]) ? $section["width"] : null;
		$heading->viewer = $this;

		$this->section[$heading->text] = $heading;

		foreach($section["attributes"] as $attribute)
			$this->section[$heading->text]->add_data($attribute);
	}

	/** Output the object entry as vCard */

	function save_vcard()
	{
		$rdn_attrib = $this->ldap_server->get_object_schema_setting(
			$this->ldap_server->get_object_class($this->ldap_entry[0])
			,"rdn_attrib");

		$rdn_list = explode(",",$rdn_attrib);

		$filename = "";
		foreach($rdn_list as $rdn)
		{
			if($filename != "") $filename .= "_";

			if(isset($this->ldap_entry[0][strtolower($rdn)][0]))
				$filename .= $this->ldap_entry[0][strtolower($rdn)][0];
			else
				$filename .= $this->ldap_entry[0][strtolower($rdn)];
		}

		header("Content-Type: text/vcard");
		header("Content-Disposition: attachment; filename=\""
			. $filename . ".vcf\"");

		$vcard = new vcard($this->ldap_server,$this->ldap_entry[0]);
		echo $vcard->data;
	}

	/** Output the object entry as HTML */

	function show()
	{
		global $thumbnail_image_size,$enable_ldap_path_thumbnail;

		$dn = $this->ldap_entry[0]["dn"];

		if($this->create)
			show_ldap_path($this->ldap_server,"CN=" . sprintf(gettext("New %s"),
				$this->ldap_server->get_object_schema_setting(
				$this->ldap_server->get_object_class($this->ldap_entry[0]),
				"display_name")) . (empty($dn) ? "" : "," . $dn),
				$this->ldap_server->get_icon_for_ldap_entry($this->ldap_entry[0]));
		else
			show_ldap_path($this->ldap_server,$dn);

		if($this->ldap_server->get_user_setting("allow_search"))
			show_search_box("");
		else
			echo "<br>";

		if($this->ldap_server->get_user_setting("allow_view"))
		{
			if($this->edit && ($this->ldap_server->get_user_setting("allow_edit")
				|| ($this->ldap_server->get_user_setting("allow_edit_self")
				&& !strcasecmp($_SESSION["LOGIN_BIND_DN"][$this->ldap_server->server_id],$dn))))
			{
				$server_id = $this->ldap_server->server_id == 0
					? "" : "&server_id=" . $this->ldap_server->server_id;

				echo "<form method=\"POST\" action=\"update.php?dn="
					. urlencode($dn) . $server_id
					. "\" style=\"display:inline\" enctype=\"multipart/form-data\">\n";

				if(isset($_GET["add_aux_class"]))
					echo "<input type=\"hidden\" name=\"add_aux_class\" value=\""
						. htmlentities($_GET["add_aux_class"],
						ENT_COMPAT,"UTF-8") . "\">\n";
			}

			$first_section=true;
			foreach($this->section as $section)
			{
				if($first_section)
					$section->first_section=true;
				$first_section=false;

				$section->show();
			}

			// close last section in layout
			echo "  </tr>\n</table>\n\n";

			if($this->ldap_server->get_object_schema_setting(
				$this->ldap_server->get_object_class($this->ldap_entry[0]),
				"is_folder") && $this->ldap_server->get_user_setting("allow_browse") && !$this->edit)
			{
				echo "<p>"
					. gettext("This record is a folder that can contain other objects.")
					. "</p>";

				echo "<a href=\"" . current_page_folder_url()
					. "?dn=" . urlencode ($dn)
					. ($this->ldap_server->server_id == 0 ? "" : "&server_id=" . $this->ldap_server->server_id)
					. "\"><button>"
					. gettext("Show Contents") . "</button></a>";
			}

			if($this->ldap_server->get_user_setting("allow_edit")
					|| ($this->ldap_server->get_user_setting("allow_edit_self")
					&& !strcasecmp($_SESSION["LOGIN_BIND_DN"][$this->ldap_server->server_id],$dn)))
				if($this->edit)
				{
					if($this->create)
						echo "<input type=\"hidden\" name=\"create\" value=\""
							. $this->ldap_entry[0]["objectclass"][0] . "\">"
							. "<input type=\"submit\" value=\"" . gettext("Create record") . "\">"
							. "\n</form>\n";
					else
						echo "<input type=\"submit\" value=\"" . gettext("Save changes") . "\">"
							. "\n</form>\n";

					echo "<a href=\"info.php?dn="
						. urlencode($dn)
						. ($this->ldap_server->server_id == 0 ? "" : "&server_id=" . $this->ldap_server->server_id)
						. "\"><button>" . gettext("Cancel") . "</button></a>\n";
				}
				else
				{
					echo "<form method=\"GET\" action=\"info.php\" style=\"display:inline\">\n"
						. "  <input type=\"hidden\" name=\"edit\" value=\"1\">\n"
						. "  <input type=\"hidden\" name=\"dn\" value=\""
						. htmlentities($dn,ENT_COMPAT,"UTF-8") . "\">\n";

					if($this->ldap_server->server_id != 0)
						echo "  <input type=\"hidden\" name=\"server_id\" value=\""
							. htmlentities($this->ldap_server->server_id,ENT_COMPAT,"UTF-8") . "\">\n";

					echo "  <input type=\"submit\" value=\"" . gettext("Edit") . "\">\n</form>\n";
				}

			if($this->ldap_server->get_user_setting("allow_move") && !$this->edit)
				echo "<a href=\"move.php?dn="
					. urlencode($dn)
					. ($this->ldap_server->server_id == 0 ? "" : "&server_id=" . $this->ldap_server->server_id)
					. "\"><button>" . gettext("Move") . "</button></a>\n";

			if($this->ldap_server->get_user_setting("allow_delete") && !$this->edit)
				echo "<a href=\"delete.php?page=info&dn="
					. urlencode($dn)
					. ($this->ldap_server->server_id == 0 ? "" : "&server_id=" . $this->ldap_server->server_id)
					. "\"><button>" . gettext("Delete") . "</button></a>\n";

			if($this->ldap_server->get_user_setting("allow_extend") && $this->ldap_server->get_user_setting("allow_edit") && !$this->edit)
				echo "<a href=\"extend.php?dn="
					. urlencode($dn)
					. ($this->ldap_server->server_id == 0 ? "" : "&server_id=" . $this->ldap_server->server_id)
					. "\"><button>" . gettext("Extend Record") . "</button></a>\n";

			if($this->ldap_server->get_user_setting("allow_export") && !$this->edit)
				echo "<a href=\"info.php?vcard=1&dn="
					. urlencode($dn)
					. ($this->ldap_server->server_id == 0 ? "" : "&server_id=" . $this->ldap_server->server_id)
					. "\"><button>" . gettext("Save as vCard") . "</button></a>\n";
		}
		else
			echo "<p>" . gettext("You do not have permission to view this record") . "</p>\n";
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
	var $new_row=false;

	/** Reference to ldap_entry_viewer object that contains this section */
	var $viewer;

	/** Is this the first section in the display layout? */
	var $first_section=false;

	/** Attributes to display in section (array of ldap_entry_viewer_attribute) */
	var $attrib=array();

	/** Width of the section

	    Value to be assigned to CSS "width" style directive for the section.
	    Typically a percentage
	*/
	var $width="";

	/** Add an attribute and its value to the display

	    @param array $attribute
		LDAP attribute to be added
	*/

	function add_data($attribute)
	{
		$this->attrib[] = new ldap_entry_viewer_attrib($this->viewer,$attribute);
	}

	/** Output this section of the object entry as HTML */

	function show()
	{
		if($this->new_row && !$this->first_section) echo "  </tr>\n</table>\n";

		echo "\n<!-- Section: " . $this->text . " -->\n\n";

		if($this->new_row) echo "<table class=\"ldap_entry_viewer\">\n  <tr>\n";

		$cell_attrib = "";
		if($this->colspan != 1)
			$cell_attrib.=" colspan=\"" . $this->colspan . "\"";

		if($this->width != "")
			$cell_attrib.=" style=\"width:" . $this->width . "\"";

		echo "    <td class=\"ldap_entry_viewer_section_frame\" " . $cell_attrib
			. ">\n      <table class=\"ldap_entry_viewer_section\">\n";

		if(!empty($this->text))
			echo "        <tr>\n          <th colspan=\"3\" class=\"column_header\">"
				. $this->text . "</th>\n        </tr>\n";

		foreach($this->attrib as $attrib)
			$attrib->show($this->viewer->create,$this->viewer->edit);

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

	/** Reference to ldap_entry_viewer object that contains this attribute */
	var $viewer;

	/** Whether this attribute should be displayed when viewing the record.

	    If this is set to false the attribute will be visible only
	    when editing.
	*/
	var $allow_view;

	/** Whether this attribute can be edited */
	var $allow_edit;

	/** Add an attribute and its value to the display

	    @param array $viewer
		ldap_entry_viewer object containing the attribute
	    @param array $attribute
		LDAP attribute to display
	*/

	function __construct($viewer,$attribute)
	{
		$this->caption = isset($attribute[1]) ? $attribute[1] : null;
		$this->ldap_attribute = $attribute[0];
		$this->icon = isset($attribute[2]) ? $attribute[2] : null;
		$this->viewer = $viewer;
		$this->allow_view = isset($attribute["allow_view"]) ? $attribute["allow_view"] : true;
		$this->allow_edit = isset($attribute["allow_edit"]) ? $attribute["allow_edit"] : true;
	}

	/** Output this object attribute as HTML

	    @param bool $create
		Whether the attribute should be rendered as for a new object
		which is being created
	    @param bool $edit
		Whether the attribute should be rendered with editing enabled
	*/

	function show($create,$edit)
	{
		global $ldap_server;

		// Attribute should be visible whilst record is being edited
		if($edit) $this->allow_view = true;

		// Attribute should not be editable if allow_edit is not granted
		if(!$this->allow_edit) $edit = false;

		if($edit || $this->allow_view)
		{
			echo "        <tr>\n";

			// Use full width if attribute has no icon or caption text
			if($this->icon == "" && $this->caption == "")
				echo "          <td colspan=\"3\" class=\""
						. ldap_attribute_to_css_class($this->ldap_attribute)
						. "\">\n            ";
			else
			{
				echo "          <th>";
				if($this->icon != "")
					echo "<img alt=\"" . $this->ldap_attribute
						. "\" src=\"schema/"
						. $this->icon . "\">";
				echo "</th>\n          "
					. "<th>"
					. htmlentities($this->caption,ENT_COMPAT,"UTF-8") . "</th>\n";

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

					$attrib = new ldap_attribute($this->viewer->ldap_server,
						$this->viewer->ldap_entry[0],$attribute);

					$attrib->edit = $edit;
					$attrib->read_only = !$this->allow_edit;	// TODO: not needed in longer term?
					$attrib->create = $create;
					$attrib->required = in_array($attribute,$this->viewer->required_attribs);

					$attrib->show();
				}
			}
			echo "\n          </td>\n        </tr>\n";
		}
	}
}

/** LDAP attribute */

class ldap_attribute
{
	/** LDAP server containing the entries to be displayed */
	var $ldap_server;

	/** LDAP object entry which is to be displayed */
	var $ldap_entry;

	/** Attribute which is to be displayed */
	var $attribute;

	/** Value of the attribute (or first value for a multi-valued attributes) */
	var $value;

	/** "Friendly" display name of attribute (typically rendered as a "tooltip") */
	var $display_name;

	/** Whether attribute is mandatory (either marked as such or the RDN) */
	var $required = false;

	/** Whether the attribute is for a new record that is being created */
	var $create = false;

	/** Whether the attribute should be rendered with editing enabled */
	var $edit = false;

	/** Whether the attribute should be rendered with buttons to add values, etc

	    (In the longer term this should not need to exist as a distinct setting
	    to $edit.)
	*/
	var $read_only = false;

	/** Whether to show attributes such as dates in "short" format */
	var $use_short_format = false;

	/** Whether to show embedded HTML links when the object is displayed */
	var $show_embedded_links = true;

	/** Constructor

	    @param array $ldap_entry
		Array containing LDAP object entry containing the
		attribute be displayed
	    @param string $attribute
		Name of attribute to be displayed
	*/

	function __construct($ldap_server,$ldap_entry,$attribute)
	{
		$this->ldap_server = $ldap_server;
		$this->ldap_entry = $ldap_entry;
		$this->attribute = $ldap_server->get_attribute_primary_class($attribute);

		$this->value = $this->get_value();

		// Get display name for attribute
		$this->display_name = $ldap_server->get_attribute_schema_setting(
			$this->attribute,"display_name",$this->attribute);

		if($this->display_name!=$this->attribute)
			$this->display_name .= " (" . $this->attribute . ")";
	}

	/** Gets the attribute's value (or first value if multi-valued)

	    @return
		Value of the requested attribute
	*/

	function get_value()
	{
		global $openldap_overlay_module,$openldap_backend_module,$ldap_server,$short_date_time_format;

		if($this->attribute == "sortableName")
		{
			// sortableName is an internal "synthesised"
			// attribute rather than retrieved from
			// the LDAP server itself.

			if(!empty($this->ldap_entry["sn"][0]))
				 $attrib_value = $this->ldap_entry["sn"][0];
			else if(!empty($this->ldap_entry["displayname"][0]))
				$attrib_value = $this->ldap_entry["displayname"][0];
			else if(isset($this->ldap_entry["cn"][0]))
				$attrib_value = $this->ldap_entry["cn"][0];
			else
				$attrib_value = "";

			if(!empty($this->ldap_entry["givenname"][0]))
				$attrib_value .= ", "
					. $this->ldap_entry["givenname"][0];

			if(trim($attrib_value) == "")
			{
				$dn_elements=ldap_explode_dn2($this->ldap_entry["dn"]);

				$data_type = $ldap_server->get_attribute_schema_setting(
					$dn_elements[0]["attrib"],"data_type","text");

				switch($data_type)
				{
					case "date_time":
						if(empty($short_date_time_format))
							$short_date_time_format="%d %b %Y %H:%M:%S";

						$date = mktime(
							substr($dn_elements[0]["value"],8,2),	// hour
							substr($dn_elements[0]["value"],10,2),	// minute
							substr($dn_elements[0]["value"],12,2),	// second
							substr($dn_elements[0]["value"],4,2),	// month
							substr($dn_elements[0]["value"],6,2),	// day
							substr($dn_elements[0]["value"],0,4)	// year
							);

						$attrib_value = strftime($short_date_time_format,$date);
						break;
					default:
						$attrib_value = $dn_elements[0]["value"];
				}
			}

			// Append OpenLDAP database naming context
			if(!empty($this->ldap_entry["olcsuffix"][0]))
				$attrib_value .= " " . sprintf(gettext("for '%s'"),$this->ldap_entry["olcsuffix"][0]);

			// Append OpenLDAP database directory location
			if(!empty($this->ldap_entry["olcdbdirectory"][0]))
				$attrib_value .= " " . sprintf(gettext("at '%s'"),$this->ldap_entry["olcdbdirectory"][0]);

			// Append OpenLDAP database URI
			if(!empty($this->ldap_entry["olcdburi"][0]))
				$attrib_value .= " " . sprintf(gettext("from '%s'"),$this->ldap_entry["olcdburi"][0]);

			// Append OpenLDAP module names to be loaded
			if(!empty($this->ldap_entry["olcmoduleload"][0]))
			{
				$attrib_value .= " - ";
				$first_value = true;
				for($i=0;$i<$this->ldap_entry["olcmoduleload"]["count"];$i++)
				{
					$module_name = explode("}",$this->ldap_entry["olcmoduleload"][$i]);
					$module_name = $module_name[1];

					if(isset($openldap_overlay_module[$module_name]))
						$module_name = $openldap_overlay_module[$module_name];
					else if(isset($openldap_backend_module[$module_name]))
						$module_name = $openldap_backend_module[$module_name];

					if(!$first_value) $attrib_value .= ", ";
						$first_value = false;

					$attrib_value .= $module_name;
				}
			}
		}
		else
		{
			$attribute = strtolower($this->attribute);

			if(!empty($this->ldap_entry[$attribute][0]))
				/** @todo
					Currently only returns the first value of the
					attribute. Should iterate over multi-valued
					attributes.
				*/
				$attrib_value = $this->ldap_entry[$attribute][0];
			else
				$attrib_value = "";
		}

		return $attrib_value;
	}

	/** display the attribute */

	function show()
	{
		if($this->attribute=="__CHILD_OBJECTS__")
			$data_type = "child_objects";
		else
			$data_type = $this->ldap_server->get_attribute_schema_setting(
				$this->attribute,"data_type","text");

		// Work around potential Active Directory schema inconsistency:
		// The serverName attribute contains a DN when used in the rootDSE
		// object, but a DNS host name when used in printQueue objects.

		if($this->attribute=="serverName" && $this->ldap_entry["dn"]==""
				&& $this->ldap_server->server_type == "ad")
			$data_type = "dn";

		switch($data_type)
		{
			case "dn":		$this->show_dn();		break;
			case "dn_list":		$this->show_dn_list();		break;
			case "date":		$this->show_date();		break;
			case "date_time":	$this->show_date_time();	break;
			case "gender":		$this->show_gender();		break;
			case "ad_group_type":	$this->show_ad_group_type();	break;
			case "ad_func_level":	$this->show_ad_func_level();	break;
			case "postcode":	$this->show_postcode();		break;
			case "country_code":	$this->show_country_code();	break;
			case "image":		$this->show_image();		break;
			case "yes_no":		$this->show_boolean_yes_no();	break;
			case "use_html_mail":	$this->show_use_html_mail();	break;
			case "delivery_method":	$this->show_delivery_method();	break;
			case "text":		$this->show_text();		break;
			case "oid_list":	$this->show_oid_list();		break;
			case "mail_preference":	$this->show_mail_preference();	break;
			case "download":	$this->show_download_list();	break;
			case "download_list":	$this->show_download_list();	break;
			case "child_objects":	$this->show_child_objects();	break;
			case "text_list":	$this->show_text_list();	break;
			case "ldap_schema":	$this->show_ldap_schema();	break;
			case "text_area":	$this->show_text_area();	break;
			case "phone_number":	$this->show_phone_number();	break;
			case "olc_dangling":	$this->show_olc_dangling();	break;
			case "olc_pcachepos":	$this->show_olc_pcachepos();	break;
			case "ldap_result":	$this->show_ldap_result();	break;
			case "oid_macro_list":	$this->show_oid_macro_list();	break;
			case "openldap_module":	$this->show_openldap_module();	break;
			case "openldap_backend":$this->show_openldap_backend();	break;
			case "openldap_tlsver":	$this->show_openldap_tlsver();	break;
			case "openldap_crlchk":	$this->show_openldap_crlchk();	break;
			case "openldap_clicrt":	$this->show_openldap_clicrt();	break;
			case "openldap_authpol":$this->show_openldap_authpol();	break;
			case "openldap_subord":	$this->show_openldap_subord();	break;
			case "ldap_version":	$this->show_ldap_version();	break;
			case "search_scope":	$this->show_search_scope();	break;
			case "alias_deref":	$this->show_alias_deref();	break;
			case "pwd_qualitycheck":$this->show_pwd_qualitycheck();	break;
			case "nested_config":	$this->show_nested_config();	break;
			case "nfap_authent":	$this->show_nfap_authent();	break;
			case "nfap_dialect":	$this->show_nfap_dialect();	break;
			case "nfap_signing":	$this->show_nfap_signing();	break;

			default:
				echo "** " . gettext("Unsupported data type:") . " <code>" . $data_type . "</code> **";
		}
	}

	/** Show single-line textual attribute (data type "text")

	    @todo
		Escape "nasty values" in $attrib_value, e.g. "
	    @todo
		Style this better.. should be 100% less a fixed number of pixels?
	*/

	function show_text()
	{
		if($this->edit)
		{
			if($this->required)
				$style = "width:98%;border-color:red;border-style:solid";
			else
				$style = "width:98%;";

			echo "<input style=\"" . $style . "\" type=\"text\" name=\"ldap_attribute_"
				. $this->attribute . "\" value=\""
				. htmlentities($this->value,ENT_COMPAT,"UTF-8")
				. "\" title=\"" . $this->display_name . "\" placeholder=\""
				. $this->display_name . "\">";
		}
		else
		{
			if($this->show_embedded_links)
				echo urls_to_links(htmlentities($this->value,ENT_COMPAT,"UTF-8"));
			else
				echo htmlentities($this->value,ENT_COMPAT,"UTF-8");
		}
	}

	/** Show Active Directory groupType attribute (data type "ad_group_type")

	    This attribute contains a bit pattern which describes scope/type
	    characteristics of an Active Directory "group" object.

	    The attribute is defined in the "microsoft" schema.

	    @see
		https://msdn.microsoft.com/en-us/library/ms675935%28v=vs.85%29.aspx

	    @todo
		Editing support for this data type
	*/

	function show_ad_group_type()
	{
		echo "<ul style=\"margin:0px;list-style-type:none;padding:0px\">";
		if($this->value & 0x80000000) echo "<li>" . gettext("Security group"); else echo "<li>" . gettext("Distribution group");
		if($this->value & 0x01) echo " (" . gettext("System generated") . ")";
		echo "</li>";

		if($this->value & 0x10) echo "<li>" . gettext("Windows Authorization Manager APP_BASIC group") . "</li>";
		if($this->value & 0x20) echo "<li>" . gettext("Windows Authorization Manager APP_QUERY group") . "</li>";
		if($this->value & 0x02) echo "<li>" . gettext("Global scope") . "</li>";
		if($this->value & 0x04) echo "<li>" . gettext("Domain local scope") . "</li>";
		if($this->value & 0x08) echo "<li>" . gettext("Universal scope") . "</li>";
		echo "</ul>";
	}

	/** Show delivery methods attribute (data type "delivery_method")

	    This attribute lists the methods by which an entity is willing/capable of
	    receiving messages. Listed in preference order, separated by $.

	    For the time being this is implemented as a simple (single valued) enumeration

	    @see
		https://tools.ietf.org/html/rfc4517#section-3.3.5

	    @todo
		Support specifying more than one delivery method
	*/

	function show_delivery_method()
	{
		$this->show_enum(
			array(
				array("value"=>"any","display_name"=>gettext("Any")),
				array("value"=>"mhs","display_name"=>gettext("Message Handling System (e.g. E-mail)")),
				array("value"=>"physical","display_name"=>gettext("Physical")),
				array("value"=>"telex","display_name"=>gettext("Telex")),
				array("value"=>"teletex","display_name"=>gettext("Teletex")),
				array("value"=>"g3fax","display_name"=>gettext("G3 Fax")),
				array("value"=>"g4fax","display_name"=>gettext("G4 Fax")),
				array("value"=>"ia5","display_name"=>gettext("IA5")),
				array("value"=>"videotex","display_name"=>gettext("Videotex")),
				array("value"=>"telephone","display_name"=>gettext("Telephone"))
				)
			);
	}

	/** Show mozillaUseHtmlMail attribute (data type "use_html_mail")

	    This attribute indicates whether or not the user prefers to receive
	    HTML-formatted e-mails.

		- TRUE - User prefers HTML
		- FALSE - User prefers plain text (does not prefer HTML)

	    The attribute is defined in the "mozilla" schema.
	*/

	function show_use_html_mail()
	{
		$this->show_enum(
			array(
				array("value"=>"FALSE","display_name"=>gettext("Plain Text")),
				array("value"=>"TRUE","display_name"=>gettext("HTML"))
				)
			);
	}

	/** Show olcTLSProtocolMin attribute (data type "openldap_tlsver")

	    This attribute indicates the minimum supported TLS version that
	    will be accepted by an OpenLDAP server.

	    The attribute is defined in the "openldap/config" schema.
	*/

	function show_openldap_tlsver()
	{
		$this->show_enum(
			array(
				array("value"=>"0.0","display_name"=>gettext("Any")),
				array("value"=>"2.0","display_name"=>gettext("SSL 2.0")),
				array("value"=>"3.0","display_name"=>gettext("SSL 3.0")),
				array("value"=>"3.1","display_name"=>gettext("TLS 1.0")),
				array("value"=>"3.2","display_name"=>gettext("TLS 1.1")),
				array("value"=>"3.2","display_name"=>gettext("TLS 1.2"))
				)
			);
	}

	/** Show olcTLSCRLCheck attribute (data type "openldap_crlchk")

	    This attribute is used to specify the extent to which TLS client certificates are
	    checked for revocation by an OpenLDAP server before accepting a connection.

	    The attribute is defined in the "openldap/config" schema.
	*/

	function show_openldap_crlchk()
	{
		$this->show_enum(
			array(
				array("value"=>"none","display_name"=>gettext("None")),
				array("value"=>"peer","display_name"=>gettext("For peer certificates only")),
				array("value"=>"all","display_name"=>gettext("For entire certificate chain")),
				)
			);
	}

	/** Show olcTLSVerifyClient attribute (data type "openldap_clicrt")

	    This attribute is used to specify whether TLS client certificates are
	    requested/required by an OpenLDAP server before accepting a connection.

	    The attribute is defined in the "openldap/config" schema.

	    @todo accept "hard" and "true" as synonyms for "demand"
	*/

	function show_openldap_clicrt()
	{
		$this->show_enum(
			array(
				array("value"=>"never","display_name"=>gettext("Don't request client certificate")),
				array("value"=>"allow","display_name"=>gettext("Request client certificate, but allow connection if invalid or not provided")),
				array("value"=>"try","display_name"=>gettext("Reject invalid client certificate, allow connection if not provided")),
				array("value"=>"demand","display_name"=>gettext("Require valid client certificate")),
				)
			);
	}

	/** Show olcAuthzPolicy attribute (data type "openldap_authpol")

	    This attribute is used to specify the proxy authorization policy of an
	    OpenLDAP server.

	    The attribute is defined in the "openldap/config" schema.

	    @todo accept "both" as synonyms for "all"
	*/

	function show_openldap_authpol()
	{
		$this->show_enum(
			array(
				array("value"=>"none","display_name"=>gettext("Disable proxy authorization")),
				array("value"=>"from","display_name"=>gettext("Local user must match an authorizaton (authzFrom) rule")),
				array("value"=>"to","display_name"=>gettext("Remote user must match an authorization (authzTo) rule")),
				array("value"=>"any","display_name"=>gettext("Either the local or remote user must match an authorization rule")),
				array("value"=>"all","display_name"=>gettext("Both the local and remote user must match authorization rules")),
				)
			);
	}

	/** Show olcSubordinate attribute (data type "openldap_subord")

	    This attribute is used to configure an OpenLDAP database for a
	    subset another database's directory tree on the same server.

	    The attribute is defined in the "openldap/config" schema.
	*/

	function show_openldap_subord()
	{
		$this->show_enum(
			array(
				array("value"=>"FALSE","display_name"=>gettext("No")),
				array("value"=>"TRUE","display_name"=>gettext("Yes (Don't list this database in the Root DSE Object)")),
				array("value"=>"advertise","display_name"=>gettext("Yes (List this database in the Root DSE Object)"))
				)
			);
	}


	/** Show nestedConfig attribute (data type "nested_config")

	    This attribute indicates whether eDirectory group nesting should
	    be enforced/enabled

		- 0 - local server nesting
		- 1 - no nesting

	    The attribute is defined in the "novell/nestgrp" schema.
	*/

	function show_nested_config()
	{
		$this->show_enum(
			array(
				array("value"=>"0","display_name"=>gettext("Local Server Nesting")),
				array("value"=>"1","display_name"=>gettext("No Nesting"))
				)
			);
	}

	/** Show olcMemberOfDangling attribute (data type "olc_dangling") */

	function show_olc_dangling()
	{
		$this->show_enum(
			array(
				array("value"=>"ignore","display_name"=>gettext("Leave the dangling reference in place (ignore)")),
				array("value"=>"drop","display_name"=>gettext("Remove the dangling reference (drop)")),
				array("value"=>"error","display_name"=>gettext("Generate an LDAP error"))
				)
			);
	}

	/** Show olcPcachePosition attribute (data type "olc_pcachepos") */

	function show_olc_pcachepos()
	{
		$this->show_enum(
			array(
				array("value"=>"tail","display_name"=>gettext("Before Other Overlay Response Callbacks")),
				array("value"=>"head","display_name"=>gettext("After Other Overlay Response Callbacks"))
				)
			);
	}

	/** Show pwdCheckQuality attribute (data type "pwd_qualitycheck") */

	function show_pwd_qualitycheck()
	{
		$this->show_enum(
			array(
				array("value"=>"0","display_name"=>gettext("Disabled")),
				array("value"=>"1","display_name"=>gettext("Check password quality if possible, otherwise accept")),
				array("value"=>"2","display_name"=>gettext("New passwords must pass quality check"))
				)
			);
	}

	/** Show nfapCIFSAuthent attribute (data type "nfap_authent") */

	function show_nfap_authent()
	{
		$this->show_enum(
			array(
				array("value"=>"1","display_name"=>gettext("Local")),
				array("value"=>"2","display_name"=>gettext("Domain"))
				)
			);
	}

	/** Show nfapCIFSDialect attribute (data type "nfap_dialect") */

	function show_nfap_dialect()
	{
		$this->show_enum(
			array(
				array("value"=>"0","display_name"=>gettext("PC")),
				array("value"=>"4","display_name"=>gettext("LM_NT_12"))
				)
			);
	}

	/** Show nfapCIFSSignatures attribute (data type "nfap_signing") */

	function show_nfap_signing()
	{
		$this->show_enum(
			array(
				array("value"=>"0","display_name"=>gettext("Disabled")),
				array("value"=>"1","display_name"=>gettext("Optional")),
				array("value"=>"2","display_name"=>gettext("Mandatory"))
				)
			);
	}

	/** Show olcModuleList attribute (data type "openldap_module")

	    @todo support editing when attribute has more than one assigned value
	*/

	function show_openldap_module()
	{
		global $openldap_overlay_module,$openldap_backend_module;

		if(!isset($this->ldap_entry[strtolower($this->attribute)]["count"]))
			$this->ldap_entry[strtolower($this->attribute)]["count"]
				= (isset($this->ldap_entry[strtolower($this->attribute)]) ? 1 : 0);

		if($this->ldap_entry[strtolower($this->attribute)]["count"] > 1)
		{
			echo "<ul style=\"margin:0px;list-style-type:none;padding:0px\">";

			foreach($this->ldap_entry[strtolower($this->attribute)] as $key=>$value)
				if(empty($key) || $key != "count")
				{
					$value = substr($value,strpos($value,"}")+1);
					echo "<li>" . urls_to_links(htmlentities($value,ENT_COMPAT,"UTF-8"));

					if(isset($openldap_overlay_module[$value]))
						echo " - " . $openldap_overlay_module[$value];

					if(isset($openldap_backend_module[$value]))
						echo " - " . $openldap_backend_module[$value];

					echo "</li>";
				}
			echo "</ul>";
		}
		else
		{
			foreach($openldap_overlay_module as $name => $description)
				$codes[] = array("value"=>"{0}" . $name,"display_name"=>$name . " - " . $description);

			foreach($openldap_backend_module as $name => $description)
				$codes[] = array("value"=>"{0}" . $name,"display_name"=>$name . " - " . $description);

			asort($codes);

			$this->show_enum($codes);
		}
	}

	/** Show olcBackend attribute (data type "openldap_backend") */

	function show_openldap_backend()
	{
		global $openldap_backend_module;

		if(empty($this->ldap_entry[strtolower($this->attribute)]["count"]))
			$this->ldap_entry[strtolower($this->attribute)]["count"]=0;

		if($this->edit)
		{
			foreach($openldap_backend_module as $name => $description)
			{
				$name = substr($name,strpos($name,"back_")+5);
				$codes[] = array("value"=>$name,
					"display_name"=>$name . " - " . $description);
			}

			asort($codes);

			$this->show_enum($codes);
		}
		else
		{
			if(isset($this->ldap_entry[strtolower($this->attribute)][0]))
				echo htmlentities($this->ldap_entry[strtolower($this->attribute)][0],
					ENT_COMPAT,"UTF-8");
			else
				echo "(" . gettext("none") . ")";
		}
	}

	/** Show LDAP result code attribute (data type "ldap_result") */

	function show_ldap_result()
	{
		global $ldap_result_code;
		asort($ldap_result_code);

		foreach($ldap_result_code as $code => $name)
			$codes[] = array("value"=>$code,"display_name"=>$name);

		$this->show_enum($codes);
	}

	/** Show LDAP version attribute (data type "ldap_version")

	    @todo
		Style this better.. should be 100% less a fixed number of pixels?
	    @todo
		Support editing
	*/

	function show_ldap_version()
	{
		if(!empty($this->ldap_entry[strtolower($this->attribute)]))
		{
			echo "<ul style=\"margin:0px;list-style-type:none;padding:0px\">";

			foreach($this->ldap_entry[strtolower($this->attribute)] as $key=>$value)
				if(empty($key) || $key != "count")
				{
					switch($value)
					{
						case "1": $display_value = gettext("LDAP v1"); break;	// rarely used
						case "2": $display_value = gettext("LDAP v2"); break;
						case "3": $display_value = gettext("LDAP v3"); break;
						default: $display_value = gettext("Unrecognised value:") . " " . $value;
					}
					echo "<li>" . urls_to_links(htmlentities($display_value,ENT_COMPAT,"UTF-8")) . "</li>";
				}
			echo "</ul>";
		}
	}

	/** Show LDAP search scope attribute (data type "search_scope")

	    @see http://www.ietf.org/rfc/rfc4516.txt
	    @see https://tools.ietf.org/html/draft-sermersheim-ldap-subordinate-scope-02
	*/

	function show_search_scope()
	{
		$this->show_enum(
			array(
				array("value"=>"base","display_name"=>gettext("Base Object Only")),
				array("value"=>"one","display_name"=>gettext("Single Level")),
				array("value"=>"sub","display_name"=>gettext("Whole Subtree")),
				array("value"=>"subord","display_name"=>gettext("Subordinate Subtree"))
				)
			);
	}

	/** Show alias dereferencing behaviour attribute (data type "alias_deref") */

	function show_alias_deref()
	{
		$this->show_enum(
			array(
				array("value"=>"never","display_name"=>gettext("Never dereference aliases")),
				array("value"=>"searching","display_name"=>gettext("Dereference aliases when searching")),
				array("value"=>"finding","display_name"=>gettext("Dereference aliases to find the base object for the search")),
				array("value"=>"always","display_name"=>gettext("Always dereference aliases"))
				)
			);
	}

	/** Show mailPreferenceOption attribute (data type "use_html_mail")

	    This attribute indicates a person's preference for inclusion in
	    mailing lists. If no value is given then the record should be
	    processed as if the value was 0, i.e. don't include in mailing
	    lists.

		- 0 - no-list-inclusion
		- 1 - any-list-inclusion
		- 2 - professional-list-inclusion

	    The attribute is defined in the COSINE schema.
	*/

	function show_mail_preference()
	{
		$this->show_enum(
			array(
				array("value"=>"","display_name"=>gettext("Not known")),
				array("value"=>"0","display_name"=>gettext("Don't include in mailing lists")),
				array("value"=>"1","display_name"=>gettext("Include in any mailing lists")),
				array("value"=>"2","display_name"=>gettext("Include in professional mailing lists only"))
				)
			);
	}

	/** Show boolean attribute, displayed as yes/no (data type "yes_no") */

	function show_boolean_yes_no()
	{
		$this->show_enum(
			array(
				array("value"=>"TRUE","display_name"=>gettext("Yes")),
				array("value"=>"FALSE","display_name"=>gettext("No"))
				)
			);
	}

	/** Show Active Directory functional level

	    These values represent Active Directory Domain, Forest and
	    Domain Controller functional levels. (The value 1 is not used
	    as a Domain Controller functional level.)
	*/

	function show_ad_func_level()
	{
		$this->show_enum(
			array(
				array("value"=>"0","display_name"=>gettext("Windows 2000")),
				array("value"=>"1","display_name"=>gettext("Windows Server 2003 Interrim")),
				array("value"=>"2","display_name"=>gettext("Windows Server 2003")),
				array("value"=>"3","display_name"=>gettext("Windows Server 2008")),
				array("value"=>"4","display_name"=>gettext("Windows Server 2008 R2")),
				array("value"=>"5","display_name"=>gettext("Windows Server 2012")),
				array("value"=>"6","display_name"=>gettext("Windows Server 2012 R2")),
				array("value"=>"7","display_name"=>gettext("Windows Server 2016"))
				)
			);
	}

	/** Show ISO 5218 gender code attribute (data type "gender")

		- 0 Not known
		- 1 Male
		- 2 Female
		- 9 Not specified
	*/

	function show_gender()
	{
		$this->show_enum(
			array(
				array("value"=>"0","display_name"=>gettext("Not known")),
				array("value"=>"1","display_name"=>gettext("Male")),
				array("value"=>"2","display_name"=>gettext("Female")),
				array("value"=>"9","display_name"=>gettext("Not specified"))
				)
			);
	}

	/** Show a general enumerated data type attribute

	    Called from other "show_" functions which implement specific
	    enumerations (boolean, gender, country code, etc)

	    @todo
		Style this better.. should be 100% less a fixed number of pixels?

	    @param array $enum
		Array describing the enumerated data type
	*/

	function show_enum($enum)
	{
		if($this->edit)
		{
			if($this->required)
				$style = "width:98%;border-color:red;border-style:solid";
			else
				$style = "width:98%;";

			echo "<select name=\"ldap_attribute_" . $this->attribute
				. "\" title=\"" . $this->display_name . "\" style=\"" . $style . "\">\n";

			foreach($enum as $enum_entry)
			{
				if($enum_entry["display_name"] == "")
					$enum_entry["display_name"] = "(" . gettext("blank") . ")";

				echo "              <option value=\"" . $enum_entry["value"] . "\""
					. ($this->value == $enum_entry["value"] ? " selected" : "")
					. ">" . $enum_entry["display_name"] . "</option>\n";
			}

			echo "</select>";
		}
		else
		{
			$found = false;
			foreach($enum as $enum_entry)
				if($this->value == $enum_entry["value"])
				{
					$found = true;
					echo $enum_entry["display_name"];
				}

			if(!$found)
				echo gettext("Unrecognised value:") . " "
					. (empty($this->value) ? "(" . gettext("none") . ")" : $this->value);
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
	*/

	function show_date()
	{
		global $date_format,$short_date_format;

		if(empty($date_format))
			$date_format="%A %d %B %Y";
		if(empty($short_date_format))
			$short_date_format="%d %b %Y";

		// Remove all non-numerics
		$attrib_value = preg_replace("/\D/","",$this->value);

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

			if($this->use_short_format)
				$formatted_date = strftime($short_date_format,$date);
			else
				$formatted_date = strftime($date_format,$date);
		}

		if($this->edit)
		{
			if($this->required)
				$style = "width:98%;border-color:red;border-style:solid";
			else
				$style = "width:98%;";

			echo "<input style=\"" . $style . "\" type=\"text\" id=\"ldap_attribute_"
				. $this->attribute . "\" name=\"ldap_attribute_"
				. $this->attribute . "\" value=\""
				. htmlentities($attrib_value,ENT_COMPAT,"UTF-8")
				. "\" title=\"" . $this->display_name . "\" placeholder=\""
				. $this->display_name . "\">";
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
	*/

	function show_date_time()
	{
		global $date_time_format,$short_date_time_format;

		if(empty($date_time_format))
			$date_time_format="%A %d %B %Y %H:%M:%S";
		if(empty($short_date_time_format))
			$short_date_time_format="%d %b %Y %H:%M:%S";

		if($this->edit)
		{
			if($this->required)
				$style = "width:98%;border-color:red;border-style:solid";
			else
				$style = "width:98%;";

			echo "<input style=\"" . $style . "\" type=\"text\" name=\"ldap_attribute_"
				. $this->attribute . "\" value=\""
				. htmlentities($this->value,ENT_COMPAT,"UTF-8")
				. "\" title=\"" . $this->display_name . "\" placeholder=\""
				. $this->display_name . "\">";
		}
		else
		{
			if($this->value == "")
				$formatted_date = "";
			else
			{
				$date = mktime(
					substr($this->value,8,2),	// hour
					substr($this->value,10,2),	// minute
					substr($this->value,12,2),	// second
					substr($this->value,4,2),	// month
					substr($this->value,6,2),	// day
					substr($this->value,0,4)	// year
					);

				if($this->use_short_format)
					$formatted_date = strftime($short_date_time_format,$date);
				else
					$formatted_date = strftime($date_time_format,$date);
			}

			echo htmlentities($formatted_date,ENT_COMPAT,"UTF-8");
		}
	}

	/** Show multi-value textual attribute (data type "text_list")

	    @todo
		Escape "nasty values" in $attrib_value, e.g. "
	    @todo
		Style this better.. should be 100% less a fixed number of pixels?
	    @todo
		Honour read-only setting by not displaying add/remove value buttons
	*/

	function show_text_list()
	{
		if(!empty($this->ldap_entry[strtolower($this->attribute)]))
		{
			echo "<ul style=\"margin:0px;list-style-type:none;padding:0px\">";

			foreach($this->ldap_entry[strtolower($this->attribute)] as $key=>$value)
				if(empty($key) || $key != "count")
				{
					echo "<li>" . urls_to_links(htmlentities($value,ENT_COMPAT,"UTF-8"));

					if(!$this->create && !$this->edit && !$this->read_only && $this->ldap_server->get_user_setting("allow_edit"))
						echo "&nbsp;<a href=\"delete_value.php?dn="
							. urlencode($this->ldap_entry["dn"])
							. "&attrib=" . urlencode($this->attribute)
							. "&value=" . urlencode($value)
							. ($this->ldap_server->server_id == 0 ? ""
							: ("&server_id=" . $this->ldap_server->server_id))
							. "\"><button>" . gettext("Remove") . "</button></a>\n";

					echo "</li>";
				}


			echo "</ul>";
		}

		if(!$this->edit && !$this->create && !$this->read_only && $this->ldap_server->get_user_setting("allow_edit") && $this->ldap_server->get_user_setting("allow_browse"))
			echo "            <a style=\"float:right\" href=\"add_text_value.php?target_dn="
				. urlencode($this->ldap_entry["dn"]) . "&attrib=" . urlencode($this->attribute)
				. ($this->ldap_server->server_id == 0 ? ""
				: ("&server_id=" . $this->ldap_server->server_id))
				. "\"><button>Add</button></a>\n";
	}

	/** Show OID macro definition list attribute (data type "oid_macro_list")

	    @todo
		Support editing
	*/

	function show_oid_macro_list()
	{
		if(empty($this->ldap_entry[strtolower($this->attribute)]))
			echo "(" . gettext("none") . ")";
		else
		{
			echo "<table>\n              <thead>\n                <tr>\n"
				. "                  <th style=\"width:1px;padding-right:1em;background-color:#e0e0e0;font-weight:bold\">"
				. gettext("Macro Name") . "</th>\n"
				. "                  <th style=\"background-color:#e0e0e0;font-weight:bold\">"
				. gettext("Definition") . "</th>\n"
				. "                </tr>\n              </thead>\n"
				. "              <tbody>\n";

			foreach($this->ldap_entry[strtolower($this->attribute)] as $key=>$value)
				if(empty($key) || $key != "count")
				{
					$oid_mapping = explode(" ",$value);
					echo "                <tr>\n";
					echo "                  <td style=\"width:1px;padding-right:1em\">"
						. htmlentities($oid_mapping[0],ENT_COMPAT,"UTF-8") . "</td>\n";
					echo "                  <td>"
						. htmlentities($oid_mapping[1],ENT_COMPAT,"UTF-8") . "</td>\n";
					echo "                </tr>\n";
				}
			echo "              </tbody>\n            </table>";
		}
	}

	/** Show LDAP schema attribute (data type "ldap_schema")

	    @todo
		Support editing
	*/

	function show_ldap_schema()
	{
		global $ldap_server;
		if(empty($this->ldap_entry[strtolower($this->attribute)]))
			echo "(" . gettext("none") . ")";
		else
		{
			echo "<table>\n";

			foreach($this->ldap_entry[strtolower($this->attribute)] as $key=>$value)
				if(empty($key) || $key != "count")
				{
					if(strpos($value,"{")===0)
						$value = substr($value,strpos($value,"}")+1);

					$schema_entry = parse_ldap_schema_entry($value);

					switch($this->attribute)
					{
						case "objectClasses":
						case "olcObjectClasses":
							$definition_type = "object";
							break;
						case "dITContentRules":
							$definition_type = "content_rule";
							break;
						case "attributeTypes":
						case "olcAttributeTypes":
							$definition_type = "attribute";	break;
						default:
							$definition_type = "unknown";
					}

					echo "              <tr>"
						. "\n                <td style=\"width:1px\">\n"
						. "                  ";

					switch($definition_type)
					{
						case "object":
							echo "<img alt=\""
								. gettext("Object Definition")
								. "\" src=\"schema/object.png\">";
							break;
						case "attribute":
							echo "<img alt=\""
								. gettext("Attribute Definition")
								. "\" src=\"schema/attribute.png\">";
							break;
						default:
							echo "<img alt=\""
								. gettext("Schema Definition")
								. "\" src=\"schema/generic24.png\">";
					}
					echo "\n                </td>\n"
						. "                <td colspan=2>\n"
						. "                  <span style=\"font-weight:bold\">"
						. htmlentities($schema_entry["name"],ENT_COMPAT,"UTF-8")
						. "</span>";

					$extra_info = "";

					if(isset($schema_entry["abstract"]))
						$extra_info = gettext("abstract class");

					if(isset($schema_entry["auxiliary"]))
						$extra_info = gettext("auxiliary class");

					if(isset($schema_entry["single-value"]))
						$extra_info = gettext("single valued attribute");

					if(isset($schema_entry["no-user-modification"]))
					{
						if(!empty($extra_info)) $extra_info .= ", ";
						$extra_info .= gettext("no user modification");
					}

					if(isset($schema_entry["obsolete"]))
					{
						if(!empty($extra_info)) $extra_info .= ", ";
						$extra_info .= gettext("obsolete definition");
					}

					if(isset($schema_entry["system-only"]))	// AD specific
					{
						if(!empty($extra_info)) $extra_info .= ", ";
						$extra_info .= gettext("system only");
					}

					if(isset($schema_entry["indexed"]))	// AD specific
					{
						if(!empty($extra_info)) $extra_info .= ", ";
						$extra_info .= gettext("indexed");
					}

					if(count($schema_entry["aliases"])>0)
					{
						if(!empty($extra_info)) $extra_info .= ", ";
						$extra_info .= gettext("also named") . ": ";
						$first_entry = true;
						foreach($schema_entry["aliases"] as $alias)
						{
							if(!$first_entry) echo ", ";
							$extra_info .= $alias;
							$first_entry = false;
						}
					}

					if(!empty($extra_info))
						echo " (" . $extra_info . ")";

					echo "\n                </td>\n";
					echo "              </tr>\n";
					$this->show_ldap_schema_setting($schema_entry,
						gettext("Description"),"DESC");
					$this->show_ldap_schema_setting($schema_entry,
						gettext("Subclass Of"),"SUP");
					$this->show_ldap_schema_setting($schema_entry,
						gettext("OID"),"OID");
					if($definition_type == "object" || $definition_type == "content_rule")
					{
						$this->show_ldap_schema_setting($schema_entry,
							gettext("Required Attributes"),"MUST");
						$this->show_ldap_schema_setting($schema_entry,
							gettext("Optional Attributes"),"MAY");
					}

					if($definition_type == "content_rule")
					{
						if($ldap_server->server_type != "ad")
							$this->show_ldap_schema_setting($schema_entry,
								gettext("Disallowed Attributes"),"NOT");
						$this->show_ldap_schema_setting($schema_entry,
							gettext("Permitted Auxiliary Classes"),"AUX");
					}

					$this->show_ldap_schema_setting($schema_entry,
						gettext("Syntax"),"SYNTAX");
					$this->show_ldap_schema_setting($schema_entry,
						gettext("Equality Matching"),"EQUALITY");
					$this->show_ldap_schema_setting($schema_entry,
						gettext("Substring Matching"),"SUBSTR");
					$this->show_ldap_schema_setting($schema_entry,
						gettext("Usage"),"USAGE");

					$this->show_ldap_schema_setting($schema_entry,
						gettext("Applies To"),"APPLIES");

					/** OpenLDAP specific settings

					    Ordered entries/values settings (X-ORDERED) are described in the
					    following Internet draft:

					    @see https://tools.ietf.org/html/draft-chu-ldap-xordered-00

					    The other settings are not published/standardised at this time.
					*/

					$this->show_ldap_schema_setting($schema_entry,
						gettext("Ordering Rule"),"X-ORDERED");
					$this->show_ldap_schema_setting($schema_entry,
						gettext("Not Human Readable"),"X-NOT-HUMAN-READABLE");
					$this->show_ldap_schema_setting($schema_entry,
						gettext("Binary Transfer Required"),"X-BINARY-TRANSFER-REQUIRED");

					/** NDS/eDirectory specific settings

					    As described in the following Internet draft:
					    @see https://tools.ietf.org/html/draft-sermersheim-nds-ldap-schema-03

					    The setting 'X-NDS_READ_FILTERED' is not described in the draft.
					    (Implemented at a later time?)

					    The setting 'X-NDS_NON_REMOVABLE' has an undercore after "NON" in
					    draft-sermersheim-nds-ldap-schema-03 but not in schema definitions
					    returned by implementation OES11 eDirectory. This function will
					    display either/both spellings.
					*/

					$this->show_ldap_schema_setting($schema_entry,
						gettext("Legacy NDS Name"),"X-NDS_NAME");

					$this->show_ldap_schema_setting($schema_entry,
						gettext("Non-Removable"),"X-NDS_NON_REMOVABLE");	// spelt as per draft
					$this->show_ldap_schema_setting($schema_entry,
						gettext("Non-Removable"),"X-NDS_NONREMOVABLE");		// spelt as per OES11

					$this->show_ldap_schema_setting($schema_entry,
						gettext("ACL Templates"),"X-NDS_ACL_TEMPLATES");
					$this->show_ldap_schema_setting($schema_entry,
						gettext("Naming Attributes"),"X-NDS_NAMING");
					$this->show_ldap_schema_setting($schema_entry,
						gettext("Can be Contained In"),"X-NDS_CONTAINMENT");
					$this->show_ldap_schema_setting($schema_entry,
						gettext("Not a Container"),"X-NDS_NOT_CONTAINER");
					$this->show_ldap_schema_setting($schema_entry,
						gettext("Lower Bound"),"X-NDS_LOWER_BOUND");
					$this->show_ldap_schema_setting($schema_entry,
						gettext("Upper Bound"),"X-NDS_UPPER_BOUND");
					$this->show_ldap_schema_setting($schema_entry,
						gettext("Readable by Public"),"X-NDS_PUBLIC_READ");
					$this->show_ldap_schema_setting($schema_entry,
						gettext("Readable by Server Objects"),"X-NDS_SERVER_READ");
					$this->show_ldap_schema_setting($schema_entry,
						gettext("Name Value Access (Write Managed)"),"X-NDS_NAME_VALUE_ACCESS");
					$this->show_ldap_schema_setting($schema_entry,
						gettext("Name Value Access (Both Managed)"),"X-NDS_BOTH_MANAGED");
					$this->show_ldap_schema_setting($schema_entry,
						gettext("Never Synchronise to Replicas"),"X-NDS_NEVER_SYNC");
					$this->show_ldap_schema_setting($schema_entry,
						gettext("Don't Schedule Synchronisation when Updated"),"X-NDS_SYNC_SCHED_NEVER");
					$this->show_ldap_schema_setting($schema_entry,
						gettext("Don't Immediately Synchronise to Replicas"),"X-NDS_NOT_SCHED_SYNC_IMMEDIATE");
					$this->show_ldap_schema_setting($schema_entry,
						gettext("Encrypt when Synchronising to Replicas"),"X-NDS_ENCRYPTED_SYNC");
					$this->show_ldap_schema_setting($schema_entry,
						gettext("Required on Filtered Replicas"),"X-NDS_FILTERED_REQUIRED");
					$this->show_ldap_schema_setting($schema_entry,
						gettext("Required for External References on Filtered Replicas"),"X-NDS_FILTERED_OPTIONAL");
					$this->show_ldap_schema_setting($schema_entry,
						gettext("Hidden from User Operations"),"X-NDS_HIDDEN");
					$this->show_ldap_schema_setting($schema_entry,
						gettext("Legacy NDS Syntax"),"X-NDS_SYNTAX");
					$this->show_ldap_schema_setting($schema_entry,
						gettext("Read Filtered"),"X-NDS_READ_FILTERED");

					/** Active Directory specific settings */

					$this->show_ldap_schema_setting($schema_entry,
						gettext("Object Class GUID"),"CLASS-GUID");
					$this->show_ldap_schema_setting($schema_entry,
						gettext("Lower Bound"),"RANGE-LOWER");
					$this->show_ldap_schema_setting($schema_entry,
						gettext("Upper Bound"),"RANGE-UPPER");
					$this->show_ldap_schema_setting($schema_entry,
						gettext("Attribute Class GUID"),"PROPERTY-GUID");
					$this->show_ldap_schema_setting($schema_entry,
						gettext("Property Set GUID"),"PROPERTY-SET-GUID");

					if($definition_type == "object")
						$schema_settings = $ldap_server->get_object_schema_settings($schema_entry["name"]);
					else if($definition_type == "attribute")
					{
						$schema_settings = $ldap_server->get_attribute_schema_settings($schema_entry["name"]);
						if(empty($schema_settings))
							$schema_settings = $ldap_server->get_attribute_schema_settings($schema_entry["name"] . ";binary");
					}
					else
						$schema_settings = array();

					if(count($schema_settings)>0)
					{
						echo "<tr style=\"height:26px\"><td style=\"width:1px\"></td><td colspan=2 style=\"padding-left:2em;font-weight:bold\">"
							. gettext("Address Book schema settings:") . "</td></tr>";

						$this->show_ldap_schema_setting($schema_settings,
							gettext("Localised Display Name"),"display_name");
						$this->show_ldap_schema_setting($schema_settings,
							gettext("Class Aliases"),"alias_names");

						$this->show_ldap_schema_setting($schema_settings,
							gettext("Display as a Folder"),"is_folder");

						if($definition_type == "object" && isset($schema_entry["structural"]))
							echo "<tr><td style=\"width:1px\"></td><td style=\"width:1px;padding-left:2em;padding-right:1em;vertical-align:top;white-space:nowrap\">"
								. gettext("Naming Attributes") . "</td><td>"
								. $ldap_server->get_object_schema_setting($schema_entry["name"],"rdn_attrib")
								. "</td></tr>";

						$this->show_ldap_schema_setting($schema_settings,
							gettext("Users Can Create"),"can_create");
						$this->show_ldap_schema_setting($schema_settings,
							gettext("Create Method"),"create_method");
						$this->show_ldap_schema_setting($schema_settings,
							gettext("Other Required Attributes"),"required_attribs");
						$this->show_ldap_schema_setting($schema_settings,
							gettext("Can Create Within"),"contained_by");
						$this->show_ldap_schema_setting($schema_settings,
							gettext("Can Contain"),"can_contain");
						$this->show_ldap_schema_setting($schema_settings,
							gettext("Parent Class"),"parent_class");
						$this->show_ldap_schema_setting($schema_settings,
							gettext("Child Classes"),"child_class");
						$this->show_ldap_schema_setting($schema_settings,
							gettext("Icon"),"icon");

						// specific to attributes
						$this->show_ldap_schema_setting($schema_settings,
							gettext("Data Type Handler"),"data_type");
					}
				}
			echo "</table>";
		}
	}

	function show_ldap_schema_setting($schema_entry,$label,$setting)
	{
		global $oid_name;

		$setting = strtolower($setting);
		if(!isset($schema_entry[$setting]) && in_array($setting,array("must","may","not")))
			$schema_entry[$setting] = "(" . gettext("none") . ")";

		if(isset($schema_entry[$setting]))
		{
			if($setting == "icon")
				$schema_entry[$setting] = "<img src=\"schema/" . $schema_entry[$setting] . "\"> " . $schema_entry[$setting];

			if($setting == "is_folder" || $setting == "can_create")
				$schema_entry[$setting] = $schema_entry[$setting] == 1 ? "TRUE" : "FALSE";

			if(in_array($setting,array("child_class","parent_class","can_contain","contained_by")))
				$schema_entry[$setting] = str_replace(",",", ",$schema_entry[$setting]);

			if($setting == "syntax" && is_numeric($schema_entry[$setting][0]))
			{
				if(strpos($schema_entry[$setting],"{"))
				{
					$oid_number=substr($schema_entry[$setting],
						0,strpos($schema_entry[$setting],"{"));
					$max_length=substr($schema_entry[$setting],
						strpos($schema_entry[$setting],"{")+1,-1);
				}
				else
				{
					$oid_number=$schema_entry[$setting];
					$max_length=false;
				}

				if(isset($oid_name[$oid_number]))
					$schema_entry[$setting] = $oid_name[$oid_number]
						. ($max_length ? " (maximum length "
						. $max_length . ")" : "");
			}

			echo "              <tr>\n                <td style=\"width:1px\"></td>"
				. "\n                <td style=\"width:1px;padding-left:2em;padding-right:1em;vertical-align:top;white-space:nowrap\">\n                  ";
			echo $label . "\n                </td>\n                <td>\n                  ";
			if(is_array($schema_entry[$setting]))
			{
			$first_value = true;
				foreach($schema_entry[$setting] as $value)
				{
					if(!$first_value) echo ", ";
					echo $value;
					$first_value = false;
				}
			}
			else
				echo $schema_entry[$setting];
			echo "\n                </td>\n              </tr>\n";
		}
	}

	/** Show list of ASN.1/X.690 object identifier attribute (data type "oid_list")

	    @todo
		Style this better.. should be 100% less a fixed number of pixels?
	    @todo
		Support editing
	*/

	function show_oid_list()
	{
		global $oid_name;

		if(!empty($this->ldap_entry[strtolower($this->attribute)]))
		{
			echo "<ul style=\"margin:0px;list-style-type:none;padding:0px\">";

			foreach($this->ldap_entry[strtolower($this->attribute)] as $key=>$value)
				if(empty($key) || $key != "count")
					if(isset($oid_name[$value]))
						echo "<li>" . htmlentities($oid_name[$value] . " (" . $value . ")",ENT_COMPAT,"UTF-8") . "</li>";
					else
						echo "<li>" . htmlentities($value,ENT_COMPAT,"UTF-8") . "</li>";
			echo "</ul>";
		}
	}

	/** Show single-value LDAP DN attribute (data type "dn")

	    Implemented as a special case of show_dn_list()
	*/

	function show_dn()
	{
		$this->show_dn_list("dn");
	}

	/** Show list of LDAP DN attribute (data type "dn_list")

	    @param string $attrib_type
		Optionally indicates the type of attribute being shown:

		- dn - single-valued DN attribute
		- dn_list - multi-valued DN attribute (default)

	    @todo
		Escape "nasty values" in $attrib_value, e.g. "
	    @todo
		Style this better.. should be 100% less a fixed number of pixels?
	*/

	function show_dn_list($attrib_type = "dn_list")
	{
		$has_values = !empty($this->ldap_entry[strtolower($this->attribute)]);

		if($attrib_type == "dn")
			$button_caption = ($has_values ? gettext("Change") : gettext("Add"));
		else
			$button_caption = gettext("Add");

		if($has_values)
		{
			$first_item = true;
			foreach($this->ldap_entry[strtolower($this->attribute)] as $key=>$value)
				if(empty($key) || $key != "count")
				{
					if(!$first_item) echo "\n            <br>\n            ";
					$first_item = false;
					// retrieve object class icon
					$search_resource = @ldap_read($this->ldap_server->connection,$value,"(objectclass=*)");

					if($search_resource)
					{
						$entry = ldap_get_entries($this->ldap_server->connection,$search_resource);

						if($entry["count"]==0)
						{
							// used if read operation returned not results (e.g no read permission)
							$icon = "schema/generic24.png";
							$alt_text = "Address Book Entry";
							$is_folder = false;
						}
						else
						{
							// assign an object class for eDirectory tree root (not defined
							// by default)
							if($this->ldap_server->server_type=="edir" && $entry[0]["dn"] == "" && !isset($entry[0]["objectclass"]))
							{
								$entry[0][$entry[0]["count"]] = "objectclass";
								$entry[0]["objectclass"][0] = "treeRoot";
								$entry[0]["count"]++;
							}
							$icon = $this->ldap_server->get_icon_for_ldap_entry($entry[0]);
							$item_object_class = $this->ldap_server->get_object_class($entry[0]);
							$is_folder = $this->ldap_server->get_object_schema_setting(
								$item_object_class,"is_folder");

							$alt_text = $item_object_class;
						}
					}
					else
					{
						$icon = "schema/generic24.png";
						$alt_text = "Address Book Entry";
						$is_folder = false;
					}

					$rdn_list = ldap_explode_dn2($value);

					if(!empty($rdn_list["0"]["value"]))
						$value_display_name = $rdn_list["0"]["value"];
					else
						$value_display_name = "[ROOT]";

					echo "<img style=\"vertical-align:middle\" alt=\""
						. $alt_text . "\" title=\"" . $alt_text
						. "\" src=\"" . $icon . "\">\n            ";

					if($this->show_embedded_links &&
						($this->ldap_server->compare_dn_to_base($value,$this->ldap_server->base_dn)
						|| $this->ldap_server->get_user_setting("allow_system_admin")))
					{
						if($is_folder)
							echo "<a href=\"" . current_page_folder_url() . "?dn=";
						else
							echo "<a href=\"info.php?dn=";
						echo urlencode($value);
						if($this->ldap_server->server_id != 0)
							echo "&server_id=" . $this->ldap_server->server_id;

						echo "\">" . htmlentities($value_display_name,ENT_COMPAT,"UTF-8") . "</a>";
					}
					else
						echo $value_display_name;

					if(!$this->edit && !$this->read_only && !$this->create && $this->ldap_server->get_user_setting("allow_edit"))
						echo "&nbsp;<a href=\"delete_value.php?dn="
							. urlencode($this->ldap_entry["dn"])
							. "&attrib=" . urlencode($this->attribute)
							. "&value=" . urlencode($value)
							. ($this->ldap_server->server_id == 0 ? ""
							: ("&server_id=" . $this->ldap_server->server_id))
							. "\"><button>" . gettext("Remove") . "</button></a>\n";
				}
		}
		else
			echo "<span style=\"line-height:24px\">(" . gettext("none") . ")</span>\n";

		if(!$this->edit && !$this->read_only && !$this->create && $this->ldap_server->get_user_setting("allow_edit")
			&& $this->ldap_server->get_user_setting("allow_browse"))
		{
			echo "            <a style=\"float:right\" href=\"add_dn_value.php?target_dn="
				. urlencode($this->ldap_entry["dn"]) . "&attrib=" . urlencode($this->attribute);
			if($this->ldap_server->server_id !=0)
				echo "&server_id=" . $this->ldap_server->server_id;
			echo "\"><button>" . $button_caption . "</button></a>\n";
		}
	}

	/** Show child objects (data type "child_objects")

	    @todo
		Escape "nasty values" in $attrib_value, e.g. "
	    @todo
		Style this better.. should be 100% less a fixed number of pixels?
	    @todo
		Support editing
	*/

	function show_child_objects()
	{
		global $openldap_overlay_module,$openldap_backend_module;

		$search_resource = @ldap_list($this->ldap_server->connection,
			$this->ldap_entry["dn"],"(objectclass=*)");

		if($search_resource && !$this->create)
		{
			$child_entries = ldap_get_entries($this->ldap_server->connection,$search_resource);
			if($child_entries["count"]>0)
				foreach($child_entries as $child_entry)
				{
					if(is_array($child_entry))
					{
						$icon = $this->ldap_server->get_icon_for_ldap_entry($child_entry);
						$item_object_class = $this->ldap_server->get_object_class($child_entry);
						$rdn_list = ldap_explode_dn2($child_entry["dn"]);
						$value_display_name = $rdn_list["0"]["value"];
						$alt_text = $item_object_class;

						$is_folder = $this->ldap_server->get_object_schema_setting(
							$item_object_class,"is_folder");

						echo "<img alt=\"" . $alt_text . "\" title=\"" . $alt_text
							. "\" src=\"" . $icon . "\"> ";

						if($this->show_embedded_links &&
							($this->ldap_server->compare_dn_to_base($child_entry["dn"],
							$this->ldap_server->base_dn)
							|| $this->ldap_server->get_user_setting("allow_system_admin")))
						{
							if($is_folder)
								echo "<a href=\"" . current_page_folder_url() . "?dn=";
							else
								echo "<a href=\"info.php?dn=";
							echo urlencode($child_entry["dn"])
								. ($this->ldap_server->server_id == 0 ? ""
								: ("&server_id=" . $this->ldap_server->server_id))
								. "\">"
								. htmlentities($value_display_name,ENT_COMPAT,"UTF-8")
								. "</a>";
						}
						else
							echo htmlentities($value_display_name,ENT_COMPAT,"UTF-8");

						// Append OpenLDAP database naming context if set
						if(!empty($child_entry["olcsuffix"][0]))
							echo " " . sprintf(gettext("for '%s'"),
								"<a href=\"" . current_page_folder_url() . "?dn="
								. urlencode($child_entry["olcsuffix"][0]) . "\">"
								. htmlentities($child_entry["olcsuffix"][0],
								ENT_COMPAT,"UTF-8") . "</a>");

						// Append OpenLDAP database directory location if set
						if(!empty($child_entry["olcdbdirectory"][0]))
							echo " " . sprintf(gettext("at '%s'"),
								htmlentities($child_entry["olcdbdirectory"][0],
								ENT_COMPAT,"UTF-8"));

			                        // Append OpenLDAP database URI if set
						if(!empty($child_entry["olcdburi"][0]))
			                                echo " " . sprintf(gettext("from '%s'"),
								htmlentities($child_entry["olcdburi"][0],
								ENT_COMPAT,"UTF-8"));

			                        // Append OpenLDAP module path if set
						if(!empty($child_entry["olcmodulepath"][0]))
			                                echo " " . sprintf(gettext("loading from '%s'"),
								htmlentities($child_entry["olcmodulepath"][0],
								ENT_COMPAT,"UTF-8"));

						// Append OpenLDAP module names to be loaded if set
						if(!empty($child_entry["olcmoduleload"][0]))
						{
							echo "<table style=\"margin-top:4pt;margin-left:40pt\">";
							$first_value = true;
							for($i=0;$i<$child_entry["olcmoduleload"]["count"];$i++)
							{
								echo "<tr>";
								$module_name = explode("}",
									$child_entry["olcmoduleload"][$i]);
								$module_name = $module_name[1];

								echo "<td style=\"width:0px;white-space:nowrap\">"
									. "&bull; " . htmlentities($module_name,
									ENT_COMPAT,"UTF-8") . "</td>";

								if(isset($openldap_overlay_module[$module_name]))
									echo "<td>&nbsp;- " . htmlentities(
										$openldap_overlay_module[$module_name],
										ENT_COMPAT,"UTF-8") . "</td>";
								else if(isset($openldap_backend_module[$module_name]))
									echo "<td>&nbsp;- " . htmlentities(
										$openldap_backend_module[$module_name],
										ENT_COMPAT,"UTF-8") . "</td>";

								echo "</tr>";
							}
							echo "</table>";
						}

						echo "<br>";
					}
				}
			else
				echo "(" . gettext("none") . ")";
		}
		else
			echo "(" . gettext("none") . ")";

		if(!$this->edit && $this->ldap_server->get_user_setting("allow_create") && !$this->create)
			echo "<br><a href=\"create.php?dn="
				. urlencode($this->ldap_entry["dn"])
				. ($this->ldap_server->server_id == 0 ? "" : ("&server_id=" . $this->ldap_server->server_id))
				. "\"><button>" . gettext("Add") . "</button></a>\n";
	}

	/** Show telephone number (data type "phone_number")

	    @todo
		Escape "nasty values" in $attrib_value, e.g. "
	    @todo
		Style this better.. should be 100% less a fixed number of pixels?
	*/

	function show_phone_number()
	{
		if($this->edit)
		{
			if($this->required)
				$style = "width:98%;border-color:red;border-style:solid";
			else
				$style = "width:98%;";

			echo "<input style=\"" . $style . "\" type=\"text\" name=\"ldap_attribute_"
				. $this->attribute . "\" value=\""
				. htmlentities($this->value,ENT_COMPAT,"UTF-8")
				. "\" title=\"" . $this->display_name . "\" placeholder=\""
				. $this->display_name . "\">";
		}
		else
		{
			if($this->show_embedded_links)
				show_phone_number_formatted($this->value);
			else
				echo htmlentities($this->value,ENT_COMPAT,"UTF-8");;
		}
	}

	/** Show multi-line textual attribute (data type "text_area")

	    @todo
		Escape "nasty values" in $attrib_value, e.g. "
	    @todo
		Style this better.. should be 100% less a fixed number of pixels?
	*/

	function show_text_area()
	{
		if($this->edit)
		{
			if($this->required)
				$style = "width:98%;border-color:red;border-style:solid";
			else
				$style = "width:98%;";

			echo "\n            <textarea style=\"" . $style . "\" name=\"ldap_attribute_"
				. $this->attribute . "\" title=\"" . $this->display_name
				. "\" placeholder=\"" . $this->display_name . "\">"
				. htmlentities($this->value,ENT_COMPAT,"UTF-8")
				. "</textarea>";
		}
		else
			if($this->show_embedded_links)
				echo nl2br(urls_to_links(htmlentities($this->value,ENT_COMPAT,"UTF-8")),false);
			else
				echo nl2br(htmlentities($this->value,ENT_COMPAT,"UTF-8"),false);
	}

	/** Show ISO 3166-1 alpha-2 country code attribute (data type "country_code")

	    @todo
		Improve handling of unrecognised country codes
	*/

	function show_country_code()
	{
		global $country_name;

		if(empty($country_name)) init_country_names();

		asort($country_name);

		$countries = array(
			array("value"=>"","display_name"=>"")
			);

		foreach($country_name as $code => $name)
			$countries[] = array("value"=>$code,"display_name"=>$name);

		$this->show_enum($countries);
	}

	/** Show postcode attribute (data type "postcode")

	    Renders a poscode attribute - single line text, with an
	    adjacent button to display location in mapping service

	    @todo
		Escape "nasty values" in $attrib_value, e.g. "
	    @todo
		Style this better.. should be 100% less a fixed number of pixels?
	    @todo make mapping service configurable (not just Google)
	*/

	function show_postcode()
	{
		if($this->edit)
		{
			if($this->required)
				$style = "width:98%;border-color:red;border-style:solid";
			else
				$style = "width:98%;";

			echo "<input style=\"" . $style . "\" type=\"text\" name=\"ldap_attribute_"
				. $this->attribute . "\" value=\""
				. htmlentities($this->value,ENT_COMPAT,"UTF-8")
				. "\" title=\"" . $this->display_name . "\" placeholder=\"" . $this->display_name . "\">";
		}
		else
			if($this->value != "")
			{
				echo htmlentities($this->value,ENT_COMPAT,"UTF-8");

				if($this->show_embedded_links)
					echo "&nbsp;(<a href=\"https://maps.google.co.uk/?q="
						. urlencode($this->value) . "\" target=\"_blank\">" . gettext("View map") . "</a>)";
			}
	}

	/** Show image attribute (data type "image") */

	function show_image()
	{
		global $photo_image_size;

		if($this->value != "")
		{
			if(!empty($photo_image_size))
				$size = "&size="
					. $photo_image_size;
			else
				$size="";

			$server_id = $this->ldap_server->server_id == 0
				? "" : "&server_id=" . $this->ldap_server->server_id;

			echo "<img src=\"image.php?dn="
				. urlencode($this->ldap_entry["dn"])
				. "&attrib=" . $this->attribute . $size . $server_id
				. "\" title=\"" . $this->display_name . "\">\n";
		}

		if($this->edit)
		{
			// Don't show "Clear Image" button if attribute is mandatory
			if($this->value == "" || $this->required)
				echo "            <input type=\"hidden\" name=\"ldap_attribute_"
					. $this->attribute . "\" value=\"\">";
			else
				echo "            <br>\n            <input type=\"checkbox\" name=\"ldap_attribute_"
					. $this->attribute . "\">" . gettext("Clear Image") . "<br>\n";

			if($this->required)
				$style = "border-color:red;border-style:solid";
			else
				$style = "";

			echo "            <input style=\"" . $style . "\" type=\"file\" name=\"ldap_attribute_"
				. $this->attribute . "_file\" title=\"" . $this->display_name
				. "\" accept=\".jpg,.jpeg,.png,.gd2,.wbmp\">";
		}
	}

	/** Show download (e.g. for binary attribute)

	    @todo
		Editing support for this data type
	*/

	function show_download_list()
	{
		if(!empty($this->ldap_entry[strtolower($this->attribute)]))
		{
			if(isset($this->ldap_entry[strtolower($this->attribute)]["count"]))
			{
				// multi-valued attributes
				echo "<ul style=\"margin:0px;list-style-type:none;padding:0px\">";
				foreach($this->ldap_entry[strtolower($this->attribute)] as $key=>$value)
					if(empty($key) || $key != "count")
					{
						echo "<li><a href=\"download.php?dn="
							. urlencode($this->ldap_entry["dn"]) . "&attrib=" . $this->attribute
							. "&index=" . $key . "\"><button>";
						if($this->ldap_entry[strtolower($this->attribute)]["count"]==1)
							echo gettext("Download");
						else
							echo sprintf(gettext("Download Item %s"),$key+1);

						echo "</button></a></li>\n";
					}
				echo "</ul>";
			}
			else
				// single valued attributes
				if(empty($this->value))
					echo "Not set";
				else
					echo "<a href=\"download.php?dn="
						. urlencode($this->ldap_entry["dn"]) . "&attrib=" . $this->attribute
						. "\"><button>" . gettext("Download") . "</button></a>\n";
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

	show_ldap_path(null,"");
	if($ldap_server->per_user_login_enabled())
	{
		if(isset($_SESSION["LOGIN_USER"]))
			echo "<p>" . gettext("You do not have permission to log in to"
				. " the address book directory.") . "</p>\n"
				. "<p><a href=\"user.php\">"
				. gettext("Log in as a different user") . "</a></p>";
		else
			echo "<p><a href=\"user.php\">"
				. gettext("Please log in to use the address book.")
				. "</a></p>";
	}
	else
	{
		echo "<p>" . gettext("Unable to connect to address book directory."
			. " (LDAP bind failed)") . "</p>\n";

		// don't show this line if the user has already configured
		// a non-blank default user
		if($ldap_server->get_user_setting("ldap_dn","__ANONYMOUS__")=="")
			echo "<p>" . sprintf(gettext("If you have not already done so, please
				%sread the manual%s for
				instructions on how to configure directory
				access."),"<a href=\"doc/\">","</a>") . "</p>";
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

		return base64_decode($_SESSION["LOGIN_PASSWORD"]);
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
	global $ldap_server;

	$user = $ldap_server->get_user_setting("ldap_dn");

	if($ldap_server->get_user_setting("allow_login"))
		return 1;
	else
		return @ldap_bind_log($ldap_link,$user,
			get_ldap_bind_password()) ? 1 : 0;
}

/** Return the method to be used for merging group and user settings together.

    In order to determine the effective value of a setting:
	- Start with the user-assigned value of the setting
	- Consider each group that the user is a member of
	- Combine the group's settings (if any) with the user's
	  settings as follows

    string
	Replace the previous value of the setting

    boolean
	If the previous value of the string is explictly set to false
	then leave as-is, otherwise replace previous value (if any).

    @param string $setting
	Setting for which the merge method is to be returned
    @return
	Merge method to be used with this setting
*/

function get_user_setting_merge_method($setting)
{
	switch($setting)
	{
		case "ldap_dn":			return "string";		break;
		case "ldap_password":		return "string";		break;

		default:			return "boolean";
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
	/** LDAP object entries which are to be displayed */
	var $ldap_entries = array("count"=>0);

	/** Column layout used for displaying search results */
	var $search_result_columns;

	/** Whether the LDAP entry list contains search results */
	var $contains_search_results = false;

	/** Whether the LDAP entry list is for selecting an object_dn */
	var $object_dn_select_mode = false;

	/** Constructor

	    @param array $search_result_columns
		Search result column layout to use for display
	*/

	function __construct($search_result_columns)
	{
		$this->search_result_columns = $search_result_columns;
	}

	/** Add LDAP entries to list

	    @param object $ldap_server
		LDAP server containing the entries to be added
	    @param mixed $ldap_entries
		LDAP entries to be added to the list, either as a search resource
		or as an array of fetched LDAP entries
	*/

	function add_entries($ldap_server,$ldap_entries)
	{
		if(!is_array($ldap_entries))
			$ldap_entries = ldap_get_entries($ldap_server->connection,$ldap_entries);

		for($i=0;$i<$ldap_entries["count"];$i++)
		{
			// Reconstruct any missing RDN attributes using object DNs

			$dn_elements=ldap_explode_dn2($ldap_entries[$i]["dn"]);

			if(!isset($ldap_entries[$i][$dn_elements[0]["attrib"]]))
			{
				$ldap_entries[$i][$ldap_entries[$i]["count"]] = $dn_elements[0]["attrib"];
				$ldap_entries[$i][$dn_elements[0]["attrib"]]["count"] = 1;
				$ldap_entries[$i][$dn_elements[0]["attrib"]][0] = $dn_elements[0]["value"];
				$ldap_entries[$i]["count"]++;
			}

			// fixup missing object class on eDirectory rootDSE
			if($ldap_entries[$i]["dn"]== "" && $ldap_server->server_type=="edir")
				$ldap_entries[$i]["objectclass"]=array("treeRoot","top");

			// Add server ID associated with each record
			$ldap_entries[$i]["SERVER"] = &$ldap_server;
		}

		$new_count = $this->ldap_entries["count"] + $ldap_entries["count"];
		$this->ldap_entries = array_merge($this->ldap_entries,$ldap_entries);
		$this->ldap_entries["count"] = $new_count;
	}

	/** Sort the list of LDAP entries

	    @param string $sort_order
		LDAP attribute that the list should be sorted by
	*/

	function sort($sort_order)
	{
		$this->ldap_entries = ldap_sort_entries(
			$this->ldap_entries,
			$sort_order == "sortableName"
			? array("sn","givenName","ou","cn","reqStart")
			: array($sort_order),
			LDAP_SORT_ASCENDING);
	}

	/** Output address book contents as vCard

	    @param string $dn
		DN to use as basis of vCard filename
	*/

	function save_vcard($dn)
	{
		global $ldap_server,$site_name;

		if($dn == $ldap_server->base_dn)
			$filename = $site_name;
		else
		{
			$rdn_list = ldap_explode_dn2($dn);
			$filename = $rdn_list[0]["value"];
		}

		header("Content-Type: text/vcard");
		header("Content-Disposition: attachment; filename=\""
			. $filename . ".vcf\"");

		for($i=0;$i < $this->ldap_entries["count"]; $i++)
		{
			$vcard = new vcard($this->ldap_entries[$i]["SERVER"],
				$this->ldap_entries[$i]);
			echo $vcard->data . "\r\n";
		}
	}

	/** Output the object entry list as HTML */

	function show()
	{
		global $display_folders_separately;

		echo "<table class=\"search_results_viewer\">\n";

		if(isset($display_folders_separately) && $display_folders_separately)
		{
			$this->show_ldap_entries(ENTRY_LIST_SHOW_FOLDER_OBJECTS);
			$this->show_ldap_entries(ENTRY_LIST_SHOW_LEAF_OBJECTS);
		}
		else
			$this->show_ldap_entries(ENTRY_LIST_SHOW_ALL_OBJECTS);

		echo "</table>\n";

		if($this->ldap_entries["count"]==0)
			if($this->contains_search_results)
			{
				echo "<p>" . gettext("Your search did not match any records in the Address Book.") . "</p>"
					. "<p>" . gettext("Suggestions:") . "</p>"
					. "<ul>"
					. "<li>" . gettext("Make sure that all words are spelled correctly") . "</li>"
					. "<li>" . gettext("Search for different or more general keywords") . "</li>"
					. "</ul>"
					. "<p>" . gettext("You can search for text in any of the following fields:") . "</p>";

				show_searchable_attributes();
			}
			else
				echo "<p>" . gettext("This is an empty folder") . "</p>";
	}

	/** Show LDAP entries

	    @param integer $objects_to_show
		Specifies which type of objects are to be shown

		- ENTRY_LIST_SHOW_ALL_OBJECTS - All objects
		- ENTRY_LIST_SHOW_FOLDER_OBJECTS - Folder objects only
		- ENTRY_LIST_SHOW_LEAF_OBJECTS - Leaf (non-folder) objects
	*/

	function show_ldap_entries($objects_to_show)
	{
		$header_shown=false;
		for($i=0;$i < $this->ldap_entries["count"]; $i++)
		{
			$item_object_class =
				$this->ldap_entries[$i]["SERVER"]->get_object_class(
				$this->ldap_entries[$i]);
			$item_is_folder =
				$this->ldap_entries[$i]["SERVER"]->get_object_schema_setting(
				$item_object_class,"is_folder");

			switch($objects_to_show)
			{
				case ENTRY_LIST_SHOW_FOLDER_OBJECTS:
					$to_be_shown = $item_is_folder;
					break;
				case ENTRY_LIST_SHOW_LEAF_OBJECTS:
					$to_be_shown = !$item_is_folder;
					break;
				default:
					$to_be_shown = true;
			}

			if($to_be_shown)
			{
				if(!$header_shown)
				{
					if($objects_to_show == ENTRY_LIST_SHOW_FOLDER_OBJECTS)
						echo "  <tr>\n    <th class=\"column_header\" colspan=\""
							. (count($this->search_result_columns)+1)
							. "\">" . gettext("Folders") . "</th>\n  </tr>\n";
					else
						$this->show_column_headings();
					$header_shown = true;
				}
				$this->show_ldap_entry($this->ldap_entries[$i]);
			}
		}
	}

	/** Display column headings */

	function show_column_headings()
	{
		echo "  <tr>\n";

		$colspan="colspan=\"2\" ";
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

	    @param array $ldap_entry
		LDAP entry to display
	*/

	function show_ldap_entry($ldap_entry)
	{
		global $enable_search_browse_thumbnail,$thumbnail_image_size;
		echo "  <tr>\n";

		// Fetch object schema details for this record

		$item_object_class = $ldap_entry["SERVER"]->get_object_class($ldap_entry);

		$dn = $ldap_entry["dn"];

		$icon = $ldap_entry["SERVER"]->get_icon_for_ldap_entry($ldap_entry);

		$item_is_folder = $ldap_entry["SERVER"]->get_object_schema_setting(
			$item_object_class,"is_folder");
		$object_rdn_attrib = $ldap_entry["SERVER"]->get_object_schema_setting(
			$item_object_class,"rdn_attrib");

		// Item object class is displayed in the tooltip. All
		// inherited object classes should be listed where no
		// specific schema entry is recognised.

		if($item_object_class == "(" . gettext("unrecognised") . ")")
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

		if($item_is_folder)
		{
			if(isset($ldap_entry[$object_rdn_attrib][0]))
				// Display the folder name, and make it a link to
				// display the folder's contents.

				$this->show_attrib($ldap_entry,$object_rdn_attrib,
					$this->search_result_columns[0]["link_type"],$item_is_folder);
			else
			{
				// Display folder name where $object_rdn_attrib
				// doesn't correspond to a single attribute,
				// e.g. object has compound RDN.

				/** @todo support for add_dn_value link type behavior for objects with multi-attribute RDNs */
				/** @todo ability to delete - not currently implemented for objects with multi-attribute RDNs */

				$dn_elements=ldap_explode_dn2($ldap_entry["dn"]);
				echo "<td colspan=\"" . count($this->search_result_columns)
					. "\"><a href=\"?dn=" . urlencode($ldap_entry["dn"])
					. "\">" . htmlentities($dn_elements[0]["value"],ENT_COMPAT,"UTF-8") . "</a></td>";
			}
		}
		else
		{
			// Display user's chosen set of columns (attributes)

			foreach($this->search_result_columns as $column)
			{
				if(!isset($column["link_type"]))
					$column["link_type"]="none";

				// Don't make the cell a link to the object
				// if the user doesn't have view permissions
				if($column["link_type"] == "object"
						&& !$ldap_entry["SERVER"]->get_user_setting("allow_view"))
					$column["link_type"] = "none";

				$this->show_attrib($ldap_entry,
					$column["attrib"],
					$column["link_type"]);
			}
		}

		if(!$this->object_dn_select_mode && $ldap_entry["SERVER"]->get_user_setting("allow_delete"))
			echo "    <td style=\"width:1px;background-color:transparent\">\n      <a href=\"delete.php?dn="
				. urlencode($dn) . ($ldap_entry["SERVER"]->server_id==0 ? ""
				: ("&server_id=" . $ldap_entry["SERVER"]->server_id))
				. "\"><button>" . gettext("Delete") . "</button></a>\n    </td>\n";

		echo "  </tr>\n";
	}

	/** Display the specified attribute of an LDAP object.

	    This corresponds to an individual table cell in the search
	    results or OU being browsed

	    @todo
		support for compound attributes, e.g. address

            @param array $ldap_entry
                Array containing LDAP object entry which is to
                be displayed
	    @param string $attrib_name
		Name of attribute to be shown
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

	function show_attrib($ldap_entry,$attrib_name,$link_type,
		$is_folder = false)
	{
		global $thumbnail_image_size;

		if($is_folder)
			$colspan = " colspan=\""
				. count($this->search_result_columns)
				. "\"";
		else
			$colspan="";

		echo "    <td class=\""
			. ldap_attribute_to_css_class($attrib_name)
			. " search_results_attrib_" . $attrib_name . "\"" . $colspan . ">\n      ";

		// Display the attribute's value

		$attrib = new ldap_attribute($ldap_entry["SERVER"],$ldap_entry,$attrib_name);
		$attrib->use_short_format = true;
		$attrib->read_only = true;		// TODO: should not be necessary in longer term?

		$server_id_value=($ldap_entry["SERVER"]->server_id==0 ? ""
			: "&server_id=" . $ldap_entry["SERVER"]->server_id);

		if($link_type == "object")
		{
			// Cell contains a link to the object
			echo "<a href=\"" . ($is_folder ? "" : "info.php")
				. "?dn=" . urlencode($ldap_entry["dn"])
				. $server_id_value . "\">";

			$attrib->show_embedded_links=false;
			$attrib->show();

			echo "</a>";
		}
		else if($link_type == "add_dn_value")
		{
			if(isset($_GET["attrib"]))
				$attrib_name = $_GET["attrib"];
			else
			{
				echo "error: missing attribute name";
				$attrib_name = "";
			}

			if(isset($_GET["target_dn"]))
				$target_dn = $_GET["target_dn"];
			else
			{
				echo "error: missing target_dn";
				$target_dn = "";
			}

			// Cell contains a link to the object
			if($is_folder)
				echo "<a href=\"add_dn_value.php"
					. "?dn=" . urlencode($ldap_entry["dn"])
					. "&attrib=" . urlencode($attrib_name)
					. "&target_dn=" . urlencode($target_dn)
					. $server_id_value
					. "\">";

			else
				echo "<a href=\"add_dn_value.php"
					. "?dn=" . urlencode($ldap_entry["dn"])
					. "&attrib=" . urlencode($attrib_name)
					. "&target_dn=" . urlencode($target_dn)
					. $server_id_value
					. "&confirm=yes"
					. "\">";

			$attrib->show_embedded_links=false;
			$attrib->show();

			echo "</a>";
		}
		else
			$attrib->show();

		echo "\n    </td>\n";
	}
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
		array("name"=>"gd","desc"=>gettext("GD Support")),
		array("name"=>"intl","desc"=>gettext("Internationalization Support")),
		array("name"=>"ldap","desc"=>gettext("LDAP Support")),
		array("name"=>"mbstring","desc"=>gettext("Multibyte String Support"))
		);

	$missing_php_extn_list="";
	foreach($php_extn_list as $extn)
		if(!extension_loaded($extn["name"]))
			$missing_php_extn_list .= "<li>" . $extn["name"]
				. " (" . $extn["desc"] . ")";

	if(!empty($missing_php_extn_list))
	{
		show_ldap_path(null,"");

		echo "<p>" . gettext("The following PHP extension modules must be "
			. "installed and enabled in order to use the "
			. "address book:") . "</p>\n";

		echo "<ul>" . $missing_php_extn_list . "</ul>";

		echo "<p>" . gettext("Please see <em>Installation and Basic Setup</em> "
			. "in the User Guide for more information.") . "</p>";
	}
	return empty($missing_php_extn_list);
}

/** vCard */

class vcard
{
	/** String containing a vCard representation of the LDAP entry */
	var $data = "";

	/** Constructor

	    @param object $ldap_server
		LDAP server containing the entry to be converted
	    @param array $entry
		LDAP entry which is to be converted to vCard
	*/

	function __construct($ldap_server,$entry)
	{
		global $exclude_logo_if_photo_present;

		$this->add_property("BEGIN","VCARD");
		$this->add_property("VERSION","2.1");

		// Structured Name (N)

		if(isset($entry["sn"][0])) $sn = $entry["sn"][0]; else $sn = "";
		if(isset($entry["givenname"][0])) $givenname = $entry["givenname"][0]; else $givenname = "";

		$this->add_property("N",
			$sn . ";"		// family name
			. $givenname . ";"	// given name
			. "" . ";"		// additional names (not used)
			. "" . ";"		// honorific prefixes (not used)
			. "");			// honorific suffixes (not used)

		// Formatted Name (FN), as used for display purposes

		$rdn_attrib = $ldap_server->get_object_schema_setting(
			$ldap_server->get_object_class($entry)
			,"rdn_attrib");

		$rdn_list = explode(",",$rdn_attrib);

		$formatted_name = "";
		foreach($rdn_list as $rdn)
		{
			if($formatted_name != "") $formatted_name .= " + ";

			if(isset($entry[strtolower($rdn)][0]))
				$formatted_name .= $entry[strtolower($rdn)][0];
			else
				$formatted_name .= $entry[strtolower($rdn)];
		}

		$this->add_property("FN",$formatted_name);

		// Title

		if(isset($entry["title"][0]))
			$this->add_property("TITLE",$entry["title"][0]);

		// Organization and Organizational Unit

		if(isset($entry["company"][0]))
		{
			$org = $entry["company"][0];
			if(isset($entry["department"][0]))
				$org .= ";" . $entry["department"][0];
			$this->add_property("ORG",$org);
		}

		if(isset($entry["streetaddress"][0])) $streetaddress = $entry["streetaddress"][0]; else $streetaddress = "";

		// Microsoft Active Directory implements "street" and "streetAddress"
		// as separate attributes, both of which are used to represent a
		// street address. Some AD object classes use one, some the other.
		//
		// RFC-compliant LDAP schemas implement "street" and "streetAddress"
		// as aliases of the same underlying attribute.

		if($ldap_server->server_type == "ad" && isset($entry["street"][0]))
		{
			if(!empty($streetaddress)) $streetaddress .= ", ";
			$streetaddress .= $entry["street"][0];
		}

		if(isset($entry["l"][0])) $l = $entry["l"][0]; else $l = "";
		if(isset($entry["st"][0])) $st = $entry["st"][0]; else $st = "";
		if(isset($entry["postalcode"][0])) $postalcode = $entry["postalcode"][0]; else $postalcode = "";

		$this->add_property("ADR",
			"" . ";"		// Post Office Address (not used)
			. "" . ";"		// Extended Address (not used)
			. $streetaddress. ";"	// Street (or Street Address)
			. $l . ";"		// Locality
			. $st . ";"		// Region
			. $postalcode . ";"	// Postal Code
			. "");			// Country Name (not used)

		// Familiar/informal name of person
		if(isset($entry["displayname"][0]))
			$this->add_property("NICKNAME",$entry["displayname"][0]);

		if(isset($entry["homephone"][0]))
			$this->add_property("TEL;TYPE=HOME;TYPE=VOICE",
				str_replace(" ","",$entry["homephone"][0]));
		if(isset($entry["telephonenumber"][0]))
			$this->add_property("TEL;TYPE=WORK;TYPE=VOICE",
				str_replace(" ","",$entry["telephonenumber"][0]));
		if(isset($entry["mobile"][0]))
			$this->add_property("TEL;TYPE=CELL;TYPE=VOICE",
				str_replace(" ","",$entry["mobile"][0]));
		if(isset($entry["facsimiletelephonenumber"][0]))
			$this->add_property("TEL;TYPE=WORK;TYPE=FAX",
				str_replace(" ","",$entry["facsimiletelephonenumber"][0]));
		if(isset($entry["mail"][0]))
			$this->add_property("EMAIL;TYPE=INTERNET",$entry["mail"][0]);
		// Presented as a "personal" URL in the default record layout
		if(isset($entry["wwwhomepage"][0]))
			$this->add_property("URL",$entry["wwwhomepage"][0]);
		// Presented as a "business" URL in the default record layout
		if(isset($entry["url"][0]))
			$this->add_property("URL",$entry["url"][0]);

		if(isset($entry["jpegphoto"][0]))
			$this->add_property("PHOTO;TYPE=JPEG",$entry["jpegphoto"][0],"BASE64");
		else if(isset($entry["thumbnailphoto"][0]))
			$this->add_property("PHOTO;TYPE=JPEG",$entry["thumbnailphoto"][0],"BASE64");

		if(isset($entry["thumbnaillogo"][0]))
		{
			if(isset($exclude_logo_if_photo_present) && $exclude_logo_if_photo_present)
			{
				if(!isset($entry["jpegphoto"][0]) && !isset($entry["thumbnailphoto"][0]))
					$this->add_property("PHOTO;TYPE=JPEG",$entry["thumbnaillogo"][0],"BASE64");
			}
			else
				$this->add_property("PHOTO;TYPE=JPEG",$entry["thumbnaillogo"][0],"BASE64");
		}

		if(isset($entry["manager"][0]))
		{
			$manager = ldap_explode_dn2($entry["manager"][0]);
			$this->add_property("X-ANDROID-CUSTOM",
				"vnd.android.cursor.item/relation;"	// Data kind representing a relation
				. $manager["0"]["value"] . ";"		// Data1: Name
				. "7;"					// Data2: Type (7 = TYPE_MANAGER)
				. ";"					// Data3: Label (unused)
				. ";;;;;;;;;;;");			// other unused fields
		}

		if(isset($entry["mozillaUseHtmlMail"]))
			$this->add_property("x-mozilla-html",$entry["mozillaUseHtmlMail"]);

		if(isset($entry["info"][0]))
			$this->add_property("NOTE",$entry["info"][0],"QUOTED-PRINTABLE");

		$this->add_property("END","VCARD");
	}

	/** Add the specified property to the vCard data

	    @param string $property
		Property to be added
	    @param string $value
		Value to be added
	    @param string $encoding
		Encoding to be applied
	*/

	function add_property($property,$value,$encoding = "")
	{
		// indicate a character set of UTF-8 if the value contains
		// any non-ASCII code points
		if(empty($encoding) && !mb_check_encoding($value,"ASCII"))
			$property .= ";CHARSET=UTF-8";

		if(!empty($encoding))
			$property .= ";ENCODING=" . $encoding;

		// Apply encoding
		if($encoding == "BASE64")
			$value = chunk_split(base64_encode($value),76,"\r\n");
		else if($encoding == "QUOTED-PRINTABLE")
		{
			$count = strlen($property)+1;
			$encoded_value = "";
			foreach(str_split($value) as $char)
			{
				$encoded_value .= "="
					. (ord($char)<16 ? "0" : "")
					. dechex(ord($char));

				$count+=3;

				// Wrap to next line if length exceeds 70 characters
				if($count>70)
				{
					$encoded_value .= "=\r\n";
					$count = 0;
				}
			}
			$value = $encoded_value;
		}

		$this->data .= $property . ":" . $value . "\r\n";
	}
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
	/* Internal server ID */
	var $server_id;

	/** Return LDAP server/schema type

	    Supported server/schema types:

		- ad - Microsoft Active Directory
		- edir - Novell eDirectory
		- openldap - OpenLDAP
		- custom - Custom type (configure schema, etc, "by hand")
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

	/* Return an array of ldap_schema objects for the server */
	var $schema_modules = array();

	/** Return the default LDAP object class to use when creating new records

	    The default class for new objects depends on the LDAP server type.
	*/
	var $default_create_class = "person";

	/** Return connection resource for communicating with the LDAP server */
	var $connection;

	/** Directory location (e.g. OU) of the address book records */
	var $base_dn;

	/** Temporary bind DN used to look up LDAP bind DN corresponding to a user name */
	var $dn_search_user = "";

	/** Password used to look up LDAP bind DN corresponding to a user name */
	var $dn_search_password = "";

	/** Search filter used to find LDAP bind DN corresponding to a user name */
	var $dn_search_filter = "(|(uid=__USERNAME__)(cn=__USERNAME__))";

	/** Search base DN used to look up LDAP bind DN corresponding to a user name

	    If set to empty then the value of $this->base_dn should be used instead
	*/
	var $dn_search_base = "";

	/** Whether to follow LDAP referrals */
	var $follow_referrals = false;

	/** Whether the server supports ldap_compare() acting on an object's DN */
	var $compare_dn_supported = false;

	/** Permissions and user name mappings between address book and LDAP server */
	var $user_map = array();

	/** Permissions and group name mappings between address book and LDAP server */
	var $group_map = array();

	/** Display layouts */
	var $display_layouts = array();

	/** Host name or URL (typically for identifying the server in error messages */
	var $host_or_url;

	/** Supported server types */
	var $server_types = array(
		array("name"=>"ad",
			"default_create_class"=>"contact",
			"schema_list"=>"microsoft",
			"dn_search_filter"=>"(sAMAccountName=__USERNAME__)"),

		array("name"=>"edir",
			"default_create_class"=>"inetOrgPerson",
			"schema_list"=>"novell",

			/**
			    Novell eDirectory does not appear to support
			    compare operations against an object's DN,
			    accessed as an attribute. eDirectory also
			    behaves in a non-standard manner when comparing
			    attributes that are not set or to which the user
			    lacks read permission. Supposedly discussed here,
		            although not fixed:

		            https://bugzilla.novell.com/show_bug.cgi?id=829296
			    (Not publicly accessible)

			    See also:

			    https://forums.netiq.com/archive/index.php/t-48106.html
			*/
			"compare_dn_supported"=>false),

		array("name"=>"openldap",
			"default_create_class"=>"inetOrgPerson",
			"schema_list"=>"openldap/system,core,cosine,nis,inetorgperson"),

		array("name"=>"custom",
			"default_create_class"=>"person",
			"schema_list"=>"")
		);

	/** Constructor

	    Member variables $object_schema, $attribute_schema and
	    $default_create_class are populated appropriately to the LDAP
	    server type. A connection to the server is opened; connection
	    resource available in $connection.

	    @param string $ldap_server_type
		Indicates LDAP server type/schema type to create
		("ad", "edir" or "openldap")

	    @param string $base_dn
		Directory location (e.g. OU) of the address book records

	    @param string $ldap_server_host_or_url
		Host name, IP address or URL of the LDAP server to connect
		to. OpenLDAP 2.0 or later is required to use URL syntax
		(e.g. "ldap://server/")
	    @param int $ldap_server_port
		Port number on LDAP server to connect to
	*/

	function __construct($ldap_server_type,$base_dn,$ldap_server_host_or_url,$ldap_server_port = null)
	{
		global $ldap_server_list;

		$ldap_server_list[] = &$this;
		$this->server_id = count($ldap_server_list)-1;

		// function may be missing if PHP's LDAP module not available - this will be
		// reported to the user via a subsequent call to prereq_components_ok()
		if(function_exists("ldap_connect"))
			if(is_null($ldap_server_port))
				$this->connection = ldap_connect($ldap_server_host_or_url);
			else
				$this->connection = ldap_connect($ldap_server_host_or_url,
					$ldap_server_port);

		$this->server_type = $ldap_server_type;
		$this->base_dn = $base_dn;
		$this->host_or_url = $ldap_server_host_or_url;

		$schema_list = "";
		foreach($this->server_types as $server_type)
		{
			if($server_type["name"] == $this->server_type)
			{
				$this->default_create_class = $server_type["default_create_class"];
				$schema_list = $server_type["schema_list"];
				if(isset($server_type["dn_search_filter"]))
					$this->dn_search_filter = $server_type["dn_search_filter"];
				if(isset($server_type["compare_dn_supported"]))
					$this->compare_dn_supported = $server_type["compare_dn_supported"];
			}
		}
		$schema_list = explode(",",$schema_list);

		foreach($schema_list as $schema)
			$this->add_schema($schema);
	}

	/** Return the schema settings for the specified LDAP attribute

	    @param string $class
		Attribute class for which the settings are required
	    @return
		Array of schema settings
	    @todo
		Support lookup by attribute class alias name
	*/

	function get_attribute_schema_settings($class)
	{
		$settings=array();

		foreach($this->attribute_schema as $schema_entry)
			if($schema_entry["name"] == $class)
				$settings = $schema_entry;

		return $settings;
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
	    @todo
		Support lookup by attribute class alias name
	*/

	function get_attribute_schema_setting($class,$setting_name,
		$setting_default="")
	{
		$setting_value = $setting_default;

		foreach($this->attribute_schema as $schema_entry)
			if($schema_entry["name"] == $class && isset($schema_entry[$setting_name]))
				$setting_value = $schema_entry[$setting_name];

		return $setting_value;
	}

	/** Return the primary class name of the specified LDAP attribute

	    @param string $class
		Attribute class for which the primary name is required
	    @return
		Primary class name of attribute (otherwise $class)
	*/

	function get_attribute_primary_class($class)
	{
		$primary_name = $class;
		$found = false;
		foreach($this->attribute_schema as $schema_entry)
		{
			if(!$found && $schema_entry["name"] == $class)
			{
				$primary_name = $schema_entry["name"];
				$found = true;
			}

			if(!$found && isset($schema_entry["alias_names"]))
			{
				$alias_names = explode(",",$schema_entry["alias_names"]);
				foreach($alias_names as $alias)
				{
					if(!$found && $alias == $class)
					{
						$primary_name = $alias;
						$found = true;
					}
				}
			}
		}
		return $primary_name;
	}

	/** Return the schema settings for the specified object class

	    @param string $class
		Object class for which the settings are required
	    @return
		Array of schema settings
	    @todo
		Support lookup by object class alias name
	*/

	function get_object_schema_settings($class)
	{
		$settings = array();

		foreach($this->object_schema as $object_class)
			if(strtolower($object_class["name"]) == strtolower($class))
				$settings = $object_class;

		return $settings;
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
	    @todo
		Support lookup by object class alias name
	*/

	function get_object_schema_setting($class,$setting_name,$setting_default="")
	{
		$setting_value = $setting_default;
		$object_data_found = false;

		foreach($this->object_schema as $object_class)
			if(strtolower($object_class["name"]) == strtolower($class) && isset($object_class[$setting_name]))
			{
				$setting_value = $object_class[$setting_name];
				$object_data_found = true;
			}

		// return useful defaults if setting not found in schema
		if(!$object_data_found)
		{
			if($setting_name == "icon") $setting_value = "generic24.png";
			if($setting_name == "is_folder") $setting_value = false;
			if($setting_name == "class_type") $setting_value = "structural";
			if($setting_name == "rdn_attrib") $setting_value = "cn";
			if($setting_name == "can_create") $setting_value = false;
			if($setting_name == "can_contain") $setting_value = "*";
			if($setting_name == "contained_by") $setting_value = "*";
			if($setting_name == "display_name") $setting_value = $class;
			if($setting_name == "parent_class") $setting_value = "top";
		}
		return $setting_value;
	}

	/** Return matching schema object class for the specified LDAP entry

	    @param array $entry
		Entry for which matching class name is to be returned
	    @return
		Most specific class name for the object, or the
		string "(unrecognised)" if none of its object class
		values appears in the schema
	*/

	function get_object_class($entry)
	{
		$object_data_found = false;
		$item_object_class = "(" . gettext("unrecognised") . ")";

		$entry_object_class = array_map("strtolower",$entry["objectclass"]);

		foreach($this->object_schema as $object_class)
		{
			if(!isset($object_class["class_type"]))
				$object_class["class_type"] = "structural";

			if(in_array(strtolower($object_class["name"]),
				$entry_object_class)
				&& $object_data_found == false && in_array($object_class["class_type"],array("structural","type88")))
			{
				$more_specific_class_exists = false;
				if(isset($object_class["child_class"]))
				{
					$child_class_list = explode(",",$object_class["child_class"]);

					foreach($child_class_list as $child_class)
						if(!$more_specific_class_exists
								&& in_array(strtolower($child_class),$entry_object_class))
							$more_specific_class_exists=true;
				}

				if(!$more_specific_class_exists)
				{
					$item_object_class = $object_class["name"];
					$object_data_found = true;
				}
			}
		}

		return $item_object_class;
	}

	/** Retrieve icon/photo thumbnail URL for the specified LDAP entry.

	    The first available image will be used from the following list
	    (i.e. image is present and thumbnails enabled in the config)

		- jpegPhoto attribute
		- thumbnailPhoto attribute
		- thumbnailLogo attribute
		- icon representing object class

	    @param mixed $entry
		Entry for which thumbnail URL is to be retrieved, either
		as an array of attributes or string containing the DN
	    @return
		URL of image. This can be either retrieved from the record
		itself (if present, and image display is turned on) or an
		icon representing the record's object class (e.g. user
		or contact).
	*/

	function get_icon_for_ldap_entry($entry)
	{
		global $enable_ldap_path_thumbnail,$thumbnail_image_size;

		// if an object DN was passed then fetch the corresponding LDAP entry
		if(is_string($entry))
		{
			$search_resource = @ldap_read($this->connection,$entry,"(objectclass=*)",array("objectclass"));

			if($search_resource)
			{
				$entry = ldap_get_entries($this->connection,$search_resource);
				$entry = $entry[0];
			}
		}

		// Resume existing session (if any exists) in order to get
		// currently logged in user
		if(!isset($_SESSION)) session_start();

		if($this->get_user_setting("allow_browse") || $this->get_user_setting("allow_search")
			|| $this->get_user_setting("allow_view"))
		{
			$dn = $entry["dn"];

			$server_id = $this->server_id == 0 ? "" : "&server_id=" . $this->server_id;

			if(!empty($entry["jpegphoto"][0])
					&& $enable_ldap_path_thumbnail)
				return "image.php?dn=" . urlencode($dn)
					. "&attrib=jpegPhoto&size="
					. $thumbnail_image_size . $server_id;
			else if(!empty($entry["thumbnailphoto"][0])
					&& $enable_ldap_path_thumbnail)
				return "image.php?dn=" . urlencode($dn)
					. "&attrib=thumbnailPhoto&size="
					. $thumbnail_image_size . $server_id;
			else if(!empty($entry["thumbnaillogo"][0])
					&& $enable_ldap_path_thumbnail)
				return "image.php?dn=" . urlencode($dn)
					. "&attrib=thumbnailLogo&size="
					. $thumbnail_image_size . $server_id;
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
		ldap_set_option($this->connection,
			LDAP_OPT_PROTOCOL_VERSION,3);

		ldap_set_option($this->connection,
			LDAP_OPT_REFERRALS,$this->follow_referrals);

		$login_name = $this->get_user_setting("login_name");

		// If the __DEFAULT__ user settings are being used then replace
		// $login_name with the use name typed into the login box
		// (if available)

		if($login_name == "__DEFAULT__" && isset($_SESSION["LOGIN_USER"]))
			$login_name = $_SESSION["LOGIN_USER"];

		// Determine the LDAP DN corresponding to $login_name.

		if(isset($_SESSION["LOGIN_BIND_DN"][$this->server_id]))
		{
			// Reuse a previously stored bind DN if available. (This
			// value gets cleared when the user logs off or the
			// PHP session times out.)

			$user_bind_dn = $_SESSION["LOGIN_BIND_DN"][$this->server_id];
		}
		else
		{
			// Use the ldap_dn setting as the starting basis for the
			// user's DN.
			//
			// If no value was explicitly assigned to this user setting
			// then a default value of "__SEARCH__" will be returned,
			// indicating that the directory must be searched to
			// convert $login_name into the user's actual DN.

			$user_bind_dn = $this->get_user_setting("ldap_dn");

			// use anonymous bind when user is __ANONYMOUS__ and
			// no ldap_dn is specified
			if($login_name == "__ANONYMOUS__" && $user_bind_dn == "__SEARCH__")
				$user_bind_dn = "";

			// Determine the LDAP filter for searching the directory to
			// find the user's actual DN, handling Active Directory login
			// by UPN as a special case.

			if($this->server_type=="ad" && !empty($user_bind_dn)
				&& !strpos($user_bind_dn,"=")>0
				&& strpos($user_bind_dn,"@")>0)
			{
				$filter = "(userPrincipalName=" . $user_bind_dn . ")";

				// Flag that user DN lookup will be required
				$user_bind_dn = "__SEARCH__";
			}
			else
				$filter = str_replace("__USERNAME__",
					$login_name,
					$this->dn_search_filter);

			// Search the directory for the user's actual DN (if required)

			if($user_bind_dn == "__SEARCH__")
			{
				// Temporary LDAP bind as $this->dn_search_user to carry out the search
				$result=@ldap_bind_log($this->connection,$this->dn_search_user,
					$this->dn_search_password);

				if($result)
				{
					// Determine the search base - default to $this->base_dn
					// unless the user has specified otherwise by assigning
					// a non-empty value to $this->dn_search_base.

					if(empty($this->dn_search_base))
						$this->dn_search_base=$this->base_dn;

					$search_resource = @ldap_search($this->connection,
						$this->dn_search_base,$filter);

					// use resulting DN if exactly 1 found (i.e. unambiguous result)

					if(is_resource($search_resource))
					{
						$entries = ldap_get_entries($this->connection,$search_resource);

						if($entries["count"]==1)
							$user_bind_dn = $entries[0]["dn"];
					}
				}
				else
				{
					show_site_header();
					show_ldap_path(null,"");

					echo "<p>\n  "
						. gettext("Unable connect to the directory to look up the user name.")
						. "\n</p>\n<p>\n  "
						. sprintf(gettext("Please check the %s and %s settings in the Address Book configuration."),
						"<code>\$ldap_server->dn_search_user</code>",
						"<code>\$ldap_server->dn_search_password</code>")
						. "\n</p>\n\n";

					show_site_footer();

					exit(0);
				}
			}

			if(!empty($user_bind_dn) && $user_bind_dn != "__SEARCH__")
				$_SESSION["LOGIN_BIND_DN"][$this->server_id] = $user_bind_dn;
		}

		// Bind as the actual user
		if($user_bind_dn == "__SEARCH__")
		{
			error_log("[" . $_SERVER["REMOTE_ADDR"]
				. "] User lookup for LDAP Address Book with '"
				. preg_replace("/[^[:print:]]/","",$filter)
				. "' failed");

			$result=false;
		}
		else
			$result=@ldap_bind_log($this->connection,$user_bind_dn,
				get_ldap_bind_password());

		if($result)
		{
			if($this->follow_referrals)
				ldap_set_rebind_proc($this->connection,
					"ldap_referral_rebind");	// callback

			// Timezone is not known to be used for anything in this
			// application, however various LDAP functions produce
			// warning messages in newer PHP versions (>=5.1.0) if it
			// is not set.
			if(!ini_get("date.timezone"))
				date_default_timezone_set("UTC");

			// User's allow_login setting must be checked after LDAP bind
			// has taken place in order to handle cases where it is
			// conditional on group membership. If the user doesn't have
			// sufficient access to the directory to enumerate the group's
			// membership then allow_login will evaluate to false.
			$result = $this->get_user_setting("allow_login");

			// Look up the user ID, which is needed to evaluate permissions
			// based on membership of posixGroup objects. The attributes
			// 'uid' and 'userid' are aliases (in a standards-compliant
			// schema implementation), but not necessarily
			// returned by all server types - accept a value being returned
			// in either or both.
			$search_resource = @ldap_read($this->connection,$user_bind_dn,
				"objectClass=*",array("uid","userid"));

			if($search_resource)
			{
				$entry = ldap_get_entries($this->connection,$search_resource);
				if(isset($entry[0]["uid"][0]))
					$_SESSION["LOGIN_UID"][$this->server_id] = $entry[0]["uid"][0];
				if(isset($entry[0]["userid"][0]))
					$_SESSION["LOGIN_UID"][$this->server_id] = $entry[0]["userid"][0];
			}

			// Assign any further permissions based on group memberships
			if(!empty($_SESSION["LOGIN_BIND_DN"][$this->server_id]))
				foreach($this->group_map as $group_map_entry)
					$this->assign_group_permissions($group_map_entry);
		}

		return $result;
	}

	/** Assign permissions to the currently logged in user based on their
	    group membership.

	    @param array $group_map_entry
		Group permission entry to be merged in
	*/

	function assign_group_permissions($group_map_entry)
	{
		global $group_member_attributes;

		if(!isset($group_member_attributes))
			$group_member_attributes = array("member","roleOccupant","memberUid");

		$query = "";
		foreach($group_member_attributes as $attrib)
		{
			if(strtolower($attrib) == "memberuid")
			{
				if(isset($_SESSION["LOGIN_UID"][$this->server_id]))
					$query .= "(" . $attrib . "="
						. ldap_escape($_SESSION["LOGIN_UID"][$this->server_id],
						null,LDAP_ESCAPE_FILTER) . ")";
			}
			else
				$query .= "(" . $attrib . "="
					. ldap_escape($_SESSION["LOGIN_BIND_DN"][$this->server_id],
					null,LDAP_ESCAPE_FILTER) . ")";
		}

		$search_resource = @ldap_read($this->connection,
			$group_map_entry["group_name"],"(|" . $query . ")",
			$group_member_attributes);

		$is_group_member = false;
		if($search_resource && ldap_count_entries($this->connection,
			$search_resource))
		{
			$entry = ldap_get_entries($this->connection,
				$search_resource);

			foreach($group_member_attributes as $attribute)
			{
				if(isset($entry[0][strtolower($attribute)]))
				{
					if(strtolower($attribute) == "memberuid")
						$test_value = $_SESSION["LOGIN_UID"][$this->server_id];
					else
						$test_value = $_SESSION["LOGIN_BIND_DN"][$this->server_id];

					foreach($entry[0][strtolower($attribute)] as $index=>$member)
						if(!($index === "count") && !strcasecmp($member,$test_value))
							$is_group_member = true;
				}
			}
		}

		// merge in the group's settings if the user is a member
		if($is_group_member)
		{
			foreach($group_map_entry as $setting=>$value)
			{
				if($setting != "group_name")
				{
					$previous_value = $this->get_user_setting($setting);
					$merge_method = get_user_setting_merge_method($setting);
					switch($merge_method)
					{
						case "boolean":
							// Don't override if already explicitly set to false
							if($previous_value = true || !$this->user_setting_exists($attrib))
								$this->assign_cached_user_setting($setting,$value);
							break;
						case "string":
							if(!$this->user_setting_exists($attrib))
								$this->assign_cached_user_setting($setting,$value);
							break;
						default:
							show_error_message(gettext("Error") . ": "
								. sprintf(gettext("Unsupported setting merge method: %s"),
								$merge_method));
					}
				}
			}
		}
	}

	/** Return whether a DN is within the specified base of the DIT

	    If supported, the comparision is done server-side in order to
	    apply the correct matching rule for each attribute (e.g. whether
	    it is case sensitive or not).

	    If server-side comparision is not supported, a client-side
	    case-insensitive string comparison is done instead.

	    @param string $dn
		DN to test
	    @param string $base_dn
		Base DN that $dn is going to be tested against
	    @return
		Whether $dn falls within $base_dn in the DIT (true/false)
	*/

	function compare_dn_to_base($dn,$base_dn)
	{
		$test_rdn_list = ldap_explode_dn($dn,0);

		// Handle case where $dn couldn't be parsed into
		// a list of RDNs - ldap_explode_dn() returns false.
		if(gettype($test_rdn_list)!="array")
			$test_rdn_list = array("count"=>1,0=>$dn);

		$base_rdn_list = ldap_explode_dn($base_dn,0);

		$base_rdn_count = $base_rdn_list["count"];

		$dn_base_section = implode(array_slice($test_rdn_list,
			-$base_rdn_count),",");

		if($base_dn == "")
			return true;
		if($this->compare_dn_supported)
			return @ldap_compare($this->connection,
				$base_dn,"DN",$dn_base_section);
		else
			return !strcasecmp($dn_base_section,$base_dn);
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

	function update_attribute(&$entry,$attrib,$is_rdn=false)
	{
		$dn = $entry["dn"];

		$new_val_set = isset($_POST["ldap_attribute_" . $attrib]);

		// For image attributes, the above is set if the "clear image" box was ticked.
		// Further checks to see if an image was uploaded:
		if($this->get_attribute_schema_setting($attrib,"data_type","text") == "image")
			$new_val_set = isset($_FILES["ldap_attribute_" . $attrib . "_file"]["tmp_name"])
				|| $new_val_set;

		if($new_val_set)
		{
			if(isset($entry[strtolower($attrib)][0]))
				$old_val = $entry[strtolower($attrib)][0];
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
						$new_val = fread($fd,MAX_IMAGE_UPLOAD);
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
					{
						$rdn_attribs = explode(",",
							$this->get_object_schema_setting(
							$this->get_object_class($entry),"rdn_attrib"));

						$new_rdn="";
						foreach($rdn_attribs as $rdn_attrib)
						{
							if(!empty($new_rdn)) $new_rdn .= "+";
							$new_rdn .= $rdn_attrib . "=";

							if($rdn_attrib == $attrib)
								$new_rdn_val = $new_val;
							else if(is_array($entry[strtolower($rdn_attrib)]))
								$new_rdn_val = $entry[strtolower($rdn_attrib)][0];
							else
								$new_rdn_val = $entry[strtolower($rdn_attrib)];

							$new_rdn .= ldap_escape($new_rdn_val,null,LDAP_ESCAPE_DN);
						}

						$result = @ldap_rename($this->connection,$dn,
							$new_rdn,null,true);
						if($result)
							if(is_array($entry[strtolower($attrib)]))
								$entry[strtolower($attrib)][0] = $new_val;
							else
								$entry[strtolower($attrib)] = $new_val;
					}
					else
						$result = @ldap_mod_replace($this->connection,$dn,$changes);

				if($result)
				{
					if($this->get_attribute_schema_setting($attrib,"data_type","text") == "image")
						if(isset($_POST["ldap_attribute_" . $attrib])
								&& $_POST["ldap_attribute_" . $attrib] != "")
							return sprintf(gettext("Clear attribute '%s'"),$attrib);
						else
							return sprintf(gettext("Set attribute '%s' to the contents of '%s'"),
								$attrib,$_FILES["ldap_attribute_". $attrib . "_file"]["name"]);
					else
						return sprintf(gettext("Set attribute '%s' to '%s'"),
							$attrib,htmlentities($new_val,ENT_COMPAT,"UTF-8"));
				}
				else
					return gettext("Error whilst setting attribute") . " '"
						. $attrib . "': " . ldap_error($this->connection) . "<br>";
			}
		}
	}

	/** Add a mapping between an address book login and the LDAP logins

	    Defines the permissions that should be given to the user

	    @param string $login_name
		Address book login name. Special names:
		* __ANONYMOUS__ - settings used when no user logged in by name
		* __DEFAULT__ - settings used for log in by name but not explicit config
	    @param array $settings
		Array of permissions/settings for the user

	    @see
		"configuring users and permissions" in the manual
	*/
	function add_user($login_name,$settings=array())
	{
		// Assign no settings if $settings argument is not an array
		if(!is_array($settings)) $settings=array();

		// Assume "allow_login"=true if not explicitly set
		if(!array_key_exists("allow_login",$settings))
			$settings["allow_login"] = true;

		// Assume "allow_ldap_path"=true if not explicitly set
		if(!array_key_exists("allow_ldap_path",$settings))
			$settings["allow_ldap_path"] = true;

		$this->user_map[] = array_merge(
			array("login_name"=>$login_name),
			$settings);
	}

	/** Add a permission mapping for an LDAP group

	    Defines the permissions that should be given to the group

	    @param string $group_name
		LDAP group name
	    @param array $settings
		Array of permissions/settings for the group

	    @see
		"configuring users and permissions" in the manual
	*/

	function add_group($group_name,$settings=array())
	{
		// Assign no settings if settings argument is not an array
		if(!is_array($settings)) $settings=array();

		$this->group_map[] = array_merge(
			array("group_name"=>$group_name),
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
			return (count($this->user_map)>0);
	}

	/** Add/enable a schema

	    @param string $name
		Schema name
	*/

	function add_schema($name)
	{
		include_once("schema/" . $name . ".php");
		$schema_class_name = str_replace("/","_",$name) . "_schema";

		bindtextdomain($schema_class_name,"./locale");
		textdomain($schema_class_name);

		$this->schema_modules[] = new $schema_class_name($this);

		textdomain("main");
	}

	/** Call the specified schema plugin/callback function

	    The named member function will be called for each schema object
	    in which it has been defined. Functions are called in the order
	    that the schemas were loaded/enabled.

	    This facility is used to implement a "plugin" architecture,
	    e.g. for applying object class specific pre-processing and/or
	    post-processing of LDAP records as they are being created,
	    updated, etc.

	    @param string $function_name
		Schema function name to be called.
	    @param array $entry
		LDAP object entry which is being processed.
	    @param array $extra_param
		Associative array of additional parameters to be
		passed to the function (where the function supports
		this).
	*/

	function call_schema_function($function_name,&$entry,&$extra_param=null)
	{
		foreach($this->schema_modules as $module)
		{
			if(method_exists($module,$function_name))
			{
				if(!empty($extra_param))
					$module->$function_name($this,
						$entry,$extra_param);
				else
					$module->$function_name($this,
						$entry);
			}
		}
	}

	/** Add an object class to the schema

	    If the object class is already defined then its previous definition
	    will be replaced. The index position of the object in the schema
	    (used to indicate inheritance) will be unchanged.

	    @param string $name
		Class name
	    @param array $settings
		Array of schema settings
	*/

	function add_object_class($name,$settings = array())
	{
		$object_class_index = $this->get_object_class_index($name);

		// if a previous object class of this name already exists then remove it
		// from any other object class's child_class attributes.

		if(!is_null($object_class_index))
		{
			foreach($this->object_schema as $object_class)
				if(isset($object_class["child_class"]))
				{
					$old_child_class_list = explode(",",$object_class["child_class"]);

					$new_child_class_list = "";
					foreach($old_child_class_list as $child_class)
						if($child_class != $name)
						{
							if(!empty($new_child_class_list))
								$new_child_class_list .= ",";
							$new_child_class_list .= $child_class;
						}
				}
		}

		// add this class to the child_class list for each of its parent classes

		if(isset($settings["parent_class"]))
		{
			$parent_classes = explode(",",$settings["parent_class"]);

			foreach($parent_classes as $parent_class)
				if(!is_null($this->get_object_class_index($parent_class)))
				{
					$parent_child_class_list = $this->get_object_schema_setting($parent_class,"child_class","");

					if($parent_child_class_list == "")
						$this->modify_object_schema($parent_class,"child_class",$name);
					else
						$this->modify_object_schema($parent_class,"child_class",
							$parent_child_class_list . "," . $name);
				}
		}

		// create new object class definition, or append/replace settings on an
		// existing definition of the same name

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

		// populate the object class's child_class list with any existing classes
		// that have it listed as their parent

		$child_class_list = "";

		foreach($this->object_schema as $object_class)
			if(isset($object_class["parent_class"]))
			{
				$object_parents = explode(",",$object_class["parent_class"]);
				if(in_array($name,$object_parents))
				{
					if(!empty($child_class_list)) $child_class_list .= ",";
					$child_class_list .= $object_class["name"];
				}
			}

		if(!empty($child_class_list))
			$this->modify_object_schema($name,"child_class",$child_class_list);
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
		behavior if attribute already present (should update existing)
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
				$layout_object_classes = array_map("strtolower",$layout["object_classes"]);

				if(in_array(strtolower($object_class),$layout_object_classes)
					|| in_array("*",$layout_object_classes))
				{
					$found = true;
					$selected_layout = $layout["layout"];
				}
				if(in_array("*",$layout_object_classes) && empty($selected_layout))
					$selected_layout = $layout["layout"];
			}
		}

		if(!$found)
			$selected_layout = array(
				array("section_name"=>sprintf(gettext("The Address Book is not able to display '%s' records"),$object_class),
					"attributes"=>array()));

		return $selected_layout;
	}

	/** Modify a setting of an attribute schema

	    @param string $attrib
		Attribute to be modified
	    @param string $setting
		Attribute setting to be modified
	    @param string $value
		Value to be assigned to the attribute's setting
	    @todo
		Support lookup by attribute class alias name
	*/

	function modify_attribute_schema($attrib,$setting,$value)
	{
		foreach($this->attribute_schema as $attrib_index=>$attrib_settings)
			if(strtolower($this->attribute_schema[$attrib_index]["name"]) == strtolower($attrib))
				$this->attribute_schema[$attrib_index][$setting] = $value;
	}

	/** Modify a setting of an object schema

	    @param string $object
		Object class to be modified
	    @param string $setting
		Object class setting to be modified
	    @param string $value
		Value to be assigned to the cobject class's setting
	    @todo
		Support lookup by object class alias name
	*/

	function modify_object_schema($object_class,$setting,$value)
	{
		foreach($this->object_schema as $object_index=>$object_settings)
			if(strtolower($this->object_schema[$object_index]["name"]) == strtolower($object_class))
				$this->object_schema[$object_index][$setting] = $value;
	}

	/** Load the specified OpenLDAP module if not already loaded

	    @param string $module_name
		Name of module to be loaded
	*/

	function ensure_openldap_module_loaded($module_name)
	{
		global $ldap_server,$openldap_module_path,$openldap_backend_module;

		if(!isset($openldap_module_path))
			$openldap_module_path = "/usr/lib/ldap";

		$search_resource = @ldap_list($ldap_server->connection,"cn=config",
			"(&(objectClass=olcModuleList)(olcModuleLoad=" . $module_name . "))",array("*","+"));

		if(ldap_count_entries($ldap_server->connection,$search_resource)==0)
		{
			$module_object_name = $module_name;

			// Add modules to existing "module{0}" object by default.
			//
			// An alternative approach would be append backend modules to "module{0}",
			// create separate objects for other module types. This approach seems to
			// cause stability problems as of OpenLDAP 2.4. To try it anyway, replace
			// the following line with:
			//
			// $add_value_to_module0=array_key_exists($module_name,$openldap_backend_module);

			$add_value_to_module0=true;

			// auditlog overlay should be created after all databases except back_monitor
			if($module_name == "auditlog")
			{
				$add_value_to_module0=false;
				$module_object_name = "~auditlog";
			}

			// create monitor database after other objects
			if($module_name=="back_monitor")
			{
				$add_value_to_module0=false;
				$module_object_name="~~back_monitor";
			}

			if($add_value_to_module0)
			{
				// find first olcModuleList object (numbered 0)

				$search_resource = @ldap_list($ldap_server->connection,"cn=config",
					"(&(objectClass=olcModuleList)(cn=*{0}))",array("dn"));

				if(ldap_count_entries($ldap_server->connection,$search_resource)==0)
				{
					// no module{0} object exists - create new instead
					$add_value_to_module0=false;
					$module_object_name="module";
				}
				else
				{
					$entry = ldap_get_entries($ldap_server->connection,$search_resource);
					$dn=$entry[0]["dn"];

					$module_entry=array("olcModuleLoad"=>array($module_name));

					$result = @ldap_mod_add($ldap_server->connection,$dn,$module_entry);

		                        if(!$result)
						show_error_message(sprintf("Unable to add '%s' to module list '%s': %s"
							,"<code>" . $module_name . "</code>","<code>" . $dn . "</code>",
							ldap_error($ldap_server->connection)));
				}
			}

			if(!$add_value_to_module0)
			{
				$module_entry = array(
					"objectclass"=>"olcModuleList",
					"dn"=>"olcDatabase=module{xxx},cn=config",
					"olcModulePath"=>$openldap_module_path,
					"olcModuleLoad"=>array($module_name)
					);

				$this->assign_ordered_sequence_rdn($module_entry,
					"olcModuleList",$module_object_name . "{%d}");

				$dn = $module_entry["dn"];
				unset($module_entry["dn"]);

	                        $result = @ldap_add($ldap_server->connection,$dn,$module_entry);

	                        if(!$result)
					show_error_message("Failed to create module list object: "
						. ldap_error($ldap_server->connection));
			}
		}
	}

	/** Assign the RDN attribute for the next object in an ordered sequence

	    @param array $entry
		LDAP object entry to which a value is to be added
	    @param mixed $class_name
		Name of object class to count existing entries, either as a
		string or as an array of objectClass entries
	    @param string $base_name
		Base name on which the DN will be based. The ordinal
		indicator will be placed at the start unless otherwise
		indicated using "{%d}" at the appropriate position.
	    @param integer $offset
		Optional offset to be added added to the object count.
		(This is used for olcDatabaseConfig objects in OpenLDAP,
		which are counted from -1)

	    @see https://tools.ietf.org/html/draft-chu-ldap-xordered-00

	    @todo
		handle object classes with multi-valued RDNs
	*/

	function assign_ordered_sequence_rdn(&$entry,$class_name,$base_name,$offset=0)
	{
		// insert ordinal at start if not explicitly indicated otherwise
		if(strpos($base_name,"{%d}")===false)
			$base_name = "{%d}" . $base_name;

		$object_class = $this->get_object_class($entry);
		$search_dn = get_parent_dn($entry["dn"]);
		$rdn_attrib = $this->get_object_schema_setting(
			$object_class,"rdn_attrib");

		$search_resource = ldap_search($this->connection,$search_dn,
			"(objectClass=" . $class_name . ")",array());

		$ordinal = $search_resource ? ldap_count_entries($this->connection,
			$search_resource) : 0;

		$rdn_value = sprintf($base_name,$ordinal+$offset);

		$entry["dn"] = $rdn_attrib . "=" . $rdn_value;
		if(!empty($search_dn))
			$entry["dn"] .= "," . $search_dn;

		// update posted attribute values to reflect new RDN
		// (use with before_create)
		$_POST["ldap_attribute_" . $rdn_attrib] = $rdn_value;

		if(isset($entry["___POPULATE_FOR_CREATE___"]))
		{
			$rdn_attrib=strtolower($rdn_attrib);
			$entry[$rdn_attrib]["count"] = 1;
		}

		unset($entry[$rdn_attrib]);
		$entry[$rdn_attrib][0] = $rdn_value;
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
		global $group_member_attributes;

		if(empty($user_name) && isset($_SESSION["CACHED_PERMISSIONS"][$attrib]))
			$attrib_value = $_SESSION["CACHED_PERMISSIONS"][$attrib];
		else
		{
			// list of ordinarily boolean attributes which if found to
			// contain a DN of an LDAP group will be evaluated based on
			// the user's group membership
			$boolean_attribs_with_ldap_lookup = array("allow_browse",
				"allow_search","allow_view","allow_create","allow_edit",
				"allow_edit_self","allow_move","allow_delete","allow_export",
				"allow_export_bulk","allow_login","allow_folder_info",
				"allow_ldap_path");

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

			$user_info = $this->get_user_info($user_name);

			if(isset($user_info["ldap_dn"]))
				$user_info["ldap_dn"]=str_replace("__USERNAME__",
					$user_name,$user_info["ldap_dn"]);

			// return the value of the requested attribute

			if(isset($user_info[$attrib]))
				$attrib_value = $user_info[$attrib];
			else
			{
				// default values to return if setting is undefined
				// for the specified user
				switch($attrib)
				{
					case "login_name";
						$attrib_value = "__ANONYMOUS__"; break;
					case "ldap_dn":
						$attrib_value = "__SEARCH__"; break;
					case "allow_browse":
					case "allow_search":
					case "allow_view":
					case "allow_create":
					case "allow_edit":
					case "allow_edit_self":
					case "allow_move":
					case "allow_delete":
					case "allow_export":
					case "allow_export_bulk":
					case "allow_login":
					case "allow_ldap_path":
					case "allow_folder_info":
						$attrib_value = false; break;
					default:
						$attrib_value = null;
				}
			}

			// If value contains a DN instead of true/false then look up
			// based on group membership instead.
			if(!is_bool($attrib_value)
				&& isset($_SESSION["LOGIN_BIND_DN"][$this->server_id])
				&& in_array($attrib,$boolean_attribs_with_ldap_lookup))
			{
				// re-use previously cached permission setting
				if(isset($_SESSION["CACHED_PERMISSIONS"][$attrib]))
					$attrib_value
						= $_SESSION["CACHED_PERMISSIONS"][$attrib];
				else
				{
					if(!isset($group_member_attributes))
						$group_member_attributes = array("member","roleOccupant","memberUid");
					$query = "";

					foreach($group_member_attributes as $attrib)
					{
						if(strtolower($attrib) == "memberuid")
						{
							if(isset($_SESSION["LOGIN_UID"][$this->server_id]))
								$query .= "(" . $attrib . "="
									. ldap_escape($_SESSION["LOGIN_UID"][$this->server_id],
									null,LDAP_ESCAPE_FILTER) . ")";
						}
						else
							$query .= "(" . $attrib . "="
								. ldap_escape($_SESSION["LOGIN_BIND_DN"][$this->server_id],
								null,LDAP_ESCAPE_FILTER) . ")";
					}

					$search_resource
						= @ldap_read($this->connection,
						$attrib_value,"(|" . $query . ")",
						$group_member_attributes);

					if(is_resource($search_resource))
					{
						$entry = ldap_get_entries(
							$this->connection,
							$search_resource);

						$attrib_value = ($entry["count"]>0);
						$this->assign_cached_user_setting($attrib,$attrib_value);
					}
					else
						// default to setting permission to false
						// if LDAP lookup failed
						$attrib_value=false;
				}
			}
		}

		return $attrib_value;
	}

	/** Retrieve the specified user's info from user_map array

	    The settings to be used for anonymous access (when no user has
	    logged in by name) can be retrieved by specifying a user name
	    of "__ANONYMOUS__".

	    If a named user is logged in but there is no user_map entry
	    matching their name then the values from the "__DEFAULT__"
	    map entry will be returned instead.

	    Settings from the user map can be merged with settings
	    derived from group membership to produce the user's overall
	    effective settings.

	    @param string $user_name
		Name of user whose settings are to be retrieved
	    @return
		Array of user settings
	*/

	function get_user_info($user_name)
	{
		$user_info = array();	// returned if no match at all
		$found=false;

		foreach($this->user_map as $map_user)
			if(!$found && ($map_user["login_name"] == $user_name
				|| ($map_user["login_name"] == "__DEFAULT__"
				&& $user_name != "__ANONYMOUS__")))
			{
				$user_info = $map_user;
				if($map_user["login_name"] != "__DEFAULT__")
					$found = true;
			}

		return $user_info;
	}


	/** Return whether the specified user setting has been explicitly
	    assigned a value.

	    @param string $attrib
		Attribute to be returned
	    @param string $user_name
		Name of user whose setting is to be returned. If this is omitted
		then the current logged in user will be used.
	    @return
		True if a value is assigned.
	*/

	function user_setting_exists($attrib,$user_name = "")
	{
		if(isset($_SESSION["CACHED_PERMISSIONS"][$attrib]))
			return true;
		else
		{
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

			$user_info = $this->get_user_info($user_name);

			return isset($user_info[$attrib]);
		}
	}

	/** Cache the effective value of the specified user setting in the PHP session

	    @param string $setting
		Name of setting to be stored
	    @param mixed $value
		Value to be stored
	*/

	function assign_cached_user_setting($setting,$value)
	{
		$_SESSION["CACHED_PERMISSIONS"][$setting] = $value;
	}

	/** Return list of auxiliary classes for the specified entry

	    @param array $ldap_entry
		Array containing LDAP object for which the
		list of auxiliary object classes is to be
		returned
	    @return
		Array of auxiliary object classes
	*/

	function get_auxiliary_classes($ldap_entry)
	{
		unset($ldap_entry["objectclass"]["count"]);

		$auxiliary_class_list = array();
		foreach($ldap_entry["objectclass"] as $object_class)
			if($this->get_object_schema_setting($object_class,"class_type") == "auxiliary")
				$auxiliary_class_list[]=$object_class;

		return $auxiliary_class_list;
	}

	/** Extend the specified display layout to show/edit auxiliary class attributes

	    @param array &$ldap_entry
		Array containing LDAP object for which the
		display layout is to be extended to include
		auxiliary class attributes
	    @param array &$entry_viewer_layout
		Entry viewer layout to be extended
	    @param array $new_aux_classes
		List of new auxiliary classes which are in the process of being added
		to the record. (Their populate_for_create function will be called after
		adding them to the display layout)
	*/

	function add_auxiliary_layouts(&$ldap_entry,&$entry_viewer_layout,$new_aux_classes = array())
	{
		global $append_auxiliary_layouts;

		$layout_attributes = get_layout_attributes($entry_viewer_layout);

		if(!isset($append_auxiliary_layouts) || $append_auxiliary_layouts)
		{
			unset($ldap_entry["objectclass"]["count"]);

			foreach($this->get_auxiliary_classes($ldap_entry) as $object_class)
			{
				$aux_layout = $this->get_display_layout($object_class);

				// remove duplicate attributes already present in the display layout
				foreach($aux_layout as $aux_section_id=>$aux_section)
					foreach($aux_section["attributes"] as $attribute_id=>$attribute)
						if(in_array($attribute[0],$layout_attributes))
							unset($aux_layout[$aux_section_id]["attributes"][$attribute_id]);

				// remove entirely empty sections
				foreach($aux_layout as $aux_section_id=>$aux_section)
					if(count($aux_section["attributes"])==0)
						unset($aux_layout[$aux_section_id]);

				// Add remaining sections/attributes to the entry layout (if any)
				if(!empty($aux_layout))
				{
					$aux_layout[0]["new_row"]=true;
					$entry_viewer_layout = array_merge($entry_viewer_layout,$aux_layout);
					$layout_attributes = get_layout_attributes($entry_viewer_layout);
				}

				if(in_array($object_class,$new_aux_classes))
					$this->call_schema_function(
						"populate_for_create_" . $object_class,$ldap_entry);
			}
		}
	}

	/* Link an additional server/DN into the object tree

	    @param string $source_dn
		DN (on source server) where link is to be made
	    @param object $destination_server
		Server to be linked to
	    @param string $destination_dn
		DN (on destination server) to be linked to
	    @param string $link_method
		Specifies how the destination location is to be linked:

		    - folder
			Link the destination as a folder (single item)
			which appears within the source location when
			when browsing the directory.

		    - contents
			Merge the contents (child objects) of the
			destination location together with the contents
			of the source location, so that they appear as
			a single list when browsing the directory.

	    @param string $display_name
		Optionally specify a display name to be used for the
		destination folder, overriding the destination folder's
		actual name. (Used with link method "folder".)
	    @param string $icon_object_class
		Optionally specify the structural object class to be
		used for displaying the destination folder's icon,
		overriding it's actual object class. (Used with link
		method "folder".)
	*/

	function link_ldap_dn($source_dn,$destination_server,
		$destination_dn,$link_method,$display_name=null,$icon_object_class=null)
	{
		global $linked_ldap_dn_list;

		$linked_ldap_dn_list[] = array(
			"source_server"=>$this->server_id,
			"source_dn"=>$source_dn,
			"link_method"=>$link_method,
			"destination_server"=>$destination_server->server_id,
			"destination_dn"=>$destination_dn,
			"name"=>$display_name,
			"object_class"=>$icon_object_class
			);
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
			. "] Authentication to LDAP Address Book as '"
			. preg_replace("/[^[:print:]]/","",$user)
			. "' failed");
	return $result;
}

/** LDAP schema */

abstract class ldap_schema
{
	/** Array of object class definitions for this schema */
	var $object_schema = array();

	/** Array of attribute class definitions for this schema */
	var $attribute_schema = array();

	/** Constructor

	    @param object $ldap_server
		LDAP server object to which the schema definitions
		will be exported
	*/

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

	/** Add a value to a multi-valued attribute of the specified LDAP entry

	    Call unset($entry["<attribute-name>"]) first in order to replace the
	    existing value(s) of the attribute rather then add to them.

	    @param object $ldap_server
		LDAP server from which the object entry was retrieved
	    @param array $entry
		LDAP object entry to which a value is to be added
	    @param string $attribute
		Name of attribute to which the value is to be added
	    @param string $value
		Value to be added
	*/

	function add_attrib_value(&$ldap_server,&$entry,$attribute,$value)
	{
		if(isset($entry[strtolower($attribute)]["count"]))
		{
			// add additional value to existing attribute
			$entry[strtolower($attribute)][$entry[strtolower($attribute)]["count"]]=$value;
			$entry[strtolower($attribute)]["count"]++;
		}
		else
		{
			// add as a new attribute
			$entry[strtolower($attribute)][0]=$value;
			$entry[strtolower($attribute)]["count"]=1;
		}
	}

	/** Add a value to a single-valued attribute of the specified LDAP entry

	    @param object $ldap_server
		LDAP server from which the object entry was retrieved
	    @param array $entry
		LDAP object entry to which a value is to be added
	    @param string $attribute
		Name of attribute to which the value is to be added
	    @param string $value
		Value to be added
	*/

	function add_attrib_single_value(&$ldap_server,&$entry,$attribute,$value)
	{
		$entry[strtolower($attribute)]=$value;
	}
}

/** Show a list of the searchable attributes */

function show_searchable_attributes()
{
	global $search_ldap_attrib,$ldap_server;
	echo "<ul>";
	foreach($search_ldap_attrib as $attrib)
	{
		echo "<li>" . $ldap_server->get_attribute_schema_setting(
			$attrib,"display_name",$attrib) . "</li>";
	}
	echo "</ul>";
}

/** Display explanatory message if configuration file is missing */

function missing_config_error()
{
	show_site_header();
	show_ldap_path(null,"");

	echo "<p>\n  "
		. sprintf(gettext("The Address Book's configuration file (%sconfig.php%s) is missing."),
		"<code>","</code>") . "\n</p>\n<p>\n  "
		. sprintf(gettext("Please read %sInstallation and Basic Setup%s in the User Guide for instructions on how to configure a new Address Book installation."),
		"<a style=\"font-style:italic\" href=\"doc/#installation\">","</a>\n ")
		. "\n</p>\n\n";

	show_site_footer();

	exit(0);
}

/** Return the DN of the specified object's parent

    @param string $dn
	DN of the object whose parent DN is to be returned
    @return
	DN of the parent object
*/

function get_parent_dn($dn)
{
	$rdn_list = ldap_explode_dn2($dn);

	if(isset($rdn_list[1]["dn"]))
		return $rdn_list[1]["dn"];
	else
		return "";
}

/** Return whether the specified container accepts subordinates of the specified type

    Container/folder object classes can be defined which only allow certain classes
    of object to be created inside them. This function is used to limit the classes
    listed in the "Create Object" menu to only those which are allowed.

    @param string $object_class
	Structural object class to be created
    @param string $contain_list
	List of object classes of container object
    @return
	True if the object can be created within the specified
	container.

    @todo check containment rules of parent classes recursively (parent of parent, etc)
*/

function can_create_in_container($object_class,$contain_list)
{
	$can_create = false;
	if($contain_list[0]=="*")		// if container will accept any object class
		$can_create = true;

	if(!$can_create && in_array($object_class["name"],$contain_list))
		$can_create = true;

	if(!$can_create && isset($object_class["parent_class"]))
	{
		$parent_class = explode(",",$object_class["parent_class"]);

		foreach($parent_class as $potential_container)
			if(in_array($potential_container,$contain_list))
				$can_create = true;
	}

	return $can_create;
}

/** Return whether the specified object is allowed to be created within the specified container

    Object classes can be defined which are only allowed to be created in certain
    containers/folders. This function is used to remove the object from "Create Object"
    menu for containers/folders where it can't be created.

    @param string $object_class
	Structural object class to be created
    @param string $contain_list
	List of object classes of container object
    @return
	True if the object can be created within the specified
	container.
*/

function can_be_contained_by($object_class,$container_object)
{
	global $ldap_server;

	$can_create = false;

	$container_object_class = $ldap_server->get_object_class($container_object);

	$contained_by_list = explode(",",
		$ldap_server->get_object_schema_setting($object_class["name"],
		"contained_by"));

	if(count(array_intersect($contained_by_list,$container_object["objectclass"]))>0)
		$can_create = true;

	if(!$can_create && isset($object_class["parent_class"]))
	{
		$parent_class = explode(",",$object_class["parent_class"]);

		foreach($parent_class as $parent_class_to_consider)
		{
			// look up the parent class's schema info
			$object_class_info = array("name"=>$parent_class_to_consider);
			foreach($ldap_server->object_schema as $object_class)
			{
				if($object_class["name"] == $parent_class_to_consider)
				$object_class_info = $object_class;
			}

			// self-recursion
			$can_create = can_be_contained_by($object_class_info,$container_object);
		}
	}
	else
		// only take "*" into account at highest parent class level
		if(!$can_create && $contained_by_list[0]=="*")
			$can_create = true;

	return $can_create;
}

/** Fix missing objectClass values in the specified LDAP entry

    Update the objectClass values of the specified LDAP entry to
    include any parent classes that are specified in the schema but
    not in the entry itself. Also adds the object class "count"
    value if this was missing.

    @param string $ldap_server
	LDAP server object containing schema definitions that the entry's
	objectClass values will be checked against.
    @param array &$entry
	LDAP entry which is to be checked/fixed.
*/

function fix_missing_object_classes($ldap_server,&$entry)
{
	$objclass_index = 0;

	// fix missing object class count
	if(!isset($entry["objectclass"]["count"]))
		$entry["objectclass"]["count"] = count($entry["objectclass"]);

	while($objclass_index < $entry["objectclass"]["count"])
	{
		$container_class = $entry["objectclass"][$objclass_index];

		$container_parent = explode(",",
			$ldap_server->get_object_schema_setting(
			$container_class,"parent_class"));

		foreach($container_parent as $parent_class)
		{
			$already_listed = false;
			foreach($entry["objectclass"] as $container_class2)
			{
				if($container_class2 == $parent_class)
					$already_listed = true;
			}

			if(!$already_listed)
			{
				$entry["objectclass"][] = $parent_class;
				$entry["objectclass"]["count"]++;
			}
		}

		$objclass_index++;
	}
}

/** Log the user out (or discard details of any in-progress login sequence) */

function reset_login_session()
{
	unset($_SESSION["LOGIN_SENT"]);
	unset($_SESSION["LOGIN_USER"]);
	unset($_SESSION["LOGIN_PASSWORD"]);
	unset($_SESSION["LOGIN_BIND_DN"]);
	unset($_SESSION["LOGIN_UID"]);
	unset($_SESSION["CACHED_PERMISSIONS"]);
}

/** Get a list of required attributes for the specified LDAP entry

    Returns an array of all attributes listed in the required_attribs
    and (for structural classes) rdn_attrib schema settings of all the
    object classes used in the entry.

    Missing parent objectClass entries which should have been present
    according to the schema will not be taken into account. If this
    is required, call fix_missing_object_classes() first to add any
    missing objectClass entries.

    @param array $ldap_entry
	Array containing LDAP object for which the
	list of required attributes is to be returned
    @return
	Array of required attribute names
*/

function get_required_attribs($ldap_entry)
{
	global $ldap_server;

	if(isset($ldap_entry["objectclass"]["count"]))
		unset($ldap_entry["objectclass"]["count"]);

	$required_attribs = array();
	foreach($ldap_entry["objectclass"] as $object_class)
	{
		$required_attribs_for_class = explode(",",
			$ldap_server->get_object_schema_setting($object_class,"required_attribs"));

		if($required_attribs_for_class[0] != "")
			$required_attribs = array_merge($required_attribs_for_class,
				$required_attribs);

		if(in_array($ldap_server->get_object_schema_setting($object_class,"class_type"),
			array("structural","type88")))
		{
			$rdn_attribs_for_class = explode(",",
				$ldap_server->get_object_schema_setting($object_class,"rdn_attrib"));

			if($rdn_attribs_for_class[0] != "")
				$required_attribs = array_merge($rdn_attribs_for_class,
					$required_attribs);
		}
	}

	$required_attribs = array_unique($required_attribs);

	return $required_attribs;
}

/** Return array of attribute class names used in the specified display layout

    @param array $layout
	Entry viewer layout for which attribute class names are to be returned
    @return
	Array of attribute class names
*/

function get_layout_attributes($layout)
{
	$layout_attributes = array();
	foreach($layout as $aux_section)
		foreach($aux_section["attributes"] as $attribute)
			$layout_attributes[]=$attribute[0];

	return $layout_attributes;
}
?>
