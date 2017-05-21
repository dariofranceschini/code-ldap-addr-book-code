<?php
/** OpenLDAP Configuration (OLC) Schema for Proxy Cache Engine Overlay */

class openldap_pcache_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"olcPcache",			"data_type"=>"text",		"display_name"=>gettext("Cache Parameters"),				"alias_names"=>"olcProxyCache"),
			array("name"=>"olcPcacheAttrset",		"data_type"=>"text_list",	"display_name"=>gettext("Cached Attribute Set"),			"alias_names"=>"olcProxyAttrset"),
			array("name"=>"olcPcacheBind",			"data_type"=>"text_list",	"display_name"=>gettext("Caching Template for Bind Credentials")),
			array("name"=>"olcPcacheMaxQueries",		"data_type"=>"text",		"display_name"=>gettext("Maximum Number of Cached Queries"),		"alias_names"=>"olcProxyCacheQueries"),
			array("name"=>"olcPcacheOffline",		"data_type"=>"yes_no",		"display_name"=>gettext("Offline Mode")),
			array("name"=>"olcPcachePersist",		"data_type"=>"yes_no",		"display_name"=>gettext("Keep Cached Queries When Proxy Restarts"),	"alias_names"=>"olcProxySaveQueries"),
			array("name"=>"olcPcachePosition",		"data_type"=>"olc_pcachepos",	"display_name"=>gettext("Proxy Cache Access Priority")),
			array("name"=>"olcPcacheTemplate",		"data_type"=>"text_list",	"display_name"=>gettext("Caching Template for Queries"),		"alias_names"=>"olcProxyCacheTemplate"),
			array("name"=>"olcPcacheValidate",		"data_type"=>"yes_no",		"display_name"=>gettext("Validate Entries Against Proxy DSA's Schema Before Caching"),	"alias_names"=>"olcProxyCheckCacheability"),

			array("name"=>"pcacheQueryURL",			"data_type"=>"text",		"display_name"=>gettext("Cached Query URI"))
			);

		// Object classes
		$this->object_schema = array(
			array("name"=>"olcPcacheConfig",		"icon"=>"openldap/overlay.png",		"is_folder"=>false,"rdn_attrib"=>"olcOverlay","required_attribs"=>"olcPcache,olcPcacheAttrset,olcPcacheTemplate","display_name"=>gettext("Proxy Cache Engine Overlay"),"parent_class"=>"olcOverlayConfig")
			);

		// auxiliary class 'olcPcacheDatabase' is also defined in this schema

		// Display layouts
		$ldap_server->add_display_layout("olcPcacheConfig",array(
			array("section_name"=>gettext("Proxy Cache Engine Overlay Settings"),"new_row"=>true,
				"attributes"=>array(
					array("olcOverlay",			gettext("Overlay Object Name"),				"openldap/overlay.png"),
					array("olcPcache",			gettext("Cache Parameters"),				"generic24.png"),
					array("olcPcacheMaxQueries",		gettext("Maximum Number of Cached Queries"),		"generic24.png"),
					array("olcPcacheOffline",		gettext("Offline Mode"),				"generic24.png"),
					array("olcPcachePersist",		gettext("Keep Cached Queries When Proxy Restarts"),	"generic24.png"),
					array("olcPcacheValidate",		gettext("Validate Entries Against Proxy DSA's Schema Before Caching"),"generic24.png"),
					array("olcPcachePosition",		gettext("Proxy Cache Access Priority"),			"generic24.png"),
					)
				),
			array("section_name"=>gettext("Cached Attribute Sets"),"new_row"=>true,
				"attributes"=>array(
					array("olcPcacheAttrset"),
					)
				),
			array("section_name"=>gettext("Caching Templates for Queries"),"new_row"=>true,
				"attributes"=>array(
					array("olcPcacheTemplate"),
					)
				),
			array("section_name"=>gettext("Caching Templates for Bind Credentials"),"new_row"=>true,
				"attributes"=>array(
					array("olcPcacheBind"),
					)
				)
			));

		parent::__construct($ldap_server);
	}

	function populate_for_create_olcPcacheConfig(&$ldap_server,&$entry)
	{
		$ldap_server->assign_ordered_sequence_rdn($entry,"olcOverlayConfig","pcache");
		$this->add_attrib_value($ldap_server,$entry,"olcPcache","bdb 10000 1 50 100");
		$this->add_attrib_value($ldap_server,$entry,"olcPcacheMaxQueries","10000");
		$this->add_attrib_value($ldap_server,$entry,"olcPcacheValidate","FALSE");
		$this->add_attrib_value($ldap_server,$entry,"olcPcacheOffline","FALSE");
		$this->add_attrib_value($ldap_server,$entry,"olcPcachePersist","FALSE");
		$this->add_attrib_value($ldap_server,$entry,"olcPcachePosition","tail");
	}

	function before_create_olcPcacheConfig(&$ldap_server,&$entry)
	{
		$ldap_server->ensure_openldap_module_loaded("pcache");
	}
}
?>
