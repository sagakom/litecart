<?php
  functions::draw_fancybox('a.fancybox', array(
    'type'          => 'iframe',
    'padding'       => '40',
    'width'         => 600,
    'height'        => 800,
    'titlePosition' => 'inside',
    'transitionIn'  => 'elastic',
    'transitionOut' => 'elastic',
    'speedIn'       => 600,
    'speedOut'      => 200,
    'overlayShow'   => true
  ));
?>
<div class="widget">
  <table width="100%" class="dataTable">
    <tr class="header">
      <th><?php echo language::translate('title_id', 'ID'); ?></th>
      <th width="100%"><?php echo language::translate('title_customer_name', 'Customer Name'); ?></th>
      <th style="text-align: center;"><?php echo language::translate('title_order_status', 'Order Status'); ?></th>
      <th style="text-align: center;"><?php echo language::translate('title_amount', 'Amount'); ?></th>
      <th style="text-align: center;"><?php echo language::translate('title_date', 'Date'); ?></th>
      <th style="text-align: center;">&nbsp;</th>
    </tr>
<?php
  $orders_query = database::query(
    "select o.*, osi.name as order_status_name from ". DB_TABLE_ORDERS ." o
    left join ". DB_TABLE_ORDER_STATUSES_INFO ." osi on (osi.order_status_id = o.order_status_id and osi.language_code = '". language::$selected['code'] ."')
    where o.order_status_id
    order by o.date_created desc
    limit 10;"
  );
  
  if (database::num_rows($orders_query) > 0) {
    
    while ($order = database::fetch($orders_query)) {
      if (!isset($rowclass) || $rowclass == 'even') {
        $rowclass = 'odd';
      } else {
        $rowclass = 'even';
      }
?>
    <tr class="<?php echo $rowclass; ?>"<?php echo ($order['order_status_id'] == 0) ? ' style="color: #999;"' : false; ?>>
      <td><?php echo $order['id']; ?></td>
      <td><a href="<?php echo document::href_link('', array('app' => 'orders', 'doc' => 'edit_order', 'order_id' => $order['id']), true); ?>"><?php echo $order['customer_company'] ? $order['customer_company'] : $order['customer_firstname'] .' '. $order['customer_lastname']; ?></a></td>
      <td style="text-align: center;"><?php echo ($order['order_status_id'] == 0) ? language::translate('title_uncompleted', 'Uncompleted') : $order['order_status_name']; ?></td>
      <td style="text-align: right;"><?php echo currency::format($order['payment_due'], false, false, $order['currency_code'], $order['currency_value']); ?></td>
      <td style="text-align: right;"><?php echo strftime(language::$selected['format_datetime'], strtotime($order['date_created'])); ?></td>
      <td><a class="fancybox" href="<?php echo document::href_link(WS_DIR_ADMIN .'orders.app/printable_packing_slip.php', array('order_id' => $order['id'], 'media' => 'print')); ?>"><?php echo functions::draw_fontawesome_icon('file-text-o'); ?></a> <a class="fancybox" href="<?php echo document::href_link(WS_DIR_ADMIN .'orders.app/printable_order_copy.php', array('order_id' => $order['id'], 'media' => 'print')); ?>"><?php echo functions::draw_fontawesome_icon('print'); ?></a> <a href="<?php echo document::href_link('', array('app' => 'orders', 'doc' => 'edit_order', 'order_id' => $order['id']), true); ?>" title="<?php echo language::translate('title_edit', 'Edit'); ?>"><?php echo functions::draw_fontawesome_icon('pencil'); ?></a></td>
    </tr>
<?php
    }
  }
?>
  </table>
</div>