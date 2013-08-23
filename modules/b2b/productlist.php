<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hitanshu
 * Date: 19/8/13
 * Time: 12:31 PM
 * To change this template use File | Settings | File Templates.
 */

include_once(dirname(__FILE__) . '/../../config/config.inc.php');
include_once(dirname(__FILE__) . '/../../init.php');
include_once(_PS_MODULE_DIR_ . 'b2b/b2b.php');
$b2b = new B2b();
$category = Tools::getValue('category');
if (!empty($category))
    $products = $b2b->getProductsList($category);
$productsList = json_encode($products);
echo $productsList;
