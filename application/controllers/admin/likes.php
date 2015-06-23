<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Likes extends Admin_controller {

  public function __construct () {
    parent::__construct ();

    if (!identity ()->get_session ('is_login'))
      return redirect (array ('admin'));
  }

  public function destroy ($id = 0) {
    if (!($like = Like::find_by_id ($id)))
      return redirect (array ('admin', 'likes'));

    $message = $like->destroy () ? '刪除成功！' : '刪除失敗！';

    return identity ()->set_session ('_flash_message', $message, true)
                      && redirect (array ('admin', 'likes'), 'refresh');
  }

  public function edit ($id = 0) {
    if (!User::count ())
      return identity ()->set_session ('_flash_message', '請先新增會員！', true)
                      && redirect (array ('admin', 'likes'), 'refresh');
  
    if (!Picture::count ())
      return identity ()->set_session ('_flash_message', '請先新增照片！', true)
                      && redirect (array ('admin', 'likes'), 'refresh');
  
    if (!($like = Like::find_by_id ($id)))
      return redirect (array ('admin', 'likes'));

    $message    = identity ()->get_session ('_flash_message', true);
    $user_id    = identity ()->get_session ('user_id', true);
    $picture_id = identity ()->get_session ('picture_id', true);

    $this->load_view (array (
        'message'    => $message,
        'user_id'    => $user_id,
        'picture_id' => $picture_id,
        'like'       => $like
      ));
  }

  public function update ($id = 0) {
    if (!($like = Like::find_by_id ($id)))
      return redirect (array ('admin', 'likes'));

    if (!$this->has_post ())
      return redirect (array ('admin', 'likes', 'edit', $like->id));

    $user_id    = trim ($this->input_post ('user_id'));
    $picture_id = trim ($this->input_post ('picture_id'));

    if (!($user_id && $picture_id))
      return identity ()->set_session ('_flash_message', '填寫資訊有少！', true)
                        ->set_session ('user_id', $user_id, true)
                        ->set_session ('picture_id', $picture_id, true)
                        && redirect (array ('admin', 'likes', 'edit', $like->id), 'refresh');

    $like->user_id    = $user_id;
    $like->picture_id = $picture_id;

    if (!$like->save ())
      return identity ()->set_session ('_flash_message', '修改失敗！', true)
                        ->set_session ('user_id', $user_id, true)
                        ->set_session ('picture_id', $picture_id, true)
                        && redirect (array ('admin', 'likes', 'edit', $like->id), 'refresh');

    return identity ()->set_session ('_flash_message', '修改成功！', true)
                      && redirect (array ('admin', 'likes'), 'refresh');
  }

  public function add () {
    if (!User::count ())
      return identity ()->set_session ('_flash_message', '請先新增會員！', true)
                      && redirect (array ('admin', 'likes'), 'refresh');
  
    if (!Picture::count ())
      return identity ()->set_session ('_flash_message', '請先新增照片！', true)
                      && redirect (array ('admin', 'likes'), 'refresh');
  
    $message    = identity ()->get_session ('_flash_message', true);
    $user_id    = identity ()->get_session ('user_id', true);
    $picture_id = identity ()->get_session ('picture_id', true);

    $this->load_view (array (
        'message'    => $message,
        'user_id'    => $user_id,
        'picture_id' => $picture_id
      ));
  }

  public function create () {
    if (!$this->has_post ())
      return redirect (array ('admin', 'likes', 'add'));

    $user_id    = trim ($this->input_post ('user_id'));
    $picture_id = trim ($this->input_post ('picture_id'));

    if (!($user_id && $picture_id))
      return identity ()->set_session ('_flash_message', '填寫資訊有少！', true)
                        ->set_session ('user_id', $user_id, true)
                        ->set_session ('picture_id', $picture_id, true)
                        && redirect (array ('admin', 'likes', 'add'), 'refresh');

    $params = array (
        'user_id'    => $user_id,
        'picture_id' => $picture_id
      );

    if (!verifyCreateOrm ($like = Like::create ($params)))
      return identity ()->set_session ('_flash_message', '新增失敗！', true)
                        ->set_session ('user_id', $user_id, true)
                        ->set_session ('picture_id', $picture_id, true)
                        && redirect (array ('admin', 'likes', 'add'), 'refresh');

    return identity ()->set_session ('_flash_message', '新增成功！', true)
                      && redirect (array ('admin', 'likes'), 'refresh');
  }

  public function index ($offset = 0) {
    $columns = array ('user_id' => 'int', 'picture_id' => 'int');
    $configs = array ('admin', 'likes', '%s');

    $conditions = conditions ($columns,
                              $configs,
                              'Like',
                              $this->input_gets ()
                              );

    $conditions = array (implode (' AND ', $conditions));

    $limit = 25;
    $total = Like::count (array ('conditions' => $conditions));
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

    $likes = Like::find ('all', array ('offset' => $offset, 'limit' => $limit, 'order' => 'id DESC', 'include' => array ('user', 'picture'), 'conditions' => $conditions));

    $message = identity ()->get_session ('_flash_message', true);

    $this->load_view (array ('message' => $message, 'pagination' => $pagination, 'likes' => $likes, 'columns' => $columns));
  }
}
