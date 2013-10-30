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
				show_try_again_message("The user name/password you entered is not valid - access denied");
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

function show_try_again_message($message)
{
	global $ldap_base_dn;
	show_site_header();
	show_ldap_path($ldap_base_dn,$ldap_base_dn,"folder.png");
	echo "<p>" . $message . "</p>\n";
	echo "<a href=\"user.php\">Try again</a>\n</body>\n</html>";
}

function reset_login_session()
{
	unset($_SESSION["LOGIN_SENT"]);
	unset($_SESSION["LOGIN_USER"]);
	unset($_SESSION["LOGIN_PASSWORD"]);
}

function return_to_previous_url()
{
	// TODO: look out for nasties being passed in HTTP_REFERER
	// TODO: figure out how to find the main page URL more nicely

	// if current page is user.php then redirect to main page instead
	// to avoid a redirect loop on user login retry

	if(basename($_SERVER["PHP_SELF"]="user.php") || empty($_SERVER["HTTP_REFERER"]))
		// go back to main page
		$redirect_uri = "/";
	else
		$redirect_uri = $_SERVER["HTTP_REFERER"];

	header("Location: " . $redirect_uri);
}

?>
