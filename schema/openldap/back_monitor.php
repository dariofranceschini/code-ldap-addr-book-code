<?php
/** OpenLDAP Monitoring Backend On-Line Configuration (OLC) schema (partial) */

class openldap_back_monitor_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		// TODO: define attributes
		$this->attribute_schema = array(
			array("name"=>"monitorConnectionStartTime",	"data_type"=>"date_time",	"display_name"=>gettext("Start Time")),
			array("name"=>"monitorConnectionActivityTime",	"data_type"=>"date_time",	"display_name"=>gettext("Activity Time")),
			array("name"=>"monitorContext",			"data_type"=>"dn",		"display_name"=>gettext("Monitoring Naming Context")),
			array("name"=>"monitorIsShadow",		"data_type"=>"yes_no",		"display_name"=>gettext("Database is Shadow/Slave")),
			array("name"=>"monitorRuntimeConfig",		"data_type"=>"yes_no",		"display_name"=>gettext("Runtime Configuration Allowed")),
			array("name"=>"monitorTimestamp",		"data_type"=>"date_time",	"display_name"=>gettext("Time Stamp")),
			array("name"=>"readOnly",			"data_type"=>"yes_no",		"display_name"=>gettext("Read Only"))
			);

		// Structural object classes
		$this->object_schema = array(
			array("name"=>"olcMonitorConfig",		"icon"=>"openldap/db.png",			"is_folder"=>false,"rdn_attrib"=>"olcDatabase","display_name"=>gettext("Monitoring Database"),"required_attribs"=>"olcSuffix","parent_class"=>"olcDatabaseConfig"),

			array("name"=>"monitor",			"icon"=>"generic24.png",			"is_folder"=>false,"display_name"=>gettext("OpenLDAP Monitoring Object")),
			array("name"=>"monitorServer",			"icon"=>"openldap/monitor-server.png",		"is_folder"=>true,"display_name"=>gettext("Monitored Server"),"parent_class"=>"monitor"),
			array("name"=>"monitorContainer",		"icon"=>"folder.png",				"is_folder"=>true,"display_name"=>gettext("Monitoring Container"),"parent_class"=>"monitor"),
			array("name"=>"monitoredObject",		"icon"=>"openldap/monitor-object.png",		"is_folder"=>false,"display_name"=>gettext("Monitored Entity"),"parent_class"=>"monitor"),
			array("name"=>"monitorConnection",		"icon"=>"openldap/connection.png",		"is_folder"=>false,"display_name"=>gettext("Monitored Connection"),"parent_class"=>"monitor"),
			array("name"=>"monitorCounterObject",		"icon"=>"openldap/monitor-counter.png",		"is_folder"=>false,"display_name"=>gettext("Monitored Counter Object"),"parent_class"=>"monitor"),
			array("name"=>"monitorOperation",		"icon"=>"openldap/monitor-operation.png",	"is_folder"=>false,"display_name"=>gettext("Monitored Operation"),"parent_class"=>"monitor"),

			// not used?
			array("name"=>"managedObject",			"icon"=>"generic24.png",			"is_folder"=>false,"display_name"=>gettext("Managed Entity"),"parent_class"=>"monitor")
			);

		parent::__construct($ldap_server);
	}
}
?>
