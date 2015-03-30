/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2014 OA Wu Design
 */

$(function () {
  // ga ('send', 'event', 'test', 'send', document.URL);
  // $('button').click (function () {
  //   // ga ('send', 'event', 'test', 'send', document.URL);
  // });

    $('body').mCustomScrollbar ({
      theme: 'minimal-dark',
      callbacks: {
        onScroll: function () {
          ga ('send', 'event', 'mCustomScrollbar', 'send', document.URL);

        }
      }
    });
});