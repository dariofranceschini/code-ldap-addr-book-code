<?php
/** COSINE and Internet X.500 Schema (cosine.schema)

    The Cooperation for Open Systems Interconnection Networking
    in Europe (COSINE) and Internet X.500 Pilot were early
    projects that implementing LDAP and X.500 directory services,
    primarily within the UK and European academic communities.

    The schema definitions designed by these projects were
    subsequently adopted by the IETF and became de facto
    Internet standards.

    (Partial implementation only)

    @see http://www.ietf.org/rfc/rfc1274.txt
    @see http://www.ietf.org/rfc/rfc4524.txt
*/

class cosine_schema extends ldap_schema
{
	function __construct(&$ldap_server)
	{
		$this->attribute_schema = array(
			array("name"=>"homePhone",			"data_type"=>"phone_number",	"display_name"=>gettext("Home Telephone Number")),
			array("name"=>"info",				"data_type"=>"text_area",	"display_name"=>gettext("General Information")),
			array("name"=>"mobile",				"data_type"=>"phone_number",	"display_name"=>gettext("Mobile/Cell Telephone Number")),
			array("name"=>"pager",				"data_type"=>"text",		"display_name"=>gettext("Pager Telephone Number")),
			);

		parent::__construct($ldap_server);
	}
}
?>
