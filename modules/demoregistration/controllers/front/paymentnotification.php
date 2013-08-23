<?php
/**
 * Created by JetBrains PhpStorm.
 * User: keshav
 * Date: 9/8/13
 * Time: 1:30 PM
 * To change this template use File | Settings | File Templates.
 */
include_once(dirname(__FILE__) . '../../../../config/config.inc.php');
include_once(dirname(__FILE__) . '/../../../../init.php');

require(getcwd() . _MODULE_DIR_ . "demoregistration/libFunctions.php");
define('_SMS_URL', 'http://admin.dove-sms.com/TransSMS/SMSAPI.jsp');
define('_WORKING_KEY', 'f6srdljv9krmyof389tjdixf86bgmc55');
define('_SMS_USERNAME', 'GreenApple1');
define('_SMS_PASSWORD', 'GreenApple1');
define('_SMS_SENDERID', 'MSNGRi');
define('_SMS_MESSAGE', 'Your demo request for Robotic Floor Cleaners has been registered succesfuly. Please check your email for additional details.');
class DemoRegistrationPaymentNotificationModuleFrontController extends ModuleFrontController
{
    public $display_column_left = false;
    public function postProcess()
    {
        $WorkingKey = _WORKING_KEY; //put in the 32 bit working key in the quotes provided here
        $card_number = '';
        $card_expiration = '';
        $card_holder = '';
        $MerchantId = isset($_REQUEST["Merchant_Id"]) ? $_REQUEST["Merchant_Id"] : '';
        $OrderId = isset($_REQUEST["Order_Id"]) ? $_REQUEST["Order_Id"] : '';
        $Amount = isset($_REQUEST["Amount"]) ? $_REQUEST["Amount"] : '';
        $AuthDesc = isset($_REQUEST["AuthDesc"]) ? $_REQUEST["AuthDesc"] : '';
        $avnChecksum = isset($_REQUEST["Checksum"]) ? $_REQUEST["Checksum"] : '';
        $nb_order_no = isset($_REQUEST["nb_order_no"]) ? $_REQUEST['nb_order_no'] : '';
        if (empty($MerchantId) || empty($OrderId) || empty($Amount) || empty($AuthDesc) || empty($avnChecksum) || empty($nb_order_no))
            $this->setTemplate('illegal_access.tpl');
        else {
            $Checksum = verifyChecksum($MerchantId, $OrderId, $Amount, $AuthDesc, $WorkingKey, $avnChecksum);
            $billing_cust_name = isset($_REQUEST["billing_cust_name"]) ? $_REQUEST["billing_cust_name"] : '';
            $billing_cust_address = isset($_REQUEST["billing_cust_address"]) ? $_REQUEST["billing_cust_address"] : '';
            $billing_cust_country = isset($_REQUEST["billing_cust_country"]) ? $_REQUEST["billing_cust_country"] : '';
            $billing_cust_tel = isset($_REQUEST["billing_cust_tel"]) ? $_REQUEST['billing_cust_tel'] : '';
            $billing_cust_email = isset($_REQUEST["billing_cust_email"]) ? $_REQUEST["billing_cust_email"] : '';
            $delivery_cust_name = isset($_REQUEST["delivery_cust_name"]) ? $_REQUEST["delivery_cust_name"] : '';
            $delivery_cust_address = isset($_REQUEST["delivery_cust_address"]) ? $_REQUEST["delivery_cust_address"] : '';
            $delivery_cust_tel = isset($_REQUEST["delivery_cust_tel"]) ? $_REQUEST["delivery_cust_tel"] : '';
            $delivery_cust_notes = isset($_REQUEST["delivery_cust_notes"]) ? $_REQUEST["delivery_cust_notes"] : '';
            $Merchant_Param = isset($_REQUEST["Merchant_Param"]) ? $_REQUEST["Merchant_Param"] : '';

            $card_category = isset($_REQUEST["card_category"]) ? $_REQUEST["card_category"] : '';

            if (true && $AuthDesc === "Y") {
                $message = "<br>Thank you for shopping with us. Your credit card has been charged and your transaction is successful. We will be shipping your order to you soon.";
                // Getting payment row already exist from order reference number
                try {
                    $currentDate = new DateTime('now', new DateTimeZone('UTC'));
                    $currentTime = $currentDate->format("Y-m-d H:i:s");
                    $data = array('status' => 'paid', 'updated_at' => $currentTime, 'nb_order_no' => $nb_order_no);
                    Db::getInstance()->update('demos', $data, 'order_id=\'' . $OrderId . '\'');
                    $affectedRows = Db::getInstance()->Affected_Rows();
                    if ($affectedRows) {
                        $this->context->smarty->assign(array(
                            'message' => $message,

                        ));
                        $orderInfo = $this->getOrderInfo($OrderId);
                        if (!empty($orderInfo)) {

                            $data = array('{date}' => $orderInfo['date'],
                                '{order_id}' => $orderInfo['orderId'], '{name}' => $orderInfo['name'],
                                '{nb_order_no}' => $nb_order_no, '{address}' => $orderInfo['address'], '{country}' => $orderInfo['country'],
                                '{state}' => $orderInfo['state'], '{city}' => $orderInfo['city'], '{zip}' => $orderInfo['zip'],
                                '{mobile}' => $orderInfo['mobile'], '{email}' => $orderInfo['email'],'{specialComments}'=>$orderInfo['specialComments']);
                            $this->sendEmails($data);
                            $this->sendMessage($orderInfo['product'], $orderInfo['date'], $orderInfo['mobile']);
                        }

                        return $this->setTemplate('success.tpl');

                    }
                } catch (Exception $e) {
                    throw new PrestaShopExceptionCore();
                }

            } else if ($Checksum && $AuthDesc === "B") {
//                echo "<br>Thank you for shopping with us.We will keep you posted regarding the status of your order through e-mail";
                $this->setTemplate('error.tpl');
                //Here you need to put in the routines/e-mail for a  "Batch Processing" order
                //This is only if payment for this transaction has been made by an American Express Card or by any netbank and status is not known is real time  the authorisation status will be  available only after 5-6 hours  at the "View Pending Orders" or you may do an order status query to fetch the status . Refer inetegrtaion document for order status tracker documentation"
            } else if ($Checksum && $AuthDesc === "N") {
                $this->setTemplate('error.tpl');

                //Here you need to put in the routines for a failed
                //transaction such as sending an email to customer
                //setting database status etc etc
            } else {

//    Tools::redirect('index.php?controller=my-account');
                //Here you need to check for the checksum, the checksum did not match hence the error.
                $this->setTemplate('error.tpl');
            }

        }

    }


    private function getOrderInfo($orderId)
    {
        $sql = "SELECT " . _DB_PREFIX_ . "demos.name as name," . _DB_PREFIX_ . "demos.amount as amount," . _DB_PREFIX_ . "demos.product as productName," . _DB_PREFIX_ . "demos.order_id as orderId,"
            . _DB_PREFIX_ . "demos.country as country," . _DB_PREFIX_ . "demos.nb_order_no as transactionId," . _DB_PREFIX_ . "demos.address as address," .
            _DB_PREFIX_ . "demos.state as state," . _DB_PREFIX_ . "demos.city as city," . _DB_PREFIX_ . "demos.zip as zip,"
            . _DB_PREFIX_ . "demos.mobile as mobile," . _DB_PREFIX_ . "demos.email as email ," . _DB_PREFIX_ . "demos.special_comments as specialComments FROM "._DB_PREFIX_."demos where " . _DB_PREFIX_ . "demos.order_id='" . $orderId . "';";
        if ($results = Db::getInstance()->getRow($sql))
            return $results;
        return array();
    }

    private
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

    private
    function sendEmails($data)
    {
        try {
            $res = Mail::Send(
                (int)1,
                'admin_mail',
                Mail::l('New Demo Details', (int)1),
                $data,
                Configuration::get('PS_SHOP_EMAIL'),
                'Administrator',
                null,
                true,
                null,
                null,
                getcwd() . _MODULE_DIR_ . DemoRegistration::MODULE_NAME . '/',
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
                Mail::l('Milagrow Human Tech : Demo Detail', (int)1),
                $data,
                $data['{email}'],
                $data['{name}'],
                null,
                true,
                null,
                null,
                getcwd() . _MODULE_DIR_ . DemoRegistration::MODULE_NAME . '/',
                false,
                null
            );

        } catch (Exception $e) {
            return false;
        }

    }


}