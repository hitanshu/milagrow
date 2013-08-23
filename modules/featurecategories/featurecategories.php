<?php

if (!defined('_PS_VERSION_'))
    exit;

class FeatureCategories extends Module
{

    private $html = '';

    public function __construct()
    {
        $this->name = 'featurecategories';
        $this->tab = 'smart_shopping';
        $this->version = 1.4;
        $this->author = 'Ssoft';
        $this->module_key = 'a2b7cee1897e09a7783e7d1fa5738873';
        $this->need_instance = 0;
        parent::__construct();
        $this->displayName = $this->l('Features by Categories and Product Comparison');
        $this->description = $this->l('Enables you to create categories for your product features and creates new products comparison page');
    }

    public function install()
    {
        if (parent::install() == false OR !$this->registerHook('productTab') OR !$this->registerHook('productTabContent')
            OR !$this->registerHook('leftColumn') OR !$this->registerHook('extraLeft') OR !$this->registerHook('header')
        )
            return false;
        Db::getInstance()->Execute('CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . '_fc_categories ( `category_id` int NOT NULL AUTO_INCREMENT, `name` varchar(250) UNIQUE, `priority` int NOT NULL DEFAULT 1, `description` varchar(512) NULL, PRIMARY KEY (`category_id`))	default CHARSET=utf8');
        Db::getInstance()->Execute('CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . '_fc_categories_features ( `category_id` int not null, `feature_id` int not null)	default CHARSET=utf8');
        Db::getInstance()->Execute('CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . '_fc_features_description ( `feature_id` int not null,`product_description` text NULL)	default CHARSET=utf8');
        Configuration::updateValue('FEATURECATEGORIES_DISP_MODE', 'separate');
        return true;
    }

    public function uninstall()
    {
        if (parent::uninstall() == false)
            return false;
        return true;
    }

    public function hookProductTab($params)
    {
        $product_id = intval($_GET['id_product']);
        $product = new Product($product_id);
        if (count($product->getFeatures()) > 0)
            return '<li><a href="#idTabFeatures" id="more_info_tab_features_by_category">' . $this->l('Specifications') . '</a></li>';
    }

    public function hookProductTabContent($params)
    {
        $product_id = intval($_GET['id_product']);
        if ($product_id < 1)
            return;
        $product_feature_categories = Db::getInstance()->ExecuteS('SELECT DISTINCT cf.category_id, c.name FROM ' . _DB_PREFIX_ . '_fc_categories_features cf
		LEFT JOIN ' . _DB_PREFIX_ . '_fc_categories c ON (c.category_id = cf.category_id) 
		WHERE cf.feature_id IN 
		(SELECT id_feature FROM ' . _DB_PREFIX_ . 'feature_product WHERE id_product=' . $product_id . ') ORDER BY c.priority');
        $output = array();
        $cat_ids = array();
        foreach ($product_feature_categories as $cat) {
            $feature_names = Db::getInstance()->ExecuteS('SELECT fl.name, fvl.value,f.id_feature  FROM ' . _DB_PREFIX_ . 'feature_lang fl
			LEFT JOIN ' . _DB_PREFIX_ . 'feature_product fp ON (fl.id_feature = fp.id_feature AND fp.id_product = ' . $product_id . ') 
			LEFT JOIN ' . _DB_PREFIX_ . 'feature_value_lang fvl ON (fp.id_feature_value = fvl.id_feature_value AND fvl.id_lang = ' . intval($this->context->language->id) . ')
                        LEFT JOIN ' . _DB_PREFIX_ . 'feature f ON(f.id_feature = fl.id_feature)
			WHERE fl.id_lang=' . intval($this->context->language->id) . ' AND fl.id_feature IN
			(SELECT feature_id FROM ' . _DB_PREFIX_ . '_fc_categories_features WHERE category_id=' . $cat['category_id'] . ') ORDER BY f.position');
            $output[$cat['name']] = $feature_names;
            $cat_ids[] = $cat['category_id'];
        }
//put uncategorized features into the "Other features" dummy category
        $distributed_feature_ids = array();
        $all_feature_ids = array();
        $not_distributed_feature_ids = array();
        $not_distributed_feature_n_v = array();
        foreach ($cat_ids as $cat_id) {
            $raw_ids = Db::getInstance()->ExecuteS('SELECT feature_id FROM ' . _DB_PREFIX_ . '_fc_categories_features WHERE category_id =' . $cat_id);
            foreach ($raw_ids as $raw_id) {
                $distributed_feature_ids[] = $raw_id['feature_id'];
            }
        }
        $raw_all_ids = Db::getInstance()->ExecuteS('SELECT id_feature FROM ' . _DB_PREFIX_ . 'feature_product WHERE id_product = ' . $product_id);
        foreach ($raw_all_ids as $raw_id) {
            $all_feature_ids[] = $raw_id['id_feature'];
        }
        $not_distributed_feature_ids = array_diff($all_feature_ids, $distributed_feature_ids);
        if (count($not_distributed_feature_ids) > 0) {
            foreach ($not_distributed_feature_ids as $id) {
                $res = Db::getInstance()->ExecuteS('SELECT fl.name, fvl.value,f.id_feature FROM ' . _DB_PREFIX_ . 'feature_lang fl
				LEFT JOIN ' . _DB_PREFIX_ . 'feature_product fp ON (fl.id_feature = fp.id_feature AND fp.id_product = ' . $product_id . ') 
				LEFT JOIN ' . _DB_PREFIX_ . 'feature_value_lang fvl ON (fp.id_feature_value = fvl.id_feature_value AND fvl.id_lang = ' . intval($this->context->language->id) . ')
                                LEFT JOIN ' . _DB_PREFIX_ . 'feature f ON(f.id_feature = fl.id_feature)
				WHERE fl.id_lang=' . intval($this->context->language->id) . ' AND fl.id_feature =' . $id . ' ORDER BY f.position');
                $not_distributed_feature_n_v = array_merge($not_distributed_feature_n_v, $res);
            }
            $output[$this->l('Other Features')] = $not_distributed_feature_n_v;
        }
        require_once(_PS_ROOT_DIR_ . '/modules/featurecategories/Helper.php');
        $helper = new Helper();
        $this->context->smarty->assign(array('fc_features' => $output, 'helper' => $helper));
        return $this->display(__FILE__, 'featurestabcontent.tpl');
    }

    public function hookExtraLeft($params)
    {
        return "<li class='fc_btn_compare'><a href=\"#\" class='fc_btn_compare' name=" . $_GET['id_product'] . " >" . $this->l('Add To Compare') . "</a></li>";
    }

    public function hookExtraRight($params)
    {
        return "<a href=\"#\" class='fc_btn_compare button' name=" . $_GET['id_product'] . " >" . $this->l('Add To Compare') . "</a>";
    }

    public function hookLeftColumn($params)
    {
        $html = '<div id="categories_block_left" class="block">
		<h4>' . $this->l('Product Comparison') . '</h4>
        <div class="block_content" id="fc_comparison">'
            . $this->l('There is no products to compare') .
            '</div>
    </div>';
        return $html;
    }

    public function hookRightColumn($params)
    {
        return $this->hookLeftColumn($params);
    }

    public function hookHeader($params)
    {
        $this->context->controller->addCSS($this->_path . 'views/css/style.css', 'all');
        $this->context->controller->addJS($this->_path . 'views/js/compare.js');
        $this->context->controller->addJS($this->_path . 'views/js/jquery.qtip-1.0.0-rc3.min.js');

    }

    public function getContent()
    {
        $this->html = '<h2>' . $this->displayName . '</h2>';
        $this->html .= '<link media="all" type="text/css" rel="stylesheet" href="' . $this->_path . 'views/css/style.css"/>';
        if (Tools::getValue('saveCategory') != NULL || Tools::getValue('editCategory') != NULL || Tools::getValue('editFeatureDescription') != NULL) {
            $this->html .= '<p style="color:red">' . $this->postProcess() . '</p>';
            $_GET['fc_tab'] = 'category'; //redirect to overview after post action
            if (Tools::getValue('editFeatureDescription'))
                $_GET['fc_tab'] = 'list_feature_description';

        } else if (Tools::getValue('submitSettings') != NULL) {
            $this->html .= '<p style="color:red">' . $this->postProcess() . '</p>';
            $this->html .= $this->displayConfirmation($this->l('Settings updated'));
        }

        $url = $_SERVER['REQUEST_URI'];
        if (strpos($url, '&fc_tab') > 0)
            $url = substr($url, 0, strpos($url, '&fc_tab'));

        $this->html .= '<ul><li ' . (Tools::getValue('fc_tab') == "category" || Tools::getValue('fc_tab') == NULL || Tools::getValue('fc_tab') == '' ? 'class="fc_admin_tab_active"' : 'class="fc_admin_tab"') . '><a style="padding:8px 8px 4px 4px;" href="' . $url . '&fc_tab=\'\'" id = "fc_categories">' . $this->l('Categories') . '</a></li>
		<li ' . (Tools::getValue('fc_tab') == "create_category" ? 'class="fc_admin_tab_active"' : 'class="fc_admin_tab"') . '><a style="padding:8px 8px 4px 4px;" href="' . $url . '&fc_tab=create_category" id = "fc_create_category">' . $this->l('Create Category') . '</a></li>
            <li ' . (Tools::getValue('fc_tab') == "list_feature_description" ? 'class="fc_admin_tab_active"' : 'class="fc_admin_tab"') . '><a style="padding:8px 8px 4px 4px;" href="' . $url . '&fc_tab=list_feature_description" id = "list_feature_description">' . $this->l('List Feature Description') . '</a></li>' .
            '<li ' . (Tools::getValue('fc_tab') == "settings" ? 'class="fc_admin_tab_active"' : 'class="fc_admin_tab"') . '><a style="padding:8px 8px 4px 4px;" href="' . $url . '&fc_tab=settings" id = "fc_settings">' . $this->l('Settings') . '</a></li></ul>';
        if (Tools::getValue('fc_tab') == 'create_category')
            $this->html .= $this->displayCreateCategory();
        else if (Tools::getValue('fc_tab') == 'edit_category') {
            $categoryId = Tools::getValue('id');
            $this->html .= $this->displayEditCategory($categoryId, $url);
        } else if (Tools::getValue('fc_tab') == 'delete_category') {
            $categoryId = Tools::getValue('id');
            $this->html .= $this->deleteCategory($categoryId, $url);
        } elseif (Tools::getValue('fc_tab') == 'settings') {
            $this->html .= $this->displaySettings();
        } elseif (Tools::getValue('fc_tab') == 'list_feature_description') {
            $this->html .= $this->listFeatureDescription($url);
        } elseif (Tools::getValue('fc_tab') == 'edit_feature_description') {
            $this->html .= $this->editFeatureDescription(Tools::getValue('feature_id'), $url);
        } else
            $this->html .= $this->displayCategories($url);
        return $this->html;
    }

    private function displayCategories($url)
    {
        $categories = Db::getInstance()->ExecuteS('SELECT * FROM ' . _DB_PREFIX_ . '_fc_categories');
        $txt = '<fieldset style="margin-top:80px;"><legend>' . $this->l('Categories Overview') . '</legend>';
        if (count($categories) > 0) {
            $txt .= '<table border="1" class="table" style="width:100%; border-collapse:collapse;"><tr><th>' . $this->l('Category Name') .
                '</th><th>' . $this->l('Features') . '</th><th>' . $this->l('Priority') . '</th><th>' . $this->l('Actions') . '</th></tr>';
            foreach ($categories as $cat) {
                $features = Db::getInstance()->ExecuteS('SELECT name FROM ' . _DB_PREFIX_ . 'feature_lang WHERE id_lang = 1 AND id_feature IN 
				(SELECT feature_id FROM ' . _DB_PREFIX_ . '_fc_categories_features WHERE category_id = ' . $cat['category_id'] . ')');
                $txt .= '<tr><td>' . $cat['name'] . '</td><td>';
                foreach ($features as $feature) {
                    $txt .= $feature['name'] . '<br/>';
                }
                $txt .= '</td><td>' . $cat['priority'] . '</td><td><a style="text-decoration:underline; color:#0000FF;" href="' .
                    $url . '&fc_tab=edit_category&id=' . $cat['category_id'] . '">' . $this->l('Edit') . '</a> |
				<a onclick="return confirm(\'' . $this->l('Are you shure?') . '\') ? true : false;" style="text-decoration:underline; color:#0000FF;" href="' . $url . '&fc_tab=delete_category&id=' . $cat['category_id'] . '">' . $this->l('Delete') . '</a></td></tr>';
            }
            $txt .= '</table>';
        } else {
            $txt .= '<p>' . $this->l('You have no categories') . '</p>';
        }
        $txt .= '</fieldset>';
        return $txt;
    }

    private function displayCreateCategory()
    {
        $features = $this->getUnassignedFeatures();
        $txt = '<fieldset style="margin-top:80px;"><legend>' . $this->l('Create Category') . '</legend>';
        $txt .= '<form action="' . $_SERVER['REQUEST_URI'] . '" method="post">';
        $txt .= '<label>' . $this->l('Category Name') . '</label>
                <div class="margin-form"><input type="text" name="name" value="' . (Tools::getValue('name', '')) . '" style="width:60%;"/></div>';
        $txt .= '<label>' . $this->l('Features') . '</label><div class="margin-form"><select name="features[]" id="features" size="15" style="width:200px" multiple="multiple">';
        foreach ($features as $feature) {
            $txt .= '<option value="' . $feature['id_feature'] . '">' . $feature['name'] . '</option>';
        }
        $txt .= '</select><p class="clear">' . $this->l('Ctrl+click to select features for the category') . '</p></div>';
        $txt .= '<label>' . $this->l('Priority') . '</label><div class="margin-form"><select name="priority" id="priority" style="width:200px" >';
        foreach (range(1, 50) as $val) {
            $txt .= '<option value="' . $val . '">' . $val . '</option>';
        }
        $txt .= '</select><p class="clear">' . $this->l('Defines the order of categories') . '</p></div>';
        $txt .= '<label>' . $this->l('Category Description') . '</label>
            <div class="margin-form"><textarea rows="5" name="description" style="width:60%;"></textarea>
			<p class="clear">' . $this->l('500 characters maximum') . '</p></div>';
        $txt .= '<input type="submit" name="saveCategory" value="' . $this->l('Save') . '" class="button" />';
        $txt .= '</form></fieldset>';

        return $txt;
    }

    private function displayEditCategory($id, $url)
    {
        $category = Db::getInstance()->ExecuteS('SELECT * FROM ' . _DB_PREFIX_ . '_fc_categories WHERE category_id =' . $id);
        $category = $category[0];
        $feature_ids_raw = Db::getInstance()->ExecuteS('SELECT feature_id FROM ' . _DB_PREFIX_ . '_fc_categories_features WHERE category_id =' . $id);
        $displayFeatures = $this->getUnassignedAndCategoryFeatures($id);
        $feature_ids = array();
        foreach ($feature_ids_raw as $f_id_raw) {
            $feature_ids[] = $f_id_raw['feature_id'];
        }
        $txt = '<fieldset style="margin-top:80px;"><legend>' . $this->l('Edit Category') . '</legend>';
        $txt .= '<form action="' . $_SERVER['REQUEST_URI'] . '" method="post">';
        $txt .= '<label>' . $this->l('Category Name') . '</label>
                <div class="margin-form"><input type="text" name="name" value="' . $category['name'] . '" style="width:60%;"/></div>';
        $txt .= '<label>' . $this->l('Features') . '</label><div class="margin-form"><select name="features[]" id="features" size="15" style="width:200px" multiple="multiple">';
        foreach ($displayFeatures as $feature) {
            $txt .= '<option value="' . $feature['id_feature'] . '"' . (in_array($feature['id_feature'], $feature_ids) ? 'selected="selected"' : '') . '>' . $feature['name'] . '</option>';
        }
        $txt .= '</select><p class="clear">' . $this->l('Ctrl+click to select features for the category') .
            '</p><button onclick="$(\'#features option:selected\').removeAttr(\'selected\');return false;">Clear Attributes</button></div>';
        $txt .= '<label>' . $this->l('Priority') . '</label><div class="margin-form"><select name="priority" id="priority" style="width:200px" >';
        foreach (range(1, 50) as $val) {
            $txt .= '<option value="' . $val . '" ' . ($category['priority'] == $val ? 'selected="selected"' : '') . '>' . $val . '</option>';
        }
        $txt .= '</select><p class="clear">' . $this->l('Defines the order of categories') . '</p></div>';
        $txt .= '<label>' . $this->l('Category Description') . '</label>
            <div class="margin-form"><textarea rows="5" name="description" style="width:60%;">' . $category['description'] . '</textarea>';
        $txt .= '<p class="clear">' . $this->l('500 characters maximum') . '</p></div>';
        $txt .= '<input type="hidden" name="id" value="' . $id . '"/>';
        $txt .= '<input type="submit" name="editCategory" value="' . $this->l('Save') . '" class="button" />';
        $txt .= '</form></fieldset>';
        return $txt;
    }

    private function displaySettings()
    {
        $txt = '<fieldset style="margin-top:80px;"><legend>' . $this->l('Settings') . '</legend>
        <form action="' . $_SERVER['REQUEST_URI'] . '" method="post">
	<label>' . $this->l('Display Comparison Table') . '</label>
	<div class="margin-form">
	<input type="radio" name="display_mode" id="separate" value="separate" ' . (Tools::getValue('display_mode', Configuration::get('FEATURECATEGORIES_DISP_MODE')) == 'separate' ? 'checked="checked" ' : '') . '/>
	<label class="t" for="separate">' . $this->l('New Tab') . '</label><br/>
	<input type="radio" name="display_mode" id="inside" value="inside" ' . (Tools::getValue('display_mode', Configuration::get('FEATURECATEGORIES_DISP_MODE')) == 'inside' ? 'checked="checked" ' : '') . '/>
	<label class="t" for="inside"> ' . $this->l('Central Column of the Site') . '</label>
	</div><input type="submit" name="submitSettings" value="' . $this->l('Save') . '" class="button" /></form></fieldset>';
        return $txt;
    }

    private function listFeatureDescription($url)
    {
        $categories = Db::getInstance()->ExecuteS('SELECT * FROM ' . _DB_PREFIX_ . '_fc_categories');
        $txt = '<fieldset style="margin-top:80px;"><legend>' . $this->l('Feature Description List') . '</legend>';
        if (count($categories) > 0) {
            $txt .= '<table border="1" class="table" style="width:100%; border-collapse:collapse;"><tr><th>' . $this->l('Category Name') .
                '</th><th>' . $this->l('Features') . '</th></tr>';
            foreach ($categories as $cat) {
                $query = 'SELECT name, product_description,' . _DB_PREFIX_ . '_fc_categories_features.feature_id from ' . _DB_PREFIX_ . '_fc_categories_features left join ' . _DB_PREFIX_ . 'feature_lang on ' . _DB_PREFIX_ . '_fc_categories_features.feature_id=' . _DB_PREFIX_ . 'feature_lang.id_feature left join ' . _DB_PREFIX_ . '_fc_features_description on ' . _DB_PREFIX_ . 'feature_lang.id_feature=' . _DB_PREFIX_ . '_fc_features_description.feature_id WHERE id_lang = 1 and category_id = ' . $cat['category_id'] . ' group by name,' . _DB_PREFIX_ . '_fc_categories_features.feature_id';
                $features = Db::getInstance()->executeS($query);
                $txt .= '<tr><td>' . $cat['name'] . '</td><td style="width:50%"><table border="1" class="table" style="width:100%; border-collapse:collapse;">';
                foreach ($features as $feature) {
                    $txt .= '<tr><td>' . $feature['name'] . '</td><td>' . $feature['product_description'] . '</td><td><a href="' . $url . '&fc_tab=edit_feature_description&feature_id=' . $feature['feature_id'] . '">' . $this->l('Edit') . '</a></td></tr>';
                }
                $txt .= '</table></td>';
            }
            $txt .= '</table>';
        } else {
            $txt .= '<p>' . $this->l('No Data Found') . '</p>';
        }
        $txt .= '</fieldset>';
        return $txt;
    }

    private function editFeatureDescription($id, $url)
    {
        $query = 'SELECT name,product_description,' . _DB_PREFIX_ . '_fc_categories_features.feature_id from ' . _DB_PREFIX_ . '_fc_categories_features left join ' . _DB_PREFIX_ . 'feature_lang on ' . _DB_PREFIX_ . '_fc_categories_features.feature_id=' . _DB_PREFIX_ . 'feature_lang.id_feature left join ' . _DB_PREFIX_ . '_fc_features_description on ' . _DB_PREFIX_ . 'feature_lang.id_feature=' . _DB_PREFIX_ . '_fc_features_description.feature_id WHERE id_lang = 1 and ' . _DB_PREFIX_ . '_fc_categories_features.feature_id=' . $id;
        $feature = Db::getInstance()->getRow($query);
        $txt = '<fieldset style="margin-top:80px;"><legend>' . $this->l('Edit Category') . '</legend>';
        if ($feature) {
            $txt .= '<form action="' . $_SERVER['REQUEST_URI'] . '" method="post">';
            $txt .= '<label>' . $this->l('Feature Name') . '</label>
                <div class="margin-form"><input type="text" readonly name="name" value="' . $feature['name'] . '" style="width:60%;"/><input type="hidden" name="feature_id" value="' . $feature['feature_id'] . '"></div>';
            $txt .= '<label>' . $this->l('Feature Description') . '</label>';
            $txt .= '<textarea name="description" rows=5 column=10>' . $feature['product_description'] . '</textarea>';
            $txt .= '<br/><input type="submit" name="editFeatureDescription" value="' . $this->l('Update') . '" class="button" /></form>';
        } else {
            $txt .= '<p>' . $this->l('No Data Found') . '</p>';
        }
        $txt .= '</fieldset>';
        return $txt;
    }

    private function deleteCategory($id, $url)
    {
        $id = intval($id);
        Db::getInstance()->Execute('DELETE FROM ' . _DB_PREFIX_ . '_fc_categories WHERE category_id = ' . $id);
        Db::getInstance()->Execute('DELETE FROM ' . _DB_PREFIX_ . '_fc_categories_features WHERE category_id = ' . $id);
        return $this->displayCategories($url);
    }

    private function getUnassignedFeatures()
    {
        $result = Db::getInstance()->ExecuteS('SELECT id_feature, name FROM ' . _DB_PREFIX_ . 'feature_lang WHERE id_lang = ' . $this->context->language->id . ' AND id_feature NOT IN
        (SELECT feature_id FROM ' . _DB_PREFIX_ . '_fc_categories_features)');
        return $result;
    }

    private function getUnassignedAndCategoryFeatures($categoryId)
    {
        $result = Db::getInstance()->ExecuteS('SELECT id_feature, name FROM ' . _DB_PREFIX_ . 'feature_lang WHERE id_lang = ' . $this->context->language->id . ' AND id_feature NOT IN
(SELECT feature_id FROM ' . _DB_PREFIX_ . '_fc_categories_features WHERE category_id != ' . $categoryId . ')');
        return $result;
    }

    private function postProcess()
    {
        if (Tools::getValue('saveCategory') != NULL) { //save new category
            //save category in fc_categories
            if (!Db::getInstance()->Execute('INSERT INTO ' . _DB_PREFIX_ . '_fc_categories (name, priority, description) VALUES ("' . Tools::getValue('name') . '", ' . Tools::getValue('priority') . ', "' . Tools::getValue('description') . '")'))
                return $this->l('Cannot create category. Category with this name already exists.');
            $new_id = Db::getInstance()->Insert_ID();
            //save category-feature relations in fc_categories_features
            if (Tools::getValue('features') != NULL) {
                $query = 'INSERT INTO ' . _DB_PREFIX_ . '_fc_categories_features (category_id, feature_id) VALUES ';
                foreach (Tools::getValue('features') as $feature_id)
                    $query .= '(' . $new_id . ', ' . $feature_id . '),';
                $query = substr($query, 0, -1);
                Db::getInstance()->Execute($query);
            }
        } else if (Tools::getValue('editCategory') != NULL) { //edit category
            Db::getInstance()->update('_fc_categories', array('name' => Tools::getValue('name'), 'priority' => Tools::getValue('priority'), 'description' => pSQL(Tools::getValue('description'))), "category_id=" . Tools::getValue('id'));
            $affectedRows = Db::getInstance()->Affected_Rows();
            if (!$affectedRows)
                return $this->l('Cannot update category. Category with this name already exists.');

            Db::getInstance()->Execute('DELETE FROM ' . _DB_PREFIX_ . '_fc_categories_features WHERE category_id = ' . Tools::getValue('id'));
            if (Tools::getValue('features') != NULL) {
                $query = 'INSERT INTO ' . _DB_PREFIX_ . '_fc_categories_features (category_id, feature_id) VALUES ';
                foreach (Tools::getValue('features') as $feature_id)
                    $query .= '(' . Tools::getValue('id') . ', ' . $feature_id . '),';
                $query = substr($query, 0, -1);
                Db::getInstance()->Execute($query);
            }
        } else if (Tools::getValue('editFeatureDescription') != NULL) {

            //edit description feature
            $row = Db::getInstance()->getRow('Select * from ' . _DB_PREFIX_ . '_fc_features_description where feature_id=' . Tools::getValue('feature_id'));
            if ($row) {
                Db::getInstance()->update('_fc_features_description', array('product_description' => pSQL(Tools::getValue('description'))), "feature_id=" . Tools::getValue('feature_id'));
                $affectedRows = Db::getInstance()->Affected_Rows();
                if (!$affectedRows)
                    return $this->l('Cannot update some error occured');
            } else {
                Db::getInstance()->insert('_fc_features_description', array(
                    'feature_id' => Tools::getValue('feature_id'),
                    'product_description' => Tools::getValue('description')
                ));
            }
        } else if (Tools::getValue('submitSettings') != NULL) { //save settings
            Configuration::updateValue('FEATURECATEGORIES_DISP_MODE', Tools::getValue('display_mode'));
        }
    }

}
