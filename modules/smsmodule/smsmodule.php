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
define('_SMS_MESSAGE_NEW_ORDER', 'Thank you for shopping with Milagrow HumanTech your order number is %s');
define('_SMS_MESSAGE_SHIPPED', 'Your order number %s has been shipped. Shipping No. is %s');
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
        return parent::install() && $this->registerHook('actionOrderStatusPostUpdate') && $this->registerHook('actionValidateOrder');
    }

    public function uninstall()
    {
        return parent::uninstall() && Configuration::deleteByName('MYMODULE_NAME');
    }

    public function hookactionValidateOrder($params = null)
    {
        try {
            $sql = 'SELECT ' . _DB_PREFIX_ . 'orders.reference,' . _DB_PREFIX_ . 'address.phone_mobile FROM ' . _DB_PREFIX_ . 'orders join ' . _DB_PREFIX_ . 'customer on ' . _DB_PREFIX_ . 'orders.id_customer=' . _DB_PREFIX_ . 'customer.id_customer join ' . _DB_PREFIX_ . 'address on ' . _DB_PREFIX_ . 'orders.id_address_delivery=' . _DB_PREFIX_ . 'address.id_address WHERE ' . _DB_PREFIX_ . 'orders.reference=\'' . $params['order']->reference.'\'';
            if ($row = Db::getInstance()->getRow($sql)) {
                $reference_number = $row['reference'];
                $mobile = $row['phone_mobile'];
                $message = sprintf(_SMS_MESSAGE_NEW_ORDER, $reference_number);
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
            $sql = 'SELECT ' . _DB_PREFIX_ . 'orders.reference,' . _DB_PREFIX_ . 'address.phone_mobile,' . _DB_PREFIX_ . 'order_state_lang.name as status,'._DB_PREFIX_.'orders.shipping_number as shipping_number FROM ' . _DB_PREFIX_ . 'orders join ' . _DB_PREFIX_ . 'customer on ' . _DB_PREFIX_ . 'orders.id_customer=' . _DB_PREFIX_ . 'customer.id_customer join ' . _DB_PREFIX_ . 'address on ' . _DB_PREFIX_ . 'orders.id_address_delivery=' . _DB_PREFIX_ . 'address.id_address join ' . _DB_PREFIX_ . 'order_state_lang on ' . _DB_PREFIX_ . 'order_state_lang.id_order_state=' . _DB_PREFIX_ . 'orders.current_state WHERE ' . _DB_PREFIX_ . 'orders.id_order=' . $params['id_order'];
            if ($row = Db::getInstance()->getRow($sql)) {
                $reference_number = $row['reference'];
                $mobile = $row['phone_mobile'];
                $shipping_number=$row['shipping_number'];
                $message = sprintf(_SMS_MESSAGE_SHIPPED, $reference_number,$shipping_number);
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

