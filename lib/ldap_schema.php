<?php
/* ************************************************************************

   RFC 2252 LDAP Schema Parsing Functions

   Derived from Net_LDAP2 2.2.0 schema interface class
   Copyright (C) 2009 Jan Wagner and Benedikt Hallinger
   http://pear.php.net/package/Net_LDAP2/

   This program is free software: you can redistribute it and/or modify
   it under the terms of the GNU Lesser General Public License as published
   by the Free Software Foundation, version 3 of the License.

   This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU Lesser General Public License for more details.

   You should have received a copy of the GNU Lesser General Public License
   along with this program.  If not, see <http://www.gnu.org/licenses/>.

   ************************************************************************ */

/** Tokenizes the given value into an array of tokens

    @param string $value
	String to parse
    @return array
	Array of tokens
*/
function tokenize_ldap_schema_entry($value)
{
	$tokens = array();	// array of tokens
	$matches = array();	// matches[0] full pattern match, [1,2,3] subpatterns

	// this one is taken from perl-ldap, modified for php
	$pattern = "/\s* (?:([()]) | ([^'\s()]+) | '((?:[^']+|'[^\s)])*)') \s*/x";

	/**
	 * This one matches one big pattern wherin only one of the three subpatterns matched
	 * We are interested in the subpatterns that matched. If it matched its value will be
	 * non-empty and so it is a token. Tokens may be round brackets, a string, or a string
	 * enclosed by '
	 */
	preg_match_all($pattern,$value,$matches);

	for ($i = 0; $i<count($matches[0]); $i++)			// number of tokens (full pattern match)
		for ($j = 1; $j<4; $j++)				// each subpattern
			if (null != trim($matches[$j][$i]))		// pattern match in this subpattern
				$tokens[$i] = trim($matches[$j][$i]);	// this is the token

	return $tokens;
}

/** Parses an attribute value into a schema entry

    @param string $value
	Attribute value
    @return array|false
	Schema entry array or false
*/
function &parse_ldap_schema_entry($value)
{
	// tokens that have no value associated
	$noValue = array(
		"single-value",
		"obsolete",
		"collective",
		"no-user-modification",
		"abstract",
		"structural",
		"auxiliary",
		"indexed",		// AD specific
		"system-only"		// AD specific
);

	// tokens that can have multiple values
	$multiValue = array("must","may","sup");

	$schema_entry = array("aliases"=>array()); // initilization

	$tokens = tokenize_ldap_schema_entry($value); // get an array of tokens

	// remove surrounding brackets
	if ($tokens[0] == "(") array_shift($tokens);
	if ($tokens[count($tokens)-1] == ")") array_pop($tokens); // -1 doesnt work on arrays :-(

	$schema_entry["oid"] = array_shift($tokens); // first token is the oid

	// cycle over the tokens until none are left
	while (count($tokens)>0)
	{
		$token = strtolower(array_shift($tokens));
		if (in_array($token,$noValue))
			$schema_entry[$token] = 1; // single value token
		else
		{
			// this one follows a string or a list if it is multivalued
			if (($schema_entry[$token] = array_shift($tokens)) == "(")
			{
				// this creates the list of values and cycles through the tokens
				// until the end of the list is reached ')'
				$schema_entry[$token] = array();
				while ($tmp = array_shift($tokens))
				{
					if ($tmp == ")") break;
					if ($tmp != "$") array_push($schema_entry[$token],$tmp);
				}
			}
			// create a array if the value should be multivalued but was not
			if (in_array($token,$multiValue) && !is_array($schema_entry[$token]))
				$schema_entry[$token] = array($schema_entry[$token]);
		}
	}
	// get max length from syntax
	if (key_exists("syntax",$schema_entry))
		if (preg_match("/{(\d+)}/",$schema_entry["syntax"],$matches))
			$schema_entry["max_length"] = $matches[1];

	// force a name
	if (empty($schema_entry["name"]))
		$schema_entry["name"] = $schema_entry["oid"];

	// make one name the default and put the other ones into aliases
	if (is_array($schema_entry["name"]))
	{
		$aliases		= $schema_entry["name"];
		$schema_entry["name"]	= array_shift($aliases);
		$schema_entry["aliases"]= $aliases;
	}
	return $schema_entry;
}
?>
