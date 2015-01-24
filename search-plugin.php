<?php
/* *********************************************************************

 This program is free software: you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation, either version 3 of the License, or
   (at your option) any later version.

   This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with this program.  If not, see <http://www.gnu.org/licenses/>.

********************************************************************* */

include "config.php";
include "utils.php";

header("Content-type: application/opensearchdescription+xml");
?>
<OpenSearchDescription xmlns="http://a9.com/-/spec/opensearch/1.1/"
                       xmlns:moz="http://www.mozilla.org/2006/browser/search/">
<ShortName><?php echo $site_name; ?></ShortName>
<Description>Search <?php echo $site_name; ?></Description>
<InputEncoding>UTF-8</InputEncoding>
<Image width="16" height="16">
data:image/png,%89PNG%0D%0A%1A%0A%00%00%00%0DIHDR%00%00%00%10%00%00%00%10%08%06%00%00%00%1F%F3%FFa%00%00%00%01sRGB%00%AE%CE%1C%E9%00%00%00%06bKGD%00%FF%00%FF%00%FF%A0%BD%A7%93%00%00%00%09pHYs%00%00%0B%13%00%00%0B%13%01%00%9A%9C%18%00%00%00%07tIME%07%DB%01%09%02%1C%15%7CkM%AE%00%00%02%D8IDAT8%CB%AD%90%DD%8B%94U%00%C6%7F%E7%CC%FB%CE%CE%87%E36%B3j%C4%B0%B3%06%B6i%60%60%10QQ%A9%2C%09R%20%95%8AZ%8D%5D%88Wa%7FGTte%24%25%11%B4%04%11DA%9F%B4%F4a%CB%DAV%CB%E8%BA%D6E%B6%E94%BB%3B%D3%CC%BB%3B%E3%BC_%E7%BC%E7t1%5B%A8%D7%3D%F0%5C%3E%9F%82%5B%20%05%EF%BD%F8%C4S%87%96%96%1A%8ER%1A%AD%15%91Rh%9D%00%90%12%02)%25k%89b%E1%EA%1F%15%E7%16%FD%93gN%9C%3A%B8%B5Rq%AE%D5.%B0%DAY%C5%BF%DE%A7%DF%EF%13%89%18%C7q%91R%22%A5%B0%BF%D9H%D43%B9wn2%18-%96%0E%8D%DD%B5%CD%F5%AE%2C%12%F4%7C%12%A5%D1z%40%00%AD%15RJb)D%3D%F2%A9_%EF~%2Co%D0%E7w%DE%BD%FDY%E9%FBt%97%9B%A8D%A3%E3%18%A5%14%D6Z%24b0%C1u%ED%7Cs%89%FD%CF%1C%B0c%A3%95%03%A9%1B%0C%3E%7F%B9z%E2%CE%A0%ED%D1%FB%DB%23%0AC%828%22%8CB%8C5X%40J%89q%D3%A2!b%D3VJ%3C%BA%FB%B1%C6%BF%0D%AA%CF%EF%DD%B7wsa%A3%ED%AD%B4P%89%26%D6j%90%9E%98%FF%122%99%2C%CD%AEG%90%CF%B1%7Bb%0F%AF%BC%F6jZ%02%128%7C%F2%F01%DB%FEkI%A8h%20L%94%26Q%1A%C3%A0%BE%94%92%AE%8E%99%8F%FB%F6%91%7D%13%B2%F6%E6%BB%00%3D%09T%8E%EC%99x0j5%C5%EAr%930%F4%89%FD%80(%08%88%A2%08%A3-Z%1B%8C1%5C%5D%EDP%1C%1B%15%9Bb%93L%5E%B9%04p%C6)%97%CB%2F%1D%3D%5E%BDmK%BEHa%B4L%1CF%84%B1%A2%B3%BC%82%0A%82%F5%F2%82%9E%D7%E6%E2%DC%0C%E3%E3%DB8%7Fy!%A5%92d%12%F8%D5%19%19%CA%3F7%F3%E9%17%BC%F1%F3%2F%26%93%C9%C8%E1%E1a%A4%94d%F39%B4%1F%E28%0ERJR%D9!%EE%7B%E8a%B2%85%02%DF~%F8%91%0F%7C%00%D4%1D%3F%EC%97%3CW%B2%EB%C8%D3%E2%85j%95%24I0%C6P%2C%8D%B01%9B%25%F6%7D%5C%D7%E5%DC%F4%0F%D4j%17%AC%FF%DD%ACX%E8v%3E%01%AE%01%9E%2C%16%8B%5E%D9%3A%14%CE%CF3%7D%FAl%B2%C1%60K%99%1C%22%8Ei%B6%5B%F4Tl%3B%FD%1E%B9t%D6%96ry%F1%FA7%9Fu%80%AF%80%DF%01%93j%B4%9A%EFO%5D%9C%CB%ADt%D76%CF%5E%BE%94%99%7C%FB%AC%BB63%87%DF%EE%E8%A0%BE%9C%18%3FHm%DDq%0F%8B%F5%3F%C5%EC%B9i%BE%FEq%E6%7B%E0-%A0%01X%B1%FE%D2%06%60%04%A8%00%E3))%8E%1A%CB%FDi%992%C7v%3D%90%B9%E3%F6-r%D8M%C7%A7%A7%BE%CC%2Fv%BD%FD%C0O%40kp%EF%CDp%00%0B%0C%01%9B%D6%0D%1F%07%EE%05%5C%60j%9D5%C0%F0%7F%E0%1F%06Wk%7B%B5%82%8C%ED%00%00%00%00IEND%AEB%60%82
</Image>
<Url type="text/html" method="GET" template="<?php echo current_page_folder_url(); ?>?filter={searchTerms}"/>
<Url type="application/x-suggestions+json" method="GET" template="<?php echo current_page_folder_url(); ?>suggest.php?filter={searchTerms}"/>
</OpenSearchDescription>
