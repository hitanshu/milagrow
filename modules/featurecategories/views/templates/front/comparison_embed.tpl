{include file="$tpl_dir./errors.tpl"}
<div class="page-title custom-page">
    <h1>{l s='Product Comparison' mod='featurecategories'}</h1>
</div>

<div class="fc_compare_container">
    <link type="text/css" rel="stylesheet"
          href="{$smarty.const._PS_JS_DIR_}../modules/featurecategories/views/css/comparison_style_embed.css"/>
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="http://code.jquery.com/jquery-migrate-1.2.1.js"></script>
    <script type="text/javascript"
            src="{$smarty.const._PS_JS_DIR_}../modules/featurecategories/views/js/jquery.qtip-1.0.0-rc3.min.js"></script>
    <script type="text/javascript"
            src="{$smarty.const._PS_JS_DIR_}../modules/featurecategories/views/js/comparison.js"></script>

    {if $products|@count > 0}
        <table class="fc_compare_table">
            <tr>
                {assign var=totalProducts value=count($products)}
                {if $totalProducts==1}
                    {assign var=width value='50%'}
                {elseif $totalProducts==2}
                    {assign var=width value='33%'}
                {elseif $totalProducts==3}
                    {assign var=width value='25%'}
                {elseif $totalProducts==4}
                    {assign var=width value='20%'}
                {else}
                    {assign var=width value=''}
                {/if}
                <td style=" width:{$width};text-align:center; border: none;">
                </td>
                {foreach from = $products item = product}
                    <td style="border: none; min-width: 100px;">
                        <div class="fc_image">
                            <a class="remove" href="{$product->id}"> </a>
                            <img src="{$link->getImageLink($product->link_rewrite, $product->id_image, 'medium_default')}"/>
                        </div>
                        <div class="fc_name">
                            <a href="{$link->getProductLink($product->id)}">{$product->name|escape:'htmlall':'UTF-8'|truncate:20:'...'} </a>
                        </div>
                        {*<a class="remove" href="{$product->id}"> </a>*}
                    </td>
                {/foreach}
            </tr>
            <tr class="fc_price_value">
                <td style="border: none;">&nbsp;</td>
                {foreach from = $products item = product}
                    <td class="price-block">{convertPrice price=$product->price+(($product->price*$product->tax_rate)/100)}</td>
                {/foreach}
            </tr>
            {assign var=cat_index value=0}
            {foreach from=$features_by_categories key=category item=feature_ids}
                <tr>
                    <td colspan={$product_count+1}  class="category" id="{$cat_index}
                    " {literal}onclick="handleCategoryClick($(this)){/literal}">
                    <img src="{$smarty.const._PS_JS_DIR_}../modules/featurecategories/views/img/collapse.png"
                         class="img_{$cat_index}"/>
                    {$category}
                    {if $helper->getDescription($category) !=null}
                        <img src="{$smarty.const._PS_JS_DIR_}../modules/featurecategories/views/img/question_icon.gif"
                             style="float:right; margin:3px 5px 0px 0px" class="question_image"/>
                        <div class="tooltip_val" style="display:none;">{$helper->getDescription($category)}</div>
                    {/if}
                    </td></tr>
                {foreach from = $feature_ids item = feature_id}
                    {if $helper->isNotEmptyValues($feature_id, $products) == true}
                        <tr class="fc_feature_value {$cat_index}">
                            <td>{$helper->getFeatureName({$feature_id})}
                                {assign var=productDescription value=$helper->getProductDescription($feature_id)}
                                {if $productDescription!=null}
                                    <img src="{$smarty.const._PS_JS_DIR_}../modules/featurecategories/views/img/question_icon.gif"
                                         style="float:right;margin:3px 5px 0px 0px" class="question_image"/>
                                    <div class="tooltip_val"
                                         style="display:none;">{$productDescription}</div>
                                {/if}
                            </td>
                            {foreach from = $products item = product}
                                <td>{$helper->getFeatureVal({$product->id},{$feature_id})}</td>
                            {/foreach}

                        </tr>
                    {/if}
                {/foreach}
                {assign var=cat_index value=$cat_index+1}
            {/foreach}
        </table>
    {else}
        {l s='No products to compare' mod='featurecategories'}
    {/if}
</div>