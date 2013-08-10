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
                    <div class="account-login input-type-field">
                        <div class="page-title">
                            <h1>BULK PURCHASE</h1>
                        </div>


                        <div class="col2-set">
                            <div class="col-1 new-users box-in">
                                <div class="content">
                                    <form action="{$form_action}" method="post" id="verifyForm" data-ajax="true">
                                        <h2>Verify Mobile</h2>

                                        <p>You have to verify your mobile to confirm your order</p>
                                        <ul class="form-list">
                                            <li>
                                                <label for="code" class="required"><em>*</em>Enter your verification Code</label>

                                                <div class="input-box">
                                                    <input type="hidden" name="key" value="{$rowId}"/>
                                                    <input type="hidden" name="verify" value="verify"/>
                                                    <input id="code" type="text" name="code"/>
                                                </div>
                                            </li>

                                            <p class="required">*Required Fields</p>

                                            <button type="submit" name="submit" class="button">
                                                <span><span>Submit</span></span>
                                            </button><span id="ajax-loader-verify" style="display: none"><img
                                                        src="{$this_path}ajax-loader.gif"
                                                        alt="{l s='ajax-loader' mod='b2b'}"/></span>

                                        </ul>

                                    </form>
                                </div>
                            </div>
                            <div class="col-1 box-in registered-users">
                                <div class="content">
                                    <form action="{$form_action}" method="post" id="updateForm" novalidate="" data-ajax=true>
                                        <h2>Update Mobile</h2>

                                        <p>Need to update your mobile phone</p>
                                        <ul class="form-list">
                                            <li>
                                                <label for="mobile" class="required"><em>*</em>Enter Your Mobile Number</label>

                                                <div class="input-box">
                                                    <input type="text" name="mobile" id="update_mobile">
                                                    <input type="hidden" name="update" value="update"/>
                                                    <input type="hidden" name="key" value="{$rowId}"/>
                                                </div>
                                            </li>

                                            <p class="required">*Required Fields</p>

                                            <button type="submit" name="submit" class="button">
                                                <span><span>Submit</span></span>
                                            </button><span id="ajax-loader-update" style="display: none"><img
                                                        src="{$this_path}ajax-loader.gif"
                                                        alt="{l s='ajax-loader' mod='b2b'}"/></span>

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
</div>