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
	https://tools.ietf.org/html/draft-sermersheim-ldap-chaining-03
	https://tools.ietf.org/html/draft-sermersheim-ldap-distproc-02
	https://tools.ietf.org/html/draft-smith-ldap-c-api-ext-vlv-00
	https://tools.ietf.org/html/draft-weltman-ldapv3-proxy-13
	https://tools.ietf.org/html/draft-zeilenga-ldap-assert-05
	https://tools.ietf.org/html/draft-zeilenga-ldap-noop-12
	https://tools.ietf.org/html/draft-zeilenga-ldup-sync-06
	https://tools.ietf.org/html/draft-zeilenga-ldap-txn-15
	http://www.umich.edu/~dirsvcs/ldap/doc/other/ldap-ref.html
*/

$ldap_result_code = array(
	0=>gettext("Success"),						// RFC 4511
	1=>gettext("Operations error"),					// RFC 4511
	2=>gettext("Protocol error"),					// RFC 4511
	3=>gettext("Time limit exceeded"),				// RFC 4511
	4=>gettext("Size limit exceeded"),				// RFC 4511
	5=>gettext("Compare False"),					// RFC 4511
	6=>gettext("Compare True"),					// RFC 4511
	7=>gettext("Authentication method not supported"),		// RFC 4511
	8=>gettext("Strong(er) authentication required"),		// RFC 4511
	9=>gettext("Partial results and referral received"),		// Referrals Within the LDAPv2 Protocol
	10=>gettext("Referral"),					// RFC 4511
	11=>gettext("Administrative limit exceeded"),			// RFC 4511
	12=>gettext("Critical extension is unavailable"),		// RFC 4511
	13=>gettext("Confidentiality required"),			// RFC 4511
	14=>gettext("SASL bind in progress"),				// RFC 4511
	// 15 - not used (or not known)
	16=>gettext("No such attribute"),				// RFC 4511
	17=>gettext("Undefined attribute type"),			// RFC 4511
	18=>gettext("Inappropriate matching"),				// RFC 4511
	19=>gettext("Constraint violation"),				// RFC 4511
	20=>gettext("Type or value exists"),				// RFC 4511
	21=>gettext("Invalid syntax"),					// RFC 4511
	// 22-31 - not used (or not known)
	32=>gettext("No such object"),					// RFC 4511
	33=>gettext("Alias problem"),					// RFC 4511
	34=>gettext("Invalid DN syntax"),				// RFC 4511
	35=>gettext("Entry is a leaf"),					// RFC 1777
	36=>gettext("Alias dereferencing problem"),			// RFC 4511
	// 37-46 - not used (or not known)
	47=>gettext("Proxy Authorization Failure (X)"),			// draft-weltman-ldapv3-proxy-13
	48=>gettext("Inappropriate authentication"),			// RFC 4511
	49=>gettext("Invalid credentials"),				// RFC 4511
	50=>gettext("Insufficient access"),				// RFC 4511
	51=>gettext("Server is busy"),					// RFC 4511
	52=>gettext("Server is unavailable"),				// RFC 4511
	53=>gettext("Server is unwilling to perform"),			// RFC 4511
	54=>gettext("Loop detected"),					// RFC 4511
	// 55-59 - not used (or not known)
	60=>gettext("Sort control is required with VLV"),		//draft-smith-ldap-c-api-ext-vlv-00
	61=>gettext("VLV index range error"),				//draft-smith-ldap-c-api-ext-vlv-00
	// 62-63 - not used (or not known)
	64=>gettext("Naming violation"),				// RFC 4511
	65=>gettext("Object class violation"),				// RFC 4511
	66=>gettext("Operation not allowed on non-leaf"),		// RFC 4511
	67=>gettext("Operation not allowed on RDN"),			// RFC 4511
	68=>gettext("Already exists"),					// RFC 4511
	69=>gettext("Cannot modify object class"),			// RFC 4511
	70=>gettext("Results too large"),				// RFC 1798
	71=>gettext("Operation affects multiple DSAs"),			// RFC 4511
	// 72-75 - not used (or not known)
	76=>gettext("Virtual List View error"),				// draft-ietf-ldapext-ldapv3-vlv-09
	// 77-79 - not used (or not known)
	80=>gettext("Other (e.g., implementation specific) error"),	// RFC 4511
	81=>gettext("Can't contact LDAP server"),			// draft-ietf-ldapext-ldap-c-api-05
	82=>gettext("Local error"),					// draft-ietf-ldapext-ldap-c-api-05
	83=>gettext("Encoding error"),					// draft-ietf-ldapext-ldap-c-api-05
	84=>gettext("Decoding error"),					// draft-ietf-ldapext-ldap-c-api-05
	85=>gettext("Timed out"),					// draft-ietf-ldapext-ldap-c-api-05
	86=>gettext("Unknown authentication method"),			// draft-ietf-ldapext-ldap-c-api-05
	87=>gettext("Bad search filter"),				// draft-ietf-ldapext-ldap-c-api-05
	88=>gettext("User cancelled operation"),			// draft-ietf-ldapext-ldap-c-api-05
	89=>gettext("Bad parameter to an LDAP routine"),		// draft-ietf-ldapext-ldap-c-api-05
	90=>gettext("Out of memory"),					// draft-ietf-ldapext-ldap-c-api-05
	91=>gettext("Connect error"),					// draft-ietf-ldapext-ldap-c-api-05
	92=>gettext("Not Supported"),					// draft-ietf-ldapext-ldap-c-api-05
	93=>gettext("Control not found"),				// draft-ietf-ldapext-ldap-c-api-05
	94=>gettext("No results returned"),				// draft-ietf-ldapext-ldap-c-api-05
	95=>gettext("More results to return"),				// draft-ietf-ldapext-ldap-c-api-05
	96=>gettext("Client Loop"),					// draft-ietf-ldapext-ldap-c-api-05
	97=>gettext("Referral Limit Exceeded"),				// draft-ietf-ldapext-ldap-c-api-05
	// 98-112 - not used (or not known)
	113=>gettext("LCUP Resources Exhausted"),			// RFC 3928
	114=>gettext("LCUP Security Violation"),			// RFC 3928
	115=>gettext("LCUP Invalid Data"),				// RFC 3928
	116=>gettext("LCUP Unsupported Scheme"),			// RFC 3928
	117=>gettext("LCUP Reload Required"),				// RFC 3928
	118=>gettext("Cancelled"),					// RFC 3909
	119=>gettext("No Operation to Cancel"),				// RFC 3909
	120=>gettext("Too Late to Cancel"),				// RFC 3909
	121=>gettext("Cannot Cancel"),					// RFC 3909
	122=>gettext("Assertion Failed"),				// RFC 4528
	123=>gettext("Proxied Authorization Denied"),			// RFC 4370
	// 124-4095 - not used (or not known)

	// 4096-16383 (0x1000-0x3FFF) - experimental use range
	4096=>gettext("Content Sync Refresh Required"),			// RFC 4533

	// 16384-65535 (0x4000-0xFFFF) - private use range
	16640=>gettext("Content Sync Refresh Required (X)"),		// draft-zeilenga-ldup-sync-06
	16654=>gettext("No Operation (X)"),				// draft-zeilenga-ldap-noop-12
	16655=>gettext("Assertion Failed (X)"),				// draft-zeilenga-ldap-assert-05
	16656=>gettext("Could not produce a referral (X)"),		// draft-sermersheim-ldap-chaining-03
	16657=>gettext("Unable to chain the request (X)"),		// draft-sermersheim-ldap-chaining-03
	16658=>gettext("Invalid Reference (X)"),			// draft-sermersheim-ldap-distproc-02
	16672=>gettext("TXN specify okay (X)"),				// draft-zeilenga-ldap-txn-15
	16673=>gettext("TXN ID is invalid (X)")				// draft-zeilenga-ldap-txn-15
	);

define("LDAP_SUCCESS",				0);	// RFC 4511
define("LDAP_OPERATIONS_ERROR",			1);	// RFC 4511
define("LDAP_PROTOCOL_ERROR",			2);	// RFC 4511
define("LDAP_TIMELIMIT_EXCEEDED",		3);	// RFC 4511
define("LDAP_SIZELIMIT_EXCEEDED",		4);	// RFC 4511
define("LDAP_COMPARE_FALSE",			5);	// RFC 4511
define("LDAP_COMPARE_TRUE",			6);	// RFC 4511
define("LDAP_AUTH_METHOD_NOT_SUPPORTED",	7);	// RFC 4511
define("LDAP_STRONG_AUTH_REQUIRED",		8);	// RFC 4511
define("LDAP_PARTIAL_RESULTS",			9);	// Referrals Within the LDAPv2 Protocol
define("LDAP_REFERRAL",				10);	// RFC 4511
define("LDAP_ADMINLIMIT_EXCEEDED",		11);	// RFC 4511
define("LDAP_UNAVAILABLE_CRITICAL_EXTENSION",	12);	// RFC 4511
define("LDAP_CONFIDENTIALITY_REQUIRED",		13);	// RFC 4511
define("LDAP_SASL_BIND_INPROGRESS",		14);	// RFC 4511
// 15 - not used (or not known)
define("LDAP_NO_SUCH_ATTRIBUTE",		16);	// RFC 4511
define("LDAP_UNDEFINED_TYPE",			17);	// RFC 4511
define("LDAP_INAPPROPRIATE_MATCHING",		18);	// RFC 4511
define("LDAP_CONSTRAINT_VIOLATION",		19);	// RFC 4511
define("LDAP_TYPE_OR_VALUE_EXISTS",		20);	// RFC 4511
define("LDAP_INVALID_SYNTAX",			21);	// RFC 4511
// 22-31 - not used (or not known)
define("LDAP_NO_SUCH_OBJECT",			32);	// RFC 4511
define("LDAP_ALIAS_PROBLEM",			33);	// RFC 4511
define("LDAP_INVALID_DN_SYNTAX",		34);	// RFC 4511
define("LDAP_IS_LEAF",				35);	// RFC 1777
define("LDAP_ALIAS_DEREF_PROBLEM",		36);	// RFC 4511
// 37-46 - not used (or not known)
define("LDAP_X_PROXY_AUTHZ_FAILURE",		47);	// draft-weltman-ldapv3-proxy-13
define("LDAP_INAPPROPRIATE_AUTH",		48);	// RFC 4511
define("LDAP_INVALID_CREDENTIALS",		49);	// RFC 4511
define("LDAP_INSUFFICIENT_ACCESS",		50);	// RFC 4511
define("LDAP_BUSY",				51);	// RFC 4511
define("LDAP_UNAVAILABLE",			52);	// RFC 4511
define("LDAP_UNWILLING_TO_PERFORM",		53);	// RFC 4511
define("LDAP_LOOP_DETECT",			54);	// RFC 4511
// 55-59 - not used (or not known)
define("LDAP_SORT_CONTROL_MISSING",		60);	// draft-smith-ldap-c-api-ext-vlv-00
define("LDAP_INDEX_RANGE_ERROR",		61);	// draft-smith-ldap-c-api-ext-vlv-00
// 62-63 - not used (or not known)
define("LDAP_NAMING_VIOLATION",			64);	// RFC 4511
define("LDAP_OBJECT_CLASS_VIOLATION",		65);	// RFC 4511
define("LDAP_NOT_ALLOWED_ON_NONLEAF",		66);	// RFC 4511
define("LDAP_NOT_ALLOWED_ON_RDN",		67);	// RFC 4511
define("LDAP_ALREADY_EXISTS",			68);	// RFC 4511
define("LDAP_NO_OBJECT_CLASS_MODS",		69);	// RFC 4511
define("LDAP_RESULTS_TOO_LARGE",		70);	// RFC 1798
define("LDAP_AFFECTS_MULTIPLE_DSAS",		71);	// RFC 4511
// 72-75 - not used (or not known)
define("LDAP_VLV_ERROR",			76);	// draft-ietf-ldapext-ldapv3-vlv-09
// 77-79 - not used (or not known)
define("LDAP_OTHER",				80);	// RFC 4511
define("LDAP_SERVER_DOWN",			81);	// draft-ietf-ldapext-ldap-c-api-05
define("LDAP_LOCAL_ERROR",			82);	// draft-ietf-ldapext-ldap-c-api-05
define("LDAP_ENCODING_ERROR",			83);	// draft-ietf-ldapext-ldap-c-api-05
define("LDAP_DECODING_ERROR",			84);	// draft-ietf-ldapext-ldap-c-api-05
define("LDAP_TIMEOUT",				85);	// draft-ietf-ldapext-ldap-c-api-05
define("LDAP_AUTH_UNKNOWN",			86);	// draft-ietf-ldapext-ldap-c-api-05
define("LDAP_FILTER_ERROR",			87);	// draft-ietf-ldapext-ldap-c-api-05
define("LDAP_USER_CANCELLED",			88);	// draft-ietf-ldapext-ldap-c-api-05
define("LDAP_PARAM_ERROR",			89);	// draft-ietf-ldapext-ldap-c-api-05
define("LDAP_NO_MEMORY",			90);	// draft-ietf-ldapext-ldap-c-api-05
define("LDAP_CONNECT_ERROR",			91);	// draft-ietf-ldapext-ldap-c-api-05
define("LDAP_NOT_SUPPORTED",			92);	// draft-ietf-ldapext-ldap-c-api-05
define("LDAP_CONTROL_NOT_FOUND",		93);	// draft-ietf-ldapext-ldap-c-api-05
define("LDAP_NO_RESULTS_RETURNED",		94);	// draft-ietf-ldapext-ldap-c-api-05
define("LDAP_MORE_RESULTS_TO_RETURN",		95);	// draft-ietf-ldapext-ldap-c-api-05
define("LDAP_CLIENT_LOOP",			96);	// draft-ietf-ldapext-ldap-c-api-05
define("LDAP_REFERRAL_LIMIT_EXCEEDED",		97);	// draft-ietf-ldapext-ldap-c-api-05
// 98-112 - not used (or not known)
define("LDAP_CUP_RESOURCES_EXHAUSTED",		113);	// RFC 3928
define("LDAP_CUP_SECURITY_VIOLATION",		114);	// RFC 3928
define("LDAP_CUP_INVALID_DATA",			115);	// RFC 3928
define("LDAP_CUP_UNSUPPORTED_SCHEME",		116);	// RFC 3928
define("LDAP_CUP_RELOAD_REQUIRED",		117);	// RFC 3928
define("LDAP_CANCELLED",			118);	// RFC 3909
define("LDAP_NO_SUCH_OPERATION",		119);	// RFC 3909
define("LDAP_TOO_LATE",				120);	// RFC 3909
define("LDAP_CANNOT_CANCEL",			121);	// RFC 3909
define("LDAP_ASSERTION_FAILED",			122);	// RFC 4528
define("LDAP_PROXIED_AUTHORIZATION_DENIED",	123);	// RFC 4370
// 124-4095 - not used (or not known)

// 4096-16383 (0x1000-0x3FFF) - experimental use range
define("LDAP_SYNC_REFRESH_REQUIRED",		4096);	// RFC 4533

// 16384-65535 (0x4000-0xFFFF) - private use range
define("LDAP_X_SYNC_REFRESH_REQUIRED",		16640);	// draft-zeilenga-ldup-sync-06
define("LDAP_X_NO_OPERATION",			16654);	// draft-zeilenga-ldap-noop-12
define("LDAP_X_ASSERTION_FAILED",		16655);	// draft-zeilenga-ldap-assert-05
define("LDAP_X_NO_REFERRALS_FOUND",		16656);	// draft-sermersheim-ldap-chaining-03
define("LDAP_X_CANNOT_CHAIN",			16657);	// draft-sermersheim-ldap-chaining-03
define("LDAP_X_INVALIDREFERENCE",		16658);	// draft-sermersheim-ldap-distproc-02
define("LDAP_X_TXN_SPECIFY_OKAY",		16672);	// draft-zeilenga-ldap-txn-15
define("LDAP_X_TXN_ID_INVALID",			16673);	// draft-zeilenga-ldap-txn-15
?>
