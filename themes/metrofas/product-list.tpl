{*
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
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

{if isset($products)}
    <!-- Products list -->
    <div id="products_wrapper">
        {*<script type="text/javascript">*}
            {**}


    {*jQuery(document).ready(function($){*}
  {*$('.link-compare').click(function(){*}
		{*var idProduct = $(this).attr('id').replace('comparator_item_', '');*}
			{*$.ajax({*}
	  			{*url: 'index.php?controller=products-comparison&ajax=1&action=add&id_product=' + idProduct,*}
	 			{*async: true,*}
                            {*dataType: "json",*}
                            {*success: function(responseData) {*}
                                        {*if(responseData==0){   *}
                                        {**}
                                                {*jQuery('body').append('<div class="alert"></div>');*}
                                                {*var $alert = jQuery('.alert');*}
                                                {*$alert.fadeIn(400);*}
                                               {*var message="<p>{l s='Allready added max value products!'}</p>";*}
                                             {*$alert.html(message);*}
                                                {*$alert.fadeIn('400', function () {*}
                                                    {*setTimeout(function () {*}
                                                        {*$alert.fadeOut('400', function () {*}
                                                            {*jQuery(this).fadeOut(400, function(){ jQuery(this).detach(); })*}
                                                        {*});*}
                                                    {*}, 7000)*}
                                                {*});*}

                                {**}
                                    {*}*}
                                    {*else{*}
                                      {*jQuery('body').append('<div class="alert"></div>');*}
                                                {*var $alert = jQuery('.alert');*}
                                                {*$alert.fadeIn(400);*}
                                               {*var message="<p>{l s='Compare list Added Successfully!'}</p>";*}
                                            {*$alert.html(message);*}
                                                {*$alert.fadeIn('400', function () {*}
                                                    {*setTimeout(function () {*}
                                                        {*$alert.fadeOut('400', function () {*}
                                                            {*jQuery(this).fadeOut(400, function(){ jQuery(this).detach(); })*}
                                                        {*});*}
                                                    {*}, 7000)*}
                                                {*});*}
                               {**}
                                {*}*}
                            {*},*}
                            {*error: function(XMLHttpRequest, textStatus, errorThrown) {*}
                               {**}
                            {*}*}
		{*});	*}
	{*});*}

{*$(".link-compare").click(function(e) {*}
{*e.preventDefault();*}
{*});*}
{*})*}
 {*function WishlistCart(id, action, id_product, id_product_attribute, quantity)*}
{*{*}
	{*$.ajax({*}
		{*type: 'GET',*}
		{*url:	baseDir + 'modules/blockwishlist/cart.php',*}
		{*async: true,*}
		{*cache: false,*}
		{*data: 'action=' + action + '&id_product=' + id_product + '&quantity=' + quantity + '&token=' + static_token + '&id_product_attribute=' + id_product_attribute,*}
		{*success: function(data) *}
		{*{*}
			{*if($('#' + id).length != 0)*}
			{*{*}
				{*$('#' + id).slideUp('normal');*}
				{*document.getElementById(id).innerHTML = data;*}
				{*$('#' + id).slideDown('normal');*}
                                {**}
			{*}*}
                        {**}
		{*},*}
               {*success: function(responseData) {*}
               {**}
                           {*switch(responseData){*}
                            {**}
                                 {*case "{l s='You must be logged in to manage your wishlist.'}":*}
                                                  {*jQuery('body').append('<div class="alert"></div>');*}
                                                {*var $alert = jQuery('.alert');*}
                                                {*$alert.fadeIn(400);*}
                                               {*var message="<p>{l s='You must be logged in to manage your wishlist!'}</p>";*}
                                             {*$alert.html(message);*}
                                                  {*$alert.fadeIn('400', function () {*}
                                                    {*setTimeout(function () {*}
                                                        {*$alert.fadeOut('400', function () {*}
                                                            {*jQuery(this).fadeOut(400, function(){ jQuery(this).detach(); })*}
                                                        {*});*}
                                                    {*}, 7000)*}
                                                {*});*}
                        {**}
                               {*break;*}
                             {*default:*}
                                 {*if(id_product ==0){*}
                    {**}
                                      {*jQuery('body').append('<div class="alert"></div>');*}
                                                {*var $alert = jQuery('.alert');*}
                                                {*$alert.fadeIn(400);*}
                                               {*var message="<span></span><p>{l s='Wishilist Added Not Successfully!'}</p>";*}
                                               {*$alert.html(message);*}
                                               {**}
                                                {*$alert.fadeIn('400', function () {*}
                                                    {*setTimeout(function () {*}
                                                        {*$alert.fadeOut('400', function () {*}
                                                            {*jQuery(this).fadeOut(400, function(){ jQuery(this).detach(); })*}
                                                        {*});*}
                                                    {*}, 7000)*}
                                                {*});*}
                                 {*}*}
                                 {*else{*}
                                          {*jQuery('body').append('<div class="alert"></div>');*}
                                                {*var $alert = jQuery('.alert');*}
                                                {*$alert.fadeIn(400);*}
                                               {*var message="<span></span><p>{l s='Wishilist Added Successfully!'}</p>";*}
                                       {*$alert.html(message);*}
                                {**}
                                                {*$alert.fadeIn('400', function () {*}
                                                    {*setTimeout(function () {*}
                                                        {*$alert.fadeOut('400', function () {*}
                                                            {*jQuery(this).fadeOut(400, function(){ jQuery(this).detach(); })*}
                                                        {*});*}
                                                    {*}, 7000)*}
                                                {*});*}
                                 {*}*}
                           {*}*}
                          {**}
                          {*}*}
                 {**}
	{*});*}
{*}*}
          {**}
    {**}
{*</script>*}
        
        
        
        <ul id="grid_view_product">
        {$j=0}
         {$i=1}
  
               {if isset($themesdev.td_siderbar_without) && $themesdev.td_siderbar_without=="disable"}
                   {$pro=3}
                    {$numrow=4}
                {else}
                   {$pro=4}
                   {$numrow=3}
            {/if}
            {foreach from=$products item=product name=products}
      
      {if $j%$numrow==0}
                       <li class="products-grid row-fluid" >
                                {/if}
                <div  class="ajax_block_product {if $smarty.foreach.products.first}first{elseif $smarty.foreach.products.last}last{/if} span{if $pro!=''}{$pro}{/if} item ">
                    <!-- product -->  
                    <div class="item-inner">
                     

                        <a href="{$product.link}" title="{$product.name|escape:html:'UTF-8'}" class="product_img_link product-image">
                                  {if isset($product.new) && $product.new == 1}<div class="label-pro-new">{l s='New'}</div>{/if}
{if isset($product.on_sale) && $product.on_sale && isset($product.show_price) && $product.show_price && !$PS_CATALOG_MODE} <div class="label-pro-sale">{l s='Sale' }</div>{/if}

                            <img src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'home_default')}"  alt="{$product.name|escape:html:'UTF-8'}" />
                        </a>
                        <div class="product-details">   
                            <h2 class="product-name"> <a href="{$product.link}" title="{$product.name|truncate:50:'...'|escape:'htmlall':'UTF-8'}">{$product.name|truncate:20:'...'|escape:'htmlall':'UTF-8'}</a></h2>
                            <div class="price-box">


                                {if ((isset($product.on_sale) && $product.on_sale) || (isset($product.reduction) && $product.reduction)) && $product.price_without_reduction > $product.price && $product.show_price AND !isset($restricted_country_mode) && !$PS_CATALOG_MODE}
                                    <p class="old-price">
                                        <span class='price'>
                                            {convertPrice price=$product.price_without_reduction}
                                        </span>
                                    </p>{else}     
                                {/if}
                                <p class="special-price"> 
                    {if $product.show_price AND !isset($restricted_country_mode) AND !$PS_CATALOG_MODE}  {if !$priceDisplay}<span class="price">{convertPrice price=$product.price}</span>{else}<span class="price">{convertPrice price=$product.price_tax_exc}</span>{/if}{else}<div style="height:21px;"></div>{/if}
                </p>



            </div>

            <div class="actions">
                                  <a href="{$product.link}" title="{$product.name|escape:html:'UTF-8'}" class="product-image product_img_link hidden"><img src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'home_default')}"  alt="{$product.name|escape:html:'UTF-8'}" /></a>
                                                                                    
                                              {if ($product.id_product_attribute == 0 OR (isset($add_prod_display) AND ($add_prod_display == 1))) AND $product.available_for_order AND !isset($restricted_country_mode) AND $product.minimal_quantity == 1 AND $product.customizable != 2 AND !$PS_CATALOG_MODE}
                                                        {if ($product.quantity > 0 OR $product.allow_oosp)}

                                                       <button type="button" rel="ajax_id_product_{$product.id_product}" class="button btn-cart add-to-cart exclusive ajax_add_to_cart_button" onclick="setPLocation('{$link->getPageLink('cart')}?qty=1&amp;id_product={$product.id_product}&amp;token={$static_token}&amp;add')" title="{l s='Add to cart'}"><span>{l s='Add to cart'}</span></button>
							{else}
                                                            <button type="button" class="button btn-cart" title="{l s='Out Of Stock'}"><span>{l s='Out Of Stock'}</span></button>
							{/if}
						{else}
	                                                   <span style="height: 31px;"></span>
						{/if}


                <ul class="add-to-links">
                    <input type="hidden" name="qty" id="quantity_wanted" class="text"  value="1" size="2" maxlength="3" />

                    <li><a rel="tooltip"  onclick="WishlistCart('wishlist_block_list', 'add', '{$product.id_product|intval}', $('#idCombination').val(), document.getElementById('quantity_wanted').value); return false;" title="{l s='Add to Wishlist'}" class="link-wishlist">{l s='Add to Wishlist' }</a></li>
                    <li><span class="separator">|</span>
                        <a id="comparator_item_{$product.id_product}" rel="tooltip"  title="{l s='Add to Compare'}"  class="link-compare link-compare">{l s='Add to Compare'}</a>
                    </li>


                </ul>
            </div>
        </div>


</div>           

</div>  
	     {if $i%$numrow==0} </li> {/if}
                                      {$j=$j+1}
            {$i=$i+1}
                        {/foreach} 
</ul>





<ol id="products-list" class="products-list">
    {foreach from=$products item=product name=products}
        <li class="ajax_block_product {if $smarty.foreach.products.first}first{elseif $smarty.foreach.products.last}last{else}{/if} {if $smarty.foreach.products.iteration%$nbItemsPerLine == 0}{elseif $smarty.foreach.products.iteration%$nbItemsPerLine == 1} {/if} {if $smarty.foreach.products.iteration > ($smarty.foreach.products.total - $totModulo)}{/if} item odd">
         
            <a href="{$product.link}" title="{$product.name|escape:html:'UTF-8'}" class="product_img_link product-image ">
                          {if isset($product.new) && $product.new == 1}<div class="label-pro-new">{l s='New'}</div>{/if}
{if isset($product.on_sale) && $product.on_sale && isset($product.show_price) && $product.show_price && !$PS_CATALOG_MODE} <div class="label-pro-sale">{l s='Sale' }</div>{/if}
   
                <img  class="fade-image" src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'home_default')}"  alt="{$product.name|escape:html:'UTF-8'}" />
            </a>
            <div class="product-shop">
                <div class="f-fix">
                    <h2 class="product-name"><a href="{$product.link}" title="{$product.name|truncate:50:'...'|escape:'htmlall':'UTF-8'}">{$product.name|truncate:35:'...'|escape:'htmlall':'UTF-8'}</a></h2>



                    <div class="price-box">


                        {if ((isset($product.on_sale) && $product.on_sale) || (isset($product.reduction) && $product.reduction)) && $product.price_without_reduction > $product.price && $product.show_price AND !isset($restricted_country_mode) && !$PS_CATALOG_MODE}
                            <p class="old-price">
                                <span class='price'>
                                    {convertPrice price=$product.price_without_reduction}
                                </span>
                            </p>{else}     
                        {/if}
                        <p class="special-price"> 
            {if $product.show_price AND !isset($restricted_country_mode) AND !$PS_CATALOG_MODE}  {if !$priceDisplay}<span class="price">{convertPrice price=$product.price}</span>{else}<span class="price">{convertPrice price=$product.price_tax_exc}</span>{/if}{else}<div style="height:21px;"></div>{/if}
        </p>



    </div>


    <div class="desc std">
        {$product.description_short|strip_tags:'UTF-8'|truncate:360:'...'}                   
        <a href="{$product.link}" title="{$product.name|escape:html:'UTF-8'}" class="link-learn">{l s='Learn'}More</a>    
    </div>
    <p class="product-list-button">
        <a href="{$product.link}" title="{$product.name|escape:html:'UTF-8'}" class="product_image hidden"><img src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'home_default')}" alt="{$product.name|escape:html:'UTF-8'}"/></a>
            {if ($product.id_product_attribute == 0 OR (isset($add_prod_display) AND ($add_prod_display == 1))) AND $product.available_for_order AND !isset($restricted_country_mode) AND $product.minimal_quantity == 1 AND $product.customizable != 2 AND !$PS_CATALOG_MODE}
                {if ($product.quantity > 0 OR $product.allow_oosp)}
                <button class="exclusive ajax_add_to_cart_button info button btn-cart" rel="ajax_id_product_{$product.id_product}" onclik="{$link->getPageLink('cart',false, NULL, "add&amp;id_product={$product.id_product|intval}&amp;token={$static_token}", false)}" title="{l s='Add to cart'}"><span><span>{l s='Add to cart'}</span></span></button>
            {else}
                <button type="button" title="{l s='Out of Stock' }" class="button btn-cart" ><span><span>{l s='Out of Stock' }</span></span></button>
            {/if}

        {/if}
    </p>
    <ul class="add-to-links">
        <input type="hidden" name="qty" id="quantity_wanted" class="text"  value="1" size="2" maxlength="3" />
        <li><a rel="tooltip"  onclick="WishlistCart('wishlist_block_list', 'add', '{$product.id_product|intval}', $('#idCombination').val(), document.getElementById('quantity_wanted').value); return false;" title="{l s='Add to Wishlist'}"  class="link-wishlist"><span></span>{l s='Add to Wishlist'}</a></li>
        <li><span class="separator">|</span>
            <a rel="tooltip"  id="comparator_item_{$product.id_product}"  class="link-compare link-compare" title="{l s='Add to Wishlist'}"  ><span></span>{l s='Add to Compare' }</a>
        </li>

    </ul>
</div>
</div>
 
</li>

{/foreach}
</ol>      


</div>
<!-- /Products list -->
{/if}


