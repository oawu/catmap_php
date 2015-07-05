<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Maps extends Site_controller {

  public function __construct () {
    parent::__construct ();
  }

  public function index () {
    $this->add_meta (array ('property' => 'og:url', 'content' => current_url ()))
         ->add_hidden (array ('id' => 'get_pictures_url', 'value' => base_url ($this->get_class (), 'get_pictures')))
         
         ->add_css (base_url ('resource', 'css', 'fancyBox_v2.1.5', 'jquery.fancybox.css'))
         ->add_css (base_url ('resource', 'css', 'fancyBox_v2.1.5', 'jquery.fancybox-buttons.css'))
         ->add_css (base_url ('resource', 'css', 'fancyBox_v2.1.5', 'jquery.fancybox-thumbs.css'))
         ->add_css (base_url ('resource', 'css', 'fancyBox_v2.1.5', 'my.css'))

         ->add_js ('https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&language=zh-TW', false)
         ->add_js (base_url ('resource', 'javascript', 'markerwithlabel_d2015_06_28', 'markerwithlabel.js'))
         ->add_js (base_url ('resource', 'javascript', 'markerclusterer_v1.0', 'markerclusterer.js'))
         ->add_js (base_url ('resource', 'javascript', 'imgLiquid_v0.9.944', 'imgLiquid-min.js'))

         ->add_js (base_url ('resource', 'javascript', 'fancyBox_v2.1.5', 'jquery.fancybox.js'))
         ->add_js (base_url ('resource', 'javascript', 'fancyBox_v2.1.5', 'jquery.fancybox-buttons.js'))
         ->add_js (base_url ('resource', 'javascript', 'fancyBox_v2.1.5', 'jquery.fancybox-thumbs.js'))
         ->add_js (base_url ('resource', 'javascript', 'fancyBox_v2.1.5', 'jquery.fancybox-media.js'))
         ->load_view (null);
  }
  public function get_pictures () {
    if (!$this->is_ajax (false))
      return show_error ("It's not Ajax request!<br/>Please confirm your program again.");

    $north_east = $this->input_post ('NorthEast');
    $south_west = $this->input_post ('SouthWest');

    if (!(isset ($north_east['latitude']) && isset ($south_west['latitude']) && isset ($north_east['longitude']) && isset ($south_west['longitude'])))
      return $this->output_json (array ('status' => true, 'pictures' => array ()));

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
}
