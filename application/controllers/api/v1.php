<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class V1 extends Api_controller {

  public function __construct () {
    parent::__construct ();
  }
  private function _method ($method) {
    return $_SERVER['REQUEST_METHOD'] !== strtoupper ($method) ? 'Request Method Error！' : '';
  }
  private function _error ($message) {
    return $this->output_json (array (
        'status' => false,
        'message' => $message
      ));
  }
  private function _user_format ($user) {
    return array (
        'id' => $user->id,
        'name' => $user->name,
        'account' => $user->account,
        'avatar' => $user->avatar->url ('100w'),
      );
  }
  private function _picture_format ($picture) {
    return array (
        'id' => $picture->id,
        'title' => $picture->title,
        'url' => $picture->name->url (),
        'gradient' => $picture->gradient,
        'latitude' => $picture->latitude,
        'longitude' => $picture->longitude,
        'altitude' => $picture->altitude,
        'like_count' => count ($picture->likes),
        'comment_count' => count ($picture->comments),
        'user' => $this->_user_format ($picture->user)
      );
  }
  public function test () {
    return $this->output_json (array (
        'method' => $_SERVER['REQUEST_METHOD'],
        'gets' => $_GET,
        'posts' => $_POST,
        'files' => $_FILES
      ));
  }

  public function prev_pictures () {
    if ($message = $this->_method ('GET'))
      return $this->_error ($message);

    $prev_id = ($prev_id = $this->input_get ('prev_id')) ? $prev_id : 0;
    $limit = ($limit = $this->input_get ('limit')) ? $limit : 5;

    $conditions = array ('id >= ?', $prev_id);
    $pictures = Picture::find ('all', array ('order' => 'id ASC', 'limit' => $limit + 1, 'include' => array ('user', 'likes', 'comments'), 'conditions' => $conditions));

    $prev_id = ($temp = (count ($pictures) > $limit ? end ($pictures) : null)) ? $temp->id : -1;

    $that = $this;
    return $this->output_json (array (
      'status' => true,
      'pictures' => array_map (function ($picture) use ($that) {
        return $that->_picture_format ($picture);
      }, array_slice ($pictures, 0, $limit)),
      'prev_id' => $prev_id
    ));
  }

  public function next_pictures () {
    if ($message = $this->_method ('GET'))
      return $this->_error ($message);

    $next_id = $this->input_get ('next_id');
    $limit = ($limit = $this->input_get ('limit')) ? $limit : 5;

    $conditions = $next_id ? array ('id <= ?', $next_id) : array ();
    $pictures = Picture::find ('all', array ('order' => 'id DESC', 'limit' => $limit + 1, 'include' => array ('user', 'likes', 'comments'), 'conditions' => $conditions));

    $next_id = ($temp = (count ($pictures) > $limit ? end ($pictures) : null)) ? $temp->id : -1;

    $that = $this;
    return $this->output_json (array (
      'status' => true,
      'pictures' => array_map (function ($picture) use ($that) {
        return $that->_picture_format ($picture);
      }, array_slice ($pictures, 0, $limit)),
      'next_id' => $next_id
    ));
  }

  public function create_picture () {
    if ($message = $this->_method ('POST'))
      return $this->_error ($message);

    $user_id   = $this->input_post ('user_id');
    $title     = trim ($this->input_post ('title'));
    $latitude  = trim ($this->input_post ('latitude'));
    $longitude = trim ($this->input_post ('longitude'));
    $altitude  = trim ($this->input_post ('altitude'));
    $name      = $this->input_post ('name', true);

    if (!($title && $latitude && $longitude && $altitude && $name))
      return $this->_error ('填寫資訊有少！');

    if (!($user = User::find_by_id ($user_id, array ('select' => 'id'))))
      return $this->_error ('user_id 錯誤！');

    if (!verifyCreateOrm ($picture = Picture::create (array (
        'user_id'   => $user->id,
        'title'     => $title,
        'name'      => '',
        'gradient'  => 1,
        'latitude'  => $latitude,
        'longitude' => $longitude,
        'altitude'  => $altitude
      ))))
      return $this->_error ('新增失敗！');

    if (!$picture->name->put ($name) && ($picture->delete () || true))
      return $this->_error ('新增失敗，上傳圖片失敗！');

    $picture->update_gradient ();

    return $this->output_json (array (
      'status' => true,
      'picture' => $this->_picture_format ($picture)
    ));
  }

  public function register () {
    if ($message = $this->_method ('POST'))
      return $this->_error ($message);

    $account  = trim ($this->input_post ('account'));
    $password = trim ($this->input_post ('password'));
    $name     = trim ($this->input_post ('name'));
    $avatar   = $this->input_post ('avatar', true);

    if (!($account && $password && $name && $avatar))
      return $this->_error ('填寫資訊有少！');

    if (User::find_by_account ($account))
      return $this->_error ('帳號已經有人使用！');

    $params = array (
        'account'  => $account,
        'password' => password ($password),
        'name'     => $name,
        'avatar'   => ''
      );

    if (!verifyCreateOrm ($user = User::create ($params)))
      return $this->_error ('新增失敗！');
    
    if (!$user->avatar->put ($avatar) && ($user->delete () || true))
      return $this->_error ('新增失敗，上傳圖片失敗！');

    return $this->output_json (array (
      'status' => true,
      'user' => $this->_user_format ($user)
    ));
  }

  public function login () {
    if ($message = $this->_method ('POST'))
      return $this->_error ($message);

    $account  = trim ($this->input_post ('account'));
    $password = trim ($this->input_post ('password'));

    if (!($user = User::find ('one', array ('conditions' => array ('account = ? AND password = ?', $account, password ($password))))))
      return $this->_error ('找不到使用者！');

    return $this->output_json (array (
      'status' => true,
      'user' => $this->_user_format ($user)
    ));  
  }
}
