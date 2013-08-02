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
<script type="text/javascript" src="{$jsSource}"></script>
<h3>{l s='B2B' mod='b2b'}</h3>
<form id="b2b" action="{$form_action}" method="post" data-ajax="true" novalidate="">
    <p>
        B2B

    </p>

    <p>
        <label for="name">Name</label>
        </br>
        <input type="text" id="name" name="name"/>
    </p>

    <p>
        <label>email</label><br>
        <input type="email" id="email" name="email"/>
    </p>

    <p>
        <label>mobile</label><br>
        <input type="text" id="mobile" name="mobile"/>
    </p>

    <p>
        <label>City</label><br>
        <input type="text" id="city" name="city"/>
    </p>

    <p>
        <label>State</label><br>
        <input type="text" id="state" name="state"/>
    </p>

    <p>
        <label>Select Product</label><br>
        <select name="product" id="product">
            <option value="">Select Product</option>
            {foreach from=$products key=myId item=i}
                <option value="{$i.id_product}">{$i.name}</option>
            {/foreach}
        </select>
    </p>
    <p>
        <label>Quantity</label><br>
        <input type="text" id="quantity" name="quantity"/>
    </p>
    <input type="hidden" name="formName" value="b2b"/>

    <p>
        <input type="submit" name="submit"/><span id="ajax-loader" style="display: none"><img
                    src="{$this_path}ajax-loader.gif" alt="{l s='ajax-loader' mod='b2b'}"/></span>
    </p>
</form>

