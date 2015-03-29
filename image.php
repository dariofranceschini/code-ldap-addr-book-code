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

include "utils.php";
include "config.php";

// TODO: sanitise base DN from URL:
//	stop "nasties" being passed through to the LDAP server
//	prevent access to directory outside of address book base DN
if(!empty($_GET["dn"])) $dn = $_GET["dn"]; else $dn = $ldap_base_dn;

// TODO: sanitise attribute name from URL:
//	stop "nasties" being passed through to the LDAP server
if(!empty($_GET["attrib"]))
	$attrib = $_GET["attrib"];
else
	$attrib = "jpegPhoto";

if($ldap_server->log_on())
{
        $search_resource = ldap_read($ldap_server->connection,$dn,"(objectclass=*)");
        $entry = ldap_get_entries($ldap_server->connection,$search_resource);

	$image = imagecreatefromstring($entry[0][strtolower($attrib)][0]);

	header("Content-type:image/jpeg");

	if(empty($_GET["size"]))
		imagejpeg($image);
	else
	{
		$size = explode("x",$_GET["size"]);
		$scaled_x = $size[0];
		$scaled_y = $size[1];

		$source_x = imagesx($image);
		$source_y = imagesy($image);

		// Determine actual scale factor (maintaining image aspect)
		$scale_factor = min(
			$scaled_x/$source_x,
			$scaled_y/$source_y);

		$scaled_x = floor($source_x * $scale_factor);
		$scaled_y = floor($source_y * $scale_factor);

		if($source_x == $scaled_x && $source_y == $scaled_y)
			// If no scaling required then use the original
			// image as-is
			imagejpeg($image);
		else
		{
			// Generate a scaled version of the image
			$scaled_image
				= imagecreatetruecolor($scaled_x,$scaled_y);
			imagecopyresampled($scaled_image,$image,0,0,0,0,
				$scaled_x,$scaled_y,$source_x,$source_y);

			imagejpeg($scaled_image);
			imagedestroy($scaled_image);
		}
	}
	imagedestroy($image);
}
else
{
	// TODO: do we want to return a textual error here, or
	// create an image containing the error text?
        show_ldap_bind_error();
}
?>
