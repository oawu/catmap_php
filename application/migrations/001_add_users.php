<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Migration_Add_users extends CI_Migration {
  public function up () {
    $this->db->query (
      "CREATE TABLE `users` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `account` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '帳號',
        `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '密碼',
        `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '暱稱',
        `avatar` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '頭像',
        `updated_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '註冊時間',
        `created_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '更新時間',
        PRIMARY KEY (`id`),
        KEY `account_index` (`account`),
        KEY `account_password_index` (`account`, `password`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;"
    );
  }
  public function down () {
    $this->db->query (
      "DROP TABLE `users`;"
    );
  }
}