<?php
/** OpenLDAP MemberOf Overlay Configuration schema (partial) */

class openldap_memberof_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"olcMemberOfDN",			"data_type"=>"dn",		"display_name"=>gettext("Membership Attribute Modifier DN")),
			array("name"=>"olcMemberOfRefInt",		"data_type"=>"yes_no",		"display_name"=>gettext("Maintain Referential Integrity")),
			array("name"=>"olcMemberOfGroupOC",		"data_type"=>"text",		"display_name"=>gettext("Group Object Class")),
			array("name"=>"olcMemberOfMemberAD",		"data_type"=>"text",		"display_name"=>gettext("Group Member Attribute")),
			array("name"=>"olcMemberOfMemberOfAD",		"data_type"=>"text",		"display_name"=>gettext("Member Of Attribute")),
			array("name"=>"olcMemberOfDangling",		"data_type"=>"olc_dangling",	"display_name"=>gettext("Dangling Reference Handling Behaviour")),
			array("name"=>"olcMemberOfDanglingError",	"data_type"=>"ldap_result",	"display_name"=>gettext("Dangling Reference Error Code")),

			array("name"=>"memberOf",			"data_type"=>"dn_list",		"display_name"=>gettext("Member Of"))
			);

		// Structural object classes
		$this->object_schema = array(
			array("name"=>"olcMemberOf",			"icon"=>"openldap/overlay.png",		"is_folder"=>false,"rdn_attrib"=>"olcOverlay","display_name"=>gettext("MemberOf Overlay"),"can_create"=>true,"atomic_create"=>true,"parent_class"=>"olcOverlayConfig")
			);

		$ldap_server->add_display_layout("olcMemberOf",array(
			array("section_name"=>"MemberOf Overlay Settings","new_row"=>true,
				"attributes"=>array(
					array("olcOverlay",			"Overlay Object Name",				"openldap/overlay.png"),
					array("olcMemberOfGroupOC",		"Group Object Class",				"group24.png"),
					array("olcMemberOfMemberAD",		"Group Member Attribute",			"user24.png"),
					array("olcMemberOfMemberOfAD",		"Member Of (Back Reference) Attribute",		"user-alias24.png"),
					array("olcMemberOfDangling",		"Dangling Reference Handling Behaviour",	"generic24.png"),
					array("olcMemberOfDanglingError",	"Dangling Reference Error Code (If Used)",	"openldap/error.png"),
					array("olcMemberOfRefInt",		"Maintain Referential Integrity",		"generic24.png")
					),
				),
			));

		parent::__construct($ldap_server);
	}

	function populate_for_create_olcMemberOf(&$ldap_server,&$entry)
	{
		$ldap_server->assign_ordered_sequence_rdn($entry,"olcOverlayConfig","memberof");
		$this->add_attrib_value($ldap_server,$entry,"olcMemberOfGroupOC","groupOfNames");
		$this->add_attrib_value($ldap_server,$entry,"olcMemberOfMemberAD","member");
		$this->add_attrib_value($ldap_server,$entry,"olcMemberOfMemberOfAD","memberof");
		$this->add_attrib_value($ldap_server,$entry,"olcMemberOfDangling","ignore");
		$this->add_attrib_value($ldap_server,$entry,"olcMemberOfDanglingError","19");	// 19 = Constraint violation

		// olcMemberOfDN - default value if not defined: root RN of database containing overlay record
	}

	function before_create_olcMemberOf(&$ldap_server,&$entry)
	{
		$ldap_server->ensure_openldap_module_loaded("memberof");
	}
}
?>
