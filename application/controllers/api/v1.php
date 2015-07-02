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
  private function _color_format ($picture) {
    if (!$picture->has_color ())
      return array ();
    else
      return array (
          'red' => $picture->color_red,
          'green' => $picture->color_green,
          'blue' => $picture->color_blue
        );
  }
  private function _position_format ($picture) {
    if (!$picture->has_position ())
      return array ();
    else
      return array (
          'latitude' => $picture->latitude,
          'longitude' => $picture->longitude,
          'altitude' => $picture->altitude
        );
  }
  private function _accuracy_format ($picture) {
    if (!$picture->has_accuracy ())
      return array ();
    else
      return array (
          'horizontal' => $picture->accuracy_horizontal,
          'vertical' => $picture->accuracy_vertical
        );
  }
  private function _address_format ($picture) {
    if (!$picture->has_address ())
      return array ();
    else
      return array (
          'city' => $picture->city,
          'country' => $picture->country,
          'address' => $picture->address
        );
  }
  private function _picture_format ($picture, $size = '800w') {
    $return = array (
        'id' => $picture->id,
        'description' => $picture->description,
        'url' => $picture->name->url ($size),
        'gradient' => $picture->gradient,
        'like_count' => count ($picture->likes),
        'comment_count' => count ($picture->comments),
        'user' => $this->_user_format ($picture->user, '140x140c'),
        'created_at' => $picture->created_at->format ('Y年m月d日 H:i')
      );

    if ($color = $this->_color_format ($picture))
      $return['color'] = $color;

    if ($position = $this->_position_format ($picture))
      $return['position'] = $position;

    if ($accuracy = $this->_accuracy_format ($picture))
      $return['accuracy'] = $accuracy;

    if ($address = $this->_address_format ($picture))
      $return['address'] = $address;

    return $return;
  }
  private function _user_format ($user, $size = '') {
    $return = array (
        'id' => $user->id,
        'name' => $user->name,
        'account' => $user->account,
        'avatar' => $user->avatar->url ($size),
      );

    if ($color = $this->_color_format ($user))
      $return['color'] = $color;

    return $return;
  }

  public function test () {
    return $this->output_json (array (
        'method' => $_SERVER['REQUEST_METHOD'],
        'gets' => $_GET,
        'posts' => $_POST,
        'files' => $_FILES
      ));
  }

  public function region_pictures () {
    $center = $this->input_get ('center');
    $span   = $this->input_get ('span');
    
    if (!(isset ($center['latitude']) && isset ($center['longitude']) && isset ($span['latitudeDelta']) && isset ($span['longitudeDelta'])))
      return $this->output_json (array ('status' => true, 'pictures' => array ()));

    $north_east = array (
      'latitude' => $center['latitude'] + ($span['latitudeDelta'] / 2),
      'longitude' => $center['longitude'] + ($span['longitudeDelta'] / 2)
      );
    $south_west = array (
      'latitude' => $center['latitude'] - ($span['latitudeDelta'] / 2),
      'longitude' => $center['longitude'] - ($span['longitudeDelta'] / 2)
      );

    $pictures = array_map (function ($picture) {
      return array (
          'id' => $picture->id,
          'lat' => $picture->latitude,
          'lng' => $picture->longitude,
          'des' => $picture->description,
          'url' => array (
              'ori' => $picture->name->url (),
              'w100' => $picture->name->url ('100w')
            )
        );
    }, Picture::find ('all', array ('conditions' => array ('latitude < ? AND latitude > ? AND longitude < ? AND longitude > ?', $north_east['latitude'], $south_west['latitude'], $north_east['longitude'], $south_west['longitude']))));

    return $this->output_json (array ('status' => true, 'pictures' => $pictures));
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

    $user_id     = $this->input_post ('user_id');
    $description = trim ($this->input_post ('description'));

    $position    = $this->input_post ('position');
    $accuracy    = $this->input_post ('accuracy');
    $address     = $this->input_post ('address');

    // $latitude    = (($latitude = trim ($this->input_post ('latitude'))) ? $latitude : '');
    // $longitude   = (($longitude = trim ($this->input_post ('longitude'))) ? $longitude : '');
    // $altitude    = (($altitude = trim ($this->input_post ('altitude'))) ? $altitude : '');

    $name        = $this->input_post ('name', true);

    if (!($description && $name && $position && $accuracy && $address))
      return $this->_error ('填寫資訊有少！');

    if (!($user_id && ($user = User::find_by_id ($user_id, array ('select' => 'id')))))
      return $this->_error ('User ID 錯誤！');

    if (!verifyCreateOrm ($picture = Picture::create (array (
        'user_id'     => $user->id,
        'description' => description ($description),
        'name'        => '',
        'gradient'    => 1,
        
        'color_red'   => '',
        'color_green' => '',
        'color_blue'  => '',

        'latitude'    => isset ($position['latitude']) ? $position['latitude'] : '',
        'longitude'   => isset ($position['longitude']) ? $position['longitude'] : '',
        'altitude'    => isset ($position['altitude']) ? $position['altitude'] : '',

        'accuracy_horizontal' => isset ($accuracy['horizontal']) ? $accuracy['horizontal'] : '',
        'accuracy_vertical'   => isset ($accuracy['vertical']) ? $accuracy['vertical'] : '',

        'city'        => isset ($address['city']) ? $address['city'] : '',
        'country'     => isset ($address['country']) ? $address['country'] : '',
        'address'     => isset ($address['address']) ? $address['address'] : ''
      ))))
      return $this->_error ('新增失敗！');

    if (!$picture->name->put ($name) && ($picture->delete () || true))
      return $this->_error ('新增失敗，上傳圖片失敗！');

    $picture->update_gradient ();

    delay_job ('main', 'picture', array (
        'id' => $picture->id
      ));

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
        'avatar'   => '',
        'color_red'   => '',
        'color_green' => '',
        'color_blue'  => ''
      );

    if (!verifyCreateOrm ($user = User::create ($params)))
      return $this->_error ('新增失敗！');
    
    if (!$user->avatar->put ($avatar) && ($user->delete () || true))
      return $this->_error ('新增失敗，上傳圖片失敗！');

    delay_job ('main', 'user', array (
        'id' => $user->id
      ));

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
