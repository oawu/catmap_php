<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Site_frame_cell extends Cell_Controller {

  /* render_cell ('site_frame_cell', 'header', array ()); */
  // public function _cache_header () {
  //   return array ('time' => 60 * 60, 'key' => null);
  // }
  public function header () {
    $left_links = array (
        array ('name' => '首頁', 'href' => base_url (), 'is_login' => false),
        array ('name' => '地圖檢視', 'href' => base_url ('maps'), 'is_login' => false),
      );
    $right_links = array (
        array ('name' => '登出', 'href' => base_url ('main', 'logout'), 'is_login' => true),
        array ('name' => '登入', 'href' => base_url ('main', 'login'), 'is_login' => false),
      );

    return $this->setUseCssList (true)
                ->load_view (array (
                    'left_links' => $left_links,
                    'right_links' => $right_links
                  ));
  }

  /* render_cell ('site_frame_cell', 'footer', array ()); */
  // public function _cache_footer () {
  //   return array ('time' => 60 * 60, 'key' => null);
  // }
  public function footer () {
    return $this->setUseCssList (true)
                ->load_view ();
  }
}