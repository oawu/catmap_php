<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Demo extends Site_controller {

  public function __construct () {
    parent::__construct ();
  }
  
  public function test () {
    $cities = array ('八里區', '三芝區', '三重區', '三峽區', '土城區', '中和區', '五股區', '平溪區', '永和區', '石門區', '石碇區', '汐止區', '坪林區', '林口區', '板橋區', '金山區', '泰山區', '烏來區', '貢寮區', '淡水區', '深坑區', '新店區', '新莊區', '瑞芳區', '萬里區', '樹林區', '雙溪區', '蘆洲區', '鶯歌區', '中正區','大同區','中山區','松山區','大安區','萬華區','信義區','士林區','北投區','內湖區','南港區','文山區');
    // echo '<meta http-equiv="Content-type" content="text/html; charset=utf-8" /><pre>';
    var_dump ($cities[array_rand ($cities)]);
    exit ();

  }
  public function create () {
    $this->load->library ('CreateDemo');

    $pics = CreateDemo::pics (1, 10, $tags = array ('貓咪', '貓', '貓星人', '柴犬', '可愛', '狗', '寵物', '台灣', '名人'));
    echo "\n 新增 " . count ($pics) . "筆會員。\n==========================================\n";

    foreach ($pics as $pic) {
      $params = array (
          'account'  => CreateDemo::password (),
          'password' => password (CreateDemo::password ()),
          'name'     => CreateDemo::text (4, 10),
          'avatar'   => $pic['url'],
          'color_red'   => '',
          'color_green' => '',
          'color_blue'  => ''
        );
      if (verifyCreateOrm ($user = User::create ($params))) {
        if ($user->avatar->put_url ($pic['url'])) {
          $user->update_color ();
          echo " Create a user, id: " . $user->id . "\n";
        } else {
          $user->delete ();
        }
      }
    }

    $pics = CreateDemo::pics (100, 200, $tags = array ('貓咪', '貓', '貓星人', '柴犬', '可愛', '狗', '寵物', '北港', '朝天宮', '象山', '新竹', '台東', '花蓮'));
    echo "\n 新增 " . count ($pics) . "筆照片。\n==========================================\n";

    $lat = 25.03684951358938;
    $lng = 121.54878616333008;
    $cities = array ('八里區', '三芝區', '三重區', '三峽區', '土城區', '中和區', '五股區', '平溪區', '永和區', '石門區', '石碇區', '汐止區', '坪林區', '林口區', '板橋區', '金山區', '泰山區', '烏來區', '貢寮區', '淡水區', '深坑區', '新店區', '新莊區', '瑞芳區', '萬里區', '樹林區', '雙溪區', '蘆洲區', '鶯歌區', '中正區','大同區','中山區','松山區','大安區','萬華區','信義區','士林區','北投區','內湖區','南港區','文山區');

    foreach ($pics as $pic) {
      $user = User::find ('one', array ('select' => 'id', 'order' => 'RAND()', 'conditions' => array ()));
      $params = array (
          'user_id'     => $user->id,
          'description' => CreateDemo::text (10, 50),
          'name'        => $pic['url'],
          'gradient'    => '1',
          
          'color_red'   => '',
          'color_green' => '',
          'color_blue'  => '',
          
          'latitude'    => $lat + (rand (-99999999, 99999999) * 0.000000001),
          'longitude'   => $lng + (rand (-99999999, 99999999) * 0.000000001),
          'altitude'    => rand (1, 60),

          'horizontal'  => rand (1, 60),
          'vertical'    => rand (1, 60),

          'city'        => $cities[array_rand ($cities)],
          'country'     => '台灣',
          'address'     => CreateDemo::text (10, 50),
        );
      if (verifyCreateOrm ($picture = Picture::create ($params))) {
        if ($picture->name->put_url ($pic['url'])) {
          $picture->update_gradient ();
          $picture->update_color ();
          echo " Create a pic, id: " . $picture->id . "\n";
        } else {
          $picture->delete ();
        }
      }
    }



    $comments = range (0, 50);
    echo "\n 新增 " . count ($comments) . "筆留言。\n==========================================\n";

    array_map (function ($i) {
      $user = User::find ('one', array ('select' => 'id', 'order' => 'RAND()', 'conditions' => array ()));
      $picture = Picture::find ('one', array ('select' => 'id', 'order' => 'RAND()', 'conditions' => array ()));

      $params = array (
        'user_id'    => $user->id,
        'picture_id' => $picture->id,
        'content'    => CreateDemo::text (40, 50)
      );

      if (verifyCreateOrm ($comment = Comment::create ($params))) {
        echo " Create a comment, id: " . $comment->id . "\n";
      }
    }, $comments);



    $likes = range (0, 50);
    echo "\n 新增 " . count ($likes) . "筆喜歡。\n==========================================\n";

    array_map (function ($i) {
      $user = User::find ('one', array ('select' => 'id', 'order' => 'RAND()', 'conditions' => array ()));
      $picture = Picture::find ('one', array ('select' => 'id', 'order' => 'RAND()', 'conditions' => array ()));

      $params = array (
        'user_id'    => $user->id,
        'picture_id' => $picture->id
      );

      if (verifyCreateOrm ($like = Like::create ($params))) {
        echo " Create a like, id: " . $like->id . "\n";
      }
    }, $likes);

    echo "\n==========================================\n  順利結束！\n==========================================\n";
  }
}
