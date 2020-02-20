<?php

  function admin_get_apps() {

    $apps_cache_token = cache::token('admin_apps', ['language']);
    if (!$apps = cache::get($apps_cache_token)) {
      $apps = [];

      foreach (glob('*.app/', GLOB_ONLYDIR) as $dir) {
        $code = preg_replace('#\.app/$#', '', $dir);
        $app_config = require vmod::check(FS_DIR_ADMIN . $dir . 'config.inc.php');
        $apps[$code] = array_merge(['code' => $code, 'dir' => $dir], $app_config);
      }
      usort($apps, function($a, $b) use ($apps) {
        if (@$a['priority'] == @$b['priority']) {
          return ($a['name'] < $b['name']) ? -1 : 1;
        }
        return (@$a['priority'] < @$b['priority']) ? -1 : 1;
      });

      cache::set($apps_cache_token, $apps);
    }

    return $apps;
  }

  function admin_get_widgets() {

    $widgets_cache_token = cache::token('admin_widgets', ['language']);
    if (!$widgets = cache::get($widgets_cache_token)) {
      $widgets = [];

      foreach (glob('*.widget/', GLOB_ONLYDIR) as $dir) {
        $code = preg_replace('#\.widget/$#', '', $dir);
        $widget_config = require vmod::check(FS_DIR_ADMIN . $dir . 'config.inc.php');
        $widgets[$code] = array_merge(['code' => $code, 'dir' => $dir], $widget_config);
      }

      usort($widgets, function($a, $b) use ($widgets) {
        if ($a['priority'] == $b['priority']) {
          return ($a['name'] < $b['name']) ? -1 : 1;
        }
        return ($a['priority'] < $b['priority']) ? -1 : 1;
      });

      cache::set($widgets_cache_token, $widgets);
    }

    return $widgets;
  }
