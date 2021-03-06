<?php

  return $app_config = array(
    'name' => language::translate('title_users', 'Users'),
    'default' => 'users',
    'priority' => 0,
    'theme' => array(
      'color' => '#f79a2e',
      'icon' => 'fa-star',
    ),
    'menu' => array(),
    'docs' => array(
      'users' => 'users.inc.php',
      'edit_user' => 'edit_user.inc.php',
    ),
  );
