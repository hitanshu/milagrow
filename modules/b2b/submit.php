<?php

include_once(dirname(__FILE__) . '/../../../config/config.inc.php');
include_once(dirname(__FILE__) . '/../../../init.php');

$name = Tools::getValue('name');
$email = Tools::getValue('email');
$mobile = Tools::getValue('mobile');
$city = Tools::getValue('city');
$state = Tools::getValue('state');
$product = Tools::getValue('product');

if (empty($name) || empty($email) || empty($mobile) || empty($city) || empty($state) || empty($product)) {
    $url = B2b::getShopDomainSsl(true, true);
    $values = array('fc' => 'module', 'module' => 'b2b', 'controller' => 'init');

    Tools::redirectLink($url . '/index.php?' . http_build_query($values, '', '&'));
}