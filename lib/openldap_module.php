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

/** Array of loadable OpenLDAP overlay modules

    The following overlays are built into other parts of OpenLDAP
    and do not exist as standalone modules:

	chain - built into back_ldap
	denyop - built into slapd
	glue - built into slapd

   The back_sock module contains both backend and overlay functionality.
   For the purposes of including it in either $openldap_overlay_module or
   $openldap_backend_module it is considered to be a backend.
*/

$openldap_overlay_module=array(
	"accesslog"	=> "Access Logging Overlay",
	"auditlog"	=> "Audit Logging Overlay",
	"autogroup"	=> "AutoGroup Overlay",
	"collect"	=> "Collective Attributes Overlay",
	"constraint"	=> "Constraints Overlay",
	"dds"		=> "Dynamic Directory Services Overlay",
	"deref"		=> "Dereferencing Overlay",
	"dyngroup"	=> "Dynamic Groups Overlay",	// Deprecated - use dynlist instead
	"dynlist"	=> "Dynamic Lists Overlay",
	"memberof"	=> "Reverse Group Membership Maintenance Overlay",
	"nssov"		=> "NSS/PAM Lookup Overlay",
	"pcache"	=> "Proxy Cache Engine Overlay",
	"ppolicy"	=> "Password Policies Overlay",
	"refint"	=> "Referential Integrity Overlay",
	"retcode"	=> "Return Code Overlay",
	"rwm"		=> "Rewrite/Remap Overlay",
	"seqmod"	=> "Serialize Concurrent Writes Overlay",
	"sssvlv"	=> "Server Side Sorting and Virtual List View Overlay",
	"syncprov"	=> "Synchronisation Provider Overlay",
	"translucent"	=> "Translucent Proxy Overlay",
	"unique"	=> "Attribute Uniqueness Overlay",
	"valsort"	=> "Value Sorting Overlay"
	);

/** Array of loadable OpenLDAP backend modules

    The following backends are typically built into slapd
    rather than existing as standalone modules:

	config
	ldif
*/

$openldap_backend_module=array(
	"back_bdb"	=> "Berkeley DB Backend",	// Deprecated - use back_mdb instead
	"back_dnssrv"	=> "DNS SRV Referral Backend",
	"back_hdb"	=> "Hierarchical Layout Berkeley DB Backend",	// Deprecated - use back_mdb instead
	"back_ldap"	=> "LDAP Proxy Backend",
	"back_mdb"	=> "Memory-Mapped Database Backend",
	"back_meta"	=> "Metadirectory Backend",
	"back_monitor"	=> "Monitoring Backend",
	"back_null"	=> "Null Backend",
	"back_passwd"	=> "Password File Backend",
	"back_perl"	=> "Perl Interpreter Backend",
	"back_relay"	=> "Relay Backend",
	"back_shell"	=> "Shell Backend",
	"back_sock"	=> "Socket Backend and Overlay",
	"back_sql"	=> "SQL Database (ODBC) Backend",
	);
?>
