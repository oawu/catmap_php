<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class UserAvatarImageUploader extends OrmImageUploader {

  public function getVersions () {
    return array (
        '' => array (),
        '130x130t' => array ('adaptiveResizeQuadrant', 130, 130, 't'),
        '200x200t' => array ('adaptiveResizeQuadrant', 200, 200, 't'),
        '400x400t' => array ('adaptiveResizeQuadrant', 400, 400, 't')
        // '140x140c' => array ('adaptiveResizeQuadrant', 140, 140, 't')
      );
  }
}