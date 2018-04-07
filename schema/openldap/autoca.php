<?php
/** OpenLDAP Configuration (OLC) Schema for Automated Certificate Authority Overlay */

class openldap_autoca_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"olcACADays",			"data_type"=>"text",			"display_name"=>gettext("CA Certificate Lifetime")),
			array("name"=>"olcACAKeybits",			"data_type"=>"text",			"display_name"=>gettext("CA Private Key Size")),
			array("name"=>"olcACAlocalDN",			"data_type"=>"dn",			"display_name"=>gettext("Local Server Certificate DN")),
			array("name"=>"olcACAserverClass",		"data_type"=>"text",			"display_name"=>gettext("Server Object Class")),
			array("name"=>"olcACAserverDays",		"data_type"=>"text",			"display_name"=>gettext("Server Certificate Lifetime")),
			array("name"=>"olcACAserverKeybits",		"data_type"=>"text",			"display_name"=>gettext("Server Private Key Size")),
			array("name"=>"olcACAuserClass",		"data_type"=>"text",			"display_name"=>gettext("User Object Class")),
			array("name"=>"olcACAuserDays",			"data_type"=>"text",			"display_name"=>gettext("User Certificate Lifetime")),
			array("name"=>"olcACAuserKeybits",		"data_type"=>"text",			"display_name"=>gettext("User Private Key Size")),

			array("name"=>"cAPrivateKey",			"data_type"=>"download",		"display_name"=>gettext("X.509 CA Private Key")),
			array("name"=>"userPrivateKey",			"data_type"=>"download",		"display_name"=>gettext("X.509 User Private Key"))
			);

		// Object classes
		$this->object_schema = array(
			array("name"=>"olcACAConfig",			"icon"=>"openldap/overlay.png",		"is_folder"=>false,"rdn_attrib"=>"olcOverlay","display_name"=>gettext("Automated CA Overlay"),"parent_class"=>"olcOverlayConfig"),

			array("name"=>"autoCA",				"icon"=>"cert-authority.png",		"class_type"=>"auxiliary","display_name"=>gettext("Automated PKI Certificate Authority"),"parent_class"=>"pkiCA"),
			array("name"=>"autoCAuser",			"icon"=>"cert.png",			"class_type"=>"auxiliary","display_name"=>gettext("Automated PKI User"),"parent_class"=>"pkiUser")
			);

                // Display layouts
                $ldap_server->add_display_layout("olcACAConfig",array(
                        array("section_name"=>gettext("Automated Certificate Authority Overlay Settings"),"new_row"=>true,
                                "attributes"=>array(
					array("olcOverlay",			gettext("Overlay Object Name"),				"openldap/overlay.png"),
					array("olcACAlocalDN",			gettext("Local Server Certificate DN"),			"alias.png"),
					array("olcACAserverClass",		gettext("Server Object Class"),				"server.png"),
					array("olcACAuserClass",		gettext("User Object Class"),				"user24.png")
                                        )
                                ),

			array("section_name"=>gettext("CA Certificate Settings"),"new_row"=>true,
				"attributes"=>array(
					array("olcACADays",			gettext("Lifetime (days)"),				"generic24.png"),
					array("olcACAKeybits",			gettext("Private Key Size"),				"generic24.png")
					)
				),


			array("section_name"=>gettext("Server Certificate Settings"),"new_row"=>true,
				"attributes"=>array(
					array("olcACAserverDays",		gettext("Lifetime (days)"),				"generic24.png"),
					array("olcACAserverKeybits",		gettext("Private Key Size"),				"generic24.png")
					)
				),

			array("section_name"=>gettext("User Certificate Settings"),"new_row"=>true,
				"attributes"=>array(
					array("olcACAuserDays",			gettext("Lifetime (days)"),				"generic24.png"),
					array("olcACAuserKeybits",		gettext("Private Key Size"),				"generic24.png")
					)
				)
			));

		parent::__construct($ldap_server);
	}

	function populate_for_create_olcACAConfig(&$ldap_server,&$entry)
	{
		$ldap_server->assign_ordered_sequence_rdn($entry,"olcOverlayConfig","autoca");
		$this->add_attrib_value($ldap_server,$entry,"olcACAserverClass","ipHost");
		$this->add_attrib_value($ldap_server,$entry,"olcACAuserClass","person");
		$this->add_attrib_value($ldap_server,$entry,"olcACADays","3652");		// 10 years
		$this->add_attrib_value($ldap_server,$entry,"olcACAKeybits","2048");
		$this->add_attrib_value($ldap_server,$entry,"olcACAserverDays","1826");		// 5 years
		$this->add_attrib_value($ldap_server,$entry,"olcACAserverKeybits","2048");
		$this->add_attrib_value($ldap_server,$entry,"olcACAuserDays","365");		// 1 year
		$this->add_attrib_value($ldap_server,$entry,"olcACAuserKeybits","2048");
	}

	function before_create_olcACAConfig(&$ldap_server,&$entry)
	{
		$ldap_server->ensure_openldap_module_loaded("autoca");
	}
}
?>
