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

// TODO: sanitise base DN from URL:
//	stop "nasties" being passed through to the LDAP server
//	prevent access to directory outside of address book base DN
if(!empty($_GET["dn"])) $dn = $_GET["dn"]; else $dn = $ldap_base_dn;

// TODO: sanitise attribute name from URL:
//	stop "nasties" being passed through to the LDAP server
if(!empty($_GET["attrib"]))
	$attrib = $_GET["attrib"];

// TODO: sanitise attribute value index from URL:
if(!empty($_GET["index"]))
	$index = $_GET["index"];
else
	$index = 0;

if($ldap_server->log_on())
{
	$search_resource = ldap_read($ldap_server->connection,$dn,"(objectclass=*)");
	$entry = ldap_get_entries($ldap_server->connection,$search_resource);

	$rdn_attrib = $ldap_server->get_object_schema_setting(
		$ldap_server->get_object_class($entry[0])
		,"rdn_attrib");

	$rdn_list = explode(",",$rdn_attrib);

	$filename = "";
	foreach($rdn_list as $rdn)
	{
		if($filename != "") $filename .= "_";

		if(isset($entry[0][strtolower($rdn)][0]))
			$filename .= $entry[0][strtolower($rdn)][0];
		else
			$filename .= $entry[0][strtolower($rdn)];
	}

	$filename.= "_" . $attrib;
	if($index>0)
		$filename .= "_" . ($index+1);

	header("Content-type:application/binary");
	header("Content-Disposition: attachment; filename=\""
		. $filename . ".bin\"");

	$attrib = strtolower($attrib);

	if(!empty($entry[0][$attrib][$index]))
		$attrib_value = $entry[0][$attrib][$index];
	else
		$attrib_value = "";

	echo $attrib_value;
}
else
{
	show_ldap_bind_error();
}
?>
