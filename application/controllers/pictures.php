<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Pictures extends Site_controller {

  public function __construct () {
    parent::__construct ();
  }

  public function destroy ($id = 0) {
    if (!($picture = Picture::find_by_id ($id)))
      return redirect (array ('pictures'));

    $message = $picture->delete () ? '刪除成功！' : '刪除失敗！';

    return identity ()->set_session ('_flash_message', $message, true)
                      && redirect (array ('pictures'), 'refresh');
  }

  public function edit ($id = 0) {
    if (!($picture = Picture::find_by_id ($id)))
      return redirect (array ('pictures'));

    $message = identity ()->get_session ('_flash_message', true);
    $title   = identity ()->get_session ('title', true);

    $this->load_view (array (
        'message' => $message,
        'title' => $title,
        'picture' => $picture
      ));
  }

  public function update ($id = 0) {
    if (!($picture = Picture::find_by_id ($id)))
      return redirect (array ('pictures'));

    if (!$this->has_post ())
      return redirect (array ('pictures', 'edit', $picture->id));

    $title = trim ($this->input_post ('title'));
    $name = $this->input_post ('name', true);

    if (!($title && ((String)$picture->name || $name)))
      return identity ()->set_session ('_flash_message', '填寫資訊有少！', true)
                        ->set_session ('title', $title, true)
                        && redirect (array ('pictures', 'edit', $picture->id), 'refresh');

    $picture->title = $title;

    if (!$picture->save ())
      return identity ()->set_session ('_flash_message', '修改失敗！', true)
                        ->set_session ('title', $title, true)
                        && redirect (array ('pictures', 'edit', $picture->id), 'refresh');

    if ($name && !($picture->name->put ($name)))
      return identity ()->set_session ('_flash_message', '修改失敗！', true)
                        ->set_session ('title', $title, true)
                        && redirect (array ('pictures', 'edit', $picture->id), 'refresh');

    return identity ()->set_session ('_flash_message', '修改成功！', true)
                      && redirect (array ('pictures'), 'refresh');
  }

  public function add () {
    $message = identity ()->get_session ('_flash_message', true);
    $title   = identity ()->get_session ('title', true);

    $this->load_view (array (
        'message' => $message,
        'title' => $title
      ));
  }

  public function create () {
    if (!$this->has_post ())
      return redirect (array ('pictures', 'add'));

    $title = trim ($this->input_post ('title'));
    $name = $this->input_post ('name', true);

    if (!($title && $name))
      return identity ()->set_session ('_flash_message', '填寫資訊有少！', true)
                        ->set_session ('title', $title, true)
                        && redirect (array ('pictures', 'add'), 'refresh');

    $params = array (
        'title' => $title,
        'name' => ''
      );

    if (!verifyCreateOrm ($picture = Picture::create ($params)))
        return identity ()->set_session ('_flash_message', '新增失敗！', true)
                          ->set_session ('title', $title, true)
                          && redirect (array ('pictures', 'add'), 'refresh');

    if (!$picture->name->put ($name) && ($picture->delete () || true))
      return identity ()->set_session ('_flash_message', '新增失敗！', true)
                        ->set_session ('title', $title, true)
                        && redirect (array ('pictures', 'add'), 'refresh');

    return identity ()->set_session ('_flash_message', '新增成功！', true)
                      && redirect (array ('pictures'), 'refresh');
  }

  public function index ($offset = 0) {

    $qks = array ('title');
    $qs = array_filter (array_combine ($qks, array_map (function ($q) { return $this->input_get ($q); }, $qks)), function ($t) { return is_numeric ($t) ? true : $t; });
    $temp = array_slice ($qs, 0);
    array_walk ($temp, function (&$v, $k) { $v = $k . '=' . $v; });
    $q = implode ('&amp;', $temp);

    $temp = array_slice ($qs, 0);
    array_walk ($temp, function (&$v, $k) { $v = in_array ($k, array ('title')) ? ($k . ' LIKE ' . Picture::escape ('%' . $v . '%')) : ($k . ' = ' . DinTaoInfo::escape ($v)); });
    $conditions = array (implode (' AND ', $temp));

    $limit = 25;
    $total = Picture::count (array ('conditions' => $conditions));
    $offset = $offset < $total ? $offset : 0;

    $this->load->library ('pagination');
    $pagination_config = array (
      'total_rows' => $total,
      'num_links' => 5,
      'per_page' => $limit,
      'base_url' => base_url (array ('pictures', '%s', $q ? '?' . $q : '')),
      'uri_segment' => $offset ? 2 : 0,
      'page_query_string' => false,
      'first_link' => '第一頁', 'last_link' => '最後頁', 'prev_link' => '上一頁', 'next_link' => '下一頁',
      'full_picture_open' => '<ul class="pagination">', 'full_picture_close' => '</ul>', 'first_picture_open' => '<li>', 'first_picture_close' => '</li>',
      'prev_picture_open' => '<li>', 'prev_picture_close' => '</li>', 'num_picture_open' => '<li>', 'num_picture_close' => '</li>',
      'cur_picture_open' => '<li class="active"><a href="#">', 'cur_picture_close' => '</a></li>',
      'next_picture_open' => '<li>', 'next_picture_close' => '</li>', 'last_picture_open' => '<li>', 'last_picture_close' => '</li>',
      );

    $this->pagination->initialize ($pagination_config);
    $pagination = $this->pagination->create_links ();

    $pictures = Picture::find ('all', array ('offset' => $offset, 'limit' => $limit, 'order' => 'id DESC', 'conditions' => $conditions));

    $message = identity ()->get_session ('_flash_message', true);

    $this->load_view (array ('message' => $message, 'pagination' => $pagination, 'pictures' => $pictures, 'qs' => $qs));
  }
}
