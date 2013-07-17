<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hitanshu
 * Date: 13/7/13
 * Time: 3:39 PM
 * To change this template use File | Settings | File Templates.
 */
include_once(dirname(__FILE__) . '/../../../config/config.inc.php');
include_once(dirname(__FILE__) . '/../../../init.php');

$reference_number = Tools::getValue('reference_number');
$is_payment_successful = Tools::getValue('payment_status');
$amount = Tools::getValue('amount');
$transaction_id = Tools::getValue('transaction_number');
$card_number = '';
$card_brand = '';
$card_expiration = '';
$card_holder = '';
// Getting payment row already exist from order reference number
try {
    $sql = 'SELECT * from ' . _DB_PREFIX_ . 'order_payment WHERE order_reference=\'' . $reference_number . '\'';
    if ($row = Db::getInstance()->getRow($sql)) {
        $existing_id_order_payment = $row['id_order_payment'];
        $existing_id_currency = $row['id_currency'];
        $existing_amount = $row['amount'];
        $existing_payment_method = $row['payment_method'];
        $existing_conversion_rate = $row['conversion_rate'];
        //inserting new payment if payment is successfully completed
        if (strcasecmp($is_payment_successful, 'completed') == 0) {
            Db::getInstance()->insert('order_payment', array(
                'id_currency' => (int)$existing_id_currency,
                'amount' => $amount,
                'payment_method' => pSQL($existing_payment_method),
                'conversion_rate' => $existing_conversion_rate,
                'transaction_id' => $transaction_id,
                'card_number' => $card_number,
                'card_brand' => $card_brand,
                'card_expiration' => $card_expiration,
                'card_holder' => $card_holder,
                'date_add' => 'now',
                'order_reference' => $reference_number
            ));

            $last_insert_payment_id = Db::getInstance()->insert_id();
            $query = 'SELECT * from ' . _DB_PREFIX_ . 'order_invoice_payment where id_order_payment=' . $existing_id_order_payment;
            if ($row = Db::getInstance()->getRow($query)) {
                $existing_id_order_invoice = $row['id_order_invoice'];
                $existing_id_order = $row['id_order'];
                Db::getInstance()->insert('order_invoice_payment', array(
                    'id_order_invoice' => (int)$existing_id_order_invoice,
                    'id_order_payment' => (int)($last_insert_payment_id),
                    'id_order' => (int)($existing_id_order),
                ));
            }
        }

    }
} catch (Exception $e) {
    throw new PrestaShopExceptionCore();
}