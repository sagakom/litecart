<?php
  header('Content-type: application/json; charset='. language::$selected['charset']);

  $json = [
    'items' => [],
    'quantity' => cart::$total['items'],
    'value' => !empty(customer::$data['display_prices_including_tax']) ? cart::$total['value'] + cart::$total['tax'] : cart::$total['value'],
    'formatted_value' => !empty(customer::$data['display_prices_including_tax']) ? currency::format(cart::$total['value'] + cart::$total['tax']) : currency::format(cart::$total['value']),
    'text_total' => language::translate('title_total', 'Total'),
  ];

  foreach (cart::$items as $item) {
    $json['items'][] = [
      'product_id' => $item['product_id'],
      'name' => $item['name'],
      'quantity' => (float)$item['quantity'],
      'price' => (float)$item['price'],
      'formatted_price' => currency::format($item['price']),
      'link' => document::ilink('product', ['product_id' => $item['product_id']]),
    ];
  }

  if (!empty(notices::$data['warnings'])) {
    $warnings = array_values(notices::$data['warnings']);
    $json['alert'] = array_shift($warnings);
  }

  if (!empty(notices::$data['errors'])) {
    $errors = array_values(notices::$data['errors']);
    $json['alert'] = array_shift($errors);
  }

  notices::reset();

  language::convert_characters($json, language::$selected['charset'], 'UTF-8');
  $json = json_encode($json, JSON_UNESCAPED_SLASHES);

  language::convert_characters($json, 'UTF-8', language::$selected['charset']);
  echo $json;

  exit;
