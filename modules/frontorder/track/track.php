<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hitanshu
 * Date: 16/7/13
 * Time: 1:07 PM
 * To change this template use File | Settings | File Templates.
 */
include_once(dirname(__FILE__) . '/../../../config/config.inc.php');
include_once(dirname(__FILE__) . '/../../../init.php');

$order_id = Tools::getValue('order_id');
if (!isset($order_id))
    return false;
try {
    $sql = 'SELECT tracking_number,url from ' . _DB_PREFIX_ . 'order_carrier join ' . _DB_PREFIX_ . 'carrier on ' . _DB_PREFIX_ . 'order_carrier.id_carrier=' . _DB_PREFIX_ . 'carrier.id_carrier WHERE id_order=' . $order_id;
    if ($row = Db::getInstance()->getRow($sql)) {
        $tracking_number = $row['tracking_number'];
        $url = $row['url'];
        if (empty($row['url']))
            $url = '#';
        $url = str_replace('@', $tracking_number, $url);
        echo json_encode(array('url' => $url));
    } else {
        echo json_encode(array('url' => '#'));
    }
} catch (Exception $e) {
    throw new PrestaShopExceptionCore($e);
}

