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

// Adapted from sample code at http://jsfiddle.net/tj_vantoll/pszLzuox/

$( function() {
  $.widget( "custom.iconselectmenu", $.ui.selectmenu, {
    _renderItem: function( ul, item ) {
      var li = $( "<li>" ),
	wrapper = $( "<div>", { text: item.label } );

      $( "<span>", {
	style: "background-image:url(" + item.element.attr( "icon" )
	  + ");background-repeat:no-repeat;padding-left:16px;height:24px",
	"class": "ui-icon"
      })
	.appendTo( wrapper );

      return li.append( wrapper ).appendTo( ul );
    }
  });

  $( "#object_class_selector" )
    .iconselectmenu();
} );
