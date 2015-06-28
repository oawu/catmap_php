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
  var _markerCluster = null;
  var _isGetPictures = false;
  var _getPicturesTimer = null;

  function formatFloat (num, pos) {
    var size = Math.pow (10, pos);
    return Math.round (num * size) / size;
  }

  function getPictures () {
    clearTimeout (_getPicturesTimer);

    _getPicturesTimer = setTimeout (function () {
          console.error ('-');
      if (_isGetPictures)
        return;
      
      $loadingData.addClass ('show');
      _isGetPictures = true;

      var northEast = _map.getBounds().getNorthEast ();
      var southWest = _map.getBounds().getSouthWest ();

      $.ajax ({
        url: $('#get_pictures_url').val (),
        data: { NorthEast: {latitude: northEast.lat (), longitude: northEast.lng ()},
                SouthWest: {latitude: southWest.lat (), longitude: southWest.lng ()},  },
        async: true, cache: false, dataType: 'json', type: 'POST',
        beforeSend: function () {}
      })
      .done (function (result) {

        if (result.status) {

          var markers = result.pictures.map (function (t) {
            var markerWithLabel = new MarkerWithLabel ({
              position: new google.maps.LatLng (t.lat, t.lng),
              draggable: false,
              raiseOnDrag: false,
              clickable: true,
              labelContent: '<div class="img"><img src="' + t.url + '" /></div>',
              labelAnchor: new google.maps.Point (50, 50),
              labelClass: "marker_label",
              icon: {
                path: 'M 0 0',
                strokeColor: 'rgba(249, 39, 114, 0)',
                strokeWeight: 1,
                fillColor: 'rgba(249, 39, 114, 0)',
                fillOpacity: 0
              },
              initCallback: function (e) { $(e).find ('.img').imgLiquid ({verticalAlign: 'top'}); }
            });

            google.maps.event.addListener(markerWithLabel, 'click', function () { });

            return {
              id: t.id,
              marker: markerWithLabel
            };
          });

          if (!_markers.length) {
            _markers = markers.map (function (t) {
              _markerCluster.addMarker (t.marker);
              return t;
            });
          } else {
            var marker_ids = markers.map (function (t) { return t.id; });
            _markers = _markers.map (function (t) {
              if ($.inArray (t.id, marker_ids) != -1)
                return t;

              _markerCluster.removeMarker (t.marker);
              return null;
            }).filter (function (t) { return t; });

            marker_ids = _markers.map (function (t) { return t.id; });
            
            markers = markers.map (function (t) {
              if ($.inArray (t.id, marker_ids) != -1)
                return null;

              _markerCluster.addMarker (t.marker);
              return t;
            }).filter (function (t) { return t; });
            _markers = _markers.concat (markers);
          }
          $loadingData.removeClass ('show');
          _isGetPictures = false;
        }
      })
      .fail (function (result) { ajaxError (result); })
      .complete (function (result) {});
    }, 750);
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
        center: new google.maps.LatLng (25.022073145389157, 121.54706954956055),
      };

    _map = new google.maps.Map ($map.get (0), option);
    _map.mapTypes.set ('map_style', styledMapType);
    _map.setMapTypeId ('map_style');
    _markerCluster = new MarkerClusterer(_map);
    

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