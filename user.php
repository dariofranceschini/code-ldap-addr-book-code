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

include "utils.php";
include "config.php";

// Create a new session (if none already exists)
if(!isset($_SESSION)) session_start();

if(!isset($_SESSION["LOGIN_USER"]))
	if(!isset($_SESSION["LOGIN_SENT"]))
	{
		// Display login dialogue

		$_SESSION["LOGIN_SENT"]=true;
		header("WWW-Authenticate: Basic realm=\"" . $site_name . "\"");
		header("HTTP/1.0 401 Unauthorized");

		// TODO: send back to originating page? message if cancel clicked
		show_try_again_message(gettext("You must enter a valid login and password"));
	}
	else
	{
		// Process credentials received from login dialogue

		unset($_SESSION["LOGIN_SENT"]);

		if(!empty($_SERVER["PHP_AUTH_USER"]))	// attempt login if user/password non blank
		{
			// once confirmed valid, set LOGIN_USER session variable
			// to the user's name. (other parts of application assume
			// blank means nobody logged in)

			$_SESSION["LOGIN_USER"] = $_SERVER["PHP_AUTH_USER"];
			$_SESSION["LOGIN_PASSWORD"] = base64_encode($_SERVER["PHP_AUTH_PW"]);
			$_SESSION["CACHED_PERMISSIONS"] = array();

			if($ldap_server->log_on())
				return_to_previous_url();
			else
			{
				reset_login_session();
				show_try_again_message(gettext("The user name/password you entered "
					. "is not valid"));
			}
		}
		else
			show_try_again_message(gettext("You must enter a user name/password to log in"));
	}
else
{
	// log user out
	reset_login_session();
	return_to_previous_url();
}

/** Display a page explaining why the login wasn't accepted

    Includes link that the user can select in order to
    try again.

    @param string $message
	Text of message to be displayed.
*/

function show_try_again_message($message)
{
	global $ldap_base_dn;
	show_site_header();
	show_ldap_path($ldap_base_dn,$ldap_base_dn,"schema/folder.png");
	echo "<p>" . $message . "</p>\n";
	echo "<a href=\"user.php\">" . gettext("Try again") . "</a>\n";
	show_site_footer();
}

/** Log the user out (or discard details of any in-progress login sequence) */

function reset_login_session()
{
	unset($_SESSION["LOGIN_SENT"]);
	unset($_SESSION["LOGIN_USER"]);
	unset($_SESSION["LOGIN_PASSWORD"]);
	unset($_SESSION["LOGIN_BIND_DN"]);
	unset($_SESSION["CACHED_PERMISSIONS"]);
}

/** Redirect user to their previous URL once login has been completed

    Previous URL is based on the HTTP "Referer" header field if
    available, otherwise returns the user to the address book's main
    page.

    Special case: If the refering URL was user.php then the user is
    redirected to the address book's main main page instead,in order
    to avoid creating a redirect loop on failed/retried login.

    @todo
	Look out for malicious user agents passing nasties to this
	function via HTTP_REFERER.
*/

function return_to_previous_url()
{
	if(empty($_SERVER["HTTP_REFERER"]) || basename($_SERVER["HTTP_REFERER"])=="user.php")
		// go back to main page
		$redirect_uri = current_page_folder_url();
	else
		$redirect_uri = $_SERVER["HTTP_REFERER"];

	header("Location: " . $redirect_uri);
}

?>
