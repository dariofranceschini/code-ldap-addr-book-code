<?php
/** OpenLDAP Proxy Cache Engine Overlay On-Line Configuration (OLC) schema */

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

		// Structural object classes
		$this->object_schema = array(
			array("name"=>"olcPcacheConfig",		"icon"=>"openldap/overlay.png",		"is_folder"=>false,"rdn_attrib"=>"olcOverlay","required_attribs"=>"olcPcache,olcPcacheAttrset,olcPcacheTemplate","display_name"=>gettext("Proxy Cache Engine Overlay"),"parent_class"=>"olcOverlayConfig")
			);

		// auxiliary class 'olcPcacheDatabase' is also defined in this schema

		parent::__construct($ldap_server);
	}
}
?>
