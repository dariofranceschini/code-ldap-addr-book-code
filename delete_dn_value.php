<?
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

if(prereq_components_ok())
{
	if($ldap_server->log_on())
	{
		if(get_user_setting("allow_edit"))
		{
			$error_message_prefix
				= gettext("Unable to delete an attribute value from this record")
				. ": ";
		        if(isset($_GET["dn"]))
				if(strlen($_GET["dn"])<=MAX_DN_LENGTH)
					$dn = $_GET["dn"];
				else
					show_error_message(
						$error_message_prefix
						. gettext("The object to delete from is not valid"));
		        else
				show_error_message($error_message_prefix
					. gettext("The record name was not specified"));

		        if(isset($_GET["attrib"]))
				$attrib = $_GET["attrib"];
		        else
				show_error_message($error_message_prefix
					. gettext("The attribute name was not specified."));

		        if(isset($_GET["value"]))
				$value = $_GET["value"];
		        else
				show_error_message($error_message_prefix
					. gettext("The attribute value was not specified."));

			$search_resource
				= @ldap_read($ldap_server->connection,
				$dn,$browse_ldap_filter,array("*","+"));

			if($search_resource)
			{
				$entry = ldap_get_entries(
					$ldap_server->connection,
					$search_resource);
				$ldap_server->call_schema_function(
					"before_delete_"
					. $ldap_server->get_object_class(
					$entry[0])
					. "_" . $attrib,$entry[0]);

				$removed_value[$attrib] = $value;

				$result = @ldap_mod_del(
					$ldap_server->connection,
					$dn,$removed_value);
				$error = ldap_error($ldap_server->connection);

				if($result)
					header("Location: info.php?dn="
						. urlencode($dn));
				else
				{
					show_site_header();
					show_ldap_path($dn);

					echo "<p>" . sprintf(gettext(
						"Error whilst deleting value from attribute '%s':  %s"),
						$attrib,$error) . "</p>";

					echo  "<p>\n  <a href=\"info.php?dn="
						. urlencode($dn) . "\">"
						. gettext("Return to the Address Book")
						. "</a>\n</p>";
				}
			}
			else
				show_error_message($error_message_prefix
					. gettext("Unable to access LDAP object."));
		}
		else
			show_error_message(gettext(
				"You do not have permission to change this record"));
	}
	else
        	 show_ldap_bind_error();
}
?>
