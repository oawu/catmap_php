<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Frame_cell extends Cell_Controller {

  /* render_cell ('frame_cell', 'header', array ()); */
  // public function _cache_header () {
  //   return array ('time' => 60 * 60, 'key' => null);
  // }
  public function header () {
    $links = array (
        array ('name' => '會員管理', 'href' => base_url ('admin', 'users')),
        array ('name' => '照片管理', 'href' => base_url ('admin', 'pictures')),
        array ('name' => '留言管理', 'href' => base_url ('admin', 'comments')),
        array ('name' => '按讚管理', 'href' => base_url ('admin', 'likes')),
      );
    return $this->setUseCssList (true)
                ->load_view (array (
                    'links' => $links
                  ));
  }

  /* render_cell ('frame_cell', 'footer', array ()); */
  // public function _cache_footer () {
  //   return array ('time' => 60 * 60, 'key' => null);
  // }
  public function footer () {
    return $this->setUseCssList (true)
                ->load_view ();
  }
  
  /* render_cell ('frame_cell', 'pagination', $pagination); */
  // public function _cache_pagination () {
  //   return array ('time' => 60 * 60, 'key' => null);
  // }
  public function pagination ($pagination) {
    return $this->setUseCssList (true)
                ->load_view (array ('pagination' => $pagination));
  }
}