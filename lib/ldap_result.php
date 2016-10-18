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

/** Array of LDAP result codes

    @see
	https://www.ietf.org/rfc/rfc1777.txt
	https://www.ietf.org/rfc/rfc1798.txt
	https://www.ietf.org/rfc/rfc3909.txt
	https://www.ietf.org/rfc/rfc3928.txt
	https://www.ietf.org/rfc/rfc4370.txt
	https://www.ietf.org/rfc/rfc4511.txt
	https://www.ietf.org/rfc/rfc4528.txt
	https://www.ietf.org/rfc/rfc4533.txt
	https://tools.ietf.org/html/draft-ietf-ldapext-ldap-c-api-05
	https://tools.ietf.org/html/draft-ietf-ldapext-ldapv3-vlv-09
	https://tools.ietf.org/html/draft-weltman-ldapv3-proxy-13
	https://tools.ietf.org/html/draft-zeilenga-ldap-assert-05
	https://tools.ietf.org/html/draft-zeilenga-ldap-noop-12
	https://tools.ietf.org/html/draft-zeilenga-ldup-sync-06
	https://tools.ietf.org/html/draft-zeilenga-ldap-txn-15
	http://www.umich.edu/~dirsvcs/ldap/doc/other/ldap-ref.html
*/

$ldap_result_code = array(
	0=>"Success",						// RFC 4511
	1=>"Operations error",					// RFC 4511
	2=>"Protocol error",					// RFC 4511
	3=>"Time limit exceeded",				// RFC 4511
	4=>"Size limit exceeded",				// RFC 4511
	5=>"Compare False",					// RFC 4511
	6=>"Compare True",					// RFC 4511
	7=>"Authentication method not supported",		// RFC 4511
	8=>"Strong(er) authentication required",		// RFC 4511
	9=>"Partial results and referral received",		// Referrals Within the LDAPv2 Protocol
	10=>"Referral",					// RFC 4511
	11=>"Administrative limit exceeded",			// RFC 4511
	12=>"Critical extension is unavailable",		// RFC 4511
	13=>"Confidentiality required",			// RFC 4511
	14=>"SASL bind in progress",				// RFC 4511
	// 15 - not used (or not known)
	16=>"No such attribute",				// RFC 4511
	17=>"Undefined attribute type",			// RFC 4511
	18=>"Inappropriate matching",				// RFC 4511
	19=>"Constraint violation",				// RFC 4511
	20=>"Type or value exists",				// RFC 4511
	21=>"Invalid syntax",					// RFC 4511
	// 22-31 - not used (or not known)
	32=>"No such object",					// RFC 4511
	33=>"Alias problem",					// RFC 4511
	34=>"Invalid DN syntax",				// RFC 4511
	35=>"Entry is a leaf",					// RFC 1777
	36=>"Alias dereferencing problem",			// RFC 4511
	// 37-46 - not used (or not known)
	47=>"Proxy Authorization Failure (X)",			// draft-weltman-ldapv3-proxy-13
	48=>"Inappropriate authentication",			// RFC 4511
	49=>"Invalid credentials",				// RFC 4511
	50=>"Insufficient access",				// RFC 4511
	51=>"Server is busy",					// RFC 4511
	52=>"Server is unavailable",				// RFC 4511
	53=>"Server is unwilling to perform",			// RFC 4511
	54=>"Loop detected",					// RFC 4511
	// 55-63 - not used (or not known)
	64=>"Naming violation",				// RFC 4511
	65=>"Object class violation",				// RFC 4511
	66=>"Operation not allowed on non-leaf",		// RFC 4511
	67=>"Operation not allowed on RDN",			// RFC 4511
	68=>"Already exists",					// RFC 4511
	69=>"Cannot modify object class",			// RFC 4511
	70=>"Results too large",				// RFC 1798
	71=>"Operation affects multiple DSAs",			// RFC 4511
	// 72-75 - not used (or not known)
	76=>"Virtual List View error",				// draft-ietf-ldapext-ldapv3-vlv-09
	// 77-79 - not used (or not known)
	80=>"Other (e.g., implementation specific) error",	// RFC 4511
	81=>"Can't contact LDAP server",			// draft-ietf-ldapext-ldap-c-api-05
	82=>"Local error",					// draft-ietf-ldapext-ldap-c-api-05
	83=>"Encoding error",					// draft-ietf-ldapext-ldap-c-api-05
	84=>"Decoding error",					// draft-ietf-ldapext-ldap-c-api-05
	85=>"Timed out",					// draft-ietf-ldapext-ldap-c-api-05
	86=>"Unknown authentication method",			// draft-ietf-ldapext-ldap-c-api-05
	87=>"Bad search filter",				// draft-ietf-ldapext-ldap-c-api-05
	88=>"User cancelled operation",			// draft-ietf-ldapext-ldap-c-api-05
	89=>"Bad parameter to an LDAP routine",		// draft-ietf-ldapext-ldap-c-api-05
	90=>"Out of memory",					// draft-ietf-ldapext-ldap-c-api-05
	91=>"Connect error",					// draft-ietf-ldapext-ldap-c-api-05
	92=>"Not Supported",					// draft-ietf-ldapext-ldap-c-api-05
	93=>"Control not found",				// draft-ietf-ldapext-ldap-c-api-05
	94=>"No results returned",				// draft-ietf-ldapext-ldap-c-api-05
	95=>"More results to return",				// draft-ietf-ldapext-ldap-c-api-05
	96=>"Client Loop",					// draft-ietf-ldapext-ldap-c-api-05
	97=>"Referral Limit Exceeded",				// draft-ietf-ldapext-ldap-c-api-05
	// 98-112 - not used (or not known)
	113=>"LCUP Resources Exhausted",			// RFC 3928
	114=>"LCUP Security Violation",			// RFC 3928
	115=>"LCUP Invalid Data",				// RFC 3928
	116=>"LCUP Unsupported Scheme",			// RFC 3928
	117=>"LCUP Reload Required",				// RFC 3928
	118=>"Cancelled",					// RFC 3909
	119=>"No Operation to Cancel",				// RFC 3909
	120=>"Too Late to Cancel",				// RFC 3909
	121=>"Cannot Cancel",					// RFC 3909
	122=>"Assertion Failed",				// RFC 4528
	123=>"Proxied Authorization Denied",			// RFC 4370
	// 124-4095 - not used (or not known)
	4096=>"Content Sync Refresh Required",			// RFC 4533

	// 16384-65535 (0x4000-0xFFFF) - private use range
	16640=>"Content Sync Refresh Required (X)",		// draft-zeilenga-ldup-sync-06
	16654=>"No Operation (X)",				// draft-zeilenga-ldap-noop-12
	16655=>"Assertion Failed (X)",				// draft-zeilenga-ldap-assert-05
	16672=>"TXN specify okay",				// draft-zeilenga-ldap-txn-15
	16673=>"TXN ID is invalid"				// draft-zeilenga-ldap-txn-15
	);
?>