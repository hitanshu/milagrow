<?php
/**
 * Created by JetBrains PhpStorm.
 * User: keshav
 * Date: 7/8/13
 * Time: 3:38 PM
 * To change this template use File | Settings | File Templates.
 */
class WarrantySuccessModuleFrontController extends ModuleFrontController
{
    public $display_column_left = true;

    public function initContent()
    {
        parent::initContent();
        $this->setTemplate('success.tpl');
    }
}