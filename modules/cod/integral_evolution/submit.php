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

require("libFunctions.php");
define('_SMS_URL', 'http://admin.dove-sms.com/TransSMS/SMSAPI.jsp');
define('_SMS_USERNAME', 'GreenApple1');
define('_SMS_PASSWORD', 'GreenApple1');
define('_SMS_SENDERID', 'MSNGRi');
define('_SMS_MESSAGE', 'Thank you for placing the order, %s of amount Rs. %s. Your order is being processed. Please check your email for additional details.');
$WorkingKey = ""; //put in the 32 bit working key in the quotes provided here
$encResponse = $_REQUEST["encResponse"];
$path = getcwd() . _MODULE_DIR_ . 'cod/integral_evolution/ccavutil.jar';
exec("java -jar $path $WorkingKey \"$encResponse\" dec", $ccaResponse);
$tok = strtok($ccaResponse[0], "&");
$param;
$value;
$allKeys = array();
$index = 0;
$MerchantId = "";
$OrderId = "";
$Amount = "";
$AuthDesc = "";
$avnChecksum = "";

$billing_cust_name = "";
$billing_cust_address = "";
$billing_cust_country = "";
$billing_cust_tel = "";
$billing_cust_email = "";
$delivery_cust_name = "";
$delivery_cust_address = "";
$delivery_cust_tel = "";
$delivery_cust_notes = "";
$Merchant_Param = "";
$card_category = '';
$nb_order_no = '';
$card_number = '';
$card_expiration = '';
$card_holder = '';

while ($tok !== false) {
    $temp = $tok;
    list($param, $value) = preg_split("/[\=]/", $temp, 2);
    $allParams["$param"] = $value;
    $allKeys[$index++] = $param;
    $tok = strtok("&");
}
if (array_key_exists("Merchant_Id", $allParams)) $MerchantId = $allParams["Merchant_Id"];
if (array_key_exists("Order_Id", $allParams)) $OrderId = $allParams["Order_Id"];
if (array_key_exists("Amount", $allParams)) $Amount = $allParams["Amount"];
if (array_key_exists("AuthDesc", $allParams)) $AuthDesc = $allParams["AuthDesc"];
if (array_key_exists("Checksum", $allParams)) $avnChecksum = $allParams["Checksum"];
$Checksum = verifyChecksum($MerchantId, $OrderId, $Amount, $AuthDesc, $WorkingKey, $avnChecksum);

if (array_key_exists("billing_cust_name", $allParams)) $billing_cust_name = $allParams["billing_cust_name"];
if (array_key_exists("billing_cust_address", $allParams)) $billing_cust_address = $allParams["billing_cust_address"];
if (array_key_exists("billing_cust_country", $allParams)) $billing_cust_country = $allParams["billing_cust_country"];
if (array_key_exists("billing_cust_tel", $allParams)) $billing_cust_tel = $allParams["billing_cust_tel"];
if (array_key_exists("billing_cust_email", $allParams)) $billing_cust_email = $allParams["billing_cust_email"];
if (array_key_exists("delivery_cust_name", $allParams)) $delivery_cust_name = $allParams["delivery_cust_name"];
if (array_key_exists("delivery_cust_address", $allParams)) $delivery_cust_address = $allParams["delivery_cust_address"];
if (array_key_exists("delivery_cust_tel", $allParams)) $delivery_cust_tel = $allParams["delivery_cust_tel"];
if (array_key_exists("delivery_cust_notes", $allParams)) $delivery_cust_notes = $allParams["delivery_cust_notes"];
if (array_key_exists("Merchant_Param", $allParams)) $Merchant_Param = $allParams["Merchant_Param"];
if (array_key_exists("nb_order_no", $allParams)) $nb_order_no = $allParams["nb_order_no"];
if (array_key_exists("card_category", $allParams)) $card_category = $allParams["card_category"];

if ($Checksum && $AuthDesc === "Y") {
    echo "<br>Thank you for shopping with us. Your credit card has been charged and your transaction is successful. We will be shipping your order to you soon.";
    // Getting payment row already exist from order reference number
    try {
        $sql = 'SELECT * from ' . _DB_PREFIX_ . 'order_payment WHERE order_reference=\'' . $OrderId . '\'';
        if ($row = Db::getInstance()->getRow($sql)) {
            $existing_id_order_payment = $row['id_order_payment'];
            $existing_id_currency = $row['id_currency'];
            $existing_amount = $row['amount'];
            $existing_payment_method = $row['payment_method'];
            $existing_conversion_rate = $row['conversion_rate'];

            Db::getInstance()->insert('order_payment', array(
                'id_currency' => (int)$existing_id_currency,
                'amount' => $amount,
                'payment_method' => pSQL('ccavenue'),
                'conversion_rate' => $existing_conversion_rate,
                'transaction_id' => $nb_order_no,
                'card_number' => $card_number,
                'card_brand' => $card_category,
                'card_expiration' => $card_expiration,
                'card_holder' => $card_holder,
                'date_add' => date('Y-m-d H:i:s'),
                'order_reference' => $OrderId
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
            Tools::redirect('index.php?controller=my-account');

        }
    } catch (Exception $e) {
        throw new PrestaShopExceptionCore();
    }
    //Here you need to put in the routines for a successful transaction such as sending an email to customer,setting database status, informing logistics etc etc. please ensure that you check that the amount and all parameters are correct and the same as posted to CCavenue.
} else if ($Checksum && $AuthDesc === "B") {
    echo "<br>Thank you for shopping with us.We will keep you posted regarding the status of your order through e-mail";
    Tools::redirect('index.php?controller=my-account');
    //Here you need to put in the routines/e-mail for a  "Batch Processing" order
    //This is only if payment for this transaction has been made by an American Express Card or by any netbank and status is not known is real time  the authorisation status will be  available only after 5-6 hours  at the "View Pending Orders" or you may do an order status query to fetch the status . Refer inetegrtaion document for order status tracker documentation"
} else if ($Checksum && $AuthDesc === "N") {
    echo "<br>Thank you for shopping with us.However,the transaction has been declined.";
    $this->errors[] = Tools::displayError('Sorry an error occured while your transaction');
    Tools::redirect('index.php?controller=my-account');
    //Here you need to put in the routines for a failed
    //transaction such as sending an email to customer
    //setting database status etc etc
} else {
    echo "<br>Security Error. Illegal access detected";
    $this->errors[] = Tools::displayError('Sorry an error occured while your transaction');
    Tools::redirect('index.php?controller=my-account');
    //Here you need to check for the checksum, the checksum did not match hence the error.
}


function sendMessage($product, $dateTime, $mobile)
{
    try {
        $message = sprintf(_SMS_MESSAGE, $product, $dateTime);

        $username = _SMS_USERNAME;
        $password = _SMS_PASSWORD;
        //create api url to hit
        $sms_url = _SMS_URL;
        $senderId = _SMS_SENDERID;
        $sms_url = "$sms_url?username=$username&password=$password&sendername=$senderId&mobileno=$mobile&message=" . urlencode($message);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $sms_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, '6');
        $result = curl_exec($ch);
        $error = curl_error($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

    } catch (Exception $e) {
        //consuming exception
    }

}

function sendEmails($data)
{
    try {
        $res = Mail::Send(
            (int)1,
            'admin_mail',
            Mail::l('', (int)1),
            $data,
            Configuration::get('PS_SHOP_EMAIL'),
            'Administrator',
            null,
            true,
            null,
            null,
            getcwd() . _MODULE_DIR_ . 'demoregistration/',
            false,
            null
        );

    } catch (Exception $e) {
        return false;
    }

    try {
        $res = Mail::Send(
            (int)1,
            'customer_mail',
            Mail::l('Milagrow Human Tech : COD Partial Payment', (int)1),
            $data,
            $data['{email}'],
            $data['{name}'],
            null,
            true,
            null,
            null,
            getcwd() . _MODULE_DIR_ . 'demoregistration/',
            false,
            null
        );

    } catch (Exception $e) {
        return false;
    }

}