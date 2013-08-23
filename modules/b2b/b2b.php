<?php
/*
* 2007-2013 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2013 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_'))
    exit;

class B2b extends Module
{
    protected $_html = '';

    public $_errors = array();

    public $context;

    public function __construct()
    {
        $this->name = 'b2b';
        $this->tab = 'front_office_features';
        $this->version = '1.0';
        $this->author = 'GAPS';

        parent::__construct();

        $this->displayName = $this->l('B2B');
        $this->description = $this->l('Millagrow B2B System');
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

        $this->page = basename(__FILE__, '.php');

    }

    public function install()
    {
        if (!parent::install()
        )
            return false;

        include_once(_PS_MODULE_DIR_ . '/' . $this->name . '/b2b_install.php');
        $b2b_install = new B2bInstall();
        $b2b_install->createTables();

        return true;
    }

    public function uninstall()
    {
        return parent::uninstall();
    }

    public function fetchTemplate($name)
    {
        if (_PS_VERSION_ < '1.4')
            $this->context->smarty->currentTemplate = $name;
        elseif (_PS_VERSION_ < '1.5') {
            $views = 'views/templates/';
            if (@filemtime(dirname(__FILE__) . '/' . $name))
                return $this->display(__FILE__, $name);
            elseif (@filemtime(dirname(__FILE__) . '/' . $views . 'hook/' . $name))
                return $this->display(__FILE__, $views . 'hook/' . $name); elseif (@filemtime(dirname(__FILE__) . '/' . $views . 'front/' . $name))
                return $this->display(__FILE__, $views . 'front/' . $name); elseif (@filemtime(dirname(__FILE__) . '/' . $views . 'back/' . $name))
                return $this->display(__FILE__, $views . 'back/' . $name);
        }

        return $this->display(__FILE__, $name);
    }


    public static function getShopDomainSsl($http = false, $entities = false)
    {
        if (method_exists('Tools', 'getShopDomainSsl'))
            return Tools::getShopDomainSsl($http, $entities);
        else {
            if (!($domain = Configuration::get('PS_SHOP_DOMAIN_SSL')))
                $domain = self::getHttpHost();
            if ($entities)
                $domain = htmlspecialchars($domain, ENT_COMPAT, 'UTF-8');
            if ($http)
                $domain = (Configuration::get('PS_SSL_ENABLED') ? 'https://' : 'http://') . $domain;
            return $domain;
        }
    }

    protected function getCurrentUrl()
    {
        $protocol_link = Tools::usingSecureMode() ? 'https://' : 'http://';
        $request = $_SERVER['REQUEST_URI'];
        $pos = strpos($request, '?');

        if (($pos !== false) && ($pos >= 0))
            $request = substr($request, 0, $pos);

        $params = urlencode($_SERVER['QUERY_STRING']);

        return $protocol_link . Tools::getShopDomainSsl() . $request . '?' . $params;
    }

    public function getProductsList($categoryId)
    {
        $query = 'select id_category from ' . _DB_PREFIX_ . 'category where id_parent=' . $categoryId;
        $subCategories = Db::getInstance()->ExecuteS($query);
        $allSelectedCategories = array();
        foreach ($subCategories as $row) {
            $allSelectedCategories[] = $row['id_category'];
        }
        $categoryString = $categoryId;
        if (!empty($allSelectedCategories))
            $categoryString = implode(',', $allSelectedCategories) . ',' . $categoryId;
        $sql = 'select distinct(' . _DB_PREFIX_ . 'product_lang.id_product) as id_product,' . _DB_PREFIX_ . 'product_lang.name as name from ' . _DB_PREFIX_ . 'category_product join ' . _DB_PREFIX_ . 'product_lang on ' . _DB_PREFIX_ . 'category_product.id_product=' . _DB_PREFIX_ . 'product_lang.id_product join ' . _DB_PREFIX_ . 'product on ' . _DB_PREFIX_ . 'product_lang.id_product=' . _DB_PREFIX_ . 'product.id_product where ' . _DB_PREFIX_ . 'category_product.id_category in(' . $categoryString . ') and ' . _DB_PREFIX_ . 'product.active=1';
        $results = Db::getInstance()->ExecuteS($sql);
        if ($results)
            return $results;
        return array();
    }
}
