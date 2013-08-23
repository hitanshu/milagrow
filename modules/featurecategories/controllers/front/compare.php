<?php
require_once(dirname(__FILE__) . '/../../Helper.php');
//parameter to control max products to compare
define('_MAX_PRODUCT_TO_COMPARE', 4);
class FeaturecategoriesCompareModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        if (Tools::getValue('action') == 'display')
            $this->displayComparison();
        else if (Tools::getValue('action') == 'show') {
            $this->returnCompareList();
            die();
        } else if (Tools::getValue('action') == 'add') {
            $status = $this->addToCompare(Tools::getValue('productId'));
            echo $status;
            exit;
        } else if (Tools::getValue('action') == 'delete') {
            $this->deleteFromCompare(Tools::getValue('productId'));
            die();
        } else if (Tools::getValue('action') == 'deleteall') {
            $this->deleteAll();
            die();
        }
    }

    private function addToCompare($productId)
    {
        if ($productId != NULL) {
            if ($this->context->cookie->comparison == NULL || $this->context->cookie->comparison == '') {
                $this->context->cookie->comparison = $productId;
                return 1;
            } else {
                $inCompareArr = explode(',', $this->context->cookie->comparison);
                if (!in_array($productId, $inCompareArr)) {
                    //checking if count of already existing products to compare is less than equal to max value
                    if (count($inCompareArr) < _MAX_PRODUCT_TO_COMPARE) {
                        //checking if product added is of same category or different
                        $query = 'Select id_product,id_category from ' . _DB_PREFIX_ . 'category_product where id_product in (' . $this->context->cookie->comparison . ',' . $productId . ') and id_category!=2';
                        if ($results = Db::getInstance()->ExecuteS($query)) {
                            $existingProductCategoriesIds = array();
                            $newProductCategoriesIds = array();
                            foreach ($results as $row) {
                                if (in_array($row['id_product'], $inCompareArr))
                                    $existingProductCategoriesIds[] = $row['id_category'];
                                if ($row['id_product'] == $productId)
                                    $newProductCategoriesIds[] = $row['id_category'];
                            }
                            if (count(array_intersect($existingProductCategoriesIds, $newProductCategoriesIds)) > 0) {
                                $this->context->cookie->comparison .= ',' . $productId;
                                return 1;
                            }
                            return 3;
                        }
                    }

                    return 0;

                }

                return 1;
            }
        }
    }


    private function deleteFromCompare($productId)
    {
        $productIds = explode(',', $this->context->cookie->comparison);
        if (in_array($productId, $productIds))
            $productIds = array_diff($productIds, array($productId));
        $strIds = implode(",", $productIds);
        $this->context->cookie->comparison = $strIds;
        $this->returnCompareList();
    }

    private function deleteAll()
    {
        $this->context->cookie->comparison = '';
        $this->returnCompareList();
    }

//    private function returnCompareList()
//    {
//        $mode = Configuration::get('FEATURECATEGORIES_DISP_MODE');
//        if ($this->context->cookie->comparison != "") {
//            $productIds = explode(',', $this->context->cookie->comparison);
//            $html = '<ul class="compare_list">';
//            foreach ($productIds as $id) {
//                $p = new Product($id);
//                $cover = Product::getCover((int)$id);
//                $imagePath = $this->context->link->getImageLink($p->link_rewrite, $cover['id_image'], 'medium_default');
//                $html .= '<li><img src="' . $imagePath . '"/><span><a href="' . $this->context->link->getProductLink($id) . '">' . $p->name[1] . '</a></span><span><a onclick ="deleteItem($(this));return false;" href ="' . __PS_BASE_URI__ . 'modules/featurecategories/compare.php?action=delete&productId=' . $id . '" class="delete_link">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a></span></li>';
//            }
//            $html .= '</ul>
//		<table style="width:100%"><tr><td><a class="exclusive_small" href = "' . __PS_BASE_URI__ . 'index.php?fc=module&module=featurecategories&controller=compare&action=display"' . ($mode == 'separate' ? 'target="_blank"' : '') . '>Compare</a></td>
//		<td style="float:right"><a onclick ="deleteAllItems();return false;" class="button_small" >Clear</a></td></tr></table>';
//        } else
//            $html = "There is no products to compare";
//        echo $html;
//    }

    private function returnCompareList()
    {
        $mode = Configuration::get('FEATURECATEGORIES_DISP_MODE');
        if ($this->context->cookie->comparison != "") {
            $productIds = explode(',', $this->context->cookie->comparison);
            $html = '<div class="line compare-cart-wrapper">
            <div class="compare-items">';
            foreach ($productIds as $id) {
                $p = new Product($id);
                $cover = Product::getCover((int)$id);
                $imagePath = $this->context->link->getImageLink($p->link_rewrite, $cover['id_image'], 'medium_default');
                $html .= '<div class="compare-item"><div class="thumb_holder"><img src="' . $imagePath . '"/></div><a class="description" href="' . $this->context->link->getProductLink($id) . '">' . $p->name[1] . '</a><span class="delete" title="remove"><a onclick ="deleteItem($(this));return false;" href ="' . __PS_BASE_URI__ . 'modules/featurecategories/compare.php?action=delete&productId=' . $id . '" class="delete_link">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a></span></div>';
            }
            $leftProducts = _MAX_PRODUCT_TO_COMPARE - count($productIds);
            if ($leftProducts) {
                for ($i = 0; $i < $leftProducts; $i++) {
                    $html .= '<div class="compare-item empty_item">
                        <div class="thumb_holder">
                          </div>
                        <p class="description">Add Another Item</p>

                    </div>';
                }
            }

            $html .= '</div><div class="compare-controls">
                     <a class="exclusive_small btn" href = "' . __PS_BASE_URI__ . 'index.php?fc=module&module=featurecategories&controller=compare&action=display"' . ($mode == 'separate' ? 'target="_blank"' : '') . '>Compare</a>
                                <span class="close" title="Close" onclick="deleteAllItems(); return false;"></span>

            </div>
            <div class="clear"></div>';
        } else
            $html = "";
        echo $html;
    }

    public function displayComparison()
    {
        $mode = Configuration::get('FEATURECATEGORIES_DISP_MODE');
        if ($mode == 'inside')
            parent::initContent();
        $productIds = explode(',', $this->context->cookie->comparison);
        $features_by_categories = array();
        $distributed_features = array();
        $all_feature_ids = array();

        $query = 'SELECT DISTINCT cf.category_id, c.name FROM ' . _DB_PREFIX_ . '_fc_categories_features cf
		LEFT JOIN ' . _DB_PREFIX_ . '_fc_categories c ON (c.category_id = cf.category_id) 
		WHERE cf.feature_id IN 
		(SELECT id_feature FROM ' . _DB_PREFIX_ . 'feature_product WHERE 0';
        foreach ($productIds as $product_id) {
            if ($product_id != '')
                $query .= ' OR id_product=' . $product_id;
        }
        $query .= ') ORDER BY c.priority';
        $product_feature_categories = Db::getInstance()->ExecuteS($query);
        foreach ($product_feature_categories as $pfc) {
            $feature_ids = array();
            $raw_features = Db::getInstance()->ExecuteS('SELECT cf.feature_id FROM ' . _DB_PREFIX_ . '_fc_categories_features cf LEFT JOIN ' . _DB_PREFIX_ . 'feature f ON(f.id_feature = cf.feature_id) WHERE cf.category_id=' . $pfc['category_id'] . ' ORDER BY f.position');
            foreach ($raw_features as $fid) {
                $feature_ids[] = $fid['feature_id'];
                $distributed_features[] = $fid['feature_id'];
            }
            $features_by_categories[$pfc['name']] = $feature_ids;
        }

        $helper = new Helper();

        $products = array();
        foreach ($productIds as $id) {
            if ($id != '') {
                $raw_all_ids = Db::getInstance()->ExecuteS('SELECT id_feature FROM ' . _DB_PREFIX_ . 'feature_product WHERE id_product = ' . $id);
                foreach ($raw_all_ids as $raw_id) {
                    if (!in_array($raw_id['id_feature'], $all_feature_ids))
                        $all_feature_ids[] = $raw_id['id_feature'];
                }
                $cover = Product::getCover((int)$id);
                $product = new Product($id, true, intval($this->context->cookie->id_lang));
                $product->id_image = Tools::htmlentitiesUTF8(Product::defineProductImage(array('id_image' => $cover['id_image'], 'id_product' => $id), $this->context->cookie->id_lang));
                $products[] = $product;
            }
        }
        $not_distributed_features = array_diff($all_feature_ids, $distributed_features);
        if (count($not_distributed_features) > 0)
            $features_by_categories['Other Features'] = $not_distributed_features;
        $count = count($productIds);
        $this->context->smarty->assign(array('features_by_categories' => $features_by_categories, 'helper' => $helper, 'products' => $products, 'product_count' => $count));
        if ($mode == 'inside')
            $this->setTemplate('comparison_embed.tpl');
        else {
            $this->context->smarty->display(dirname(__FILE__) . '/../../views/templates/front/comparison.tpl');
            die();
        }

    }

}