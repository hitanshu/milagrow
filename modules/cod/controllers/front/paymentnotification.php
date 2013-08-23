<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hitanshu
 * Date: 12/8/13
 * Time: 12:58 PM
 * To change this template use File | Settings | File Templates.
 */
define('_WORKING_KEY', 'f6srdljv9krmyof389tjdixf86bgmc55');
define('_CCAVENUE_COD_SUCCESS', 'Thank you placing the order, %s of amount Rs. %s. Your order is being processed. Please check your email for additional details.');
class CodPaymentNotificationModuleFrontController extends ModuleFrontController
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
            $billing_cust_state = isset($_REQUEST["billing_cust_state"]) ? $_REQUEST["billing_cust_state"] : '';
            $billing_cust_city = isset($_REQUEST["billing_cust_city"]) ? $_REQUEST["billing_cust_city"] : '';
            $billing_zip_code = isset($_REQUEST["billing_zip_code"]) ? $_REQUEST["billing_zip_code"] : '';
            $billing_cust_country = isset($_REQUEST["billing_cust_country"]) ? $_REQUEST["billing_cust_country"] : '';
            $billing_cust_tel = isset($_REQUEST["billing_cust_tel"]) ? $_REQUEST['billing_cust_tel'] : '';
            $billing_cust_email = isset($_REQUEST["billing_cust_email"]) ? $_REQUEST["billing_cust_email"] : '';
            $Merchant_Param = isset($_REQUEST["Merchant_Param"]) ? $_REQUEST["Merchant_Param"] : '';

            $card_category = isset($_REQUEST["card_category"]) ? $_REQUEST["card_category"] : '';

            if ($Checksum && $AuthDesc === "Y") {

                // Getting payment row already exist from order reference number
                try {
                    $sql = 'SELECT * from ' . _DB_PREFIX_ . 'order_payment WHERE order_reference=\'' . $OrderId . '\'';
                    if ($row = Db::getInstance()->getRow($sql)) {
                        $existing_id_order_payment = $row['id_order_payment'];
                        $existing_id_currency = $row['id_currency'];
                        $existing_conversion_rate = $row['conversion_rate'];

                        Db::getInstance()->insert('order_payment', array(
                            'id_currency' => (int)$existing_id_currency,
                            'amount' => $Amount,
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

                        //sending success message to customer
                        $sql = 'SELECT ' . _DB_PREFIX_ . 'orders.reference,' . _DB_PREFIX_ . 'address.phone_mobile,total_paid_tax_incl as amount FROM ' . _DB_PREFIX_ . 'orders join ' . _DB_PREFIX_ . 'customer on ' . _DB_PREFIX_ . 'orders.id_customer=' . _DB_PREFIX_ . 'customer.id_customer join ' . _DB_PREFIX_ . 'address on ' . _DB_PREFIX_ . 'orders.id_address_delivery=' . _DB_PREFIX_ . 'address.id_address WHERE ' . _DB_PREFIX_ . 'orders.reference=\'' . $OrderId . '\'';
                        if ($row = Db::getInstance()->getRow($sql)) {
                            $this->sendMessage($OrderId, $Amount, $row['phone_mobile']);
                        }
                        $billingAddress = array('firstname' => $billing_cust_name, 'lastname' => '', 'state' => $billing_cust_state, 'phone' => $billing_cust_tel, 'postcode' => $billing_zip_code, 'city' => $billing_cust_city, 'address1' => $billing_cust_address, 'address2' => '', 'country' => $billing_cust_country);
                        $this->sendEmailToCustomer($billingAddress, $OrderId, $existing_id_order, $Amount);
                        $this->context->smarty->assign(array(
                            'txn_id' => $$nb_order_no,
                        ));
                        $this->setTemplate('success.tpl');
                    }
                } catch (Exception $e) {
                    throw new PrestaShopExceptionCore();
                }

            } else if ($Checksum && $AuthDesc === "B") {
                $new_history = new OrderHistory();
                $order_id = $this->getOrderIdFromReference($OrderId);
                if ($order_id) {
                    $new_history->id_order = (int)$order_id;
                    $new_history->changeIdOrderState(Configuration::get('PS_OS_ERROR'), $order_id, true);
                }

                $this->setTemplate('error.tpl');


                //Here you need to put in the routines/e-mail for a  "Batch Processing" order
                //This is only if payment for this transaction has been made by an American Express Card or by any netbank and status is not known is real time  the authorisation status will be  available only after 5-6 hours  at the "View Pending Orders" or you may do an order status query to fetch the status . Refer inetegrtaion document for order status tracker documentation"
            } else if ($Checksum && $AuthDesc === "N") {
                $new_history = new OrderHistory();
                $order_id = $this->getOrderIdFromReference($OrderId);
                if ($order_id) {
                    $new_history->id_order = (int)$order_id;
                    $new_history->changeIdOrderState(Configuration::get('PS_OS_ERROR'), $order_id, true);
                }

                $this->setTemplate('error.tpl');
                //Here you need to put in the routines for a failed
                //transaction such as sending an email to customer
                //setting database status etc etc
            } else {

//    Tools::redirect('index.php?controller=my-account');
                //Here you need to check for the checksum, the checksum did not match hence the error.
                $new_history = new OrderHistory();
                $order_id = $this->getOrderIdFromReference($OrderId);
                if ($order_id) {
                    $new_history->id_order = (int)$order_id;
                    $new_history->changeIdOrderState(Configuration::get('PS_OS_ERROR'), $order_id, true);
                }
                $this->setTemplate('error.tpl');
            }

        }
    }

    function sendMessage($orderReference, $amount, $mobile)
    {
        try {
            $message = sprintf(_CCAVENUE_COD_SUCCESS, $orderReference, $amount);

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

    private function sendEmailToCustomer($billingAddress, $orderReference, $orderId, $amount)
    {
        $order = new Order($orderId);
        $products_list = '';
        $virtual_product = true;
        //finding products for particular cart
        $productsSql = 'Select ' . _DB_PREFIX_ . 'cart_product.id_product as id_product,' . _DB_PREFIX_ . 'cart_product.id_product_attribute as id_product_attribute,' . _DB_PREFIX_ . 'cart_product.quantity as cart_quantity,' . _DB_PREFIX_ . 'product.reference as reference,'
            . _DB_PREFIX_ . 'product_lang.name as name from ' . _DB_PREFIX_ . 'cart_product join '
            . _DB_PREFIX_ . 'orders on ' . _DB_PREFIX_ . 'cart_product.id_cart=' . _DB_PREFIX_ . 'orders.id_cart join '
            . _DB_PREFIX_ . 'product on ' . _DB_PREFIX_ . 'cart_product.id_product=' . _DB_PREFIX_ . 'product.id_product join '
            . _DB_PREFIX_ . 'product_lang on ' . _DB_PREFIX_ . 'product.id_product=' . _DB_PREFIX_ . 'product_lang.id_product where id_order=' . $orderId;
        $products = Db::getInstance()->ExecuteS($productsSql);
        foreach ($products as $key => $product) {
            $price = Product::getPriceStatic((int)$product['id_product'], false, ($product['id_product_attribute'] ? (int)$product['id_product_attribute'] : null), 6, null, false, true, $product['cart_quantity'], false, (int)$order->id_customer, (int)$order->id_cart, (int)$order->{Configuration::get('PS_TAX_ADDRESS_TYPE')});
            $price_wt = Product::getPriceStatic((int)$product['id_product'], true, ($product['id_product_attribute'] ? (int)$product['id_product_attribute'] : null), 2, null, false, true, $product['cart_quantity'], false, (int)$order->id_customer, (int)$order->id_cart, (int)$order->{Configuration::get('PS_TAX_ADDRESS_TYPE')});

            $customization_quantity = 0;
            $customized_datas = Product::getAllCustomizedDatas((int)$order->id_cart);
            if (isset($customized_datas[$product['id_product']][$product['id_product_attribute']])) {
                $customization_text = '';
                foreach ($customized_datas[$product['id_product']][$product['id_product_attribute']][$order->id_address_delivery] as $customization) {
                    if (isset($customization['datas'][Product::CUSTOMIZE_TEXTFIELD]))
                        foreach ($customization['datas'][Product::CUSTOMIZE_TEXTFIELD] as $text)
                            $customization_text .= $text['name'] . ': ' . $text['value'] . '<br />';

                    if (isset($customization['datas'][Product::CUSTOMIZE_FILE]))
                        $customization_text .= sprintf(Tools::displayError('%d image(s)'), count($customization['datas'][Product::CUSTOMIZE_FILE])) . '<br />';
                    $customization_text .= '---<br />';
                }
                $customization_text = rtrim($customization_text, '---<br />');

                $customization_quantity = (int)$product['customization_quantity'];
                $products_list .=
                    '<tr style="background-color: ' . ($key % 2 ? '#DDE2E6' : '#EBECEE') . ';">
								<td style="padding: 0.6em 0.4em;width: 15%;">' . $product['reference'] . '</td>
								<td style="padding: 0.6em 0.4em;width: 30%;"><strong>' . $product['name'] . (isset($product['attributes']) ? ' - ' . $product['attributes'] : '') . ' - ' . Tools::displayError('Customized') . (!empty($customization_text) ? ' - ' . $customization_text : '') . '</strong></td>
								<td style="padding: 0.6em 0.4em; width: 20%;">' . Tools::displayPrice(Product::getTaxCalculationMethod() == PS_TAX_EXC ? Tools::ps_round($price, 2) : $price_wt, $this->context->currency, false) . '</td>
								<td style="padding: 0.6em 0.4em; width: 15%;">' . $customization_quantity . '</td>
								<td style="padding: 0.6em 0.4em; width: 20%;">' . Tools::displayPrice($customization_quantity * (Product::getTaxCalculationMethod() == PS_TAX_EXC ? Tools::ps_round($price, 2) : $price_wt), $this->context->currency, false) . '</td>
							</tr>';
            }

            if (!$customization_quantity || (int)$product['cart_quantity'] > $customization_quantity)
                $products_list .=
                    '<tr style="background-color: ' . ($key % 2 ? '#DDE2E6' : '#EBECEE') . ';">
								<td style="padding: 0.6em 0.4em;width: 15%;">' . $product['reference'] . '</td>
								<td style="padding: 0.6em 0.4em;width: 30%;"><strong>' . $product['name'] . (isset($product['attributes']) ? ' - ' . $product['attributes'] : '') . '</strong></td>
								<td style="padding: 0.6em 0.4em; width: 20%;">' . Tools::displayPrice(Product::getTaxCalculationMethod() == PS_TAX_EXC ? Tools::ps_round($price, 2) : $price_wt, $this->context->currency, false) . '</td>
								<td style="padding: 0.6em 0.4em; width: 15%;">' . ((int)$product['cart_quantity'] - $customization_quantity) . '</td>
								<td style="padding: 0.6em 0.4em; width: 20%;">' . Tools::displayPrice(((int)$product['cart_quantity'] - $customization_quantity) * (Product::getTaxCalculationMethod() == PS_TAX_EXC ? Tools::ps_round($price, 2) : $price_wt), $this->context->currency, false) . '</td>
							</tr>';

            // Check if is not a virutal product for the displaying of shipping
            if (!$product['is_virtual'])
                $virtual_product &= false;

        }
        $delivery = new Address($order->id_address_delivery);
        $delivery_state = $delivery->id_state ? new State($delivery->id_state) : false;
        $data = array(
            '{firstname}' => $this->context->customer->firstname,
            '{lastname}' => $this->context->customer->lastname,
            '{email}' => $this->context->customer->email,
            '{delivery_block_txt}' => $this->getFormattedDeliveryAddress($delivery, false),
            '{invoice_block_txt}' => $this->getFormattedBillingAddress($billingAddress, false),
            '{delivery_block_html}' => $this->getFormattedDeliveryAddress($delivery, true),
            '{invoice_block_html}' => $this->getFormattedBillingAddress($billingAddress, true),
            '{delivery_company}' => $delivery->company,
            '{delivery_firstname}' => $delivery->firstname,
            '{delivery_lastname}' => $delivery->lastname,
            '{delivery_address1}' => $delivery->address1,
            '{delivery_address2}' => $delivery->address2,
            '{delivery_city}' => $delivery->city,
            '{delivery_postal_code}' => $delivery->postcode,
            '{delivery_country}' => $delivery->country,
            '{delivery_state}' => $delivery->id_state ? $delivery_state->name : '',
            '{delivery_phone}' => ($delivery->phone) ? $delivery->phone : $delivery->phone_mobile,
            '{delivery_other}' => $delivery->other,
            '{invoice_company}' => '',
            '{invoice_vat_number}' => '',
            '{invoice_firstname}' => $billingAddress['firstname'],
            '{invoice_lastname}' => $billingAddress['lastname'],
            '{invoice_address2}' => $billingAddress['address2'],
            '{invoice_address1}' => $billingAddress['address1'],
            '{invoice_city}' => $billingAddress['city'],
            '{invoice_postal_code}' => $billingAddress['postcode'],
            '{invoice_country}' => $billingAddress['country'],
            '{invoice_state}' => $billingAddress['state'],
            '{invoice_phone}' => $billingAddress['phone'],
            '{invoice_other}' => '',
            '{order_name}' => $orderReference,
            '{date}' => Tools::displayDate(date('Y-m-d H:i:s'), (int)$order->id_lang, 1),
            '{carrier}' => Tools::displayError('No carrier'),
            '{payment}' => Tools::substr($order->payment, 0, 32),
            '{products}' => $products_list,
            '{discounts}' => '',
            '{ccavenuePayment}' => $amount,
            '{total_paid}' => Tools::displayPrice($order->total_paid - $amount, $this->context->currency, false),
            '{total_products}' => Tools::displayPrice($order->total_paid - $order->total_shipping - $order->total_wrapping + $order->total_discounts, $this->context->currency, false),
            '{total_discounts}' => Tools::displayPrice($order->total_discounts, $this->context->currency, false),
            '{total_shipping}' => Tools::displayPrice($order->total_shipping, $this->context->currency, false),
            '{total_wrapping}' => Tools::displayPrice($order->total_wrapping, $this->context->currency, false));


        // Join PDF invoice
        if ((int)Configuration::get('PS_INVOICE') && $order->invoice_number) {
            $pdf = new PDF($order->getInvoicesCollection(), PDF::TEMPLATE_INVOICE, $this->context->smarty);
            $file_attachement['content'] = $pdf->render(false);
            $file_attachement['name'] = Configuration::get('PS_INVOICE_PREFIX', (int)$order->id_lang, null, $order->id_shop) . sprintf('%06d', $order->invoice_number) . '.pdf';
            $file_attachement['mime'] = 'application/pdf';
        } else
            $file_attachement = null;

        if (Validate::isEmail($this->context->customer->email))
            Mail::Send(
                (int)$order->id_lang,
                'order_conf',
                Mail::l('Order confirmation', (int)$order->id_lang),
                $data,
                $this->context->customer->email,
                $this->context->customer->firstname . ' ' . $this->context->customer->lastname,
                null,
                null,
                $file_attachement,
                null, getcwd() . _MODULE_DIR_ . 'cod/', false, (int)$order->id_shop
            );

        // updates stock in shops
        if (Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT')) {
            $product_list = $order->getProducts();
            foreach ($product_list as $product) {
                // if the available quantities depends on the physical stock
                if (StockAvailable::dependsOnStock($product['product_id'])) {
                    // synchronizes
                    StockAvailable::synchronize($product['product_id'], $order->id_shop);
                }


            }
        }

    }

    private function getFormattedDeliveryAddress($address, $html = true)
    {
        if ($html) {
            return '<strong>' . $address->firstname . '' . $address->lastname . '</strong><br/>' . $address->address1 .
                '<br>' . $address->address2 . '<br>' . $address->city . ',' . $address->state . '<br>' . $address->country . '<br>' . $address->postcode;

        }

        return $address->firstname . ' ' . $address->lastname . '\r\n' . $address->address1 .
            '\r\n' . $address->address2 . '\r\n' . $address->city . ',' . $address->state . '\r\n' . $address->country . '\r\n' . $address->postcode;
    }

    private function getFormattedBillingAddress($address, $html = true)
    {

        if ($html) {
            return '<strong>' . $address['firstname'] . '' . $address['lastname'] . '</strong><br/>' . $address['address1'] .
                '<br>' . $address['address2'] . '<br>' . $address['city'] . ',' . $address['state'] . '<br>' . $address['country'] . '<br>' . $address['postcode'];

        }

        return $address['firstname'] . ' ' . $address['lastname'] . '\r\n' . $address['address1'] .
            '\r\n' . $address['address2'] . '\r\n' . $address['city'] . ',' . $address['state'] . '\r\n' . $address['country'] . '\r\n' . $address['postcode'];
    }

    private function getOrderIdFromReference($referenceNumber)
    {
        $sql = 'Select * from ' . _DB_PREFIX_ . 'orders where reference=\'' . $referenceNumber . '\'';
        if ($row = Db::getInstance()->getRow($sql)) {
            return $row['id_order'];
        }
        return false;
    }

}