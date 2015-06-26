<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */
class Main extends Delay_controller {

  public function __construct () {
    parent::__construct ();
  }

  public function picture () {
    if (($id = $this->input_post ('id')) && ($picture = Picture::find_by_id ($id)))
      $picture->update_color ();
  }
  public function user () {
    if (($id = $this->input_post ('id')) && ($user = User::find_by_id ($id)))
      $user->update_color ();
  }
}
