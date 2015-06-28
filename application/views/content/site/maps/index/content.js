/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

$(function () {
  var $map = $('#map');
  var $loading = $('<div />').attr ('id', 'loading')
                             .append ($('<div />'))
                             .appendTo ($('body'));
  
  var _map = null;
  var _markers = [];


  function formatFloat (num, pos) {
    var size = Math.pow (10, pos);
    return Math.round (num * size) / size;
  }

  function initialize () {
    var styledMapType = new google.maps.StyledMapType ([
      { featureType: 'transit.station.bus',
        stylers: [{ visibility: 'off' }]
      }, {
        featureType: 'poi',
        stylers: [{ visibility: 'off' }]
      }, {
        featureType: 'poi.attraction',
        stylers: [{ visibility: 'on' }]
      }, {
        featureType: 'poi.school',
        stylers: [{ visibility: 'on' }]
      }
    ]);

    var option = {
        zoom: 16,
        scaleControl: true,
        navigationControl: true,
        disableDoubleClickZoom: true,
        mapTypeControl: false,
        zoomControl: true,
        scrollwheel: true,
        streetViewControl: false,
        center: new google.maps.LatLng (23.568596231491233, 120.3035703338623),
      };

    _map = new google.maps.Map ($map.get (0), option);
    _map.mapTypes.set ('map_style', styledMapType);
    _map.setMapTypeId ('map_style');


 //    var populationOptions = {
 //      strokeColor: '#FF0000',
 //      strokeOpacity: 0.8,
 //      strokeWeight: 2,
 //      fillColor: '#FF0000',
 //      fillOpacity: 0.35,
 //      map: _map,
 //      center: new google.maps.LatLng (23.568596231491233, 120.3035703338623),
 //      radius: 10
 //    };
 //    var c = new google.maps.Circle (populationOptions);

 // var strictBounds = new google.maps.LatLngBounds(
 // new google.maps.LatLng(23.568596231491233, 121.3035703338623),
 // new google.maps.LatLng(23.568596231491233, 121.3035703338623));
new google.maps.Marker ({
        map: _map,
        draggable: false,
        position: new google.maps.LatLng (23.568596231491233, 120.3035703338623),
        
      });
new google.maps.Marker ({
        map: _map,
        draggable: false,
        position: new google.maps.LatLng (23.559912647638175, 120.29327065124517),
        
      });
// new google.maps.Marker ({
//         map: _map,
//         draggable: false,
//         position: new google.maps.LatLng (23.577279241269004, 120.31387001647954),
        
//       });

    google.maps.event.addListener(_map, 'zoom_changed', function() {
      console.error (_map.getCenter ().lat ());
      console.error (_map.getCenter ().lng ());
      console.error (_map.getZoom());
      c.setRadius (200);
    });
    google.maps.event.addListener(_map, 'dragend', function() {
      // map.setZoom(8);
      // map.setCenter(marker.getPosition());
// strictBounds.contains(_map.getCenter());

//     var maxX = strictBounds.getNorthEast().lng(),
//          maxY = strictBounds.getNorthEast().lat(),
//          minX = strictBounds.getSouthWest().lng(),
//          minY = strictBounds.getSouthWest().lat();

//       // console.error (_map.getCenter ().lat ());
//       // console.error (_map.getCenter ().lng ());
//       // console.error (_map.getZoom());

//       console.error (maxX);
//       console.error (maxY);
//       console.error (minX);
//       console.error (minY);
// console.error (strictBounds.contains(_map.getCenter()));
console.error (_map.getBounds().getSouthWest ());
console.error (_map.getBounds().getNorthEast ());

    });

    $loading.fadeOut (function () {
      $(this).hide (function () {
        $(this).remove ();
      });
    });
  }

  google.maps.event.addDomListener (window, 'load', initialize);
});