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

include "config.php";
include "utils.php";

show_site_header();

// TODO: sanitise base DN from URL:
//      stop "nasties" being passed through to the LDAP server
//      prevent access to directory outside of address book base DN

if(!empty($_GET["dn"])) $dn = str_replace("|","=",$_GET["dn"]);
else $dn = $ldap_base_dn;

if(log_on_to_directory($ldap_link))
{
	$search_resource = ldap_read($ldap_link,$dn,"(objectclass=*)");
	$entry = ldap_get_entries($ldap_link,$search_resource);

	$entry_viewer = new ldap_entry_viewer($entry);

	// ---------------------------------------------

	$entry_viewer->add_section("Personal",NEW_ROW);

	$entry_viewer->add_to_section("displayName",			"Preferred Name",	"contact24.png");
	$entry_viewer->add_to_section("mail",				"E-mail",		"mail.png");
	$entry_viewer->add_to_section("homePhone",			"Home Phone",		"landline-phone.png");
	// $entry_viewer->add_to_section("pager",			"Pager");
	$entry_viewer->add_to_section("mobile",				"Mobile Phone",		"cell-phone.png");
	$entry_viewer->add_to_section("wWWHomePage",			"Web Page",	"internet.png"); // business and personal web swapped round
	$entry_viewer->add_to_section("streetAddress:l:st:postalCode",	"Postal Address",	"address.png");
	$entry_viewer->add_to_section("c",				"Country",		"country.png");

	// ---------------------------------------------

	$entry_viewer->add_section("Business/Work",NULL,NULL,"50%");

	$entry_viewer->add_to_section("company",			"Company",		"company.png");
	$entry_viewer->add_to_section("url",				"Web Page",	"internet.png"); // business and personal web swapped round
	$entry_viewer->add_to_section("telephoneNumber",		"Office Phone",		"landline-phone.png");
	$entry_viewer->add_to_section("facsimileTelephoneNumber",	"Office Fax",		"fax.png");
	$entry_viewer->add_to_section("title",				"Job Title",		"id.png");
	$entry_viewer->add_to_section("department",			"Department",		"org.png");
	$entry_viewer->add_to_section("physicalDeliveryOfficeName",	"Office",		"office.png");

	// ---------------------------------------------

	$entry_viewer->add_section("Additional Notes",NEW_ROW,2);

	$entry_viewer->add_to_section("info");

	// ---------------------------------------------

	$entry_viewer->show();
}
else
{
	show_ldap_path($ldap_base_dn,$ldap_base_dn,"folder.png");
	show_ldap_bind_error();
}

echo "\n\n";

show_site_footer();
?>
