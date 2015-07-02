<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Migration_Add_pictures extends CI_Migration {
  public function up () {
    $this->db->query (
      "CREATE TABLE `pictures` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `user_id` int(11) NOT NULL,
        `description` text COMMENT '描述',
        `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '名稱',
        `gradient` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '斜率，height/width',
        
        `color_red` varchar(5) COLLATE utf8_unicode_ci NOT NULL COMMENT 'RGB 紅',
        `color_green` varchar(5) COLLATE utf8_unicode_ci NOT NULL COMMENT 'RGB 綠',
        `color_blue` varchar(5) COLLATE utf8_unicode_ci NOT NULL COMMENT 'RGB 藍',
        
        `latitude` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '緯度',
        `longitude` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '經度',
        `altitude` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '海拔',

        `accuracy_horizontal` varchar(5) COLLATE utf8_unicode_ci NOT NULL COMMENT '平面準確度(單位 m)',
        `accuracy_vertical` varchar(5) COLLATE utf8_unicode_ci NOT NULL COMMENT '高度準確度(單位 m)',

        `city` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '城市',
        `country` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '國家',
        `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '地址',

        `updated_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '註冊時間',
        `created_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '更新時間',
        PRIMARY KEY (`id`),
        KEY `user_id_index` (`user_id`),
        FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;"
    );
  }
  public function down () {
    $this->db->query (
      "DROP TABLE `pictures`;"
    );
  }
}