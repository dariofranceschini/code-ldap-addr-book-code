<?php
/** LDAP Core Schema for User Applications (core.schema)

    @see http://www.ietf.org/rfc/rfc2079.txt
    @see http://www.ietf.org/rfc/rfc2256.txt
    @see http://www.ietf.org/rfc/rfc3280.txt
    @see http://www.ietf.org/rfc/rfc4519.txt
    @see http://www.ietf.org/rfc/rfc4523.txt
*/

class core_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"aliasedObjectName",		"data_type"=>"dn",		"display_name"=>gettext("Aliased Object"),		"alias_names"=>"aliasedEntryName"),
			// "associatedDomain" (in OpenLDAP core schema) is defined in COSINE schema instead as per RFC 4524
			array("name"=>"businessCategory",		"data_type"=>"text",		"display_name"=>gettext("Business Category")),
			array("name"=>"c",				"data_type"=>"country_code",	"display_name"=>gettext("Country Code"),		"alias_names"=>"countryName"),
			array("name"=>"cn",				"data_type"=>"text",		"display_name"=>gettext("Common Name/Full Name"),	"alias_names"=>"commonName"),
			array("name"=>"dc",				"data_type"=>"text",		"display_name"=>gettext("Domain Component Name"),	"alias_names"=>"domainComponent"),
			array("name"=>"description",			"data_type"=>"text",		"display_name"=>gettext("Description")),
			array("name"=>"destinationIndicator",		"data_type"=>"text",		"display_name"=>gettext("Telegram Destination Indicator")),
			array("name"=>"distinguishedName",		"data_type"=>"dn",		"display_name"=>gettext("Distinguished Name")),
			array("name"=>"dnQualifier",			"data_type"=>"text",		"display_name"=>gettext("DN Qualifier")),
			array("name"=>"enhancedSearchGuide",		"data_type"=>"text_list",	"display_name"=>gettext("Enhanced Search Guide")),
			array("name"=>"facsimileTelephoneNumber",	"data_type"=>"text",		"display_name"=>gettext("Fax Number"),			"alias_names"=>"fax"),
			array("name"=>"generationQualifier",		"data_type"=>"text",		"display_name"=>gettext("Generation Qualifier")),
			array("name"=>"givenName",			"data_type"=>"text",		"display_name"=>gettext("Given Name"),			"alias_names"=>"gn"),
			array("name"=>"houseIdentifier",		"data_type"=>"text",		"display_name"=>gettext("House Identifier")),
			array("name"=>"initials",			"data_type"=>"text",		"display_name"=>gettext("Initials")),
			array("name"=>"internationaliSDNNumber",	"data_type"=>"text",		"display_name"=>gettext("ISDN Number")),
			array("name"=>"l",				"data_type"=>"text",		"display_name"=>gettext("Locality (e.g. Town/City)"),	"alias_names"=>"localityName"),
			array("name"=>"labeledURI",			"data_type"=>"text",		"display_name"=>gettext("URI (with Optional Label)")),
			array("name"=>"mail",				"data_type"=>"text",		"display_name"=>gettext("E-mail Address"),		"alias_names"=>"rfc822Mailbox"),
			array("name"=>"member",				"data_type"=>"dn_list",		"display_name"=>gettext("Member")),
			array("name"=>"name",				"data_type"=>"text",		"display_name"=>gettext("Name")),
			array("name"=>"o",				"data_type"=>"text",		"display_name"=>gettext("Organization Name"),		"alias_names"=>"organizationName"),
			array("name"=>"objectClass",			"data_type"=>"text",		"display_name"=>gettext("Object Class")),
			array("name"=>"ou",				"data_type"=>"text",		"display_name"=>gettext("Organizational Unit Name"),	"alias_names"=>"organizationalUnitName"),
			array("name"=>"owner",				"data_type"=>"dn_list",		"display_name"=>gettext("Owner")),
			array("name"=>"physicalDeliveryOfficeName",	"data_type"=>"text",		"display_name"=>gettext("Office")),
			array("name"=>"postalAddress",			"data_type"=>"text_area",	"display_name"=>gettext("Postal Address")),
			array("name"=>"postalCode",			"data_type"=>"postcode",	"display_name"=>gettext("Postal Code")),
			array("name"=>"postOfficeBox",			"data_type"=>"text",		"display_name"=>gettext("Post Office Box")),
			array("name"=>"preferredDeliveryMethod",	"data_type"=>"text",		"display_name"=>gettext("Preferred Delivery Method")),
			array("name"=>"registeredAddress",		"data_type"=>"text_area",	"display_name"=>gettext("Registered Address")),
			array("name"=>"roleOccupant",			"data_type"=>"dn_list",		"display_name"=>gettext("Role Occupant")),
			array("name"=>"searchGuide",			"data_type"=>"text_list",	"display_name"=>gettext("Search Guide")),
			array("name"=>"seeAlso",			"data_type"=>"dn_list",		"display_name"=>gettext("See Also")),
			array("name"=>"serialNumber",			"data_type"=>"text",		"display_name"=>gettext("Serial Number")),
			array("name"=>"sn",				"data_type"=>"text",		"display_name"=>gettext("Surname"),			"alias_names"=>"surname"),
			array("name"=>"st",				"data_type"=>"text",		"display_name"=>gettext("State (or Province/County)"),	"alias_names"=>"stateOrProvinceName"),
			array("name"=>"street",				"data_type"=>"text_area",	"display_name"=>gettext("Street Address"),		"alias_names"=>"streetAddress"),
			array("name"=>"telephoneNumber",		"data_type"=>"phone_number",	"display_name"=>gettext("Telephone Number")),
			array("name"=>"teletexTerminalIdentifier",	"data_type"=>"text",		"display_name"=>gettext("Teletex Terminal Identifier")),
			array("name"=>"telexNumber",			"data_type"=>"text",		"display_name"=>gettext("Telex Number")),
			array("name"=>"title",				"data_type"=>"text",		"display_name"=>gettext("Job Title")),
			array("name"=>"uid",				"data_type"=>"text",		"display_name"=>gettext("User ID"),			"alias_names"=>"userid"),
			/** @todo "uniqueMember" data type is approximate only - the optional UID suffix notation is not currently supported */
			array("name"=>"uniqueMember",			"data_type"=>"dn_list",		"display_name"=>gettext("Unique Member")),
			array("name"=>"userPassword",			"data_type"=>"text",		"display_name"=>gettext("User Password")),
			array("name"=>"x121Address",			"data_type"=>"text",		"display_name"=>gettext("X.121 Network Address")),
			array("name"=>"x500UniqueIdentifier",		"data_type"=>"download_list",	"display_name"=>gettext("X.500 Unique Identifier")),

			// LDAP PKI (RFC 4523)

			array("name"=>"authorityRevocationList;binary",	"data_type"=>"download_list",	"display_name"=>gettext("Certification Authority Revocation List")),
			array("name"=>"cACertificate;binary",		"data_type"=>"download_list",	"display_name"=>gettext("Certification Authority Certificate")),
			array("name"=>"certificateRevocationList;binary","data_type"=>"download_list",	"display_name"=>gettext("Certificate Revocation List")),
			array("name"=>"crossCertificatePair;binary",	"data_type"=>"download",	"display_name"=>gettext("Cross Certificate Pair")),
			array("name"=>"deltaRevocationList;binary",	"data_type"=>"download_list",	"display_name"=>gettext("Delta Revocation List")),
			array("name"=>"email",				"data_type"=>"text",		"display_name"=>gettext("PKCS #9 E-mail Address"),	"alias_names"=>"emailAddress,pkcs9email"),
			array("name"=>"pseudonym",			"data_type"=>"text",		"display_name"=>gettext("Pseudonym")),
			array("name"=>"supportedAlgorithms;binary",	"data_type"=>"download_list",	"display_name"=>gettext("Supported Algorithms")),
			array("name"=>"userCertificate;binary",		"data_type"=>"download_list",	"display_name"=>gettext("User Certificate")),

			// Other legacy attributes not carried forward from predecessors of RFC 4519

			array("name"=>"dmdName",			"data_type"=>"text",		"display_name"=>gettext("Directory Management Domain Name")),
			array("name"=>"knowledgeInformation",		"data_type"=>"text",		"display_name"=>gettext("Knowledge Information")),
			array("name"=>"presentationAddress",		"data_type"=>"text",		"display_name"=>gettext("OSI Presentation Address")),
			array("name"=>"protocolInformation",		"data_type"=>"text",		"display_name"=>gettext("Protocol Information")),
			array("name"=>"supportedApplicationContext",	"data_type"=>"oid_list",	"display_name"=>gettext("Supported OSI Application Contexts")),
			);

		// Structural object classes
		$this->object_schema = array(
			array("name"=>"alias",				"icon"=>"alias.png",		"is_folder"=>false,"display_name"=>gettext("Alias"),"required_attribs"=>"aliasedObjectName"),
			array("name"=>"organizationalUnit",		"icon"=>"folder.png",		"is_folder"=>true,"rdn_attrib"=>"ou","display_name"=>gettext("Organizational Unit"),"can_create"=>true),
			array("name"=>"groupOfNames",			"icon"=>"group24.png",		"is_folder"=>false,"display_name"=>gettext("Group"),"required_attribs"=>"member","can_create"=>true),
			array("name"=>"groupOfUniqueNames",		"icon"=>"group24.png",		"is_folder"=>false,"display_name"=>gettext("Group (Unique Names)"),"required_attribs"=>"uniqueMember"),
			array("name"=>"person",				"icon"=>"user24.png",		"is_folder"=>false,"display_name"=>gettext("Person")),
			array("name"=>"organizationalPerson",		"icon"=>"user24.png",		"is_folder"=>false,"display_name"=>gettext("Organizational Person"),"parent_class"=>"person"),
			array("name"=>"residentialPerson",		"icon"=>"user24.png",		"is_folder"=>false,"display_name"=>gettext("Residential Person"),"parent_class"=>"person"),
			array("name"=>"applicationProcess",		"icon"=>"app.png",		"is_folder"=>false,"display_name"=>gettext("Application Process")),
			array("name"=>"device",				"icon"=>"device.png",		"is_folder"=>false,"display_name"=>gettext("Device")),
			array("name"=>"organizationalRole",		"icon"=>"org-role.png",		"is_folder"=>false,"display_name"=>gettext("Organizational Role"),"can_create"=>true),
			array("name"=>"country",			"icon"=>"country.png",		"is_folder"=>true,"rdn_attrib"=>"c","display_name"=>gettext("Country"),"can_create"=>true),
			array("name"=>"locality",			"icon"=>"locality.png",		"is_folder"=>true,"rdn_attrib"=>"l","display_name"=>gettext("Locality"),"can_create"=>true),
			array("name"=>"organization",			"icon"=>"org.png",		"is_folder"=>true,"rdn_attrib"=>"o","display_name"=>gettext("Organization"),"can_create"=>true),

			// LDAP PKI (RFC 4523)
			array("name"=>"cRLDistributionPoint",		"icon"=>"crl-distrib-point.png","is_folder"=>false,"display_name"=>gettext("Certificate Revocation List Distribution Point")),

			// legacy object classes not carried forward from predecessors of RFC 4519
			array("name"=>"applicationEntity",		"icon"=>"app.png",		"is_folder"=>false,"display_name"=>gettext("Application Entity")),
			array("name"=>"dmd",				"icon"=>"generic24.png",	"is_folder"=>false,"display_name"=>gettext("Directory Management Domain")),
			array("name"=>"dSA",				"icon"=>"ldap-server.png",	"is_folder"=>false,"display_name"=>gettext("Directory System Agent"),"parent_class"=>"applicationEntity"),
			);

		// Display layouts
		$ldap_server->add_display_layout("organizationalUnit",array(
			array("section_name"=>gettext("Folder Details"),
				"attributes"=>array(
					array("ou",				gettext("OU Name"),		"folder.png"),
					array("description",			gettext("Description"),		"description.png"),
					// array("businessCategory",		gettext("Business Category"),	"company.png"),
					// array("physicalDeliveryOfficeName",	gettext("Office Name"),		"office.png"),

					// Address of Organizational Unit
					//
					// Several potential address layouts are possible, depending on country-specific
					// conventions and individual preferences. If used, the postalCode attribute can
					// control a link to a web-based map service which shows the organization's location.

					// Multi-attribute address representation, not including a field for PO Box:

					array("street:l:st:postalCode",		gettext("Postal Address"),	"address.png"),

					// Multi-attribute address representation, including a field for PO Box:
					//
					// array("postOfficeBox:street:l:st:postalCode",gettext("Postal Address"),"address.png"),

					// Complete addresses represented using a single attribute value:
					//
					// array("postalAddress",		gettext("Postal Address"),	"address.png"),
					// array("registeredAddress",		gettext("Registered Address"),	"address.png"),

					array("telephoneNumber",		gettext("Phone"),		"landline-phone.png"),
					array("facsimileTelephoneNumber",	gettext("Fax"),			"fax.png")
					// array("internationaliSDNNumber",	gettext("ISDN"),		"landline-phone.png"),
					// array("seeAlso",			gettext("See Also"),		"alias.png"),
					)
				)
			));

		$ldap_server->add_display_layout("groupOfNames",array(
			array("colspan"=>2,"new_row"=>true,
				"attributes"=>array(
					array("cn",				gettext("Group Name"),		"group24.png"),
					array("description",			gettext("Description"),		"description.png"),

					// array("ou",				gettext("Department"),		"org.png"),
					// array("o",				gettext("Organization"),	"company.png"),
					// array("businessCategory",		gettext("Business Category"),	"company.png"),
					// array("owner",			gettext("Owner"),		"alias.png"),
					// array("seeAlso",			gettext("See Also"),		"alias.png"),
					)
				),
			array("section_name"=>gettext("Group Members"),"new_row"=>true,"width"=>"50%",
				"attributes"=>array(
					array("member")
					)
				)
			));

		$ldap_server->add_display_layout("groupOfUniqueNames",array(
			array("colspan"=>2,"new_row"=>true,
				"attributes"=>array(
					array("cn",				gettext("Group Name"),		"group24.png"),
					array("description",			gettext("Description"),		"description.png"),

					// array("ou",				gettext("Department"),		"org.png"),
					// array("o",				gettext("Organization"),	"company.png"),
					// array("businessCategory",		gettext("Business Category"),	"company.png"),
					// array("owner",			gettext("Owner"),		"alias.png"),
					// array("seeAlso",			gettext("See Also"),		"alias.png"),
					)
				),
			array("section_name"=>gettext("Group Members"),"new_row"=>true,"width"=>"50%",
				"attributes"=>array(
					array("uniqueMember")
					)
				)
			));

		$ldap_server->add_display_layout("locality",array(
			array("colspan"=>2,"new_row"=>true,
				"attributes"=>array(
					array("l",				gettext("Locality Name"),	"locality.png"),
					array("description",			gettext("Description"),		"description.png"),
					array("street:st",			gettext("Address"),		"address.png")
					// array("seeAlso",			gettext("See Also"),		"alias.png"),
					)
				)
			));

		$ldap_server->add_display_layout("organization",array(
			array("section_name"=>gettext("Organization Details"),
				"attributes"=>array(
					array("o",				gettext("Organization Name"),	"org.png"),
					array("description",			gettext("Description"),		"description.png"),
					// array("businessCategory",		gettext("Business Category"),	"company.png"),
					// array("physicalDeliveryOfficeName",	gettext("Office Name"),		"office.png"),

					// Address of Organization
					//
					// Several potential address layouts are possible, depending on country-specific
					// conventions and individual preferences. If used, the postalCode attribute can
					// control a link to a web-based map service which shows the organization's location.

					// Multi-attribute address representation, not including a field for PO Box:

					array("street:l:st:postalCode",		gettext("Postal Address"),	"address.png"),

					// Multi-attribute address representation, including a field for PO Box:
					//
					// array("postOfficeBox:street:l:st:postalCode",gettext("Postal Address"),"address.png"),

					// Complete addresses represented using a single attribute value:
					//
					// array("postalAddress",		gettext("Postal Address"),	"address.png"),
					// array("registeredAddress",		gettext("Registered Address"),	"address.png"),

					array("telephoneNumber",		gettext("Phone"),		"landline-phone.png"),
					array("facsimileTelephoneNumber",	gettext("Fax"),			"fax.png")
					// array("internationaliSDNNumber",	gettext("ISDN"),		"landline-phone.png"),
					// array("seeAlso",			gettext("See Also"),		"alias.png"),
					)
				)
			));

		$ldap_server->add_display_layout("organizationalRole",array(
			array("section_name"=>gettext("Organizational Role"),
				"attributes"=>array(
					array("cn",				gettext("Role Name"),		"org-role.png"),
					array("description",			gettext("Description"),		"description.png"),
					array("ou",				gettext("Department"),		"org.png"),

					// array("physicalDeliveryOfficeName",	gettext("Office Name"),		"office.png"),

					// Address of Organizational Role
					//
					// Several potential address layouts are possible, depending on country-specific
					// conventions and individual preferences. If used, the postalCode attribute can
					// control a link to a web-based map service which shows the organization's location.

					// Multi-attribute address representation, not including a field for PO Box:
					array("street:l:st:postalCode",		gettext("Postal Address"),	"address.png"),

					// Multi-attribute address representation, including a field for PO Box:
					//
					// array("postOfficeBox:street:l:st:postalCode",gettext("Postal Address"),"address.png"),

					// Complete addresses represented using a single attribute value:
					//
					// array("postalAddress",		gettext("Postal Address"),	"address.png"),
					// array("registeredAddress",		gettext("Registered Address"),	"address.png"),

					array("telephoneNumber",		gettext("Phone"),		"landline-phone.png"),
					array("facsimileTelephoneNumber",	gettext("Fax"),			"fax.png")
					// array("internationaliSDNNumber",	gettext("ISDN"),		"landline-phone.png"),
					// array("seeAlso",			gettext("See Also"),		"alias.png"),
					)
				),
			array("section_name"=>gettext("Role Occupants"),"new_row"=>true,
				"attributes"=>array(
					array("roleOccupant")
					)
				)
			));

		$ldap_server->add_display_layout("country",array(
			array("colspan"=>2,"new_row"=>true,
				"attributes"=>array(
					array("c",				gettext("Country Code"),	"country.png"),
					array("description",			gettext("Description"),		"description.png"),
					)
				)
			));

		$ldap_server->add_display_layout("alias",array(
			array("colspan"=>2,"new_row"=>true,
				"attributes"=>array(
					array("aliasedObjectName",		"Aliased Object",	"alias24.png"),
					)
				)
			));

		parent::__construct($ldap_server);
	}

	/** The "member" attribute of "groupOfNames" objects must always have at
	    least one value assigned. It cannot be omitted in order to represent
	    an empty group (i.e. a group which has no members).

	    Many LDAP appliications allow an empty group to be represented using
	    a "member" attribute containing single, blank value. The following
	    functions are responsible for adding this special value when the group
	    is empty, removing it when other group members are present and hiding
	    it in the user interface.

	    A group which contains the rootDSE object (blank DN) will appear
	    as an empty group.

	    Alternative workarounds used in other directory types include:

	    eDirectory:
		defines "member" as optional, which allows it to be missing
		if the group has no members

	    Active Directory:
		uses a separate "group" class in which "member" is optional.
	*/

	/** Add a blank placeholder group member when a new group is created */

	function before_create_groupOfNames(&$ldap_server,&$entry)
	{
		if(empty($entry["member"]))
			$this->add_attrib_single_value($ldap_server,$entry,"member","");
	}

	/** Hide the blank placeholder group member when displaying an empty group */

	function before_show_groupOfNames(&$ldap_server,&$entry)
	{
		if(isset($entry["member"]["count"]) && $entry["member"]["count"]==1 && empty($entry["member"][0]))
			unset($entry["member"]);
	}

	/** Remove the blank placeholder group member after a real member is added to a group */

	function after_add_groupOfNames_member(&$ldap_server,&$entry)
	{
		if($entry["member"]["count"]==2)
			@ldap_mod_del($ldap_server->connection,$entry["dn"],array("member"=>""));
	}

	/** Add a blank placeholder group member after the last (non-blank) group member
	is removed from a group */

	function before_delete_groupOfNames_member(&$ldap_server,&$entry)
	{
		if($entry["member"]["count"]==1)
			@ldap_mod_add($ldap_server->connection,$entry["dn"],array("member"=>""));
	}

	/** The "uniqueMember" attribute of "groupOfUniqueNames" objects must always have at
	    least one value assigned, similar to "groupOfNames" objects as described above.

	    Alternative workarounds used in other directory types include:

	    eDirectory:
		defines "groupOfUniqueNames" as an alias of "groupOfNames" rather than
		a separate class.

	    Active Directory:
		uses a separate "group" class and maintains referential integrity between
		the its member list and the corresponding directory objects.
	*/

	/** Add a blank placeholder group member when a new group is created */

	function before_create_groupOfUniqueNames(&$ldap_server,&$entry)
	{
		if(empty($entry["uniquemember"]))
			$this->add_attrib_single_value($ldap_server,$entry,"uniqueMember","");
	}

	/** Hide the blank placeholder group member when displaying an empty group */

	function before_show_groupOfUniqueNames(&$ldap_server,&$entry)
	{
		if(isset($entry["uniquemember"]["count"]) && $entry["uniquemember"]["count"]==1 && empty($entry["uniquemember"][0]))
			unset($entry["uniquemember"]);
	}

	/** Remove the blank placeholder group member after a real member is added to a group */

	function after_add_groupOfUniqueNames_uniqueMember(&$ldap_server,&$entry)
	{
		if($entry["uniquemember"]["count"]==2)
			@ldap_mod_del($ldap_server->connection,$entry["dn"],array("uniqueMember"=>""));
	}

	/** Add a blank placeholder group member after the last (non-blank) group member
	is removed from a group */

	function before_delete_groupOfUniqueNames_uniqueMember(&$ldap_server,&$entry)
	{
		if($entry["uniquemember"]["count"]==1)
			@ldap_mod_add($ldap_server->connection,$entry["dn"],array("uniqueMember"=>""));
	}
}
?>
