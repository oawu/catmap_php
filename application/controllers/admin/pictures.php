<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Pictures extends Admin_controller {

  public function __construct () {
    parent::__construct ();

    if (!identity ()->get_session ('is_login'))
      return redirect (array ('admin'));
  }

  public function destroy ($id = 0) {
    if (!($picture = Picture::find_by_id ($id)))
      return redirect (array ('admin', 'pictures'));

    $message = $picture->destroy () ? '刪除成功！' : '刪除失敗！';

    return identity ()->set_session ('_flash_message', $message, true)
                      && redirect (array ('admin', 'pictures'), 'refresh');
  }

  public function show ($id = 0) {
    if (!($picture = Picture::find_by_id ($id)))
      return redirect (array ('admin', 'pictures'));

    $this->load_view (array (
        'picture' => $picture
      ));
  }

  public function edit ($id = 0) {
    if (!User::count ())
      return identity ()->set_session ('_flash_message', '請先新增會員！', true)
                      && redirect (array ('admin', 'pictures'), 'refresh');
  
    if (!($picture = Picture::find_by_id ($id)))
      return redirect (array ('admin', 'pictures'));

    $message = identity ()->get_session ('_flash_message', true);
    $user_id = identity ()->get_session ('user_id', true);
    $title   = identity ()->get_session ('title', true);

    $this->load_view (array (
        'picture' => $picture,
        'message' => $message,
        'user_id' => $user_id,
        'title'   => $title
      ));
  }

  public function update ($id = 0) {
    if (!($picture = Picture::find_by_id ($id)))
      return redirect (array ('admin', 'pictures'));

    if (!$this->has_post ())
      return redirect (array ('admin', 'pictures', 'edit', $picture->id));

    $user_id = trim ($this->input_post ('user_id'));
    $title   = trim ($this->input_post ('title'));
    $name    = $this->input_post ('name', true);

    if (!($user_id && $title && ((String)$picture->name || $name)))
      return identity ()->set_session ('_flash_message', '填寫資訊有少！', true)
                        ->set_session ('user_id', $user_id, true)
                        ->set_session ('title', $title, true)
                        && redirect (array ('admin', 'pictures', 'edit', $picture->id), 'refresh');

    if ($name) {
      if (!($picture->name->put ($name)))
        return identity ()->set_session ('_flash_message', '修改失敗！', true)
                          ->set_session ('user_id', $user_id, true)
                          ->set_session ('title', $title, true)
                          && redirect (array ('admin', 'pictures', 'edit', $picture->id), 'refresh');
      
      $picture->update_gradient ();
    }

    $picture->user_id = $user_id;
    $picture->title   = $title;

    if (!$picture->save ())
      return identity ()->set_session ('_flash_message', '修改失敗！', true)
                        ->set_session ('user_id', $user_id, true)
                        ->set_session ('title', $title, true)
                        && redirect (array ('admin', 'pictures', 'edit', $picture->id), 'refresh');

    return identity ()->set_session ('_flash_message', '修改成功！', true)
                      && redirect (array ('admin', 'pictures'), 'refresh');
  }

  public function add () {
    if (!User::count ())
      return identity ()->set_session ('_flash_message', '請先新增會員！', true)
                      && redirect (array ('admin', 'pictures'), 'refresh');
  
    $message = identity ()->get_session ('_flash_message', true);
    $user_id = identity ()->get_session ('user_id', true);
    $title   = identity ()->get_session ('title', true);

    $this->load_view (array (
        'message' => $message,
        'user_id' => $user_id,
        'title'   => $title
      ));
  }

  public function create () {
    if (!$this->has_post ())
      return redirect (array ('admin', 'pictures', 'add'));

    $user_id = trim ($this->input_post ('user_id'));
    $title   = trim ($this->input_post ('title'));
    $name    = $this->input_post ('name', true);

    if (!($user_id && $title && $name))
      return identity ()->set_session ('_flash_message', '填寫資訊有少！', true)
                        ->set_session ('user_id', $user_id, true)
                        ->set_session ('title', $title, true)
                        && redirect (array ('admin', 'pictures', 'add'), 'refresh');

    $params = array (
        'user_id'   => $user_id,
        'title'     => $title,
        'name'      => '',
        'gradient'  => '1',
        'latitude'  => '',
        'longitude' => '',
        'altitude'  => ''
      );

    if (!verifyCreateOrm ($picture = Picture::create ($params)))
      return identity ()->set_session ('_flash_message', '新增失敗！', true)
                        ->set_session ('user_id', $user_id, true)
                        ->set_session ('title', $title, true)
                        && redirect (array ('admin', 'pictures', 'add'), 'refresh');

    if (!$picture->name->put ($name) && ($picture->delete () || true))
      return identity ()->set_session ('_flash_message', '新增失敗！', true)
                        ->set_session ('user_id', $user_id, true)
                        ->set_session ('title', $title, true)
                        && redirect (array ('admin', 'pictures', 'add'), 'refresh');

    $picture->update_gradient ();

    return identity ()->set_session ('_flash_message', '新增成功！', true)
                      && redirect (array ('admin', 'pictures'), 'refresh');
  }

  public function index ($offset = 0) {
    $columns = array ('title' => 'string');
    $configs = array ('admin', 'pictures', '%s');

    $conditions = conditions ($columns,
                              $configs,
                              'Picture',
                              $this->input_gets ()
                              );

    $conditions = array (implode (' AND ', $conditions));

    $limit = 25;
    $total = Picture::count (array ('conditions' => $conditions));
    $offset = $offset < $total ? $offset : 0;

    $this->load->library ('pagination');
    $configs = array_merge (array (
          'total_rows' => $total,
          'num_links' => 5,
          'per_page' => $limit,
          'uri_segment' => 0,
          'base_url' => '',
          'page_query_string' => false, 'first_link' => '第一頁', 'last_link' => '最後頁', 'prev_link' => '上一頁', 'next_link' => '下一頁', 'full_tag_open' => '<ul class="pagination">', 'full_tag_close' => '</ul>', 'first_tag_open' => '<li>', 'first_tag_close' => '</li>', 'prev_tag_open' => '<li>', 'prev_tag_close' => '</li>', 'num_tag_open' => '<li>', 'num_tag_close' => '</li>', 'cur_tag_open' => '<li class="active"><a href="#">', 'cur_tag_close' => '</a></li>', 'next_tag_open' => '<li>', 'next_tag_close' => '</li>', 'last_tag_open' => '<li>', 'last_tag_close' => '</li>',
        ), $configs);
    $this->pagination->initialize ($configs);
    $pagination = $this->pagination->create_links ();

    $pictures = Picture::find ('all', array ('offset' => $offset, 'limit' => $limit, 'order' => 'id DESC', 'include' => array ('user'), 'conditions' => $conditions));

    $message = identity ()->get_session ('_flash_message', true);

    $this->load_view (array ('message' => $message, 'pagination' => $pagination, 'pictures' => $pictures, 'columns' => $columns));
  }
}
