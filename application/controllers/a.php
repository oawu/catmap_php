<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class A extends Site_controller {

  public function __construct () {
    parent::__construct ();
  }
  public function demo_pictures () {
    $this->load->library ('CreateDemo');

    foreach (CreateDemo::pics (1, 30) as $pic) {
      if (verifyCreateOrm ($picture = Picture::create (array (
                'title' => $pic['title'],
                'name' => $pic['url'],
                'gradient' => '1'
              )))) {
        $picture->name->put_url ($pic['url']);
        $picture->update_gradient (); 
      }
    }
  }

  public function prev_pictures () {
    $prev_id = ($prev_id = $this->input_get ('prev_id')) ? $prev_id : 0;
    $limit = ($limit = $this->input_get ('limit')) ? $limit : 5;

    $conditions = array ('id >= ?', $prev_id);
    $pictures = Picture::find ('all', array ('order' => 'id ASC', 'limit' => $limit + 1, 'conditions' => $conditions));

    $prev_id = ($temp = (count ($pictures) > $limit ? end ($pictures) : null)) ? $temp->id : -1;

    return $this->output_json (array (
      'status' => true,
      'pictures' => array_map (function ($picture) {
        return array (
            'id' => $picture->id,
            'title' => $picture->title,
            'url' => $picture->name->url ('800w'),
            'gradient' => $picture->gradient
          );
      }, array_slice ($pictures, 0, $limit)),
      'prev_id' => $prev_id
    ));
  }

  public function next_pictures () {
    $next_id = $this->input_get ('next_id');
    $limit = ($limit = $this->input_get ('limit')) ? $limit : 5;

    $conditions = $next_id ? array ('id <= ?', $next_id) : array ();
    $pictures = Picture::find ('all', array ('order' => 'id DESC', 'limit' => $limit + 1, 'conditions' => $conditions));

    $next_id = ($temp = (count ($pictures) > $limit ? end ($pictures) : null)) ? $temp->id : -1;

    return $this->output_json (array (
      'status' => true,
      'pictures' => array_map (function ($picture) {
        return array (
            'id' => $picture->id,
            'title' => $picture->title,
            'url' => $picture->name->url ('800w'),
            'gradient' => $picture->gradient
          );
      }, array_slice ($pictures, 0, $limit)),
      'next_id' => $next_id
    ));
  }

  public function pictures () {
    $pictures = Picture::all ();

    return $this->output_json (array (
      'status' => true,
      'pictures' => array_map (function ($picture) {
        return array (
            'id' => $picture->id,
            'title' => $picture->title,
            'url' => $picture->name->url ('800w'),
            'gradient' => $picture->gradient
          );
      }, $pictures)
    ));
  }
  public function add_picture () {
    $title = trim ($this->input_post ('title'));
    $name = $this->input_post ('name', true);

    if (!($title && $name))
      return $this->output_json (array (
        'status' => false,
        'message' => '1'
      ));

    if (!verifyCreateOrm ($picture = Picture::create (array (
        'title' => $title,
        'name' => '',
        'gradient' => 1
      ))))
      return $this->output_json (array (
        'status' => false,
        'message' => '2'
      ));

    if (!$picture->name->put ($name) && ($picture->delete () || true))
      return $this->output_json (array (
        'status' => false,
        'message' => '3'
      ));

    $picture->update_gradient ();

    return $this->output_json (array (
      'status' => true,
      'picture' => array (
            'id' => $picture->id,
            'title' => $picture->title,
            'url' => $picture->name->url (),
            'gradient' => $picture->gradient
        )
    ));
  }
  public function delete_event () {
    $id = $this->input_post ('id');

    if (!$event = Event::find_by_id ($id))
      return $this->output_json (array (
        'status' => false
      ));

    if (!$event->delete ())
      return $this->output_json (array (
        'status' => false
      ));

    return $this->output_json (array (
      'status' => true
    ));
  }
  public function add_event () {

    $title = trim ($this->input_post ('title'));

    if (!$title)
      return $this->output_json (array (
        'status' => false
      ));

    if (!verifyCreateOrm ($event = Event::create (array (
        'title' => $title
      ))))
      return $this->output_json (array (
        'status' => false
      ));

    return $this->output_json (array (
      'status' => true,
      'event' => array (
            'id' => $event->id,
            'title' => $event->title
      )
    ));
  }
  public function update_event () {
    $id = $this->input_post ('id');
    $title = trim ($this->input_post ('title'));

    if (!$event = Event::find_by_id ($id))
      return $this->output_json (array (
        'status' => false
      ));
    
    $event->title = $title;
    if (!$event->save ())
      return $this->output_json (array (
        'status' => false
      ));

    return $this->output_json (array (
      'status' => true,
      'event' => array (
            'id' => $event->id,
            'title' => $event->title
      )
    ));
  }
  public function event () {
    $id = $this->input_get ('id');

    $event = Event::find_by_id ($id);

    return $this->output_json (array (
      'status' => $event ? true : false,
      'event' => array (
            'id' => $event->id,
            'title' => $event->title
      )
    ));
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
