/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

$(function () {
  var $pictures = $('#pictures');
  var scroll_timer = null;

  var masonry = new Masonry ($pictures.selector, {
                  itemSelector: '.picture',
                  columnWidth: 1,
                  transitionDuration: '0.3s',
                  visibleStyle: {
                    opacity: 1,
                    transform: 'none'
                  }});

  var setPictureFeature = function ($obj) {
    $obj.imagesLoaded (function () {
      $obj.find ('.img').css ({'height': $obj.show ().find ('.img img').css ('height')});
      $obj.find ('.timeago').timeago ();
      $obj.find ('.avatar').imgLiquid ({verticalAlign: 'top'});

      masonry.appended ($obj.get (0));
      return $obj;
    });
  };

  var loadPicturesFromServer = function () {
    if ($pictures.data ('next_id') > -1) {
      $.ajax ({
        url: $('#get_pictures_url').val (),
        data: { next_id: $pictures.data ('next_id') },
        async: true, cache: false, dataType: 'json', type: 'POST',
        beforeSend: function () { }
      })
      .done (function (result) {
        if (result.status) {
          result.pictures.map (function (t) {
            setPictureFeature ($(t).appendTo ($pictures).hide ());
          });
          $pictures.data ('next_id', result.next_id);

          if (result.next_id < 0) $pictures.find ('~ .loading').remove ();
          else $(window).scroll ();
        }
      }.bind (this))
      .fail (function (result) { ajaxError (result); })
      .complete (function (result) { });
    }
  };

  $(window).scroll (function () {
    clearTimeout (scroll_timer);
    if ($(window).height () + $(window).scrollTop () > $pictures.height () + $pictures.offset ().top - 50) {
      scroll_timer = setTimeout (loadPicturesFromServer, 500);
    }
  }.bind (this)).scroll ();

});