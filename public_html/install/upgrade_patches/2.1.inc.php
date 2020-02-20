<?php

// Copy missing short description from description
  $products_info_query = database::query(
    "select id, description from ". DB_TABLE_PRODUCTS_INFO ."
    where short_description = ''
    and description != '';"
  );

  while ($product_info = database::fetch($products_info_query)) {

    $short_description = strip_tags($product_info['description']);
    $short_description = preg_replace('#\R#s', ' ', $short_description);
    $short_description = preg_replace('#\s+#s', ' ', $short_description);

    if (strlen($short_description) > 250) {
      $short_description = substr($short_description, 0, strpos(wordwrap($short_description, 250), "\n")) . ' …';
    }

    database::query(
      "update ". DB_TABLE_PRODUCTS_INFO ."
      set short_description = '". database::input($short_description) ."'
      where id = ". (int)$product_info['id'] ."
      limit 1;"
    );
  }

  $deleted_files = [
    FS_DIR_APP . 'ext/responsiveslides/',
    FS_DIR_APP . 'ext/trumbowyg/plugins/base64/',
  ];

  foreach ($deleted_files as $pattern) {
    if (!file_delete($pattern)) {
      die('<span class="error">[Error]</span></p>');
    }
  }

// Modify some files
  $modified_files = [
    [
      'file'    => FS_DIR_APP . 'includes/templates/*.catalog/views/listing_product.inc.php',
      'search'  => '<div class="product column shadow hover-light">',
      'replace' => '<div class="product column shadow hover-light" data-id="<?php echo $product_id; ?>" data-name="<?php echo htmlspecialchars($name); ?>" data-price="<?php echo currency::format_raw($campaign_price ? $campaign_price : $regular_price); ?>">',
    ],
    [
      'file'    => FS_DIR_APP . 'includes/templates/*.catalog/views/listing_product.inc.php',
      'search'  => '<div class="product shadow hover-light">',
      'replace' => '<div class="product shadow hover-light" data-id="<?php echo $product_id; ?>" data-name="<?php echo htmlspecialchars($name); ?>" data-price="<?php echo currency::format_raw($campaign_price ? $campaign_price : $regular_price); ?>">',
    ],
    [
      'file'    => FS_DIR_APP . 'includes/templates/*.catalog/views/listing_product.inc.php',
      'search'  => '<?php echo $price; ?>',
      'replace' => '<?php echo currency::format($regular_price); ?>',
    ],
    [
      'file'    => FS_DIR_APP . 'includes/templates/*.catalog/views/listing_product.inc.php',
      'search'  => '<?php echo $campaign_price; ?>',
      'replace' => '<?php echo currency::format($campaign_price); ?>',
    ],
    [
      'file'    => FS_DIR_APP . 'includes/config.inc.php',
      'search'  => 'define(\'WS_DIR_HTTP_HOME\', str_replace(FS_DIR_HTTP_ROOT, \'\', str_replace(\'\\\\\', \'/\', realpath(dirname(__FILE__) . \'/\' . \'..\') . \'/\')));',
      'replace' => 'define(\'WS_DIR_HTTP_HOME\', rtrim(str_replace(FS_DIR_HTTP_ROOT, \'\', str_replace(\'\\\\\', \'/\', realpath(__DIR__.\'/..\'))), \'/\') . \'/\');',
    ],
    [
      'file'    => FS_DIR_APP . 'includes/config.inc.php',
      'search'  => '  define(\'DB_TABLE_ADDRESSES\',                         \'`\'. DB_DATABASE .\'`.`\'. DB_PREFIX . \'addresses`\');' . PHP_EOL,
      'replace' => '',
    ],
  ];

  foreach ($modified_files as $modification) {
    if (!file_modify($modification['file'], $modification['search'], $modification['replace'])) {
      die('<span class="error">[Error]</span></p>');
    }
  }
