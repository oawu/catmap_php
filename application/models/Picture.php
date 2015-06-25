<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Picture extends OaModel {

  static $table_name = 'pictures';

  static $has_one = array (
  );

  static $has_many = array (
    array ('comments', 'class_name' => 'Comment'),
    array ('likes', 'class_name' => 'Like')
  );

  static $belongs_to = array (
    array ('user', 'class_name' => 'User')
  );

  public function __construct ($attributes = array (), $guard_attributes = true, $instantiating_via_find = false, $new_record = true) {
    parent::__construct ($attributes, $guard_attributes, $instantiating_via_find, $new_record);

    OrmImageUploader::bind ('name', 'PictureNameImageUploader');

  }

  public function destroy () {
    array_map (function ($comment) {
      $comment->destroy ();
    }, $this->comments);

    array_map (function ($like) {
      $like->destroy ();
    }, $this->likes);

    return $this->name->cleanAllFiles () && $this->delete ();
  }

  public function update_gradient () {
    $image_utility = ImageUtility::create (FCPATH . implode('/', $this->name->path ()));
    if (ImageUtility::verifyDimension ($dimension = $image_utility->getDimension ())) {
      $this->gradient = gradient ($dimension['height'] / $dimension['width']);
      $this->save ();      
    }
  }

  public function update_color () {
    $image_utility = ImageUtility::create (FCPATH . implode('/', $this->name->path ()));

    if (($analysis_datas = $image_utility->getAnalysisDatas (1)) && isset ($analysis_datas[0]['color']) && ($analysis_datas = $analysis_datas[0]['color']) && (isset ($analysis_datas['r']) && isset ($analysis_datas['g']) && isset ($analysis_datas['b']))) {
      $this->color_red   = $analysis_datas['r'];
      $this->color_green = $analysis_datas['g'];
      $this->color_blue  = $analysis_datas['b'];
      $this->save ();
    }
  }
}