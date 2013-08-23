<?php
require(getcwd() . _MODULE_DIR_ . "demoregistration/libFunctions.php");
define('_MERCHANT_ID', 'M_milagrow_6881');
define('_BILLING_PAGE_HEADING', 'Milagrow Humantech - Sales Home Demo for Robotic Floor Cleaners');

class DemoRegistrationPaymentModuleFrontController extends ModuleFrontController
{
    public $display_column_left = true;


    public function postProcess()
    {

    }

    public function initContent()
    {
        parent::initContent();
        $orderId = Tools::getValue('order_id');
        $error = "";
        if ($orderId) {
            $sql = 'SELECT * FROM ' . _DB_PREFIX_ . 'demos where order_id=\'' . $orderId . '\'';
            if ($result = Db::getInstance()->getRow($sql)) {
                if ($result['status'] != 'paid') {

                    $paymentNotificationUrl = DemoRegistration::getShopDomainSsl(true, true);
                    $values = array('fc' => 'module', 'module' => DemoRegistration::MODULE_NAME, 'controller' => 'paymentnotification');
                    $paymentNotificationUrl .= '/index.php?' . http_build_query($values, '', '&');
                    //take user to the payment gateway screen for some down payment
                    $Merchant_Id = _MERCHANT_ID; //This id(also User_Id)  available at "Generate Working Key" of "Settings & Options"

                    $Amount = floatval($result['amount']); //your script should substitute the amount here in the quotes provided here
                    
                    $WorkingKey = "f6srdljv9krmyof389tjdixf86bgmc55"; //put in the 32 bit alphanumeric key in the quotes provided here.Please note that get this key login to your
                    $Checksum = getChecksum($Merchant_Id, $orderId, $Amount, $paymentNotificationUrl, $WorkingKey);

                    $this->context->smarty->assign(array(
                        'merchant_id' => _MERCHANT_ID,
                        'amount' => $Amount,
                        'redirectLink' => $paymentNotificationUrl,
                        'orderId' => $orderId,
                        'delivery_cust_name' => $result['name'],
                        'delivery_cust_address' => $result['address'],
                        'delivery_cust_country' => $result['country'],
                        'delivery_cust_state' => $result['state'],
                        'delivery_city' => $result['city'],
                        'delivery_zip' => $result['zip'],
                        'delivery_cust_tel' => $result['mobile'],
                        'delivery_cust_email' => $result['email'],
                        'billing_cust_name' => $result['name'],
                        'billing_cust_address' => $result['address'],
                        'billing_cust_country' => $result['country'],
                        'billing_cust_state' => $result['state'],
                        'billing_city' => $result['city'],
                        'billing_zip' => $result['zip'],
                        'billing_cust_tel' => $result['mobile'],
                        'billing_cust_email' => $result['email'],
                        'billingPageHeading' => _BILLING_PAGE_HEADING,
                        'delivery_cust_notes' => "",
                        'checksum' => $Checksum
                    ));
                    return $this->setTemplate('confirmation.tpl');

                } else {
                    $error = "Payment already received for this order";

                }
            } else {
                $error = "Error in finding your order";
            }
        } else {
            $error = "Bad request";

        }
        $this->context->smarty->assign(array(
            'message' => $error,

        ));
        $this->setTemplate('error.tpl');
    }


}
