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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<!--[if lt IE 7]>
<html class="no-js lt-ie9 lt-ie8 lt-ie7 " lang="{$lang_iso}"> <![endif]-->
<!--[if IE 7]>
<html class="no-js lt-ie9 lt-ie8 ie7" lang="{$lang_iso}"> <![endif]-->
<!--[if IE 8]>
<html class="no-js lt-ie9 ie8" lang="{$lang_iso}"> <![endif]-->
<!--[if gt IE 8]>
<html class="no-js ie9" lang="{$lang_iso}"> <![endif]-->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{$lang_iso}">
<head>
<title>{$meta_title|escape:'htmlall':'UTF-8'}</title>
{if isset($meta_description) AND $meta_description}
    <meta name="description" content="{$meta_description|escape:html:'UTF-8'}"/>
{/if}
{if isset($meta_keywords) AND $meta_keywords}
    <meta name="keywords" content="{$meta_keywords|escape:html:'UTF-8'}"/>
{/if}
<meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=1"/>
<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8"/>
<meta http-equiv="content-language" content="{$meta_language}"/>
<meta name="generator" content="PrestaShop"/>
<meta name="robots" content="{if isset($nobots)}no{/if}index,{if isset($nofollow) && $nofollow}no{/if}follow"/>
<link rel="icon" type="image/vnd.microsoft.icon" href="{$favicon_url}?{$img_update_time}"/>
<link rel="shortcut icon" type="image/x-icon" href="{$favicon_url}?{$img_update_time}"/>
<script type="text/javascript">
    var baseDir = '{$content_dir}';
    var baseUri = '{$base_uri}';
    var static_token = '{$static_token}';
    var token = '{$token}';
    var priceDisplayPrecision = {$priceDisplayPrecision*$currency->decimals};
    var priceDisplayMethod = {$priceDisplay};
    var roundMode = {$roundMode};
</script>
{if isset($css_files)}
    {foreach from=$css_files key=css_uri item=media}
        <link href="{$css_uri}" rel="stylesheet" type="text/css" media="{$media}"/>
    {/foreach}
{/if}
<script type="text/javascript" src="{$js_dir}theme/jquery.js"></script>
<script type="text/javascript" src="{$js_dir}theme/ma.jq.slide.js"></script>
<script type="text/javascript" src="{$js_dir}theme/ma.flexslider.js"></script>
<script type="text/javascript" src="{$js_dir}theme/ma.mobilemenu.js"></script>
<script type="text/javascript" src="{$js_dir}theme/bootstrap.min.js"></script>
<script type="text/javascript" src="{$js_dir}theme/bootstrap-tooltip.js"></script>

<script type="text/javascript" src="{$js_dir}theme/ma.accordion.js"></script>
<script type="text/javascript" src="{$js_dir}theme/ma.script.vert.js"></script>
{if isset($js_files)}
    {foreach from=$js_files item=js_uri}
        <script type="text/javascript" src="{$js_uri}"></script>
    {/foreach}
{/if}
<!--[if lt IE 7]>
<script type="text/javascript">
    //<![CDATA[
        var BLANK_URL = '{$js_dir}theme/blank.html';
        var BLANK_IMG = '{$js_dir}theme/spacer.gif';
    //]]>
    </script>
    <![endif]-->

<link rel="stylesheet" type="text/css" href="{$css_dir}theme/bootstrap.css" media="all"/>
<link rel="stylesheet" type="text/css" href="{$css_dir}theme/bootstrap-responsive.css" media="all"/>
<link rel="stylesheet" type="text/css" href="{$css_dir}theme/styles_skin3.css" media="all"/>

<script type="text/javascript" src="{$js_dir}theme/backtotop.js"></script>
<script type="text/javascript" src="{$js_dir}theme/js.js"></script>


<script type="text/javascript" src="{$js_dir}theme/jquery.selectbox.js"></script>
<link rel="stylesheet" type="text/css" href="{$css_dir}theme/ma.newslider.css" media="all"/>
<script type="text/javascript" src="{$js_dir}theme/ma.zoom.js"></script>
<script type="text/javascript" src="{$js_dir}theme/superfish.js"></script>
<script type="text/javascript" src="{$js_dir}theme/script.js"></script>
<link href="//fonts.googleapis.com/css?family={$themesdev.td_shop_heading_font_face}" rel="stylesheet" type="text/css"
      media="all"/>


<link rel="stylesheet" type="text/css" href="{$css_dir}theme/fish_menu.css" media="screen"/>
<style type="text/css">
    body {
    {if $themesdev.td_enableordisable_bg=="enable"} {if $themesdev.td_body_bg_custom!=""} background: url("{$themesdev.td_body_bg_custom}") {$themesdev.td_bgrepeat} {$themesdev.td_bgattachment} {$themesdev.td_bgposition} {$themesdev.td_body_bg_color} !important;
    {else} background: url("{$themesdev.td_body_bg}") {$themesdev.td_bgrepeat} {$themesdev.td_bgattachment} {$themesdev.td_bgposition} {$themesdev.td_body_bg_color} !important;
    {/if} {else} background: none repeat scroll 0 0 {$themesdev.td_body_bg_color} !important;
    {/if} color: {$themesdev.td_body_font_color};
        font-family: '{$themesdev.td_shop_body_font_face}';
    }

    .page-title h1, .page-title h2, .block .block-title strong, .block-right-static .block-title h3, .block-subscribe-right .block-title h3, .ma-mostviewed-product-title h2, .ma-upsellslider-container .product-name, .ma-newproductslider-container .product-name, .products-grid .product-name, .ma-featured-product-title h2, .ma-newproductslider-title h2, .products-list .product-name, .product-collateral h2, .product-view .product-shop .product-name h1, .product-view .box-up-sell h2, .footer-freeshipping h3, .footer-subscribe .form-subscribe-header h4, .ma-footer-static .footer-static-title h3, .product-tabs a, .wine_menu a, .fish_menu a, .wine_menu ul li a {
        font-family: '{$themesdev.td_shop_heading_font_face}';
    }

    ul.step li.step_current span, .view-mode strong.grid:hover, .product-view .product-shop .add-to-links  .link-compare:hover span, .product-view .product-shop .add-to-links  .link-wishlist:hover span, .pager .view-mode a.list:hover, .view-mode strong.grid-active, .pager .view-mode a.list-active, .ac_over, .selectbox li.selected, .selectbox li:hover, ul.step li.step_done a, .cart_total_price .total_price_container p, ul.step li.step_current_end span, .label-pro-new, #add_to_cart .btn-cart, .fish_menu li.active a, div.alert, .product-view button.btn-cart span {
        background-color: {$themesdev.td_mainColor} !important;
    }

    .custom_block_left #custom_box_icon, .custom_block_right #custom_box_icon, button.button span, .products-list .link-compare:hover span, .products-list .link-wishlist:hover span, a.button span, .header .form-search button.button:hover span, .ma-newproductslider-container .item-inner:hover .actions, .products-grid .item:hover .actions, .ma-brand-slider-contain .flex-direction-nav a, .ma-brand-slider-contain .flex-direction-nav .flex-next, .ma-new-vertscroller-wrap .jcarousel-next-vertical:hover, .ma-new-vertscroller-wrap .jcarousel-prev-vertical:hover, .add-to-cart input.qty-decrease:hover, .add-to-cart input.qty-increase:hover, .product-prev:hover, .product-next:hover, .ma-thumbnail-container .flex-direction-nav a:hover, .footer-freeshipping .shipping-icon, .wine_menu li.active a, .fish_menu li.active a, .wine_menu li.over a, .fish_menu li.over a, .wine_menu a:hover, .fish_menu a:hover, #back-top, .content-sample-block .block2 img, .productpage-sample-block .block1 img {
        background-color: {$themesdev.td_mainColor};
    }

    .selectbox li.selected a, .selectbox li:hover a {
        color: #FFFFFF !important;
    }

    .products-list button.button:hover span, .productdetail button.button:hover span {
        background-color: {$themesdev.td_mainColor} !important;
    }

    a:hover, .fish_menu li ul li a:hover, #availability_statut #availability_value {
        color: {$themesdev.td_mainColor} !important;
    }

    .products-grid .item-inner:hover, .ma-newproductslider-container .item-inner:hover {
        border-color: {$themesdev.td_mainColor};
    }

    .custom_block_right .custom_box, .custom_block_left .custom_box {
        border: 4px solid {$themesdev.td_mainColor} !important;
    }

    .fish_menu ul,
    .fish_menu div {
        border: 1px solid {$themesdev.td_mainColor};
    }

    .fish_menu ul ul,
    .fish_menu ul div {
        border: 1px solid {$themesdev.td_mainColor};
    }

    .ma-newproductslider-container .actions, .product-view .product-shop .add-to-links  .link-compare span, .product-view .product-shop .add-to-links  .link-wishlist span, .products-list .link-compare span, .products-list .link-wishlist span, .view-mode strong.grid, .pager .view-mode a.list, .label-pro-sale, .products-grid .actions, .ma-brand-slider-contain .flex-direction-nav a:hover, .ma-footer-static-container, .ma-nav-mobile-container, .ma-nav-container, .content-sample-block .block1 img, .productpage-sample-block .block2 img {
        background-color: {$themesdev.td_secondcolor};
    }

    #add_to_cart .btn-cart:hover, .product-view button.btn-cart:hover span {
        background-color: {$themesdev.td_secondcolor} !important;
    }

    .idTabs a.selected, .idTabs a:hover {
        border-top: 3px solid {$themesdev.td_mainColor};
    }

    a, .breadcrumbs li strong, .pager .pages a:hover, .pager .pages .current, .header .welcome-msg a, .header .currency-language .language-switcher a:hover, .header .links li a:hover, .top-cart-contain .price, .block .block-title strong .word2, .block-right-static .block-title strong, .block-subscribe-right .block-title strong, .block-account .block-content li a:hover, .block-account .block-content li.current, .block-layered-nav li:hover, .block-layered-nav li a:hover, .ma-featured-product-title h2 .word2, .ma-newproductslider-title h2 .word2, .products-list .product-name a:hover, .product-view .product-shop .availability span, .price-box .price, .regular-price, .regular-price .price, .block .regular-price, .block .regular-price .price, .special-price .price-label, .special-price .price, .add-to-cart .qty, .product-collateral h2 .word2, .product-view .product-shop .product-sku span, .product-view .product-shop .product-brand span, .product-view .product-shop .add-to-links a:hover, .email-friend a:hover, .ma-footer-static .fcol1 li:hover, .ma-footer-static .fcol1 li a:hover, .mobilemenu li.active a, .mobilemenu a:hover, #ticker a {
        color: {$themesdev.td_mainColor};
    }


</style>


<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('.currency-language select').selectbox();


        $(".grid").click(function (e) {
            $("#products_wrapper ol.products-list").hide();
            $("#products_wrapper ul#grid_view_product").show();
            $('.list').removeClass('');
            $(this).addClass('grid-active');
            e.preventDefault();
        });
        $(".list").click(function (e) {
            $("#products_wrapper ol.products-list").show();
            $("#products_wrapper ul#grid_view_product").hide();
            $(this).addClass('list-active');
            $('.grid').removeClass('grid-active');
            e.preventDefault();
        });

    })

    {if $themesdev.td_proviewstyle=="gridview"}
    jQuery(document).ready(function ($) {
        $("#products_wrapper ol.products-list").hide();
        $("#products_wrapper ul#grid_view_product").show();
        $('.list').removeClass('list-active');
        $('.grid').addClass('grid-active');
    });
    {else}
    jQuery(document).ready(function ($) {
        $("#products_wrapper ol.products-list").show();
        $("#products_wrapper ul#grid_view_product").hide();
        $('.list').addClass('list-active');
        $('.grid').removeClass('grid-active');
    });
    {/if}

</script>
<script type="text/javascript">
    function getCompareProducts() {
        $.ajax({
            type: 'POST',
            url: baseDir + 'index.php?fc=module&module=featurecategories&controller=compare',
            async: true,
            cache: false,
            data: 'action=show',
            success: function (jsonData, textStatus, jqXHR) {
                $('#fc_comparison').empty().html(jsonData);
            }
        });
    }
    jQuery(document).ready(function ($) {
        $('.link-compare').click(function () {
            var idProduct = $(this).attr('id').replace('comparator_item_', '');
            $.ajax({
                url: 'index.php?fc=module&module=featurecategories&controller=compare&action=add&productId=' + idProduct,
                async: true,
                dataType: "json",
                success: function (responseData) {
                    if (responseData == 0) {

                        jQuery('body').append('<div class="alert"></div>');
                        var $alert = jQuery('.alert');
                        $alert.fadeIn(400);
                        var message = "<p>{l s='Allready added max value products!'}</p>";
                        $alert.html(message);
                        $alert.fadeIn('400', function () {
                            setTimeout(function () {
                                $alert.fadeOut('400', function () {
                                    jQuery(this).fadeOut(400, function () {
                                        jQuery(this).detach();
                                    })
                                });
                            }, 7000)
                        });
                        getCompareProducts();

                    }
                    else if (responseData == 3) {
                        jQuery('body').append('<div class="alert"></div>');
                        var $alert = jQuery('.alert');
                        $alert.fadeIn(400);
                        var message = "<p>{l s='You can\'t compare selected item with existing products '}</p>";
                        $alert.html(message);
                        $alert.fadeIn('400', function () {
                            setTimeout(function () {
                                $alert.fadeOut('400', function () {
                                    jQuery(this).fadeOut(400, function () {
                                        jQuery(this).detach();
                                    })
                                });
                            }, 7000)
                        });
                        getCompareProducts();
                    }

                    else if (responseData == 1) {
                        jQuery('body').append('<div class="alert"></div>');
                        var $alert = jQuery('.alert');
                        $alert.fadeIn(400);
                        var message = "<p>{l s='Compare list Added Successfully!'}</p>";
                        $alert.html(message);
                        $alert.fadeIn('400', function () {
                            setTimeout(function () {
                                $alert.fadeOut('400', function () {
                                    jQuery(this).fadeOut(400, function () {
                                        jQuery(this).detach();
                                    })
                                });
                            }, 7000)
                        });
                        getCompareProducts();

                    }

                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {

                }
            });


        });

        $(".link-compare").click(function (e) {
            e.preventDefault();
        });
    })
    function WishlistCart(id, action, id_product, id_product_attribute, quantity) {
        $.ajax({
            type: 'GET',
            url: baseDir + 'modules/blockwishlist/cart.php',
            async: true,
            cache: false,
            data: 'action=' + action + '&id_product=' + id_product + '&quantity=' + quantity + '&token=' + static_token + '&id_product_attribute=' + id_product_attribute,
            success: function (data) {
                if (action == 'add') {
                    jQuery('body').append('<div class="alert"></div>');
                    var $alert = jQuery('.alert');
                    $alert.fadeIn(400);
                    var message = "<span></span><p>{l s='Wishilist Added Successfully!'}</p>";
                    $alert.html(message);

                    $alert.fadeIn('400', function () {
                        setTimeout(function () {
                            $alert.fadeOut('400', function () {
                                $(this).fadeOut(400, function () {
                                    jQuery(this);
                                })
                            });
                        }, 7000)
                    });
                }
                if (action == 'delete') {
                    jQuery('body').append('<div class="alert"></div>');
                    var $alert = jQuery('.alert');
                    $alert.fadeIn(400);
                    var message = "<span></span><p>{l s='Wishilist Delete Successfully!'}</p>";
                    $alert.html(message);

                    $alert.fadeIn('400', function () {
                        setTimeout(function () {
                            $alert.fadeOut('400', function () {
                                $(this).fadeOut(400, function () {
                                    jQuery(this);
                                })
                            });
                        }, 7000)
                    });
                }
                if (data == 'You must be logged in to manage your wishlist.') {
                    jQuery('body').append('<div class="alert"></div>');
                    var $alert = jQuery('.alert');
                    $alert.fadeIn(400);
                    var message = "<p>{l s='You must be logged in to manage your wishlist!'}</p>";
                    $alert.html(message);
                    $alert.fadeIn('400', function () {
                        setTimeout(function () {
                            $alert.fadeOut('400', function () {
                                $(this).fadeOut(400, function () {
                                    jQuery(this);
                                })
                            });
                        }, 7000)
                    });
                }
                if ($('#' + id).length != 0) {
                    $('#' + id).slideUp('normal');
                    document.getElementById(id).innerHTML = data;
                    $('#' + id).slideDown('normal');
                }
            }
        });
    }


</script>

{$HOOK_HEADER}
<style type="text/css">
    {$themesdev.td_custom_style|html_entity_decode}
</style>
<script type="text/javascript">
    {$themesdev.td_custom_js|html_entity_decode}
</script>
</head>
<body {if isset($page_name)}id="{$page_name|escape:'htmlall':'UTF-8'}"{/if}
      class="{if $hide_left_column} {/if} {if $hide_right_column} {/if} {if $content_only}{/if} cms-index-index cms-home">
{if !$content_only}
{if isset($restricted_country_mode) && $restricted_country_mode}
    <div id="restricted-country">
        <p>{l s='You cannot place a new order from your country.'} <span class="bold">{$geolocation_country}</span></p>
    </div>
{/if}
{if $themesdev.td_facebook_likebox=='enable'}
    <div class="{if $themesdev.td_diaplay_fblikebox=='enable'}fb_right{else}fb_left{/if} hidden-phone">
        <div id="fb_icon"></div>
        <div class="fb_box">


            {if $themesdev.td_fb_page_url}
                <iframe src="http://www.facebook.com/plugins/likebox.php?href={$themesdev.td_fb_page_url|html_entity_decode}&amp;width=292&amp;height=375&amp;colorscheme=light&amp;show_faces=true&amp;border_color&amp;stream=false&amp;header=false"
                        scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:292px; height:385px;"
                        allowTransparency="true"></iframe>
            {else}
                <iframe src="http://www.facebook.com/plugins/likebox.php?id={$themesdev.td_fb_page_id|html_entity_decode}&amp;width=292&amp;colorscheme=light&amp;border_color&amp;show_faces=true&amp;connections=6&amp;stream=false&amp;header=375&amp;height=false"
                        scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:292px; height:385px;"
                        allowTransparency="true"></iframe>
            {/if}

            <script type="text/javascript">
                $(function () {
                    $(".fb_right").hover(function () {
                                $(".fb_right").stop(true, false).animate({ right: "0" }, 800, 'easeOutQuint');
                            },
                            function () {
                                $(".fb_right").stop(true, false).animate({ right: "-300" }, 800, 'easeInQuint');
                            }, 1000);
                });
                $(function () {
                    $(".fb_left").hover(function () {
                                $(".fb_left").stop(true, false).animate({ left: "0" }, 800, 'easeOutQuint');
                            },
                            function () {
                                $(".fb_left").stop(true, false).animate({ left: "-300" }, 800, 'easeInQuint');
                            }, 1000);
                });
            </script>

        </div>
    </div>
{/if}
{if $themesdev.td_edcustomblcok=='enable'}
    <div class="{if $themesdev.td_display_cb=='enable'}custom_block_left{else}custom_block_right {/if}">
        <div id="custom_box_icon"></div>
        <div class="custom_box">


            {$themesdev.td_custom_blcok|html_entity_decode}

            <script type="text/javascript">
                $(function () {
                    $(".custom_block_right").hover(function () {
                                $(".custom_block_right").stop(true, false).animate({ right: "0" }, 800, 'easeOutQuint');
                            },
                            function () {
                                $(".custom_block_right").stop(true, false).animate({ right: "-245" }, 800, 'easeInQuint');
                            }, 1000);
                });
                $(function () {
                    $(".custom_block_left").hover(function () {
                                $(".custom_block_left").stop(true, false).animate({ left: "0" }, 800, 'easeOutQuint');
                            },
                            function () {
                                $(".custom_block_left").stop(true, false).animate({ left: "-245" }, 800, 'easeInQuint');
                            }, 1000);
                });
            </script>
        </div>
    </div>
{/if}
<div class="ma-wrapper">
    <div class="ma-page">
        <div class="ma-header-wrapper" id="header">

            {$HOOK_TOP}
        </div>
        <div id="center_column">
            {if $page_name!="index"}
            <div class="ma-main-container {if $page_name=="index" || $page_name=="category" || $page_name=="supplier" || $page_name=="best-sales" || $page_name=="cms" || $page_name=="contact" || $page_name=="manufacturer" || $page_name=="my-account" ||
            $page_name=="product" ||  $page_name=="new-products" || $page_name=="prices-drop" || $page_name=="search" ||  $page_name=="sitemap" || $page_name=="stores" || $page_name=="module-blockwishlist-mywishlist"} col2-left-layout{else}col1-layout{/if}">
                <div class="container">
                    <div class="contain-size">
                        {/if}
{/if}