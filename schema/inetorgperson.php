<?php
/** Internet/Intranet Organizational Person Schema (inetorgperson.schema)

    The inetOrgPerson object class extends the X.521 standard's
    organizationalPerson class with additional attributes to better
    meet the requirements of Internet/Intranet directory service
    deployments.

    @see https://www.ietf.org/rfc/rfc2798.txt
*/

class inetorgperson_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"carLicense",			"data_type"=>"text",		"display_name"=>gettext("Vehicle License/Registration")),
			array("name"=>"departmentNumber",		"data_type"=>"text",		"display_name"=>gettext("Department Number")),
			array("name"=>"displayName",			"data_type"=>"text",		"display_name"=>gettext("Display/Preferred Name")),
			array("name"=>"employeeNumber",			"data_type"=>"text",		"display_name"=>gettext("Employee Number")),
			array("name"=>"employeeType",			"data_type"=>"text",		"display_name"=>gettext("Employee Type")),
			array("name"=>"jpegPhoto",			"data_type"=>"image",		"display_name"=>gettext("Photograph")),
			array("name"=>"preferredLanguage",		"data_type"=>"text",		"display_name"=>gettext("Preferred Language")),
			array("name"=>"userPKCS12;binary",		"data_type"=>"download_list",	"display_name"=>gettext("PKCS #12 PFX Data")),
			array("name"=>"userSMIMECertificate;binary",	"data_type"=>"download_list",	"display_name"=>gettext("S/MIME Certificate"))
			);

		// Object classes
		$this->object_schema = array(
			array("name"=>"inetOrgPerson",			"icon"=>"user24.png",		"is_folder"=>false,"required_attribs"=>"sn","can_create"=>true,"parent_class"=>"organizationalPerson")
			);

		// Display layouts
		$ldap_server->add_display_layout("inetOrgPerson",array(
			array("section_name"=>gettext("Personal"),
				"attributes"=>array(
					array("givenName",			gettext("Given Name"),		"contact24.png","allow_view"=>false),
			//		array("initials",			gettext("Initials"),		"contact24.png","allow_view"=>false),
					array("sn",				gettext("Surname"),		"contact24.png","allow_view"=>false),
					array("cn",				gettext("Full Name"),		"contact24.png","allow_view"=>false),
					array("displayName",			gettext("Preferred Name"),	"contact24.png"),
					array("mail",				gettext("E-mail"),		"mail.png"),
					array("homePhone",			gettext("Home Phone"),		"landline-phone.png"),
			//		array("pager",				gettext("Pager"),		"generic24.png"),
					array("mobile",				gettext("Mobile Phone"),	"cell-phone.png"),
			//		array("preferredLanguage",		gettext("Preferred Language"),	"chat.png"),
			//		array("preferredDeliveryMethod",	gettext("Preferred Delivery Method"),	"generic24.png"),

					// Address of Internet/Intranet Organizational Person
					// Several potential address layouts are possible, depending on country-specific
					// convention and individual preference.
					// If used, the postalCode attribute can control a link to a web-based map
					// service which shows the organizational unit's location.

					// Component-based address representation:
					array("street:l:st:postalCode",		gettext("Postal Address"),	"address.png"),

					// Component-based address representation including a field for PO Box
					// array("postOfficeBox:street:l:st:postalCode",gettext("Postal Address"),"address.png"),

					// Addresses combined into a single attribute:
					// array("homePostalAddress",		gettext("Home Address"),	"address.png"),
					// array("postalAddress",		gettext("Postal Address"),	"address.png"),

			//		array("carLicense",			gettext("Car License Plate"),	"generic24.png"),

					array("jpegPhoto",			gettext("Photo"),		"photo24.png")
					)
				),
			array("section_name"=>gettext("Business/Work"),"width"=>"50%",
				"attributes"=>array(
					array("o",				gettext("Company"),		"company.png"),
					// array("businessCategory",		gettext("Business Category"),	"company.png"),
					array("labeledURI",			gettext("Web Page"),		"internet.png"),
					array("telephoneNumber",		gettext("Office Phone"),	"landline-phone.png"),
					array("facsimileTelephoneNumber",	gettext("Office Fax"),		"fax.png"),
					// array("internationaliSDNNumber",	gettext("ISDN"),		"landline-phone.png"),
					array("title",				gettext("Job Title"),		"org-role.png"),
			//		array("employeeNumber",			gettext("Employee Number"),	"id.png"),
			//		array("employeeType",			gettext("Employee Type"),	"generic24.png"),
			//		array("roomNumber",			gettext("Room Number"),		"room.png"),
			//		array("manager",			gettext("Manager"),		"org-role.png"),
			//		array("secretary",			gettext("Secretary"),		"org-role.png"),
					array("ou",				gettext("Department"),		"org.png"),

					// If your directory's address fields contain a business address rather than a
					// home address then you may want to move the address from the "Personal" section
					// of the display layout (above) to here. Another possible setup is:
					//
					//	homePostalAddress - Used for personal/home address
					//	postalAddress - Used for business/work address

					// array("postalAddress",		gettext("Postal Address"),	"address.png"),

			//		array("departmentNumber",		gettext("Department Number"),	"org.png"),
					array("physicalDeliveryOfficeName",	gettext("Office"),		"office.png")
					)
				),

			// Uncomment this section in order to display login details

			/*
			array("section_name"=>gettext("Login Details"),"new_row"=>true,"colspan"=>2,
				"attributes"=>array(
					array("uid",				gettext("User ID"),		"id.png"),
					array("userPassword",			gettext("Password"),		"password.png"),
					)
				),
			*/

			// Uncomment this section in order to display group memberships.
			// On OpenLDAP this requires the "memberof" overlay to be set up.

			/*
			array("section_name"=>gettext("Group Membership"),"new_row"=>true,"colspan"=>2,
				"attributes"=>array(
					array("memberOf",null,null,"allow_edit"=>false)
					)
				),
			*/

			// Uncomment this section in order to display certificate data.
			// (You may wish to remove/comment out those fields which are
			// not used in your directory.)

			/*
			array("section_name"=>gettext("Certificate Data"),"new_row"=>true,"colspan"=>2,
				"attributes"=>array(
					array("userCertificate;binary",		gettext("User Certificate"),	"cert.png"),
					array("userSMIMECertificate;binary",	gettext("S/MIME Certificate"),	"cert.png"),
					array("userPKCS12;binary",		gettext("PKCS #12 PFX Data"),	"key-material.png")
					)
				),
			*/

			array("section_name"=>gettext("Additional Notes"),"new_row"=>true,"colspan"=>2,
				"attributes"=>array(
					array("description")
					)
				),

			/*
			array("section_name"=>gettext("See Also"),"new_row"=>true,"colspan"=>2,
				"attributes"=>array(
					array("seeAlso")
					)
				)
			*/
			));

		parent::__construct($ldap_server);
	}
}
?>
