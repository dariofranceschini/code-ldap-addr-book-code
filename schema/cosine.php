<?php
/** COSINE and Internet X.500 Schema (cosine.schema)

    The Cooperation for Open Systems Interconnection Networking
    in Europe (COSINE) and Internet X.500 Pilot were early
    projects that implementing LDAP and X.500 directory services,
    primarily within the UK and European academic communities.

    The schema definitions designed by these projects were
    subsequently adopted by the IETF and became de facto
    Internet standards.

    (This file includes the attribute definitions only)

    @see http://www.ietf.org/rfc/rfc1274.txt
    @see http://www.ietf.org/rfc/rfc4524.txt
*/

class cosine_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			// Attributes defined in RFC 4524

			array("name"=>"associatedDomain",		"data_type"=>"text",		"display_name"=>gettext("Associated Domain")),
			array("name"=>"associatedName",			"data_type"=>"dn_list",		"display_name"=>gettext("Associated Name")),
			array("name"=>"buildingName",			"data_type"=>"text",		"display_name"=>gettext("Building Name")),
			array("name"=>"co",				"data_type"=>"text",		"display_name"=>gettext("Country Name")),
			array("name"=>"documentAuthor",			"data_type"=>"dn_list",		"display_name"=>gettext("Document Author")),
			array("name"=>"documentIdentifier",		"data_type"=>"text",		"display_name"=>gettext("Document Identifier")),
			array("name"=>"documentLocation",		"data_type"=>"text",		"display_name"=>gettext("Document Location")),
			array("name"=>"documentPublisher",		"data_type"=>"text",		"display_name"=>gettext("Document Publisher")),
			array("name"=>"documentTitle",			"data_type"=>"text",		"display_name"=>gettext("Document Title")),
			array("name"=>"documentVersion",		"data_type"=>"text",		"display_name"=>gettext("Document Version")),
			array("name"=>"drink",				"data_type"=>"text",		"display_name"=>gettext("Favorite Drink")),
			array("name"=>"homePhone",			"data_type"=>"phone_number",	"display_name"=>gettext("Home Telephone Number")),
			array("name"=>"homePostalAddress",		"data_type"=>"text_area",	"display_name"=>gettext("Home Address")),
			array("name"=>"host",				"data_type"=>"text",		"display_name"=>gettext("Host Computer")),
			array("name"=>"info",				"data_type"=>"text_area",	"display_name"=>gettext("General Information")),
			// "mail" (alias "rfc822Mailbox", included in COSINE as per RFC 4524) is defined in core schema instead
			array("name"=>"manager",			"data_type"=>"dn_list",		"display_name"=>gettext("Manager")),
			array("name"=>"mobile",				"data_type"=>"phone_number",	"display_name"=>gettext("Mobile/Cell Telephone Number")),
			array("name"=>"organizationalStatus",		"data_type"=>"text",		"display_name"=>gettext("Organizational Status")),
			array("name"=>"pager",				"data_type"=>"text",		"display_name"=>gettext("Pager Telephone Number")),
			array("name"=>"personalTitle",			"data_type"=>"text",		"display_name"=>gettext("Personal Title")),
			array("name"=>"roomNumber",			"data_type"=>"text",		"display_name"=>gettext("Room Number")),
			array("name"=>"secretary",			"data_type"=>"dn_list",		"display_name"=>gettext("Secretary")),
			array("name"=>"uniqueIdentifier",		"data_type"=>"text",		"display_name"=>gettext("Unique Identifier")),
			array("name"=>"userClass",			"data_type"=>"text",		"display_name"=>gettext("Category of User")),

			// LDAP representation of domain information (formerly experimental; now legacy)

			array("name"=>"aRecord",			"data_type"=>"text",		"display_name"=>gettext("IPv4 Address Record")),
			array("name"=>"mDRecord",			"data_type"=>"text",		"display_name"=>gettext("Mail Destination Record")),
			array("name"=>"mXRecord",			"data_type"=>"text",		"display_name"=>gettext("Mail Exchange Record")),
			array("name"=>"nSRecord",			"data_type"=>"text",		"display_name"=>gettext("Name Server Record")),
			array("name"=>"sOARecord",			"data_type"=>"text",		"display_name"=>gettext("Start of Authority Record")),
			array("name"=>"cNAMERecord",			"data_type"=>"text",		"display_name"=>gettext("Canonical Name Record")),

			// LDAP QoS information (formerly experimental; now legacy)
			// (data types/formats not supported by LDAP Address Book application, data_type values are approximate only)

			array("name"=>"dSAQuality",			"data_type"=>"text",		"display_name"=>gettext("Service Quality for this DSA")),
			array("name"=>"singleLevelQuality",		"data_type"=>"text",		"display_name"=>gettext("Data Quality for this DIT Level")),
			array("name"=>"subtreeMinimumQuality",		"data_type"=>"text",		"display_name"=>gettext("Minimum Data Quality for DIT Subtree")),
			array("name"=>"subtreeMaximumQuality",		"data_type"=>"text",		"display_name"=>gettext("Maximum Data Quality for DIT Subtree")),

			// Other legacy attributes not carried forward from predecessors of RFC 4524

			// "dc" (alias "domainComponent", included in COSINE as per RFC 1274) is defined in core schema instead
			array("name"=>"dITRedirect",			"data_type"=>"dn_list",		"display_name"=>gettext("DIT Redirection")),
			array("name"=>"janetMailbox",			"data_type"=>"text",		"display_name"=>gettext("Janet Mailbox")),
			array("name"=>"lastModifiedTime",		"data_type"=>"text",		"display_name"=>gettext("Last Modified Time")),
			array("name"=>"lastModifiedBy",			"data_type"=>"dn_list",		"display_name"=>gettext("Last Modified By")),
			array("name"=>"otherMailbox",			"data_type"=>"text",		"display_name"=>gettext("Other Mailbox")),
			array("name"=>"textEncodedORAddress",		"data_type"=>"text",		"display_name"=>gettext("Text Encoded O/R Address")),
			// "uid" (alias "userid", included in COSINE as per RFC 1274) is defined in core schema instead

			// Other legacy attributes not carried forward from predecessors of RFC 4524, of data types/formats not
			// supported by the LDAP Address Book application. (data_type values are approximate only)

			array("name"=>"audio",				"data_type"=>"text",		"display_name"=>gettext("Audio (u Law Format)")),
			array("name"=>"mailPreferenceOption",		"data_type"=>"text",		"display_name"=>gettext("Mail Preference Option")),
			array("name"=>"personalSignature",		"data_type"=>"image",           "display_name"=>gettext("Signature (G3 Fax Format)")),
			array("name"=>"photo",				"data_type"=>"image",		"display_name"=>gettext("Photo (G3 Fax Format)"))
			);

		parent::__construct($ldap_server);
	}
}
?>
