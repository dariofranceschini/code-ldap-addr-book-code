<?php
include "config.php";
include "utils.php";

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
		show_try_again_message("You must enter a valid login and password");
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
			$_SESSION["LOGIN_PASSWORD"] = $_SERVER["PHP_AUTH_PW"];

			if(log_on_to_directory($ldap_link))
				return_to_previous_url();
			else
			{
				reset_login_session();
				show_try_again_message("The user name/password you entered "
					. "is not valid");
			}
		}
		else
			show_try_again_message("You must enter a user name/password to log in");
	}
else
{
	// log user out
	reset_login_session();
	return_to_previous_url();
}

// Display a page explaining why the login wasn't accepted, with
// a link to try again.
//
// $message - Text of message to be displayed.

function show_try_again_message($message)
{
	global $ldap_base_dn;
	show_site_header();
	show_ldap_path($ldap_base_dn,$ldap_base_dn,"folder.png");
	echo "<p>" . $message . "</p>\n";
	echo "<a href=\"user.php\">Try again</a>\n";
	show_site_footer();
}

// Log user out/remove details of any in-progress login

function reset_login_session()
{
	unset($_SESSION["LOGIN_SENT"]);
	unset($_SESSION["LOGIN_USER"]);
	unset($_SESSION["LOGIN_PASSWORD"]);
}

// Return user to their previous URL once login has been completed
// (using contents of HTTP "Referer" header field if available,
// otherwise to the address book's main page)

function return_to_previous_url()
{
	// TODO: look out for nasties being passed in HTTP_REFERER

	// if current page is user.php then redirect to main page instead
	// to avoid a redirect loop on user login retry

	if(basename($_SERVER["PHP_SELF"]=="user.php") || empty($_SERVER["HTTP_REFERER"]))
		// go back to main page
		$redirect_uri = current_page_folder_url();
	else
		$redirect_uri = $_SERVER["HTTP_REFERER"];

	header("Location: " . $redirect_uri);
}

?>
