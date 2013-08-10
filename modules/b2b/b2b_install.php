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
 *  @version  Release: $Revision: 14390 $
 *  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

if (!defined('_PS_VERSION_'))
    exit;

class B2bInstall
{
    /**
     * Create B2B tables
     */
    public function createTables()
    {
        /* Set database */
        if (!Db::getInstance()->Execute('
		CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'b2b` (
			`id_b2b` int(10) unsigned NOT NULL AUTO_INCREMENT,
			`name` varchar(255) DEFAULT NULL,
			`email` varchar(255) DEFAULT NULL,
			`mobile` varchar(100) NOT NULL,
			`city` varchar(255) NOT NULL,
			`state` varchar(255) NOT NULL,
			`product` int(10) NOT NULL,
			`quantity` varchar(255) NOT NULL,
			`is_verified` BOOLEAN DEFAULT NULL,
			`mobileCode` varchar(50) DEFAULT NULL,
		    PRIMARY KEY (`id_b2b`)
		) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8 AUTO_INCREMENT=2')
        )
            return false;
    }


}
