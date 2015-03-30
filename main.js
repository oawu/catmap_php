$(function (e) {
  // $bits.click (function () {
  //   $(this).val ($(this).is (':checked') ? '1' : '0');
  //   $(this).next ('label').text ($(this).val ());
  // }).change (function () {
  //   if ($(this).is (':checked'))
  //     $digit.addClass ($(this).attr ('id'));
  //   else
  //     $digit.removeClass ($(this).attr ('id'));
  // });

  // var $bits = $('input[type="checkbox"]'),



  // var count = function (c) {
  //   var $digit = $('.digits .digit');
  //   var cs = c.toString ().split ('').filter (function (t) {
  //     return !isNaN (t);
  //   }).reverse ();

  //   $digit.removeClass ('b0 b1 b2 b3');
  //   for (var i = 0; i < cs.length; i++) {
  //     var t = parseInt (cs[i], 10).toString (2).split ('').reverse ();

  //     for (var j = 0; j < t.length; j++)
  //       $digit.eq (0 - i - 1).addClass ((t[j] == 1 ? 'b' : 'a') + j);
  //   }
  // };
  // var setTime = function () {
  //   var d = new Date ();
  //   count ('' + ('0' + d.getFullYear ()).slice (-4) + ('0' + (d.getMonth () + 1)).slice (-2) + ('0' + d.getDate ()).slice (-2) + ('0' + d.getHours ()).slice (-2) + ('0' + d.getMinutes ()).slice (-2) + ('0' + d.getSeconds ()).slice (-2));
  // };

  // setTime ();

  // window.setInterval (setTime, 1000);

  // $('#input').keyup (function () {
  //   count ($(this).val ());
  // });

  // var a = 0;
  // var func = function () {
  //   a = a.toString (2).split ('').reverse ();

  //   $digit.removeClass ('b0 b1 b2 b3');
  //   for (var i = 0; i < a.length; i++)
  //     $digit.last ().addClass ((a[i] == 1 ? 'b' : 'a') + i);
  //   a = parseInt (a.reverse ().join (''), 2) + 1;

  //   setTimeout (func, 1000);
  // };
  // func ();


});