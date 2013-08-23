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
<div class="container">
    <div class="contain-size">
        <div class="main">
            <div class="main-inner">
                <div class="col-main">
                    <div class="page-title">
                        <h1>BULK PURCHASE</h1>
                    </div>
                    <p>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus ut neque pulvinar, egestas
                        purus nec, dignissim orci. In tempus massa vestibulum, imperdiet dui eget, varius dolor.
                    </p>

                    <div class="col2-set">
                        <div class="col-1 box-in">
                            <div class="content">
                                <form id="b2b" action="{$form_action}" method="post" data-ajax="true" novalidate="">

                                    <ul class="form-list">
                                        <li>
                                            <label for="name" class="required"><em>*</em>Name</label>

                                            <div class="input-box">
                                                <input type="text" id="name" name="name"/>
                                            </div>
                                        </li>
                                        <li>
                                            <label for="email" class="required"><em>*</em>Email</label>

                                            <div class="input-box">
                                                <input type="email" id="email" name="email"/>
                                            </div>
                                        </li>
                                        <li>
                                            <label for="mobile" class="required"><em>*</em>Mobile</label>

                                            <div class="input-box">
                                                <input type="text" id="mobile" name="mobile"/>
                                            </div>
                                        </li>
                                        <li>
                                            <label for="city" class="required"><em>*</em>City</label>

                                            <div class="input-box">
                                                <input type="text" id="city" name="city"/>
                                            </div>
                                        </li>
                                        <li>
                                            <label for="state" class="required"><em>*</em>State</label>

                                            <div class="input-box">
                                                <input type="text" id="state" name="state"/>
                                            </div>
                                        </li>
                                        <li>
                                            <label for="category" class="required"><em>*</em>Select Category</label>

                                            <div class="input-box">
                                                <select name="category" id="category">
                                                    <option value="">Select Category</option>
                                                    {foreach from=$categories key=myId item=i}
                                                        <option value="{$i.id_category}">{$i.name}</option>
                                                    {/foreach}
                                                </select>
                                                <span
                                                        id="ajax-loader-category"
                                                        style="display: none"><img
                                                            src="{$this_path}ajax-loader.gif"
                                                            alt="{l s='ajax-loader' mod='b2b'}"/></span>
                                            </div>
                                        </li>
                                        <li>
                                            <label for="product" class="required"><em>*</em>Select Product</label>

                                            <div class="input-box">
                                                <select name="product" id="product">
                                                    <option value="">Select Product</option>
                                                </select>
                                            </div>
                                        </li>
                                        <li>
                                            <label for="quantity" class="required"><em>*</em>Quantity</label>

                                            <div class="input-box">
                                                <select name="quantity" id="quantity">
                                                    <option value="">Select Quantity</option>
                                                    <option value="10-20">10-20</option>
                                                    <option value="21-50">21-50</option>
                                                    <option value="51-100">51-100</option>
                                                    <option value="101">More than 100</option>
                                                </select>
                                            </div>
                                        </li>
                                        <input type="hidden" name="formName" value="b2b"/>
                                        <li>
                                            <p class="required">*Required Fields</p>
                                            <button type="submit" name="submit" class="button">
                                                <span><span>Submit</span></span>
                                            </button><span
                                                    id="ajax-loader"
                                                    style="display: none"><img
                                                        src="{$this_path}ajax-loader.gif"
                                                        alt="{l s='ajax-loader' mod='b2b'}"/></span>

                                        </li>
                                    </ul>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

