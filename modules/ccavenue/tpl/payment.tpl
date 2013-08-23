<form action="https://www.ccavenue.com/shopzone/cc_details.jsp" method="post" id="ccAvenue_payment_form" class="payment_module">
	<input type=hidden name=Merchant_Id value="{$ccAvenue_merchant_id}">
	<input type=hidden name=Amount value="{$ccAvenue_amount}">
	<input type=hidden name=Order_Id value="{$ccAvenue_order_id}">
	<input type=hidden name=Redirect_Url value="{$ccAvenue_redirect_link}">
	<input type=hidden name=Checksum value="{$ccAvenue_checksum}">
    <input type=hidden name=billing_cust_name value="{$billing_cust_name}">
    <input type=hidden name=billing_cust_address value="{$billing_cust_address}">
    <input type=hidden name=billing_cust_country value="{$billing_cust_country}">
    <input type=hidden name=billing_cust_state value="{$billing_cust_state}">
    <input type=hidden name=billing_cust_city value="{$billing_city}">
    <input type=hidden name=billing_zip_code value="{$billing_zip}">
    <input type=hidden name=billing_cust_tel value="{$billing_cust_tel}">
    <input type=hidden name=billing_cust_email value="{$billing_cust_email}">
	<a href="javascript:void(0);" onclick="$('#ccAvenue_payment_form').submit();"><img src="{$module_dir}img/logo.png" alt="" border="0" style="float: none; vertical-align: middle;" />{l s='PAY WITH CCAVENUE' mod='ccavenue'}</a>
</form>