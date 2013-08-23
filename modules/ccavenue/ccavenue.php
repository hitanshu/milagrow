<?php
/*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2013 PrestaShop SA
*  @version  Release: $Revision: 1.1 $
*
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_'))
    exit;

class CCAvenue extends PaymentModule
{
    public $keys = array();

    public function __construct()
    {
        $this->name = 'ccavenue';
        $this->tab = 'payments_gateways';
        $this->version = '1.1';
        $this->author = 'PrestaShop';

        parent::__construct();
        $this->displayName = $this->l('CCAvenue&reg;');
        $this->description = $this->l('CCAvenue&reg; is a premiere payment gateway used by more than 85% of all e-merchants in India.');

        /* Backward compatibility */
        if (_PS_VERSION_ < 1.5)
            require(_PS_MODULE_DIR_ . 'ccavenue/backward_compatibility/backward.php');
    }

    public function install()
    {
        /* The hook "displayMobileHeader" has been introduced in v1.5.x - Called separately to fail silently if the hook does not exist */
        $this->registerHook('displayMobileHeader');

        /* initialisation of configuration key value */
        return parent::install() && $this->registerHook('payment') && $this->registerHook('orderConfirmation')
            && Configuration::updateValue('CCAVENUE_PENDING_STATUS', $this->_addState()) && Configuration::updateValue('CCAVENUE_MERCHANT_ID', '')
            && Configuration::updateValue('CCAVENUE_WORKING_KEY', '') && Configuration::updateValue('CCAVENUE_MERCHANT_CURRENCY', '');
    }

    /******************************************************************/
    /** Add payment state: CCAvenue: Payment Pending ******************/
    /** For order that are still in pending verification **************/
    /******************************************************************/
    private function _addState()
    {
        $orderState = new OrderState();
        $orderState->name = array();
        foreach (Language::getLanguages() as $language)
            $orderState->name[$language['id_lang']] = $this->l('CCAvenue: Payment Pending');
        $orderState->send_email = false;
        $orderState->color = '#DDEEFF';
        $orderState->hidden = false;
        $orderState->delivery = false;
        $orderState->logable = true;
        if ($orderState->add())
            copy(dirname(__FILE__) . '/logo.gif', dirname(__FILE__) . '/../../img/os/' . (int)$orderState->id . '.gif');
        return $orderState->id;
    }

    public function uninstall()
    {
        /* Uninstall parent and Delete Configurations Keys */
        return parent::uninstall() && Configuration::deleteByName('CCAVENUE_MERCHANT_ID') && Configuration::deleteByName('CCAVENUE_WORKING_KEY') && Configuration::deleteByName('CCAVENUE_MERCHANT_CURRENCY');
    }

    public function getContent()
    {
        $html = '';
        if (Tools::isSubmit('submitCCAvenue') && isset($_POST['ccAvenueMerchantID']) && !empty($_POST['ccAvenueMerchantID']) && isset($_POST['ccAvenueWorkingKey']) && !empty($_POST['ccAvenueWorkingKey'])) {
            Configuration::updateValue('CCAVENUE_MERCHANT_ID', pSQL($_POST['ccAvenueMerchantID']));
            Configuration::updateValue('CCAVENUE_WORKING_KEY', pSQL($_POST['ccAvenueWorkingKey']));
            Configuration::updateValue('CCAVENUE_MERCHANT_CURRENCY', pSQL($_POST['ccAvenueMerchantCurrency']));
            if (Configuration::get('CCAVENUE_MERCHANT_CURRENCY') == 'INR' && !Currency::exists(Configuration::get('CCAVENUE_MERCHANT_CURRENCY'), 356)) {
                $currency = new Currency();
                $currency->iso_code = 'INR';
                $currency->name = $this->l('Indian Rupee');
                $currency->active = 1;
                $currency->deleted = 0;
                $currency->conversion_rate = 1;
                $currency->iso_code_num = 356;
                $currency->sign = 'Rs';
                $currency->blank = true;
                $currency->decimals = true;
                $currency->format = 1;
                $currency->add();
                if ($currency->id)
                    PaymentModule::addCurrencyPermissions($currency->id, array($this->id));
            } elseif (Configuration::get('CCAVENUE_MERCHANT_CURRENCY') == 'USD' && !Currency::exists(Configuration::get('CCAVENUE_MERCHANT_CURRENCY'), 840)) {
                $currency = new Currency();
                $currency->iso_code = 'USD';
                $currency->name = $this->l('US Dollar');
                $currency->active = 1;
                $currency->deleted = 0;
                $currency->conversion_rate = 1;
                $currency->iso_code_num = 840;
                $currency->sign = '$';
                $currency->blank = true;
                $currency->decimals = true;
                $currency->format = 1;
                $currency->add();
                if ($currency->id)
                    PaymentModule::addCurrencyPermissions($currency->id, array($this->id));
            }
            Currency::refreshCurrencies();

            $html .= '<div class="conf">' . $this->l('Configuration updated successfully') . '</div>';
        } elseif (Tools::isSubmit('submitCCAvenue'))
            $html = '<div class="error">' . $this->l('Please fill the required fields') . '</div>';

        $this->context->smarty->assign(array(
            'ccAvenue_form' => './index.php?tab=AdminModules&configure=ccavenue&token=' . Tools::getAdminTokenLite('AdminModules') . '&tab_module=' . $this->tab . '&module_name=ccavenue',
            'ccAvenue_tracking' => 'http://www.prestashop.com/modules/ccavenue.png?url_site=' . Tools::safeOutput($_SERVER['SERVER_NAME']) . '&amp;id_lang=' . (int)$this->context->cookie->id_lang,
            'ccAvenue_ssl' => Configuration::get('PS_SSL_ENABLED'),
            'prestashop_version' => _PS_VERSION_ >= 1.5 ? '1.5' : '1.4',
            'ccAvenue_merchant_id' => Configuration::get('CCAVENUE_MERCHANT_ID'),
            'ccAvenue_working_key' => Configuration::get('CCAVENUE_WORKING_KEY'),
            'ccAvenue_merchant_currency' => Configuration::get('CCAVENUE_MERCHANT_CURRENCY'),
            'ccAvenue_confirmation' => $html
        ));

        return $this->display(__FILE__, 'tpl/configuration.tpl');
    }

    /* The hook "displayMobileHeader" has been introduced in v1.5.x - Called separately to fail silently if the hook does not exist */
    public function hookDisplayMobileHeader()
    {
        return $this->hookHeader();
    }

    /* Displays CCAvenue's payment form on the Front-office */
    public function hookPayment($params)
    {
        $currency = new Currency((int)$params['cart']->id_currency);

        if (!$this->active || !Configuration::get('CCAVENUE_MERCHANT_ID') || !Configuration::get('CCAVENUE_WORKING_KEY') || $currency->iso_code != Configuration::get('CCAVENUE_MERCHANT_CURRENCY'))
            return false;

        $check_sum = $this->_adler32(Configuration::get('CCAVENUE_MERCHANT_ID') . '|ccAvenue_' . (int)$params['cart']->id . '|' . (float)$params['cart']->getOrderTotal() . '|http://' . htmlspecialchars($_SERVER['HTTP_HOST'], ENT_COMPAT, 'UTF-8') . __PS_BASE_URI__ . 'modules/ccavenue/validation.php|' . Configuration::get('CCAVENUE_WORKING_KEY'));
        //customize code to add billing Address

        $billing = new Address($params['cart']->id_address_invoice);
        $billing_state = $billing->id_state ? new State($billing->id_state) : false;
        $this->context->smarty->assign(array(
            'ccAvenue_merchant_id' => Tools::safeOutput(Configuration::get('CCAVENUE_MERCHANT_ID')),
            'ccAvenue_checksum' => Tools::safeOutput($check_sum),
            'ccAvenue_order_id' => 'ccAvenue_' . (int)$params['cart']->id,
            'ccAvenue_amount' => (float)$params['cart']->getOrderTotal(),
            'ccAvenue_redirect_link' => 'http://' . htmlspecialchars($_SERVER['HTTP_HOST'], ENT_COMPAT, 'UTF-8') . __PS_BASE_URI__ . 'modules/ccavenue/validation.php',
            'billing_cust_name' => $billing->firstname . '' . $billing->lastname,
            'billing_cust_address' => $billing->address1 . '' . $billing->address2,
            'billing_cust_country' => $billing->country,
            'billing_cust_state' => $billing->id_state ? $billing_state->name : '',
            'billing_city' => $billing->city,
            'billing_zip' => $billing->postcode,
            'billing_cust_tel' => ($billing->phone) ? $billing->phone : $billing->phone_mobile,
            'billing_cust_email' => $this->context->customer->email,
        ));

        return $this->display(__FILE__, 'tpl/payment.tpl');
    }

    /**
     * Display a confirmation message after an order has been placed
     *
     * @param array Hook parameters
     */
    public function hookOrderConfirmation($params)
    {
        if ($params['objOrder']->module != $this->name)
            return false;
        if ($params['objOrder'] && Validate::isLoadedObject($params['objOrder']) && isset($params['objOrder']->valid)) {
            $state = ($params['objOrder']->current_state == Configuration::get('CCAVENUE_PENDING_STATUS') ? true : false);
            if (version_compare(_PS_VERSION_, '1.5', '>=') && isset($params['objOrder']->reference))
                $this->smarty->assign('ccAvenue_order', array('reference' => $params['objOrder']->reference, 'valid' => $params['objOrder']->valid, 'state' => $state));
            else
                $this->smarty->assign('ccAvenue_order', array('id' => $params['objOrder']->id, 'valid' => $params['objOrder']->valid));

            return $this->display(__FILE__, 'tpl/order-confirmation.tpl');
        }
    }

    /* Called when an order was placed and CCAvenue is keeping us posted about the payment validation */
    public function validation()
    {
        $amount = $_REQUEST['Amount'];
        $cart_id = (int)str_replace('ccAvenue_', '', $_REQUEST['Order_Id']);
        $auth_desc = $_REQUEST['AuthDesc'];

        $cart = new Cart($cart_id);

        if ($_REQUEST['Merchant_Id'] == Configuration::get('CCAVENUE_MERCHANT_ID')) {
            $verifCheckSum = $this->_adler32(Configuration::get('CCAVENUE_MERCHANT_ID') . '|ccAvenue_' . $cart_id . '|' . $amount . '|' . $auth_desc . '|' . Configuration::get('CCAVENUE_WORKING_KEY')) == $_REQUEST['Checksum'] ? true : false;
            if ($verifCheckSum) {
                if ($auth_desc == 'Y')
                    $this->validateOrder((int)$cart->id, (int)Configuration::get('PS_OS_PAYMENT'), (float)$amount, $this->displayName, $this->l('Your credit card has been charged and the transaction is successful'), array(), null, false, $cart->secure_key);
                elseif ($auth_desc == 'B')
                    $this->validateOrder((int)$cart->id, (int)Configuration::get('CCAVENUE_PENDING_STATUS'), (float)$amount, $this->displayName, $this->l('The transaction is in pending verification'), array(), null, false, $cart->secure_key); elseif ($auth_desc == 'N')
                    $this->validateOrder((int)$cart->id, (int)Configuration::get('PS_OS_ERROR'), (float)$amount, $this->displayName, $this->l('The transaction has been declined'), array(), null, false, $cart->secure_key);
                if (version_compare(_PS_VERSION_, '1.5', '<'))
                    $redirect = __PS_BASE_URI__ . 'order-confirmation.php?id_cart=' . (int)$cart->id . '&id_module=' . (int)$this->id . '&id_order=' . (int)$this->currentOrder . '&key=' . $cart->secure_key;
                else
                    $redirect = __PS_BASE_URI__ . 'index.php?controller=order-confirmation&id_cart=' . (int)$cart->id . '&id_module=' . (int)$this->id . '&id_order=' . (int)$this->currentOrder . '&key=' . $cart->secure_key;

                header('Location: ' . $redirect);
                exit;
            }
        }
        Tools::redirectLink(__PS_BASE_URI__ . 'history.php');
    }

    /* _adler: Private function that take as parameter a string and crypt it */
    /* Checksum algorithm for the security of the payment ********************/

    private function _adler32($str)
    {
        $BASE = 65521;
        $adler = 1;

        $s1 = $adler & 0xffff;
        $s2 = ($adler >> 16) & 0xffff;
        for ($i = 0; $i < strlen($str); $i++) {
            $s1 = ($s1 + Ord($str[$i])) % $BASE;
            $s2 = ($s2 + $s1) % $BASE;
        }

        $str = DecBin($s2);
        for ($i = 0; $i < (64 - strlen($str)); $i++)
            $str = '0' . $str;

        for ($i = 0; $i < 16; $i++) {
            $str = $str . '0';
            $str = substr($str, 1);
        }

        for ($n = 0, $dec = 0; $n < strlen($str); $n++) {
            $temp = $str[$n];
            $dec = $dec + $temp * pow(2, strlen($str) - $n - 1);
        }

        return $dec + $s1;
    }
}