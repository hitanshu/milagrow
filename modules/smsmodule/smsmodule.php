<?php
if (!defined('_PS_VERSION_'))
    exit;
/* Send SMS on order confirmation */
define('_PS_SMS_SEND', true);
/* SMS URLs */
define('_SMS_URL', 'http://admin.dove-sms.com/TransSMS/SMSAPI.jsp');
define('_SMS_USERNAME', 'GreenApple1');
define('_SMS_PASSWORD', 'GreenApple1');
define('_SMS_SENDERID', 'MSNGRi');
define('_SMS_MESSAGE_ORDER_PAYMENT_SUCCESS', 'Thank you placing the order, %s of amount Rs.%s. Your order is being processed. Please check your email for additional details.');
define('_SMS_MESSAGE_SHIPPED_WITH_TRACKING_NUMBER_WITH_CARRIER_NAME', 'Your order, %s , has been shipped via %s with Tracking ID %s. Please check your account for additional details.');
define('_SMS_MESSAGE_SHIPPED_WITH_TRACKING_NUMBER', 'Your order, %s , has been shipped with Tracking ID %s. Please check your account for additional details.');
define('_SMS_MESSAGE_SHIPPED_WITHOUT_TRACKING_NUMBER', 'Your order, %s , has been shipped. Please check your account for additional details.');
class SmsModule extends Module
{
    public function __construct()
    {
        $this->name = 'smsmodule';
        $this->tab = 'front_office_features';
        $this->version = '1.0';
        $this->author = 'GAPS';

        $this->need_instance = 0;
//        $this->ps_versions_compliancy = array('min' => '1.5', 'max' => '1.5');

        parent::__construct();

        $this->displayName = $this->l('SMS Module');
        $this->description = $this->l('SMS Module for sending sms when order confirm');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

        if (!Configuration::get('MYMODULE_NAME'))
            $this->warning = $this->l('No name provided');
    }

    public function install()
    {
        if (Shop::isFeatureActive())
            Shop::setContext(Shop::CONTEXT_ALL);
        return parent::install() && $this->registerHook('actionOrderStatusPostUpdate') && $this->registerHook('actionPaymentConfirmation');
    }

    public function uninstall()
    {
        return parent::uninstall() && Configuration::deleteByName('MYMODULE_NAME');
    }

    public function hookactionPaymentConfirmation($params = null)
    {
        try {
            $sql = 'SELECT ' . _DB_PREFIX_ . 'orders.reference,' . _DB_PREFIX_ . 'address.phone_mobile,total_paid_tax_incl as amount FROM ' . _DB_PREFIX_ . 'orders join ' . _DB_PREFIX_ . 'customer on ' . _DB_PREFIX_ . 'orders.id_customer=' . _DB_PREFIX_ . 'customer.id_customer join ' . _DB_PREFIX_ . 'address on ' . _DB_PREFIX_ . 'orders.id_address_delivery=' . _DB_PREFIX_ . 'address.id_address WHERE ' . _DB_PREFIX_ . 'orders.id_order=' . $params['id_order'];
            if ($row = Db::getInstance()->getRow($sql)) {
                $reference_number = $row['reference'];
                $mobile = $row['phone_mobile'];
                $amount = $row['amount'];
                $message = sprintf(_SMS_MESSAGE_ORDER_PAYMENT_SUCCESS, $reference_number, $amount);
                if (_PS_SMS_SEND) {
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
                }

            }
        } catch (Exception $e) {
            throw new PrestaShopExceptionCore($e);
        }

    }

    public function hookactionOrderStatusPostUpdate($params = null)
    {
        try {
            $carriersArr = array('FDX' => 'FEDEX', 'BLD' => 'BlueDart');
            $sql = 'SELECT ' . _DB_PREFIX_ . 'orders.reference,' . _DB_PREFIX_ . 'address.phone_mobile,' . _DB_PREFIX_ . 'order_state_lang.name as status,' . _DB_PREFIX_ . 'orders.shipping_number as shipping_number FROM ' . _DB_PREFIX_ . 'orders join ' . _DB_PREFIX_ . 'customer on ' . _DB_PREFIX_ . 'orders.id_customer=' . _DB_PREFIX_ . 'customer.id_customer join ' . _DB_PREFIX_ . 'address on ' . _DB_PREFIX_ . 'orders.id_address_delivery=' . _DB_PREFIX_ . 'address.id_address join ' . _DB_PREFIX_ . 'order_state_lang on ' . _DB_PREFIX_ . 'order_state_lang.id_order_state=' . _DB_PREFIX_ . 'orders.current_state WHERE ' . _DB_PREFIX_ . 'orders.id_order=' . $params['id_order'];
            if ($row = Db::getInstance()->getRow($sql)) {
                $reference_number = $row['reference'];
                $mobile = $row['phone_mobile'];
                $shipping_number = $row['shipping_number'];
                if (empty($shipping_number))
                    $message = sprintf(_SMS_MESSAGE_SHIPPED_WITHOUT_TRACKING_NUMBER, $reference_number);
                else {
                    $data = explode('-', $shipping_number);
                    $carrier = null;
                    if (!empty($data)) {
                        if (array_key_exists($data[0], $carriersArr))
                            $carrier = $carriersArr[$data[0]];
                    }

                    $exactTrackingNumber = ltrim(strstr($shipping_number, '-'), '-');
                    if (!empty($carrier))
                        $message = sprintf(_SMS_MESSAGE_SHIPPED_WITH_TRACKING_NUMBER_WITH_CARRIER_NAME, $reference_number, $carrier, $exactTrackingNumber);
                    else
                        $message = sprintf(_SMS_MESSAGE_SHIPPED_WITH_TRACKING_NUMBER, $reference_number, $exactTrackingNumber);
                }

                if (_PS_SMS_SEND) {
                    if ($row['status'] == "Shipped" || $row['status'] == "shipped") {
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
                    }
                }

            }
        } catch (Exception $e) {
            throw new PrestaShopExceptionCore($e);
        }
    }
}

