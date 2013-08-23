<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hitanshu
 * Date: 30/7/13
 * Time: 10:46 AM
 * To change this template use File | Settings | File Templates.
 */

class B2bSuccessModuleFrontController extends ModuleFrontController
{
    public $display_column_left = true;

    public function initContent()
    {
        parent::initContent();

        $this->b2b = new b2b();
        $this->context = Context::getContext();
        $this->id_module = (int)Tools::getValue('id_module');
        $currentDate = date('Y-m-d');
        $nextDate = $this->getNextDate($currentDate);
        $key = Tools::getValue('key');
        $query = 'Select email,mobile,city,state,quantity,' . _DB_PREFIX_ . 'product_lang.name as product_name,' . _DB_PREFIX_ . 'b2b.name as name,' . _DB_PREFIX_ . 'b2b.id_b2b as id_b2b,' . _DB_PREFIX_ . 'b2b.is_verified as is_verified,' . _DB_PREFIX_ . 'b2b.product as product from ' . _DB_PREFIX_ . 'b2b join ' . _DB_PREFIX_ . 'product_lang on ' . _DB_PREFIX_ . 'b2b.product=' . _DB_PREFIX_ . 'product_lang.id_product where id_b2b=' . $key;
        if ($b2bRow = Db::getInstance()->getRow($query)) {
            if ($b2bRow['is_verified']) {
                $adminVars = array(
                    '{name}' => $b2bRow['name'],
                    '{email}' => $b2bRow['email'],
                    '{mobile}' => $b2bRow['mobile'],
                    '{city}' => $b2bRow['city'],
                    '{state}' => $b2bRow['state'],
                    '{product}' => $b2bRow['product_name'],
                    '{quantity}' => $b2bRow['quantity']
                );
                $adminEmail = Configuration::get('PS_SHOP_EMAIL');


                //sending mail to milagrow related to form details
                Mail::Send(
                    (int)1,
                    'b2b_admin_mail',
                    Mail::l('Bulk Purchase Details', (int)1),
                    $adminVars,
                    $adminEmail,
                    'admin',
                    null,
                    null,
                    null,
                    null,
                    getcwd() . _MODULE_DIR_ . 'b2b/',
                    false,
                    null
                );
                $quantity = explode('-', $b2bRow['quantity']);
                $sql = 'select distinct(code) as couponCode,date_from as startDate,date_to as endDate from ' . _DB_PREFIX_ .
                    'cart_rule_product_rule join ' . _DB_PREFIX_ . 'cart_rule_product_rule_group on ' . _DB_PREFIX_ .
                    'cart_rule_product_rule.id_product_rule_group=' . _DB_PREFIX_ . 'cart_rule_product_rule_group.id_product_rule_group join ' . _DB_PREFIX_ .
                    'cart_rule_product_rule_value on ' . _DB_PREFIX_ . 'cart_rule_product_rule_value.id_product_rule=' . _DB_PREFIX_ .
                    'cart_rule_product_rule_value.id_product_rule join ' . _DB_PREFIX_ . 'cart_rule on ' . _DB_PREFIX_ .
                    'cart_rule.id_cart_rule=' . _DB_PREFIX_ . 'cart_rule_product_rule_group.id_cart_rule where active=1 and '
                    . _DB_PREFIX_ . 'cart_rule_product_rule_group.quantity>=' . $quantity[0];
                if (isset($quantity[1]))
                    $sql .= ' and ' . _DB_PREFIX_ . 'cart_rule_product_rule_group.quantity<=' . $quantity[1];
                $sql .= '  and ' . _DB_PREFIX_ . 'cart_rule_product_rule_value.id_item=' . $b2bRow['product'] . ' and date_from <=\'' . $nextDate . '\' and date_to>\'' . $currentDate . '\'';
                if ($row = Db::getInstance()->getRow($sql)) {
                    //coupon found sent mail to user coupon
                    $vars = array(
                        '{couponCode}' => $row['couponCode'],
                        '{startDate}' => $row['startDate'],
                        '{endDate}' => $row['endDate'],
                        '{name}' => $b2bRow['name']
                    );

                    Mail::Send(
                        (int)1,
                        'b2b_mail',
                        Mail::l('Milagrow Human Tech : Bulk Purchase Discount Coupon', (int)1),
                        $vars,
                        $b2bRow['email'],
                        $b2bRow['name'],
                        null,
                        null,
                        null,
                        null,
                        getcwd() . _MODULE_DIR_ . 'b2b/',
                        false,
                        null
                    );
                } else {
                    //no coupons available
                    //content for mail to be given
                }
                $this->setTemplate('success.tpl');
            } else {
                $url = B2b::getShopDomainSsl(true, true);
                $values = array('fc' => 'module', 'module' => 'b2b', 'controller' => 'verify', 'key' => $b2bRow['id_b2b']);
                $url .= '/index.php?' . http_build_query($values, '', '&');
                Tools::redirectLink($url);
            }

        } else {
            // If not in mode dev, display an error page
            if (file_exists(_PS_ROOT_DIR_ . '/error500.html'))
                echo file_get_contents(_PS_ROOT_DIR_ . '/error500.html');
            exit;
        }
    }

    private function getNextDate($date)
    {
        return date('Y-m-d', strtotime('+1 day', strtotime($date)));
    }

}