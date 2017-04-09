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

/** Country code standards/names

    @see
	http://www.iso.org/iso/country_codes
	https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2
	https://en.wikipedia.org/wiki/ISO_3166-3
	http://www.wipo.int/export/sites/www/standards/en/pdf/03-03-01.pdf
*/

/** Officially assigned ISO 3166-1 alpha-2 country codes/names */

$iso3166_1_official_country_name=array(
	"AD" => "Andorra",
	"AE" => "United Arab Emirates",
	"AF" => "Afghanistan",
	"AG" => "Antigua and Barbuda",
	"AI" => "Anguilla",			// code formerly assigned to "French Afar and Issas"
	"AL" => "Albania",
	"AM" => "Armenia",
	"AO" => "Angola",
	"AQ" => "Antarctica",
	"AR" => "Argentina",
	"AS" => "American Samoa",
	"AT" => "Austria",
	"AU" => "Australia",
	"AW" => "Aruba",
	"AX" => "Åland Islands",
	"AZ" => "Azerbaijan",
	"BA" => "Bosnia and Herzegovina",
	"BB" => "Barbados",
	"BD" => "Bangladesh",
	"BE" => "Belgium",
	"BF" => "Burkina Faso",
	"BG" => "Bulgaria",
	"BH" => "Bahrain",
	"BI" => "Burundi",
	"BJ" => "Benin",
	"BL" => "Saint Barthélemy",
	"BM" => "Bermuda",
	"BN" => "Brunei Darussalam",
	"BO" => "Bolivia",
	"BQ" => "Bonaire, Sint Eustatius and Saba",	// code formerly assigned to "British Antarctic Territory"
	"BR" => "Brazil",
	"BS" => "Bahamas",
	"BT" => "Bhutan",
	"BV" =>	"Bouvet Island",
	"BW" => "Botswana",
	"BY" => "Belarus",
	"BZ" => "Belize",
	"CA" => "Canada",
	"CC" => "Cocos (Keeling) Islands",
	"CD" => "Congo, The Democratic Republic of the",
	"CF" => "Central African Republic",
	"CG" => "Congo",
	"CH" => "Switzerland",
	"CI" => "Côte d'Ivoire",
	"CK" => "Cook Islands",
	"CL" => "Chile",
	"CM" => "Cameroon",
	"CN" => "China",
	"CO" => "Colombia",
	"CR" => "Costa Rica",
	"CU" => "Cuba",
	"CV" => "Cabo Verde",		// named as per ISO 3166-1, a.k.a. "Cape Verde"
	"CW" => "Curaçao",
	"CX" => "Christmas Island",
	"CY" => "Cyprus",
	"CZ" => "Czechia",		// named as per ISO 3166-1, a.k.a. "Czech Republic"
	"DE" => "Germany",
	"DJ" => "Djibouti",
	"DK" => "Denmark",
	"DM" => "Dominica",
	"DO" => "Dominican Republic",
	"DZ" => "Algeria",
	"EC" => "Ecuador",
	"EE" => "Estonia",
	"EG" => "Egypt",
	"EH" => "Western Sahara",
	"ER" => "Eritrea",
	"ES" => "Spain",
	"ET" => "Ethiopia",
	"FI" => "Finland",
	"FJ" => "Fiji",
	"FK" => "Falkland Islands (Malvinas)",
	"FM" => "Micronesia, Federated States of",
	"FO" => "Faroe Islands",
	"FR" => "France",
	"GA" => "Gabon",
	"GB" => "United Kingdom",
	"GD" => "Grenada",
	"GE" => "Georgia",			// code formerly assigned to "Gilbert and Ellice Islands"
	"GF" => "French Guiana",
	"GG" => "Guernsey",
	"GH" => "Ghana",
	"GI" => "Gibraltar",
	"GL" => "Greenland",
	"GM" => "Gambia",
	"GN" => "Guinea",
	"GP" => "Guadeloupe",
	"GQ" => "Equatorial Guinea",
	"GR" => "Greece",
	"GS" => "South Georgia and the South Sandwich Islands",
	"GT" => "Guatemala",
	"GU" => "Guam",
	"GW" => "Guinea-Bissau",
	"GY" => "Guyana",
	"HK" => "Hong Kong",
	"HM" => "Heard Island and McDonald Islands",
	"HN" => "Honduras",
	"HR" => "Croatia",
	"HT" => "Haiti",
	"HU" => "Hungary",
	"ID" => "Indonesia",
	"IE" => "Ireland",
	"IL" => "Israel",
	"IM" => "Isle of Man",
	"IN" => "India",
	"IO" => "British Indian Ocean Territory",
	"IQ" => "Iraq",
	"IR" => "Iran, Islamic Republic of",
	"IS" => "Iceland",
	"IT" => "Italy",
	"JE" => "Jersey",
	"JM" => "Jamaica",
	"JO" => "Jordan",
	"JP" => "Japan",
	"KE" => "Kenya",
	"KG" => "Kyrgyzstan",
	"KH" => "Cambodia",
	"KI" => "Kiribati",
	"KM" => "Comoros",
	"KN" => "Saint Kitts and Nevis",
	"KP" => "Korea, Democratic People's Republic of",	// a.k.a. North Korea
	"KR" => "Korea, Republic of",				// a.k.a. South Korea
	"KW" => "Kuwait",
	"KY" => "Cayman Islands",
	"KZ" => "Kazakstan",
	"LA" => "Lao People's Democratic Republic",
	"LB" => "Lebanon",
	"LC" => "Saint Lucia",
	"LI" => "Liechtenstein",
	"LK" => "Sri Lanka",
	"LR" => "Liberia",
	"LS" => "Lesotho",
	"LT" => "Lithuania",			// code formerly assigned to "Libya Tripoli"
	"LU" => "Luxembourg",
	"LV" => "Latvia",
	"LY" => "Libya",
	"MA" => "Morocco",
	"MC" => "Monaco",
	"MD" => "Moldova, Republic of",
	"ME" => "Montenegro",			// code formerly assigned to "Western Sahara"
	"MF" => "Saint Martin (French part)",
	"MG" => "Madagascar",
	"MH" => "Marshall Islands",
	"MK" => "Macedonia, former Yugoslav Republic of",	// named as per ISO 3166-1 (disputed)
	"ML" => "Mali",
	"MM" => "Myanmar",
	"MN" => "Mongolia",
	"MO" => "Macao",
	"MP" => "Northern Mariana Islands",
	"MQ" => "Martinique",
	"MR" => "Mauritania",
	"MS" => "Montserrat",
	"MT" => "Malta",
	"MU" => "Mauritius",
	"MV" => "Maldives",
	"MW" => "Malawi",
	"MX" => "Mexico",
	"MY" => "Malaysia",
	"MZ" => "Mozambique",
	"NA" => "Namibia",
	"NC" => "New Caledonia",
	"NE" => "Niger",
	"NF" => "Norfolk Island",
	"NG" => "Nigeria",
	"NI" => "Nicaragua",
	"NL" => "Netherlands",
	"NO" => "Norway",
	"NP" => "Nepal",
	"NR" => "Nauru",
	"NU" => "Niue",
	"NZ" => "New Zealand",
	"OM" => "Oman",
	"PA" => "Panama",
	"PE" => "Peru",
	"PF" => "French Polynesia",
	"PG" => "Papua New Guinea",
	"PH" => "Philippines",
	"PK" => "Pakistan",
	"PL" => "Poland",
	"PM" => "Saint Pierre and Miquelon",
	"PN" => "Pitcairn",
	"PR" => "Puerto Rico",
	"PS" => "Palestine, State of",		// named as per ISO 3166-1 (disputed)
	"PT" => "Portugal",
	"PW" => "Palau",
	"PY" => "Paraguay",
	"QA" => "Qatar",
	"RE" => "Réunion",
	"RO" => "Romania",
	"RS" => "Serbia",
	"RU" => "Russian Federation",		// code formerly assigned to "Barundi"
	"RW" => "Rwanda",
	"SA" => "Saudi Arabia",
	"SB" => "Solomon Islands",
	"SC" => "Seychelles",
	"SD" => "Sudan",
	"SE" => "Sweden",
	"SG" => "Singapore",
	"SH" => "Saint Helena, Ascension and Tristan da Cunha",
	"SI" => "Slovenia",
	"SJ" => "Svalbard and Jan Mayen",
	"SK" => "Slovakia",			// code formerly assigned to "Sikkim"
	"SL" => "Sierra Leone",
	"SM" => "San Marino",
	"SN" => "Senegal",
	"SO" => "Somalia",
	"SR" => "Suriname",
	"SS" => "South Sudan",
	"ST" => "São Tomé and Príncipe",
	"SV" => "El Salvador",
	"SX" => "Sint Maarten (Dutch part)",
	"SY" => "Syrian Arab Republic",
	"SZ" => "Swaziland",
	"TC" => "Turks and Caicos Islands",
	"TD" => "Chad",
	"TF" => "French Southern Territories",
	"TG" => "Togo",
	"TH" => "Thailand",
	"TJ" => "Tajikistan",
	"TK" => "Tokelau",
	"TL" => "Timor-Leste",
	"TM" => "Turkmenistan",
	"TN" => "Tunisia",
	"TO" => "Tonga",
	"TR" => "Turkey",
	"TT" => "Trinidad and Tobago",
	"TV" => "Tuvalu",
	"TW" => "Taiwan (Province of China)",		// named as per ISO 3166-1 (disputed)
	"TZ" => "Tanzania, United Republic of",
	"UA" => "Ukraine",
	"UG" => "Uganda",
	"UM" => "United States Minor Outlying Islands",
	"US" => "United States",
	"UY" => "Uruguay",
	"UZ" => "Uzbekistan",
	"VA" => "Holy See (Vatican City State)",
	"VC" => "Saint Vincent and the Grenadines",
	"VE" => "Venezuela",
	"VG" => "Virgin Islands, British",
	"VI" => "Virgin Islands, U.S.",
	"VN" => "Viet Nam",		// named as per ISO 3166-1, also spelt "Vietnam"
	"VU" => "Vanuatu",
	"WF" => "Wallis and Futuna",
	"WS" => "Samoa",
	"YE" => "Yemen",
	"YT" => "Mayotte",
	"ZA" => "South Africa",
	"ZM" => "Zambia",
	"ZW" => "Zimbabwe",
	);

/** User-assigned ISO 3166-1 alpha-2 country codes/names */

$iso3166_1_user_assigned_country_name=array(
	"AA" => "AA (Reserved)",
	"QM" => "QM (Reserved)",
	"QN" => "QN (Reserved)",
	"QO" => "Outlying Oceana",					// Unicode CLDR
	"QP" => "QP (Reserved)",
	"QQ" => "QQ (Reserved)",
	"QR" => "QR (Reserved)",
	"QS" => "QS (Reserved)",
	"QT" => "QT (Reserved)",
	"QU" => "QU (Reserved)",
	"QV" => "QV (Reserved)",
	"QW" => "QW (Reserved)",
	"QX" => "QX (Reserved)",
	"QY" => "QY (Reserved)",
	"QZ" => "Community Plant Variety Office (European Union)",	// WIPO
	"XA" => "Canary Islands",					// Switzerland (duplicates "IC")
	"XB" => "XB (Reserved)",
	"XC" => "XC (Reserved)",
	"XD" => "XD (Reserved)",
	"XE" => "England",						// WhatsApp
	"XF" => "XF (Reserved)",
	"XG" => "XG (Reserved)",
	"XH" => "XH (Reserved)",
	"XI" => "XI (Reserved)",
	"XJ" => "XJ (Reserved)",
	"XK" => "Kosovo",						// various (temporary code)
	"XL" => "XL (Reserved)",
	"XM" => "XM (Reserved)",
	"XN" => "Nordic Patent Institute",				// WIPO
	"XO" => "XO (Reserved)",
	"XP" => "XP (Reserved)",
	"XQ" => "XQ (Reserved)",
	"XR" => "XR (Reserved)",
	"XS" => "Scotland",						// WhatsApp
	"XT" => "XT (Reserved)",
	"XU" => "International Union for the Protection of New Varieties of Plants",	// WIPO
	"XV" => "Visegrad Patent Institute",				// WIPO
	"XW" => "Wales",						// WhatsApp
	"XX" => "XX (Reserved)",
	"XY" => "XY (Reserved)",
	"XZ" => "International Waters",					// UN/LOCODE
	"ZZ" => "ZZ (Reserved)",
	);

/** Exceptionally reserved ISO 3166-1 alpha-2 country codes/names */

$iso3166_1_exceptional_country_name=array(
	"AC" =>	"Ascension Island",			// UPU: stamp issuing area
	"CP" =>	"Clipperton Island",			// ITU: telecommunications installations
	"DG" => "Diego Garcia",				// ITU: telecommunications installations
	"EA" => "Ceuta, Melilla",			// WCO: customs area
	"EU" => "European Union",			// ISO: european monetary unit (Euro)
	"EZ" => "Eurozone",				// ISO: security numbers (ISINs)
	"FX" => "France, Metropolitan",			// France (also appears in ISO 3166-3)
	"IC" => "Canary Islands",			// WCO: customs area
	"SU" => "Union of Soviet Socialist Republics",	// ISO (also appears in ISO 3166-3)
	"TA" => "Tristan da Cunha",			// UPU: stamp issuing area
	"UK" => "United Kingdom",			// United Kingdom
	"UN" => "United Nations"			// ISO: reprents the United Nations
	);

/** Transitionally reserved ISO 3166-1 alpha-2 country codes/names */

$iso3166_1_transitional_country_name=array(
	"AN" => "Netherlands Antilles",
	"BU" => "Burma",
	"CS" => "Czechoslovakia",	// also used for "Serbia and Montenegro" (now RS and ME)
	"NT" => "Neutral Zone",		// territory between Iraq (IQ) and Saudi Arabia (SA)
	"TP" => "East Timor",
	"YU" => "Yugoslavia",
	"ZR" => "Zaire"
	);

/** Indeterminately reserved ISO 3166-1 alpha-2 country codes/names */

$iso3166_1_indeterminate_country_name=array(
	"DY" => "Benin",				// country formerly named Dahomey
	"EW" => "Estonia",
	"FL" => "Liechtenstein",
	"JA" => "Jamaica",
	"LF" => "Libya Fezzan",
	"PI" => "Philippines",
	"RA" => "Argentina",
	"RB" => "Bolivia/Botswana",			// Double allocated country code
	"RC" => "China",
	"RH" => "Haiti",
	"RI" => "Indonesia",
	"RL" => "Lebanon",
	"RM" => "Madagascar",
	"RN" => "Niger",
	"RP" => "Philipines",
	"SF" => "Finland",				// was transitionally reserved between 1995-2012
	"WG" => "Grenada",
	"WL" => "Saint Lucia",
	"WV" => "Saint Vincent",
	"YV" => "Venezuela",
	);

/** ISO 3166-3 former country codes/names */

$iso3166_3_former_country_name=array(
	"AI" => "French Afar and Issas",		// reused for "Anguilla"
	"AN" => "Netherlands Antilles",			// also ISO 3166-1 transitional reservation
	"BQ" => "British Antarctic Territory",		// reused for "Bonaire, Sint Eustatius and Saba"
	"BU" => "Burma",				// also ISO 3166-1 transitional reservation
	"BY" => "Byelorussian SSR",			// name changed to "Belarus"
	"CS" => "Czechoslovakia",			// also ISO 3166-1 transitional reservation (also used for "Serbia and Montenegro" for a time)
	"CT" => "Canton and Enderbury Islands",
	"DD" => "German Democratic Republic",		// a.k.a. East Germany
	"DY" => "Dahomey",				// also ISO 3166-1 indeterminate reservation
	"FQ" => "French Southern and Antarctic Territories",
	"FX" => "France, Metropolitan",			// also ISO 3166-1 exceptional reservation
	"GE" => "Gilbert and Ellice Islands",		// reused for "Georgia"
	"HV" => "Upper Volta",
	"JT" => "Johnson Island",
	"MI" => "Midway Islands",
	"NH" => "New Hebrides",
	"NQ" => "Dronning Maud Land",			// part of Norwegian Antarctic Territory (former country)
	"NT" => "Neutral Zone",				// also ISO 3166-1 transitional reservation - divided between Iraq (IQ) and Saudi Arabia (SA)
	"PC" => "Pacific Islands, Trust Territory of the",
	"PU" => "U.S. Miscellaneous Pacific Islands",	// former aggregate of various islands
	"PZ" => "Panama Canal Zone",
	"RH" => "Southern Rhodesia",			// also ISO 3166-1 indeterminate reservation; also formerly "Haiti"
	"SU" => "Union of Soviet Socialist Republics",	// also ISO 3166-1 exceptional reservation
	"SK" => "Sikkim",				// reused for Slovakia
	"TP" => "East Timor",				// also ISO 3166-1 transitional and intermediate reservations
	"VD" => "Viet-Nam, Democratic Republic of",	// a.k.a. North Vietnam (former country)
	"WK" => "Wake Island",
	"YD" => "Yemen, Democratic",			// a.k.a. South Yemen; former country
	"YU" => "Yugoslavia",				// also ISO 3166-1 transitional reservation
	"ZR" => "Zaire",				// also ISO 3166-1 transitional reservation
	);

/** WIPO ST.3 (November 2016 ed.) country codes/names */

$wipo_st3_country_name=array(
	"AD" => "Andorra",
	"AE" => "United Arab Emirates",
	"AF" => "Afghanistan",
	"AG" => "Antigua and Barbuda",
	"AI" => "Anguilla",
	"AL" => "Albania",
	"AM" => "Armenia",
	"AO" => "Angola",
	"AP" => "African Regional Industrial Property Organization",	// ISO have agreed not to use this code
	"AR" => "Argentina",
	"AT" => "Austria",
	"AU" => "Australia",
	"AW" => "Aruba",
	"AZ" => "Azerbaijan",
	"BA" => "Bosnia and Herzegovina",
	"BB" => "Barbados",
	"BD" => "Bangladesh",
	"BE" => "Belgium",
	"BF" => "Burkina Faso",
	"BG" => "Bulgaria",
	"BH" => "Bahrain",
	"BI" => "Burundi",
	"BJ" => "Benin",
	"BM" => "Bermuda",
	"BN" => "Brunei Darussalam",
	"BO" => "Bolivia, Plurinational State of",
	"BQ" => "Bonaire, Sint Eustatius and Saba",
	"BR" => "Brazil",
	"BS" => "Bahamas",
	"BT" => "Bhutan",
	"BV" =>	"Bouvet Island",
	"BW" => "Botswana",
	"BX" => "Benelux Trademarks and Design Offices",		// ISO have agreed not to use this code
	"BY" => "Belarus",
	"BZ" => "Belize",
	"CA" => "Canada",
	"CD" => "Congo, The Democratic Republic of the",
	"CF" => "Central African Republic",
	"CG" => "Congo",
	"CH" => "Switzerland",
	"CI" => "Côte d'Ivoire",
	"CK" => "Cook Islands",
	"CL" => "Chile",
	"CM" => "Cameroon",
	"CN" => "China",
	"CO" => "Colombia",
	"CR" => "Costa Rica",
	"CU" => "Cuba",
	"CV" => "Cabo Verde",		// named as per WIPO ST.3, a.k.a. "Cape Verde"
	"CW" => "Curaçao",
	"CY" => "Cyprus",
	"CZ" => "Czechia",		// named as per WIPO ST.3, a.k.a. "Czech Republic"
	"DE" => "Germany",
	"DJ" => "Djibouti",
	"DK" => "Denmark",
	"DM" => "Dominica",
	"DO" => "Dominican Republic",
	"DZ" => "Algeria",
	"EA" => "Eurasian Patent Organization",		// previously assigned code "EV"?
	"EC" => "Ecuador",
	"EE" => "Estonia",
	"EG" => "Egypt",
	"EH" => "Western Sahara",
	"EM" => "European Union Intellectual Property Office",	// ISO have agreed not to use this code
	"EP" => "European Patent Office",	// ISO have agreed not to use this code
	"ER" => "Eritrea",
	"ES" => "Spain",
	"ET" => "Ethiopia",
	"FI" => "Finland",
	"FJ" => "Fiji",
	"FK" => "Falkland Islands (Malvinas)",
	"FO" => "Faroe Islands",
	"FR" => "France",
	"GA" => "Gabon",
	"GB" => "United Kingdom",
	"GC" => "Patent Office of the Cooperation Council for the Arab States of the Gulf (GCC)",
								// ISO have agreed not to use this code
	"GD" => "Grenada",
	"GE" => "Georgia",
	"GG" => "Guernsey",
	"GH" => "Ghana",
	"GI" => "Gibraltar",
	"GL" => "Greenland",
	"GM" => "Gambia",
	"GN" => "Guinea",
	"GQ" => "Equatorial Guinea",
	"GR" => "Greece",
	"GS" => "South Georgia and the South Sandwich Islands",
	"GT" => "Guatemala",
	"GW" => "Guinea-Bissau",
	"GY" => "Guyana",
	"HK" => "Hong Kong Special Administrative Region of the People's Republic of China",
	"HN" => "Honduras",
	"HR" => "Croatia",
	"HT" => "Haiti",
	"HU" => "Hungary",
	"IB" => "International Bureau of WIPO",		// ISO have agreed not to use this code
	"ID" => "Indonesia",
	"IE" => "Ireland",
	"IL" => "Israel",
	"IM" => "Isle of Man",
	"IN" => "India",
	"IQ" => "Iraq",
	"IR" => "Iran, Islamic Republic of",
	"IS" => "Iceland",
	"IT" => "Italy",
	"JE" => "Jersey",
	"JM" => "Jamaica",
	"JO" => "Jordan",
	"JP" => "Japan",
	"KE" => "Kenya",
	"KG" => "Kyrgyzstan",
	"KH" => "Cambodia",
	"KI" => "Kiribati",
	"KM" => "Comoros",
	"KN" => "Saint Kitts and Nevis",
	"KP" => "Korea, Democratic People's Republic of",	// a.k.a. North Korea
	"KR" => "Korea, Republic of",				// a.k.a. South Korea
	"KW" => "Kuwait",
	"KY" => "Cayman Islands",
	"KZ" => "Kazakstan",
	"LA" => "Lao People's Democratic Republic",
	"LB" => "Lebanon",
	"LC" => "Saint Lucia",
	"LI" => "Liechtenstein",
	"LK" => "Sri Lanka",
	"LR" => "Liberia",
	"LS" => "Lesotho",
	"LT" => "Lithuania",
	"LU" => "Luxembourg",
	"LV" => "Latvia",
	"LY" => "Libya",
	"MA" => "Morocco",
	"MC" => "Monaco",
	"MD" => "Moldova, Republic of",
	"ME" => "Montenegro",
	"MG" => "Madagascar",
	"MK" => "Macedonia, former Yugoslav Republic of",	// named as per WIPO ST.3 (disputed)
	"ML" => "Mali",
	"MM" => "Myanmar",
	"MN" => "Mongolia",
	"MO" => "Macao",
	"MP" => "Northern Mariana Islands",
	"MR" => "Mauritania",
	"MS" => "Montserrat",
	"MT" => "Malta",
	"MU" => "Mauritius",
	"MV" => "Maldives",
	"MW" => "Malawi",
	"MX" => "Mexico",
	"MY" => "Malaysia",
	"MZ" => "Mozambique",
	"NA" => "Namibia",
	"NE" => "Niger",
	"NG" => "Nigeria",
	"NI" => "Nicaragua",
	"NL" => "Netherlands",
	"NO" => "Norway",
	"NP" => "Nepal",
	"NR" => "Nauru",
	"NZ" => "New Zealand",
	"OA" => "African Intellectual Property Organization", // ISO have agreed not to use this code
	"OM" => "Oman",
	"PA" => "Panama",
	"PE" => "Peru",
	"PG" => "Papua New Guinea",
	"PH" => "Philippines",
	"PK" => "Pakistan",
	"PL" => "Poland",
	"PT" => "Portugal",
	"PW" => "Palau",
	"PY" => "Paraguay",
	"QA" => "Qatar",
	"QZ" => "Community Plant Variety Office (European Union)",
	"RO" => "Romania",
	"RS" => "Serbia",
	"RU" => "Russian Federation",	// formerly assigned to Barundi
	"RW" => "Rwanda",
	"SA" => "Saudi Arabia",
	"SB" => "Solomon Islands",
	"SC" => "Seychelles",
	"SD" => "Sudan",
	"SE" => "Sweden",
	"SG" => "Singapore",
	"SH" => "Saint Helena, Ascension and Tristan da Cunha",
	"SI" => "Slovenia",
	"SK" => "Slovakia",		// formerly assigned to Sikkim
	"SL" => "Sierra Leone",
	"SM" => "San Marino",
	"SN" => "Senegal",
	"SO" => "Somalia",
	"SR" => "Suriname",
	"SS" => "South Sudan",
	"ST" => "São Tomé and Príncipe",
	"SV" => "El Salvador",
	"SX" => "Sint Maarten (Dutch part)",
	"SY" => "Syrian Arab Republic",
	"SZ" => "Swaziland",
	"TC" => "Turks and Caicos Islands",
	"TD" => "Chad",
	"TG" => "Togo",
	"TH" => "Thailand",
	"TJ" => "Tajikistan",
	"TL" => "Timor-Leste",
	"TM" => "Turkmenistan",
	"TN" => "Tunisia",
	"TO" => "Tonga",
	"TR" => "Turkey",
	"TT" => "Trinidad and Tobago",
	"TV" => "Tuvalu",
	"TW" => "Taiwan (Province of China)",		// named as per WIPO ST.3 (disputed)
	"TZ" => "Tanzania, United Republic of",
	"UA" => "Ukraine",
	"UG" => "Uganda",
	"US" => "United States",
	"UY" => "Uruguay",
	"UZ" => "Uzbekistan",
	"VA" => "Holy See (Vatican City State)",
	"VC" => "Saint Vincent and the Grenadines",
	"VE" => "Venezuela, Bolivarian Republic of",
	"VG" => "Virgin Islands, British",
	"VN" => "Viet Nam",		// named as per WIPO ST.3, also spelt "Vietnam"
	"VU" => "Vanuatu",
	"WO" => "World Intellectual Property Organization", // ISO have agreed not to use this code
	"WS" => "Samoa",
	"XN" => "Nordic Patent Institute",
	"XU" => "International Union for the Protection of New Varieties of Plants",
	"XV" => "Visegrad Patent Institute",
	"YE" => "Yemen",
	"ZA" => "South Africa",
	"ZM" => "Zambia",
	"ZW" => "Zimbabwe",
	);

$country_name=array();

function init_country_names()
{
	global $country_code_standard,$country_name,
		$iso3166_1_official_country_name,
		$iso3166_1_user_assigned_country_name,
		$iso3166_1_exceptional_country_name,
		$iso3166_1_transitional_country_name,
		$iso3166_1_indeterminate_country_name,
		$iso3166_3_former_country_name,
		$wipo_st3_country_name;

	if(!isset($country_code_standard))
		$country_code_standard="official";

	foreach(array_reverse(explode(",",$country_code_standard)) as $standard)
	{
		switch($standard)
		{
			case "official":
				$country_name=array_merge(
					$iso3166_1_official_country_name,
					$country_name);
				break;
			case "user":
				$country_name=array_merge(
					$iso3166_1_user_assigned_country_name,
					$country_name);
				break;
			case "exceptional":
				$country_name=array_merge(
					$iso3166_1_exceptional_country_name,
					$country_name);
				break;
			case "transitional":
				$country_name=array_merge(
					$iso3166_1_transitional_country_name,
					$country_name);
				break;
			case "indeterminate":
				$country_name=array_merge(
					$iso3166_1_indeterminate_country_name,
					$country_name);
				break;
			case "former":
				$country_name=array_merge(
					$iso3166_3_former_country_name,
					$country_name);
				break;
			case "wipo":
				$country_name=array_merge(
					$wipo_st3_country_name,
					$country_name);
				break;
			default:
				echo "Unrecognised country code standard: "
					. $standard . "\n";
				exit (1);
		}
	}
}
?>
