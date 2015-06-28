/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

$(function () {
  var $map = $('#map');
  var $loadingData = $('.map .loading_data');
  var $loading = $('<div />').attr ('id', 'loading')
                             .append ($('<div />'))
                             .appendTo ($('.map'));
  
  var _map = null;
  var _markers = [];

  function formatFloat (num, pos) {
    var size = Math.pow (10, pos);
    return Math.round (num * size) / size;
  }

  function getPictures () {
    $loadingData.addClass ('show');
    var northEast = _map.getBounds().getNorthEast ();
    var southWest = _map.getBounds().getSouthWest ();

    $.ajax ({
      url: $('#get_pictures_url').val (),
      data: { NorthEast: {latitude: northEast.lat (), longitude: northEast.lng ()},
              SouthWest: {latitude: southWest.lat (), longitude: southWest.lng ()},  },
      async: true, cache: false, dataType: 'json', type: 'POST',
      beforeSend: function () { }
    })
    .done (function (result) {
      // console.error (result);

      if (result.status) {
        _markers.map (function (t) {
          t.setMap (null);
        });
        _markers = result.pictures.map (function (t) {
          var markerWithLabel = new MarkerWithLabel ({
            position: new google.maps.LatLng (t.lat, t.lng),
            draggable: false,
            raiseOnDrag: false,
            map: _map,
            clickable: true,
            labelContent: ''+
              '<div class="img">'+
                '<img src="' + t.url + '" />'+
              '</div>'+
            '',
            labelAnchor: new google.maps.Point (50, 50),
            labelClass: "marker_label",
            icon: {
              path: 'M 0 0',
              strokeColor: 'rgba(249, 39, 114, 0)',
              strokeWeight: 1,
              fillColor: 'rgba(249, 39, 114, 0)',
              fillOpacity: 0
            },
            initCallback: function (e) {
              $(e).find ('.img').imgLiquid ({verticalAlign: 'top'});
            }
          });

          google.maps.event.addListener(markerWithLabel, 'click', function () {

          });

          return markerWithLabel;
          // return new google.maps.Marker ({
          //   map: _map,
          //   draggable: true,
          //   optimized: false,
          //   position: new google.maps.LatLng (t.lat, t.lng)
          // });
        });
        // $('.marker_label').imgLiquid ({verticalAlign: 'top'});
        $loadingData.removeClass ('show');
        setTimeout (function () {
        // $('.marker_label').imgLiquid ({verticalAlign: 'top'});
          // console.error ($('.marker_label').length);
        },500);
      }
    })
    .fail (function (result) { ajaxError (result); })
    .complete (function (result) {
        $loadingData.removeClass ('show');
    });
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
        zoom: 15,
        scaleControl: true,
        navigationControl: true,
        disableDoubleClickZoom: true,
        mapTypeControl: false,
        zoomControl: true,
        scrollwheel: true,
        streetViewControl: false,
        center: new google.maps.LatLng (25.03684951358938, 121.54878616333008),
      };

    _map = new google.maps.Map ($map.get (0), option);
    _map.mapTypes.set ('map_style', styledMapType);
    _map.setMapTypeId ('map_style');
    

    google.maps.event.addListener(_map, 'zoom_changed', getPictures);
    google.maps.event.addListener(_map, 'dragend', getPictures);


    $loading.fadeOut (function () {
      $(this).hide (function () {
        $(this).remove ();
        getPictures ();
      });
    });
  }

  google.maps.event.addDomListener (window, 'load', initialize);
});