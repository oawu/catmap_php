<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Users extends Site_controller {

  public function __construct () {
    parent::__construct ();
  }

  public function destroy ($id = 0) {
    if (!($user = User::find_by_id ($id)))
      return redirect (array ('users'));

    $message = $user->delete () ? '刪除成功！' : '刪除失敗！';

    return identity ()->set_session ('_flash_message', $message, true)
                    && redirect (array ('users'), 'refresh');
  }

  public function edit ($id = 0) {
    if (!($user = User::find_by_id ($id)))
      return redirect (array ('users'));

    $message  = identity ()->get_session ('_flash_message', true);
    $account  = identity ()->get_session ('account', true);
    $password = identity ()->get_session ('password', true);
    $name     = identity ()->get_session ('name', true);

    $this->load_view (array (
        'message' => $message,
        'account' => $account,
        'password' => $password,
        'name' => $name,
        'user' => $user
      ));
  }

  public function update ($id = 0) {
    if (!($user = User::find_by_id ($id)))
      return redirect (array ('users'));

    if (!$this->has_post ())
      return redirect (array ('users', 'edit', $user->id));

    $account  = trim ($this->input_post ('account'));
    $password = trim ($this->input_post ('password'));
    $name     = trim ($this->input_post ('name'));

    if (!($account && $password && $name))
      return identity ()->set_session ('_flash_message', '填寫資訊有少！', true)
                        ->set_session ('account', $account, true)
                        ->set_session ('password', $password, true)
                        ->set_session ('name', $name, true)
                        && redirect (array ('users', 'edit', $user->id), 'refresh');
    
    if (User::find_by_account ($account))
      return identity ()->set_session ('_flash_message', '帳號已經有人使用！', true)
                        ->set_session ('account', $account, true)
                        ->set_session ('password', $password, true)
                        ->set_session ('name', $name, true)
                        && redirect (array ('users', 'edit', $user->id), 'refresh');

    $user->account  = $account;
    $user->password = password ($password);
    $user->name     = $name;

    if (!$user->save ())
      return identity ()->set_session ('_flash_message', '修改失敗！', true)
                        ->set_session ('account', $account, true)
                        ->set_session ('password', $password, true)
                        ->set_session ('name', $name, true)
                        && redirect (array ('users', 'edit', $user->id), 'refresh');

    return identity ()->set_session ('_flash_message', '修改成功！', true)
                      && redirect (array ('users'), 'refresh');
  }

  public function add () {
    $message  = identity ()->get_session ('_flash_message', true);
    $account  = identity ()->get_session ('account', true);
    $password = identity ()->get_session ('password', true);
    $name     = identity ()->get_session ('name', true);

    $this->load_view (array (
        'message' => $message,
        'account' => $account,
        'password' => $password,
        'name' => $name
      ));
  }

  public function create () {
    if (!$this->has_post ())
      return redirect (array ('users', 'add'));

    $account  = trim ($this->input_post ('account'));
    $password = trim ($this->input_post ('password'));
    $name     = trim ($this->input_post ('name'));

    if (!($account && $password && $name))
      return identity ()->set_session ('_flash_message', '填寫資訊有少！', true)
                        ->set_session ('account', $account, true)
                        ->set_session ('password', $password, true)
                        ->set_session ('name', $name, true)
                        && redirect (array ('users', 'add'), 'refresh');

    $params = array (
        'account' => $account,
        'password' => password ($password),
        'name' => $name
      );

    if (!verifyCreateOrm ($user = User::create ($params)))
        return identity ()->set_session ('_flash_message', '新增失敗！', true)
                        ->set_session ('account', $account, true)
                        ->set_session ('password', $password, true)
                        ->set_session ('name', $name, true)
                        && redirect (array ('users', 'add'), 'refresh');

    return identity ()->set_session ('_flash_message', '新增成功！', true)
                      && redirect (array ('users'), 'refresh');
  }

  public function index ($offset = 0) {

    $qks = array ('account', 'name');
    $qs = array_filter (array_combine ($qks, array_map (function ($q) { return $this->input_get ($q); }, $qks)), function ($t) { return is_numeric ($t) ? true : $t; });
    $temp = array_slice ($qs, 0);
    array_walk ($temp, function (&$v, $k) { $v = $k . '=' . $v; });
    $q = implode ('&amp;', $temp);

    $temp = array_slice ($qs, 0);
    array_walk ($temp, function (&$v, $k) { $v = in_array ($k, array ('account', 'name')) ? ($k . ' LIKE ' . User::escape ('%' . $v . '%')) : ($k . ' = ' . DinTaoInfo::escape ($v)); });
    $conditions = array (implode (' AND ', $temp));

    $limit = 25;
    $total = User::count (array ('conditions' => $conditions));
    $offset = $offset < $total ? $offset : 0;

    $this->load->library ('pagination');
    $pagination_config = array (
      'total_rows' => $total,
      'num_links' => 5,
      'per_page' => $limit,
      'base_url' => base_url (array ('users', '%s', $q ? '?' . $q : '')),
      'uri_segment' => $offset ? 2 : 0,
      'page_query_string' => false,
      'first_link' => '第一頁', 'last_link' => '最後頁', 'prev_link' => '上一頁', 'next_link' => '下一頁',
      'full_user_open' => '<ul class="pagination">', 'full_user_close' => '</ul>', 'first_user_open' => '<li>', 'first_user_close' => '</li>',
      'prev_user_open' => '<li>', 'prev_user_close' => '</li>', 'num_user_open' => '<li>', 'num_user_close' => '</li>',
      'cur_user_open' => '<li class="active"><a href="#">', 'cur_user_close' => '</a></li>',
      'next_user_open' => '<li>', 'next_user_close' => '</li>', 'last_user_open' => '<li>', 'last_user_close' => '</li>',
      );

    $this->pagination->initialize ($pagination_config);
    $pagination = $this->pagination->create_links ();

    $users = User::find ('all', array ('offset' => $offset, 'limit' => $limit, 'order' => 'id DESC', 'conditions' => $conditions));

    $message = identity ()->get_session ('_flash_message', true);

    $this->load_view (array ('message' => $message, 'pagination' => $pagination, 'users' => $users, 'qs' => $qs));
  }
}
