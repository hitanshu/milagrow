<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hitanshu
 * Date: 30/7/13
 * Time: 10:43 AM
 * To change this template use File | Settings | File Templates.
 */
define('_IS_SMS_SEND', true);
define('_SMS_URL', 'http://admin.dove-sms.com/TransSMS/SMSAPI.jsp');
define('_SMS_USERNAME', 'GreenApple1');
define('_SMS_PASSWORD', 'GreenApple1');
define('_SMS_SENDERID', 'MSNGRi');
define('_B2B_SMS_MESSAGE', 'Please use code %s to validate your inquiry at Milagrow Bulk Purchase. Please ignore if you have received this message in error.');

class B2bVerifyModuleFrontController extends ModuleFrontController
{
    public $display_column_left = true;

    public function initContent()
    {

        parent::initContent();

        $rowId = Tools::getValue('key');
        if (empty($rowId))
            Tools::redirect(B2b::getShopDomainSsl(true, true) . '/index.php?fc=module&module=b2b&controller=init');

        $this->context->smarty->assign(array(
            'form_action' => B2b::getShopDomainSsl(true, true) . '/index.php?fc=module&module=b2b&controller=verify',
            'rowId' => $rowId,
            'this_path' => $this->module->getPathUri(),
            'jsSource' => $this->module->getPathUri() . 'b2b.js'
        ));

        $this->setTemplate('verify-mobile.tpl');
    }

    public function postProcess()
    {
        if (Tools::getValue('verify')) {
            $rowId = Tools::getValue('key');
            $code = Tools::getValue('code');

            $sql = 'Select * from ' . _DB_PREFIX_ . 'b2b where id_b2b=' . $rowId . ' and mobileCode=\'' . $code . '\'';
            if ($row = Db::getInstance()->getRow($sql)) {
                Db::getInstance()->update('b2b', array('is_verified' => 1), "id_b2b=$rowId");
                $url = B2b::getShopDomainSsl(true, true);
                $values = array('fc' => 'module', 'module' => 'b2b', 'controller' => 'success', 'key' => $row['id_b2b']);
                $url .= '/index.php?' . http_build_query($values, '', '&');
                echo json_encode(array('status' => true, 'url' => $url));
            } else {
                echo json_encode(array('status' => false, 'message' => 'Please check the code you have entered'));
            }
            exit;
        }
        if (Tools::getValue('update')) {
            $rowId = Tools::getValue('key');
            $mobile = Tools::getValue('mobile');
            $newVerificationCode = mt_rand(100000, 999999);
            Db::getInstance()->update('b2b', array('mobile' => $mobile, 'mobileCode' => $newVerificationCode, 'is_verified' => 0), "id_b2b=$rowId");
            $affectedRows = Db::getInstance()->Affected_Rows();
            if ($affectedRows) {
                $this->sendSMS($mobile, $newVerificationCode);
                echo json_encode(array('status' => true, 'message' => 'Mobile number updated and new code sent.'));
            } else {
                echo json_encode(array('status' => false, 'message' => 'Sorry an error occured'));
            }
            exit;
        }

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