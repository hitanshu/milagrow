<?php
/*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2013 PrestaShop SA
*  @version  Release: $Revision: 1.1 $
*
*  International Registered Trademark & Property of PrestaShop SA
*/

include_once(dirname(__FILE__).'/../../config/config.inc.php');
include_once(dirname(__FILE__).'/../../init.php');
include_once(_PS_MODULE_DIR_.'ccavenue/ccavenue.php');

$ccAvenue = new CCAvenue();
if ($ccAvenue->active)
	$ccAvenue->validation();
