<?php
/**
 * Created by JetBrains PhpStorm.
 * User: keshav
 * Date: 6/8/13
 * Time: 5:15 PM
 * To change this template use File | Settings | File Templates.
 */
if (!defined('_PS_VERSION_'))
    exit;

class WarrantyInstall
{
    /**
     * Create B2B tables
     */
    public function createTables()
    {

        /* Set database */
        if (!Db::getInstance()->Execute('CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'product_registration` (
        `id_product_registration` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mobile` varchar(100) NOT NULL,
  `product` varchar(1000) DEFAULT NULL,
  `product_number` varchar(100) DEFAULT NULL,
  `date` date NOT NULL,
  `store_name` varchar(500) NOT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT \'0000-00-00 00:00:00\',
  PRIMARY KEY (`id_product_registration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=2;
')
        )
            return false;
    }


}