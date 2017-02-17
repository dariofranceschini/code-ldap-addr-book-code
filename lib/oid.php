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

/** Array of ASN.1/X.690 object identifiers/names

    @see https://www.ietf.org/assignments/ldap-parameters/ldap-parameters.xml
    @see http://svn.apache.org/repos/asf/directory/studio/trunk/plugins/connection.core/src/main/resources/org/apache/directory/studio/connection/core/OIDDescriptions.properties
    @see http://www.novell.com/documentation/developer/ldapover/ldap_enu/data/a6ik7oi.html
    @see http://fossies.org/linux/web2ldap/etc/web2ldap/ldapoidreg.py
    @see http://search.cpan.org/dist/perl-ldap-0.57/lib/Net/LDAP/Constant.pm
    @see https://docs.ldap.com/ldap-sdk/docs/specs/internet-drafts.html
    @see https://github.com/cannatag/ldap3/blob/master/ldap3/protocol/oid.py
    @see https://tools.ietf.org/html/draft-armijo-ldap-syntax-00
*/

$oid_name=array(
	// LDAP Grouping Types

	"2.16.840.1.113719.1.27.103.8"		=>"Transaction",				// draft-zeilenga-ldap-grouping-06

	// LDAP Controls

	"1.2.826.0.1.3344810.2.3"		=>"Matched Values",				// RFC3876
	"1.2.840.113556.1.4.319"		=>"Simple Paged Results",			// RFC2696
	"1.2.840.113556.1.4.473"		=>"Server Side Sorting Request",		// RFC2891
	"1.2.840.113556.1.4.474"		=>"Server Side Sorting Response",		// RFC2891
	"1.2.840.113556.1.4.417"		=>"Show Deleted",				// Microsoft
	"1.2.840.113556.1.4.521"		=>"Cross-Domain Move",				// Microsoft
	"1.2.840.113556.1.4.528"		=>"Server Search Notification",			// Microsoft
	"1.2.840.113556.1.4.529"		=>"Extended DN",				// Microsoft
	"1.2.840.113556.1.4.619"		=>"Lazy Commit",				// Microsoft
	"1.2.840.113556.1.4.801"		=>"Security Descriptor Flags",			// Microsoft
	"1.2.840.113556.1.4.802"		=>"Range Property",				// draft-kashi-incremental-00
	"1.2.840.113556.1.4.805"		=>"Tree Delete",				// draft-armijo-ldap-treedelete-02
	"1.2.840.113556.1.4.841"		=>"Directory Synchronization",			// Microsoft
	"1.2.840.113556.1.4.970"		=>"Get Stats",					// Microsoft
	"1.2.840.113556.1.4.1338"		=>"Verify Name",				// Microsoft
	"1.2.840.113556.1.4.1339"		=>"Domain Scope",				// Microsoft
	"1.2.840.113556.1.4.1340"		=>"Search Options",				// Microsoft
	"1.2.840.113556.1.4.1341"		=>"RODC DCPROMO",				// Microsoft
	"1.2.840.113556.1.4.1413"		=>"Permissive Modify",				// Microsoft
	"1.2.840.113556.1.4.1504"		=>"Attribute Scoped Query",			// Microsoft
	"1.2.840.113556.1.4.1852"		=>"Quota",					// Microsoft
	"1.2.840.113556.1.4.1907"		=>"Shutdown Notify",				// Microsoft
	"1.2.840.113556.1.4.1948"		=>"Range Retrieval No Error",			// Microsoft
	"1.2.840.113556.1.4.1974"		=>"Force Update",				// Microsoft
	"1.2.840.113556.1.4.2026"		=>"Input DN",					// Microsoft
	"1.2.840.113556.1.4.2064"		=>"Show Recycled",				// Microsoft
	"1.2.840.113556.1.4.2065"		=>"Show Deactivated Link",			// Microsoft
	"1.2.840.113556.1.4.2066"		=>"Policy Hints (Deprecated)",			// Microsoft
	"1.2.840.113556.1.4.2090"		=>"DirSync EX",					// Microsoft
	"1.2.840.113556.1.4.2204"		=>"Tree Delete EX",				// Microsoft
	"1.2.840.113556.1.4.2205"		=>"Update Stats",				// Microsoft
	"1.2.840.113556.1.4.2206"		=>"Search Hints",				// Microsoft
	"1.2.840.113556.1.4.2211"		=>"Expected Entry Count",			// Microsoft
	"1.2.840.113556.1.4.2239"		=>"Policy Hints",				// Microsoft
	"1.2.840.113556.1.4.2255"		=>"Set Owner",					// Microsoft
	"1.2.840.113556.1.4.2256"		=>"Bypass Quota",				// Microsoft
	"1.2.840.113556.1.4.2309"		=>"Link TTL",					// Microsoft
	"1.3.6.1.1.12"				=>"Assertion",					// RFC4528
	"1.3.6.1.1.13.1"			=>"Pre-read",					// RFC4527
	"1.3.6.1.1.13.2"			=>"Post-read",					// RFC4527
	"1.3.6.1.1.22"				=>"Don't Use Copy",				// RFC6171
	"1.3.6.1.4.1.4203.1.10.1"		=>"Subentries",					// RFC3672
	"1.3.6.1.4.1.4203.666.5.2"		=>"No-Op",					// draft-zeilenga-ldap-noop-12
	"2.16.840.1.113719.1.27.101.6"		=>"Create Forward Reference Request",		// Novell
	"2.16.840.1.113719.1.27.101.5"		=>"Simple Password",				// Novell
	"2.16.840.1.113719.1.27.103.7"		=>"Grouping",					// draft-zeilenga-ldap-grouping-06
	"2.16.840.1.113719.1.27.101.40"		=>"LDAP_CONTROL_SSTATREQUEST",			// Novell (Search Status Request?)
	"2.16.840.1.113730.3.4.2"		=>"Manage DSA Information Tree",		// RFC3296
	"2.16.840.1.113730.3.4.3"		=>"Persistent Search",				// draft-ietf-ldapext-psearch-03
	"2.16.840.1.113730.3.4.9"		=>"Virtual List View Request",			// draft-ietf-ldapext-ldapv3-vlv-09
	"2.16.840.1.113730.3.4.10"		=>"Virtual List View Response",			// draft-ietf-ldapext-ldapv3-vlv-09
	"2.16.840.1.113730.3.4.18"		=>"Proxy Authorization",			// RFC4370

	// LDAP Features

	"1.3.6.1.1.14"				=>"Modify-Increment",				// RFC4525
	"1.3.6.1.4.1.4203.1.5.1"		=>"All Operational Attributes",			// RFC3673
	"1.3.6.1.4.1.4203.1.5.2"		=>"Object Class Attribute Description Lists",	// RFC4529
	"1.3.6.1.4.1.4203.1.5.3"		=>"True/False Filters",				// RFC4526
	"1.3.6.1.4.1.4203.1.5.4"		=>"Language Tag Options",			// RFC3866
	"1.3.6.1.4.1.4203.1.5.5"		=>"Language Range Options",			// RFC3866
	"2.16.840.1.113719.1.27.99.1"		=>"Superior References",			// Novell

	// LDAP Extended Operations

	"1.3.6.1.1.8"				=>"Cancel",					// RFC3909
	"1.3.6.1.4.1.1466.20037"		=>"Start TLS",					// RFC4511, RFC4513
	"1.3.6.1.4.1.4203.1.11.1"		=>"Modify Password",				// RFC3062
	"1.3.6.1.4.1.4203.1.11.3"		=>"Who Am I?",					// RFC4532
	"2.16.840.1.113719.1.27.100.1"		=>"ndsToLdapResponse",				// Novell
	"2.16.840.1.113719.1.27.100.3"		=>"createNamingContextRequest",			// Novell
	"2.16.840.1.113719.1.27.100.5"		=>"mergeNamingContextRequest",			// Novell
	"2.16.840.1.113719.1.27.100.7"		=>"addReplicaRequest",				// Novell
	"2.16.840.1.113719.1.27.100.9"		=>"refreshLDAPServerRequest",			// Novell
	"2.16.840.1.113719.1.27.100.11"		=>"removeReplicaRequest",			// Novell
	"2.16.840.1.113719.1.27.100.13"		=>"namingContextEntryCountRequest",		// Novell
	"2.16.840.1.113719.1.27.100.15"		=>"changeReplicaTypeRequest",			// Novell
	"2.16.840.1.113719.1.27.100.17"		=>"getReplicaInfoRequest",			// Novell
	"2.16.840.1.113719.1.27.100.19"		=>"listReplicaRequest",				// Novell
	"2.16.840.1.113719.1.27.100.21"		=>"receiveAllUpdatesRequest",			// Novell
	"2.16.840.1.113719.1.27.100.23"		=>"sendAllUpdatesRequest",			// Novell
	"2.16.840.1.113719.1.27.100.25"		=>"requestNamingContextSyncRequest",		// Novell
	"2.16.840.1.113719.1.27.100.27"		=>"requestSchemaSyncRequest",			// Novell
	"2.16.840.1.113719.1.27.100.29"		=>"abortNamingContextOperationRequest",		// Novell
	"2.16.840.1.113719.1.27.100.31"		=>"getContextIdentityNameRequest",		// Novell
	"2.16.840.1.113719.1.27.100.33"		=>"getEffectivePrivilegesRequest",		// Novell
	"2.16.840.1.113719.1.27.100.35"		=>"SetReplicationFilterRequest",		// Novell
	"2.16.840.1.113719.1.27.100.37"		=>"getReplicationFilterRequest",		// Novell
	"2.16.840.1.113719.1.27.100.39"		=>"createOrphanPartitionrequest",		// Novell
	"2.16.840.1.113719.1.27.100.41"		=>"removeOrphanPartitionRequest",		// Novell
	"2.16.840.1.113719.1.27.100.43"		=>"triggerBKLinkerRequest",			// Novell
	"2.16.840.1.113719.1.27.100.45"		=>"triggerDRLProcessRequest",			// Novell
	"2.16.840.1.113719.1.27.100.47"		=>"triggerJanitorRequest",			// Novell
	"2.16.840.1.113719.1.27.100.49"		=>"triggerLimberRequest",			// Novell
	"2.16.840.1.113719.1.27.100.51"		=>"triggerSkulkerRequest",			// Novell
	"2.16.840.1.113719.1.27.100.53"		=>"triggerSchemaSyncRequest",			// Novell
	"2.16.840.1.113719.1.27.100.55"		=>"triggerPartitionPurgeRequest",		// Novell
	"2.16.840.1.113719.1.27.100.79"		=>"EventMonitorRequest",			// Novell
	"2.16.840.1.113719.1.27.100.84"		=>"filteredEventMonitorRequest",		// Novell
	"2.16.840.1.113719.1.27.100.96"		=>"LDAPBackupRequest",				// Novell
	"2.16.840.1.113719.1.27.100.98"		=>"LDAPRestoreRequest",				// Novell
	"2.16.840.1.113719.1.27.100.101"	=>"LDAPDNStoX500DNRequest",			// Novell
	"2.16.840.1.113719.1.27.100.103"	=>"Get Privileges List Request",		// Novell
	"2.16.840.1.113719.1.27.103.1"		=>"createGroupingRequest",			// draft-zeilenga-ldap-grouping-06
	"2.16.840.1.113719.1.27.103.2"		=>"endGroupingRequest",				// draft-zeilenga-ldap-grouping-06
	"2.16.840.1.113719.1.39.42.100.1"	=>"NMAS Put Login Configuration Request",	// Novell
	"2.16.840.1.113719.1.39.42.100.3"	=>"NMAS Get Login Configuration Request",	// Novell
	"2.16.840.1.113719.1.39.42.100.5"	=>"NMAS Delete Login Configuration Request",	// Novell
	"2.16.840.1.113719.1.39.42.100.7"	=>"NMAS Put Login Secret Request",		// Novell
	"2.16.840.1.113719.1.39.42.100.9"	=>"NMAS Delete Login Secret Request",		// Novell
	"2.16.840.1.113719.1.39.42.100.11"	=>"NMAS Set Password Request",			// Novell
	"2.16.840.1.113719.1.39.42.100.13"	=>"NMAS Get Password Request",			// Novell
	"2.16.840.1.113719.1.39.42.100.15"	=>"NMAS Delete Password Request",		// Novell
	"2.16.840.1.113719.1.39.42.100.17"	=>"NMAS Password Policy Check Request",		// Novell
	"2.16.840.1.113719.1.39.42.100.19"	=>"Get Password Policy Information",		// Novell
	"2.16.840.1.113719.1.39.42.100.21"	=>"Change Universal Password",			// Novell
	"2.16.840.1.113719.1.39.42.100.23"	=>"Graded Authentication Management",		// Novell
	"2.16.840.1.113719.1.39.42.100.25"	=>"NMAS Management",				// Novell
	"2.16.840.1.113719.1.142.100.1"		=>"startFramedProtocolRequest",			// Novell
	"2.16.840.1.113719.1.142.100.4"		=>"endFramedProtocolRequest",			// Novell
	"2.16.840.1.113719.1.142.100.6"		=>"lburpOperationRequest",			// Novell
	"2.16.840.1.113719.1.148.100.1"		=>"Put Login Configuration",			// Novell
	"2.16.840.1.113719.1.148.100.3"		=>"Get Login Configuration",			// Novell
	"2.16.840.1.113719.1.148.100.5"		=>"Delete Login Configuration",			// Novell
	"2.16.840.1.113719.1.148.100.7"		=>"Put Login Secret",				// Novell
	"2.16.840.1.113719.1.148.100.9"		=>"Delete Login Secret",			// Novell
	"2.16.840.1.113719.1.148.100.11"	=>"Set Universal Password",			// Novell
	"2.16.840.1.113719.1.148.100.13"	=>"Get Universal Password",			// Novell
	"2.16.840.1.113719.1.148.100.15"	=>"Delete Universal Password",			// Novell
	"2.16.840.1.113719.1.148.100.17"	=>"Check Password Against Password Policy",	// Novell

	// LDAP Capabilities

	"1.2.840.113556.1.4.800"		=>"Active Directory",				// Microsoft
	"1.2.840.113556.1.4.1670"		=>"Active Directory V51: Windows Server 2003",	// Microsoft
	"1.2.840.113556.1.4.1791"		=>"Active Directory LDAP Integration",		// Microsoft
	"1.2.840.113556.1.4.1851"		=>"Active Directory ADAM: Lightweight Directory Services", // Microsoft
	"1.2.840.113556.1.4.1880"		=>"Active Directory ADAM: Accept DIGEST-MD5 Bind", // Microsoft
	"1.2.840.113556.1.4.1920"		=>"Active Directory: Read-Only Domain Controller", // Microsoft
	"1.2.840.113556.1.4.1935"		=>"Active Directory V60: Windows Server 2008",	// Microsoft
	"1.2.840.113556.1.4.2080"		=>"Active Directory V61R2: Windows Server 2008 R2", // Microsoft
	"1.2.840.113556.1.4.2237"		=>"LDAP_CAP_ACTIVE_DIRECTORY_W8_OID",		// Microsoft (Windows Server 2012)

	// LDAP Attribute Syntax Types

	"1.2.840.113556.1.4.903"		=>"DN with Octet String",			// Microsoft
	"1.2.840.113556.1.4.904"		=>"DN with Unicode String",			// Microsoft
	"1.2.840.113556.1.4.905"		=>"Case Ignore String (Telex)",			// Microsoft
	"1.2.840.113556.1.4.906"		=>"Large Integer",				// Microsoft
	"1.2.840.113556.1.4.907"		=>"Object Security Descriptor",			// Microsoft
	"1.2.840.113556.1.4.1221"		=>"OR Name",					// Microsoft
	"1.2.840.113556.1.4.1362"		=>"Case Exact String",				// Microsoft
	"1.3.6.1.1.1.0.0"			=>"RFC2307 NIS Netgroup Triple",		// RFC2307
	"1.3.6.1.1.1.0.1"			=>"RFC2307 Boot Parameter",			// RFC2307
	"1.3.6.1.1.16.1"			=>"UUID",					// RFC4530
	"1.3.6.1.4.1.1466.115.121.1.1"		=>"ACI Item",					// RFC2252
	"1.3.6.1.4.1.1466.115.121.1.2"		=>"Access Point",				// RFC2252
	"1.3.6.1.4.1.1466.115.121.1.3"		=>"Attribute Type Description",			// RFC2252
	"1.3.6.1.4.1.1466.115.121.1.4"		=>"Audio",					// RFC2252
	"1.3.6.1.4.1.1466.115.121.1.5"		=>"Binary",					// RFC2252
	"1.3.6.1.4.1.1466.115.121.1.6"		=>"Bit String",					// RFC2252
	"1.3.6.1.4.1.1466.115.121.1.7"		=>"Boolean",					// RFC2252
	"1.3.6.1.4.1.1466.115.121.1.8"		=>"Certificate",				// RFC2252
	"1.3.6.1.4.1.1466.115.121.1.9"		=>"Certificate List",				// RFC2252
	"1.3.6.1.4.1.1466.115.121.1.10"		=>"Certificate Pair",				// RFC2252
	"1.3.6.1.4.1.1466.115.121.1.11"		=>"Country String",				// RFC2252
	"1.3.6.1.4.1.1466.115.121.1.12"		=>"DN",						// RFC2252
	"1.3.6.1.4.1.1466.115.121.1.13"		=>"Data Quality Syntax",			// RFC2252
	"1.3.6.1.4.1.1466.115.121.1.14"		=>"Delivery Method",				// RFC2252
	"1.3.6.1.4.1.1466.115.121.1.15"		=>"Directory String",				// RFC2252
	"1.3.6.1.4.1.1466.115.121.1.16"		=>"DIT Content Rule Description",		// RFC2252
	"1.3.6.1.4.1.1466.115.121.1.17"		=>"DIT Structure Rule Description",		// RFC2252
	"1.3.6.1.4.1.1466.115.121.1.18"		=>"DL Submit Permission",			// RFC2252
	"1.3.6.1.4.1.1466.115.121.1.19"		=>"DSA Quality Syntax",				// RFC2252
	"1.3.6.1.4.1.1466.115.121.1.20"		=>"DSE Type",					// RFC2252
	"1.3.6.1.4.1.1466.115.121.1.21"		=>"Enhanced Guide",				// RFC2252
	"1.3.6.1.4.1.1466.115.121.1.22"		=>"Facsimile Telephone Number",			// RFC2252
	"1.3.6.1.4.1.1466.115.121.1.23"		=>"Fax",					// RFC2252
	"1.3.6.1.4.1.1466.115.121.1.24"		=>"Generalized Time",				// RFC2252
	"1.3.6.1.4.1.1466.115.121.1.25"		=>"Guide",					// RFC2252
	"1.3.6.1.4.1.1466.115.121.1.26"		=>"IA5 String",					// RFC2252
	"1.3.6.1.4.1.1466.115.121.1.27"		=>"Integer",					// RFC2252
	"1.3.6.1.4.1.1466.115.121.1.28"		=>"JPEG",					// RFC2252
	"1.3.6.1.4.1.1466.115.121.1.29"		=>"Master And Shadow Access Points",		// RFC2252
	"1.3.6.1.4.1.1466.115.121.1.30"		=>"Matching Rule Description",			// RFC2252
	"1.3.6.1.4.1.1466.115.121.1.31"		=>"Matching Rule Use Description",		// RFC2252
	"1.3.6.1.4.1.1466.115.121.1.32"		=>"Mail Preference",				// RFC2252
	"1.3.6.1.4.1.1466.115.121.1.33"		=>"MHS OR Address",				// RFC2252
	"1.3.6.1.4.1.1466.115.121.1.34"		=>"Name And Optional UID",			// RFC2252
	"1.3.6.1.4.1.1466.115.121.1.35"		=>"Name Form Description",			// RFC2252
	"1.3.6.1.4.1.1466.115.121.1.36"		=>"Numeric String",				// RFC2252
	"1.3.6.1.4.1.1466.115.121.1.37"		=>"Object Class Description",			// RFC2252
	"1.3.6.1.4.1.1466.115.121.1.38"		=>"OID",					// RFC2252
	"1.3.6.1.4.1.1466.115.121.1.39"		=>"Other Mailbox",				// RFC2252
	"1.3.6.1.4.1.1466.115.121.1.40"		=>"Octet String",				// RFC2252
	"1.3.6.1.4.1.1466.115.121.1.41"		=>"Postal Address",				// RFC2252
	"1.3.6.1.4.1.1466.115.121.1.42"		=>"Protocol Information",			// RFC2252
	"1.3.6.1.4.1.1466.115.121.1.43"		=>"Presentation Address",			// RFC2252
	"1.3.6.1.4.1.1466.115.121.1.44"		=>"Printable String",				// RFC2252
	"1.3.6.1.4.1.1466.115.121.1.45"		=>"Subtree Specification",			// RFC2252
	"1.3.6.1.4.1.1466.115.121.1.46"		=>"Supplier Information",			// RFC2252
	"1.3.6.1.4.1.1466.115.121.1.47"		=>"Supplier Or Consumer",			// RFC2252
	"1.3.6.1.4.1.1466.115.121.1.48"		=>"Supplier And Consumer",			// RFC2252
	"1.3.6.1.4.1.1466.115.121.1.49"		=>"Supported Algorithm",			// RFC2252
	"1.3.6.1.4.1.1466.115.121.1.50"		=>"Telephone Number",				// RFC2252
	"1.3.6.1.4.1.1466.115.121.1.51"		=>"Teletex Terminal Identifier",		// RFC2252
	"1.3.6.1.4.1.1466.115.121.1.52"		=>"Telex Number",				// RFC2252
	"1.3.6.1.4.1.1466.115.121.1.53"		=>"UTC Time",					// RFC2252
	"1.3.6.1.4.1.1466.115.121.1.54"		=>"LDAP Syntax Description",			// RFC2252
	"1.3.6.1.4.1.1466.115.121.1.55"		=>"Modify Rights",				// RFC2252
	"1.3.6.1.4.1.1466.115.121.1.56"		=>"LDAP Schema Definition",			// RFC2252
	"1.3.6.1.4.1.1466.115.121.1.57"		=>"LDAP Schema Description",			// RFC2252
	"1.3.6.1.4.1.1466.115.121.1.58"		=>"Substring Assertion",			// RFC2252
	"1.3.6.1.4.1.4203.1.1.1"		=>"OpenLDAP Void",				// OpenLDAP
	"1.3.6.1.4.1.4203.666.2.1"		=>"OpenLDAP Experimental ACI",			// OpenLDAP
	"1.3.6.1.4.1.4203.666.2.7"		=>"OpenLDAP Authz",				// OpenLDAP
	"1.3.6.1.4.1.4203.666.11.2.1"		=>"CSN",					// OpenLDAP
	"1.3.6.1.4.1.4203.666.11.5.3.1"		=>"Control",					// OpenLDAP
	"2.16.840.1.113719.1.1.5.1.0"		=>"Unknown",					// Novell
	"2.16.840.1.113719.1.1.5.1.6"		=>"Case Ignore List",				// Novell
	"2.16.840.1.113719.1.1.5.1.12"		=>"Tagged Data",				// Novell
	"2.16.840.1.113719.1.1.5.1.13"		=>"Octet List",					// Novell
	"2.16.840.1.113719.1.1.5.1.14"		=>"Tagged String",				// Novell
	"2.16.840.1.113719.1.1.5.1.15"		=>"Tagged Name and String",			// Novell
	"2.16.840.1.113719.1.1.5.1.16"		=>"NDS Replica Pointer",			// Novell
	"2.16.840.1.113719.1.1.5.1.17"		=>"NDS ACL",					// Novell
	"2.16.840.1.113719.1.1.5.1.19"		=>"NDS Timestamp",				// Novell
	"2.16.840.1.113719.1.1.5.1.22"		=>"Counter",					// Novell
	"2.16.840.1.113719.1.1.5.1.23"		=>"Tagged Name",				// Novell
	"2.16.840.1.113719.1.1.5.1.25"		=>"Typed Name"					// Novell
	);
?>
