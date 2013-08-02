<?php

define('_IS_SMS_SEND', true);
define('_SMS_URL', 'http://admin.dove-sms.com/TransSMS/SMSAPI.jsp');
define('_SMS_USERNAME', 'GreenApple1');
define('_SMS_PASSWORD', 'GreenApple1');
define('_SMS_SENDERID', 'MSNGRi');
define('_B2B_SMS_MESSAGE', 'Your mobile verification code is %s.Milagrow HumanTech');
class B2bInitModuleFrontController extends ModuleFrontController
{
    public $display_column_left = true;

    public function postProcess()
    {
        if (Tools::getValue('formName')) {
            $name = Tools::getValue('name');
            $email = Tools::getValue('email');
            $mobile = Tools::getValue('mobile');
            $city = Tools::getValue('city');
            $state = Tools::getValue('state');
            $product = Tools::getValue('product');
            $quantity = Tools::getValue('quantity');

            if (empty($name) || empty($email) || empty($mobile) || empty($product) || empty($quantity)) {
                echo json_encode(array('status' => false, 'message' => 'Please Fill the required fields'));
            }

            $mobileCode = mt_rand(100000, 999999);
            $insertData = array('name' => $name, 'email' => $email, 'mobileCode' => $mobileCode, 'mobile' => $mobile, 'city' => $city, 'state' => $state, 'product' => $product, 'quantity' => $quantity);
            Db::getInstance()->insert('b2b', $insertData
            );

            if (Db::getInstance()->Insert_ID()) {
                $this->sendSMS($mobile, $mobileCode);
                $last_insert_id = Db::getInstance()->Insert_ID();
                $url = B2b::getShopDomainSsl(true, true);
                $values = array('fc' => 'module', 'module' => 'b2b', 'controller' => 'verify', 'key' => $last_insert_id);
                $url .= '/index.php?' . http_build_query($values, '', '&');
                echo json_encode(array('status' => true, 'url' => $url));
            } else {
                echo json_encode(array('status' => false, 'message' => 'Sorry an error occured please try again..'));
            }
            exit;
        }
    }

    public function initContent()
    {

        parent::initContent();

        $this->b2b = new b2b();
        $this->context = Context::getContext();
        $this->id_module = (int)Tools::getValue('id_module');


        $this->context->smarty->assign(array(
            'form_action' => B2b::getShopDomainSsl(true, true) . '/index.php?fc=module&module=' . $this->b2b->name . '&controller=init',
            'products' => $this->getProducts(),
            'jsSource' => $this->module->getPathUri() . 'b2b.js',
            'this_path' => $this->module->getPathUri()
        ));

        $this->setTemplate('b2b.tpl');
    }

    private function getProducts()
    {
        $sql = 'select * from ' . _DB_PREFIX_ . 'product_lang';
        if ($results = Db::getInstance()->ExecuteS($sql))
            return $results;
        return array();
    }

    private function sendSMS($mobileNumber, $verificationCode)
    {
        $message = sprintf(_B2B_SMS_MESSAGE, $verificationCode);
        if (_IS_SMS_SEND) {
            $username = _SMS_USERNAME;
            $password = _SMS_PASSWORD;
            //create api url to hit
            $sms_url = _SMS_URL;
            $senderId = _SMS_SENDERID;
            $sms_url = "$sms_url?username=$username&password=$password&sendername=$senderId&mobileno=$mobileNumber&message=" . urlencode($message);
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
