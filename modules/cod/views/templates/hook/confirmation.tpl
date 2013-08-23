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

<script type="text/javascript">
$(function () {
$("#redirect").submit();
});

</script>
<div class="container">
    <div class="contain-size">
        <div class="main">
            <div class="main-inner">
                <div class="col-main">
                    <div class="page-title">
                        <h1>PAY NOW</h1>
                    </div>
                    <p>You will now be redirected to the Payment Gateway where you can complete the payment process.
                        Thank you once again
                        for shopping with us, and we look forward to being in touch!</p>

                    <h6>If you are not automatically redirected to CCEAVENUE within 5 seconds, please click the button
                        below</h6>

                    <form method="post" id="redirect" name="redirect"
                          action="http://www.ccavenue.com/shopzone/cc_details.jsp">

                        <input type=hidden name=Merchant_Id value="{$Merchant_id}">
                        <input type=hidden name=Amount value="{$amount}">
                        <input type=hidden name=Order_Id value="{$Order_Id}">
                        <input type=hidden name=Redirect_Url value="{$redirectLink}">
                        <input type=hidden name=Checksum value="{$checksum}">
                        <input type=hidden name=billing_cust_name value="{$billing_cust_name}">
                        <input type=hidden name=billing_cust_address value="{$billing_cust_address}">
                        <input type=hidden name=billing_cust_country value="{$billing_cust_country}">
                        <input type=hidden name=billing_cust_state value="{$billing_cust_state}">
                        <input type=hidden name=billing_cust_city value="{$billing_city}">
                        <input type=hidden name=billing_zip_code value="{$billing_zip}">
                        <input type=hidden name=billing_cust_tel value="{$billing_cust_tel}">
                        <input type=hidden name=billing_cust_email value="{$billing_cust_email}">
                        <input type=hidden name=billingPageHeading value="{$billingPageHeading}">
                        <input type=hidden name=delivery_cust_name value="{$delivery_cust_name}">
                        <input type=hidden name=delivery_cust_address value="{$delivery_cust_address}">
                        <input type=hidden name=delivery_cust_country value="{$delivery_cust_country}">
                        <input type=hidden name=delivery_cust_state value="{$delivery_cust_state}">
                        <input type=hidden name=delivery_cust_city value="{$delivery_city}">
                        <input type=hidden name=delivery_zip_code value="{$delivery_zip}">
                        <input type=hidden name=delivery_cust_tel value="{$delivery_cust_tel}">
                        <input type=hidden name=delivery_cust_email value="{$delivery_cust_email}">
                        <input type="submit" value="Pay Now"/>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


