<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class UserAvatarImageUploader extends OrmImageUploader {

  public function getVersions () {
    return array (
        '' => array (),
        '140x140c' => array ('adaptiveResizeQuadrant', 140, 140, 'c')
      );
  }
}