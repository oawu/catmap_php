<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Maps extends Site_controller {

  public function __construct () {
    parent::__construct ();
  }

  public function xxx ($a = 'no') {
    if ($a == 'yes') {
      $this->load->library ('CreateDemo');
      $pics = CreateDemo::pics (10, 30, $tags = array ('北港', '朝天宮', '象山', '新竹', '台東', '花蓮'));
      echo "\n 新增 " . count ($pics) . "筆照片。\n==========================================\n";

      foreach ($pics as $pic) {
        $user = User::find ('one', array ('select' => 'id', 'order' => 'RAND()', 'conditions' => array ()));
        $params = array (
            'user_id'     => $user->id,
            'description' => CreateDemo::text (10, 50),
            'name'        => $pic['url'],
            'gradient'    => '1',
            'latitude'    => '',
            'longitude'   => '',
            'altitude'    => '',
            'color_red'   => '',
            'color_green' => '',
            'color_blue'  => ''
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
    }


    $lat = 25.03684951358938;
    $lng = 121.54878616333008;
    foreach (Picture::all () as $pic) {
      $pic->latitude = $lat + (rand (-99999999, 99999999) * 0.000000001);
      $pic->longitude = $lng + (rand (-99999999, 99999999) * 0.000000001);
      $pic->save ();
    }
  }
  public function index () {
    $this->add_hidden (array ('id' => 'get_pictures_url', 'value' => base_url ($this->get_class (), 'get_pictures')))
         ->add_js ('https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&language=zh-TW', false)
         ->add_js (base_url ('resource', 'javascript', 'markerwithlabel_d2015_06_28', 'markerwithlabel.js'))
         ->add_js (base_url ('resource', 'javascript', 'markerclusterer_v1.0', 'markerclusterer.js'))
         ->add_js (base_url ('resource', 'javascript', 'imgLiquid_v0.9.944', 'imgLiquid-min.js'))
         ->add_js ('', false)
         ->load_view (null);
  }
  public function get_pictures () {
    if (!$this->is_ajax (false))
      return show_error ("It's not Ajax request!<br/>Please confirm your program again.");

    $north_east = $this->input_post ('NorthEast');
    $south_west = $this->input_post ('SouthWest');

    $pictures = array_map (function ($picture) {
      return array (
          'id' => $picture->id,
          'lat' => $picture->latitude,
          'lng' => $picture->longitude,
          'url' => $picture->name->url ('100w'),
        );
    }, Picture::find ('all', array ('conditions' => array ('latitude < ? AND latitude > ? AND longitude < ? AND longitude > ?', $north_east['latitude'], $south_west['latitude'], $north_east['longitude'], $south_west['longitude']))));

    return $this->output_json (array ('status' => true, 'pictures' => $pictures));
  }
}
