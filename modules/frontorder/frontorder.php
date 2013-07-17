<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hitanshu
 * Date: 12/7/13
 * Time: 6:23 PM
 * To change this template use File | Settings | File Templates.
 */

class FrontOrder extends Module
{
    public $order_invoice;

    public function __construct()
    {
        $this->name = 'frontorder';
        $this->tab = 'front_office_features';
        $this->version = '1.0';
        $this->author = 'GAPS';
        $this->need_instance = 0;
//        $this->ps_versions_compliancy = array('min' => '1.5', 'max' => '1.5');

        parent::__construct();
        $this->order_invoice = new OrderInvoice();
        $this->displayName = $this->l('Front End Order Display');
        $this->description = $this->l('Module for changing the front end order screen');
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

        if (!Configuration::get('FRONT_ORDER_NAME'))
            $this->warning = $this->l('No name provided');
    }

    public function install()
    {
        if (Shop::isFeatureActive())
            Shop::setContext(Shop::CONTEXT_ALL);
        return parent::install() && $this->registerHook('displayOrderDetail');
    }

    public function uninstall()
    {
        return parent::uninstall() && Configuration::deleteByName('FRONT_ORDER_NAME');
    }


    public function hookdisplayOrderDetail($params = null)
    {
        $order = new Order($params['order']->id);
        if (Validate::isLoadedObject($order) && $order->id_customer == $this->context->customer->id) {
            $id_order_state = (int)($order->getCurrentState());
            $carrier = new Carrier((int)($order->id_carrier), (int)($order->id_lang));
            $addressInvoice = new Address((int)($order->id_address_invoice));
            $addressDelivery = new Address((int)($order->id_address_delivery));

            $inv_adr_fields = AddressFormat::getOrderedAddressFields($addressInvoice->id_country);
            $dlv_adr_fields = AddressFormat::getOrderedAddressFields($addressDelivery->id_country);

            $invoiceAddressFormatedValues = AddressFormat::getFormattedAddressFieldsValues($addressInvoice, $inv_adr_fields);
            $deliveryAddressFormatedValues = AddressFormat::getFormattedAddressFieldsValues($addressDelivery, $dlv_adr_fields);

            if ($order->total_discounts > 0)
                $this->context->smarty->assign('total_old', (float)($order->total_paid - $order->total_discounts));
            $products = $order->getProducts();

            /* DEPRECATED: customizedDatas @since 1.5 */
            $customizedDatas = Product::getAllCustomizedDatas((int)($order->id_cart));
            Product::addCustomizationPrice($products, $customizedDatas);

            OrderReturn::addReturnedQuantity($products, $order->id);

            $customer = new Customer($order->id_customer);

            $this->context->smarty->assign(array(
                'shop_name' => strval(Configuration::get('PS_SHOP_NAME')),
                'order' => $order,
                'return_allowed' => (int)($order->isReturnable()),
                'currency' => new Currency($order->id_currency),
                'order_state' => (int)($id_order_state),
                'invoiceAllowed' => (int)(Configuration::get('PS_INVOICE')),
                'invoice' => (OrderState::invoiceAvailable($id_order_state) && count($order->getInvoicesCollection())),
                'order_history' => $order->getHistory($this->context->language->id, false, true),
                'products' => $products,
                'discounts' => $order->getCartRules(),
                'carrier' => $carrier,
                'address_invoice' => $addressInvoice,
                'invoiceState' => (Validate::isLoadedObject($addressInvoice) && $addressInvoice->id_state) ? new State($addressInvoice->id_state) : false,
                'address_delivery' => $addressDelivery,
                'inv_adr_fields' => $inv_adr_fields,
                'dlv_adr_fields' => $dlv_adr_fields,
                'invoiceAddressFormatedValues' => $invoiceAddressFormatedValues,
                'deliveryAddressFormatedValues' => $deliveryAddressFormatedValues,
                'deliveryState' => (Validate::isLoadedObject($addressDelivery) && $addressDelivery->id_state) ? new State($addressDelivery->id_state) : false,
                'is_guest' => false,
                'messages' => CustomerMessage::getMessagesByOrderId((int)($order->id), false),
                'CUSTOMIZE_FILE' => Product::CUSTOMIZE_FILE,
                'CUSTOMIZE_TEXTFIELD' => Product::CUSTOMIZE_TEXTFIELD,
                'isRecyclable' => Configuration::get('PS_RECYCLABLE_PACK'),
                'use_tax' => Configuration::get('PS_TAX'),
                'group_use_tax' => (Group::getPriceDisplayMethod($customer->id_default_group) == PS_TAX_INC),
                'order_invoice' => $this->getOrderPayment($order->id),
                /* DEPRECATED: customizedDatas @since 1.5 */
                'customizedDatas' => $customizedDatas
                /* DEPRECATED: customizedDatas @since 1.5 */
            ));

            if ($carrier->url && $order->shipping_number)
                $this->context->smarty->assign('followup', str_replace('@', $order->shipping_number, $carrier->url));
            echo $this->display(__FILE__, 'frontorder.tpl');
            exit;


        }
    }

    public function getOrderPayment($orderId)
    {
        $payments = Db::getInstance()->executeS('SELECT id_order_payment FROM `' . _DB_PREFIX_ . 'order_invoice_payment` WHERE id_order = ' . (int)$orderId);
        if (!$payments)
            return array();

        $payment_list = array();
        foreach ($payments as $payment)
            $payment_list[] = $payment['id_order_payment'];

        $orderInvoice = Db::getInstance()->executeS('SELECT * FROM ' . _DB_PREFIX_ . 'order_payment WHERE id_order_payment IN(' . implode($payment_list,',') . ')');
        return $orderInvoice;
    }


}