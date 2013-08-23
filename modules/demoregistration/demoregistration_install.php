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

class DemoRegistrationInstall
{
    /**
     * Create B2B tables
     */
    public function createTables()
    {

        /* Set database */
        $sql = "CREATE TABLE IF NOT EXISTS " . _DB_PREFIX_ . "demos (
  `demos_id` int(10) NOT NULL AUTO_INCREMENT,
  `date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `amount` decimal(20,6) NOT NULL,
  `status` varchar(1000) DEFAULT NULL,
  `product` varchar(1000) DEFAULT NULL,
  `order_id` varchar(100) NOT NULL,
  `name` varchar(1000) DEFAULT NULL,
  `nb_order_no` varchar(1000) DEFAULT NULL,
  `special_comments` text DEFAULT NULL,
  `address` text,
  `country` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `zip` varchar(10) DEFAULT NULL,
  `mobile` varchar(100) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`demos_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
        if (!Db::getInstance()->Execute($sql)
        )
            return false;
    }


}