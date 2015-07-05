<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Users extends Admin_controller {

  public function __construct () {
    parent::__construct ();
    
    if (!identity ()->get_session ('is_login'))
      return redirect (array ('admin'));
  }

  public function destroy ($id = 0) {
    if (!($user = User::find_by_id ($id)))
      return redirect (array ('admin', 'users'));

    $message = $user->destroy () ? '刪除成功！' : '刪除失敗！';

    return identity ()->set_session ('_flash_message', $message, true)
                    && redirect (array ('admin', 'users'), 'refresh');
  }

  public function edit ($id = 0) {
    if (!($user = User::find_by_id ($id)))
      return redirect (array ('admin', 'users'));

    $message  = identity ()->get_session ('_flash_message', true);
    $account  = identity ()->get_session ('account', true);
    $password = identity ()->get_session ('password', true);
    $name     = identity ()->get_session ('name', true);

    $this->load_view (array (
        'user'     => $user,
        'message'  => $message,
        'account'  => $account,
        'password' => $password,
        'name'     => $name
      ));
  }

  public function update ($id = 0) {
    if (!($user = User::find_by_id ($id)))
      return redirect (array ('admin', 'users'));

    if (!$this->has_post ())
      return redirect (array ('admin', 'users', 'edit', $user->id));

    $account  = trim ($this->input_post ('account'));
    $password = trim ($this->input_post ('password'));
    $name     = trim ($this->input_post ('name'));
    $avatar   = $this->input_post ('avatar', true);

    if (!($account && $password && $name && ((String)$user->avatar || $avatar)))
      return identity ()->set_session ('_flash_message', '填寫資訊有少！', true)
                        ->set_session ('account', $account, true)
                        ->set_session ('password', $password, true)
                        ->set_session ('name', $name, true)
                        && redirect (array ('admin', 'users', 'edit', $user->id), 'refresh');

    if ($avatar)
      if (!($user->avatar->put ($avatar)))
        return identity ()->set_session ('_flash_message', '修改失敗！', true)
                          ->set_session ('account', $account, true)
                          ->set_session ('password', $password, true)
                          ->set_session ('name', $name, true)
                          && redirect (array ('admin', 'users', 'edit', $user->id), 'refresh');
      else
        delay_job ('main', 'user', array (
            'id' => $user->id
          ));

    $user->account  = strtolower ($account);
    $user->password = password ($password);
    $user->name     = $name;

    if (!$user->save ())
      return identity ()->set_session ('_flash_message', '修改失敗！', true)
                        ->set_session ('account', $account, true)
                        ->set_session ('password', $password, true)
                        ->set_session ('name', $name, true)
                        && redirect (array ('admin', 'users', 'edit', $user->id), 'refresh');

    return identity ()->set_session ('_flash_message', '修改成功！', true)
                      && redirect (array ('admin', 'users'), 'refresh');
  }

  public function add () {
    $message  = identity ()->get_session ('_flash_message', true);
    $account  = identity ()->get_session ('account', true);
    $password = identity ()->get_session ('password', true);
    $name     = identity ()->get_session ('name', true);

    $this->load_view (array (
        'message'  => $message,
        'account'  => $account,
        'password' => $password,
        'name'     => $name
      ));
  }

  public function create () {
    if (!$this->has_post ())
      return redirect (array ('admin', 'users', 'add'));

    $account  = trim ($this->input_post ('account'));
    $password = trim ($this->input_post ('password'));
    $name     = trim ($this->input_post ('name'));
    $avatar   = $this->input_post ('avatar', true);

    if (!($account && $password && $name && $avatar))
      return identity ()->set_session ('_flash_message', '填寫資訊有少！', true)
                        ->set_session ('account', $account, true)
                        ->set_session ('password', $password, true)
                        ->set_session ('name', $name, true)
                        && redirect (array ('admin', 'users', 'add'), 'refresh');

    $params = array (
        'account'  => strtolower ($account),
        'password' => password ($password),
        'name'     => $name,
        'avatar'   => '',
        'color_red'   => '',
        'color_green' => '',
        'color_blue'  => ''
      );

    if (!verifyCreateOrm ($user = User::create ($params)))
      return identity ()->set_session ('_flash_message', '新增失敗！', true)
                        ->set_session ('account', $account, true)
                        ->set_session ('password', $password, true)
                        ->set_session ('name', $name, true)
                        && redirect (array ('admin', 'users', 'add'), 'refresh');
    
    if (!$user->avatar->put ($avatar) && ($user->delete () || true))
      return identity ()->set_session ('_flash_message', '新增失敗！', true)
                        ->set_session ('account', $account, true)
                        ->set_session ('password', $password, true)
                        ->set_session ('name', $name, true)
                        && redirect (array ('admin', 'users', 'add'), 'refresh');

    delay_job ('main', 'user', array (
        'id' => $user->id
      ));

    return identity ()->set_session ('_flash_message', '新增成功！', true)
                      && redirect (array ('admin', 'users'), 'refresh');
  }

  public function index ($offset = 0) {
    $columns = array ('account' => 'string', 'name' => 'string');
    $configs = array ('admin', 'users', '%s');

    $conditions = conditions ($columns,
                              $configs,
                              'User',
                              $this->input_gets ()
                              );

    $conditions = array (implode (' AND ', $conditions));

    $limit = 25;
    $total = User::count (array ('conditions' => $conditions));
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

    $users = User::find ('all', array ('offset' => $offset, 'limit' => $limit, 'order' => 'id DESC', 'conditions' => $conditions));

    $message = identity ()->get_session ('_flash_message', true);

    $this->load_view (array ('message' => $message, 'pagination' => $pagination, 'users' => $users, 'columns' => $columns));
  }
}
