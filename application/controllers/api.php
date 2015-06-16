<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Api extends Site_controller {

  public function __construct () {
    parent::__construct ();
  }

  public function events () {
    $next_id = $this->input_get ('next_id');

    $limit = 0;

    $conditions = $next_id ? array ('id > ?', $next_id) : array ();
    $events = Event::find ('all', array ('order' => 'id DESC', 'limit' => $limit, 'conditions' => $conditions));

    $next_id = isset ($events[0]) ? $events[0]->id : 0;

    return $this->output_json (array (
      'status' => true,
      'events' => array_map (function ($event) {
        return array (
            'id' => $event->id,
            'title' => $event->title
          );
      }, $events),
      'next_id' => $next_id
    ));
  }
  public function login () {

    $account = trim ($this->input_post ('account'));
    $password = trim ($this->input_post ('password'));

    if ($user = User::find ('one', array ('conditions' => array ('account = ? AND password = ?', $account, password ($password))))) {
      return $this->output_json (array (
        'status' => true,
        'user' => array (
            'id' => $user->id,
            'name' => $user->name,
            'account' => $user->account
          )
      ));  
    } else {
      return $this->output_json (array (
        'status' => false,
        'user' => null
      ));
    }
    
  }

  public function index () {
    // $this->load_view (null);
  }
}