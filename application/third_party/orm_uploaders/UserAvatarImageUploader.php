<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class UserAvatarImageUploader extends OrmImageUploader {

  public function getVersions () {
    return array (
        '' => array (),
        '65x65c' => array ('adaptiveResizeQuadrant', 65, 65, 't'),
        '100x100c' => array ('adaptiveResizeQuadrant', 100, 100, 't'),
        '200x200c' => array ('adaptiveResizeQuadrant', 200, 200, 't')
        // '140x140c' => array ('adaptiveResizeQuadrant', 140, 140, 't')
      );
  }
}