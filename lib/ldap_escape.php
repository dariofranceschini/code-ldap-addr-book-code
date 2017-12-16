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

// provide ldap_escape function for PHP <5.6

if(!defined("LDAP_ESCAPE_FILTER")) define("LDAP_ESCAPE_FILTER",1);
if(!defined("LDAP_ESCAPE_DN")) define("LDAP_ESCAPE_DN",2);

/** Prepare a string for use as an LDAP value or DN.

    Escapes otherwise invalid characters, e.g. guard against
    "LDAP injection" attacks.

    Based on ldap_escape 2.0 by Chris Wright, modified to be
    more syntax compatible with ldap_escape() introduced in
    PHP 5.6

    @see
    http://stackoverflow.com/questions/8560874/php-ldap-add-function-to-escape-ldap-special-characters-in-dn-syntax

    @param string $subject
	Text to be escaped for use as an LDAP value or DN
    @param mixed $ignore
	Optional list of characters to leave untouched
	(set to null if no characters to be left untouched)
    @param integer $flags
	The context the escaped string will be used in:

	LDAP_ESCAPE_FILTER - escape for use in a search filter
	LDAP_ESCAPE_DN - escape for use as a DN

    @return
	Sanitised version of string with invalid characters escaped
*/

function ldap_escape($subject,$ignore=null,$flags=null)
{
	// The base array of characters to escape
	switch($flags)
	{
		case LDAP_ESCAPE_FILTER:
			$search = array('\\',"*","(",")","\x00");
			break;
		case LDAP_ESCAPE_DN:
			$search = array('\\',",","=","+","<",">",";","\"","#");
			break;
		default:
			// as per PHP 5.6 implementation
			$search = array();
	}

	// Flip to keys for easy use of unset()
	$search = array_flip($search);

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

	if(!empty($result))
	{
		// Encode leading spaces in DN values
		if($flags==LDAP_ESCAPE_DN && $result[0] == " ")
			$result = '\\20' . substr($result, 1);

		// Encode trailing spaces in DN values
		if($flags==LDAP_ESCAPE_DN && $result[strlen($result)-1] == " ")
			$result = substr($result,0,-1) . '\\20';
	}

	return $result;
}
?>
