<html>
    <head>
        <title>
            {l s='Product Comparison' mod='featurecategories'}	
        </title>
        <link type="text/css" rel="stylesheet" href="{$smarty.const._PS_JS_DIR_}../modules/featurecategories/views/css/comparison_style.css" />
        <script type="text/javascript" src="{$smarty.const._PS_JS_DIR_}../modules/featurecategories/views/js/jquery-1.7.2.min.js" ></script>
        <script type="text/javascript" src="{$smarty.const._PS_JS_DIR_}../modules/featurecategories/views/js/jquery.qtip-1.0.0-rc3.min.js" ></script>
        <script type="text/javascript" src="{$smarty.const._PS_JS_DIR_}../modules/featurecategories/views/js/comparison.js" ></script>
    </head>
    <body>
        {if $products|@count > 0}
            <table border=1>
                <tr>
                    <td style="text-align:center;">{l s='Products' mod='featurecategories'}<br/>
                    </td>
                    {foreach from = $products item = product}
                        <td style="width:200px;"> 
                            <div class="image">
                                <img src="{$link->getImageLink($product->link_rewrite, $product->id_image, 'medium_default')}" />
                            </div>
                            <div class="name">
                                <a href="{$link->getProductLink($product->id)}">{$product->name|escape:'htmlall':'UTF-8'} </a>
                            </div>
                            <a class="remove" href="{$product->id}"> </a>
                        </td>
                    {/foreach}
                </tr>
                <tr class="price_value">
                    <td>Price</td>
                    {foreach from = $products item = product}
                        <td>{convertPrice price=$product->price}</td>
                    {/foreach}
                </tr>
                {assign var=cat_index value=0}
                {foreach from=$features_by_categories key=category item=feature_ids}
                    <tr><td colspan ={$product_count+1} class="category" id="{$cat_index}" {literal}onclick="handleCategoryClick($(this)){/literal}">
                            <img src="{$smarty.const._PS_JS_DIR_}../modules/featurecategories/views/img/collapse.png" class="img_{$cat_index}"/>
                            {$category}
                            {if $helper->getDescription($category) !=null}
                                <img src="{$smarty.const._PS_JS_DIR_}../modules/featurecategories/views/img/question_icon.gif" style="float:right; margin:3px 5px 0px 0px" class="question_image"/>
                                <div class="tooltip_val" style="display:none;">{$helper->getDescription($category)}</div>
                            {/if}
                        </td></tr>
                        {foreach from = $feature_ids item = feature_id}
                            {if $helper->isNotEmptyValues($feature_id, $products) == true}
                            <tr class="feature_value {$cat_index}" ><td>{$helper->getFeatureName({$feature_id})}</td>
                                {foreach from = $products item = product}
                                    <td>{$helper->getFeatureVal({$product->id},{$feature_id})}</td>
                                {/foreach}
                            {/if}
                        </tr>
                    {/foreach}
                    {assign var=cat_index value=$cat_index+1}
                {/foreach}
            </table>
        {else}
            {l s='No products to compare' mod='featurecategories'}	
        {/if}
    </body>
</html>