<link href="{$module_dir}css/ccavenue.css" rel="stylesheet" type="text/css">
<img src="{$ccAvenue_tracking|escape:'htmlall':'UTF-8'}" alt="" style="display: none;"/>
<div class="ccavenue-wrap">
	{$ccAvenue_confirmation}
	<p class="ccavenue-intro"><a href="https://mars.ccavenue.com/" target="_blank" class="ccavenue-logo"><img src="{$module_dir}img/ccavenue-logo.png" alt="ccavenue" border="0" /></a>{l s='LARGEST Payment Gateway' mod='ccavenue'}<br />
	<span>{l s='{ in India }' mod='ccavenue'}</span><a href="https://mars.ccavenue.com/" target="_blank" class="ccavenue-link">{l s='Sign up now!' mod='ccavenue'}</a></p>
	<div class="clear"></div>
	<div class="ccavenue-content">
		<div class="ccavenue-right">
			<img src="{$module_dir}img/ccavenue-img.jpg" alt="ccavenue" class="ccavenue-img" />
			<h3>{l s='Setup CCAvenue&reg; in three easy steps!' mod='ccavenue'}</h3>
			<ol>
				<li>{l s='Create an account with CCAvenue&reg; and then login' mod='ccavenue'}</li>
				<li>{l s='Go to "Settings & Options" and click on "Generate Working Key"' mod='ccavenue'}</li>
				<li>{l s='Copy/Paste your "Merchant ID" and "Working Key" in the input fields below' mod='ccavenue'}</li>
			</ol>
		</div>		
		<h3>{l s='CCAvenue&reg; is the No. 1 online payment gateway in India!' mod='ccavenue'}</h3>
		<p>{l s='Used by more than 85%% of e-merchants in India, CCAvenue&reg; is one of the most trusted names in global e-commerce.' mod='ccavenue'}</p>
		<ul>
			<li>{l s='All major international credit cards accepted' mod='ccavenue'}</li>
			<li>{l s='Widen your market by going global with US Dollar processing' mod='ccavenue'}</li>
			<li>{l s='Expand your market reach with Indian payment options' mod='ccavenue'}</li>
			<li>{l s='Highest level of security employed' mod='ccavenue'}</li>
			<li>{l s='Enjoy quick and convenient pay-out options' mod='ccavenue'}</li>
			<li>{l s='Robust system scalability' mod='ccavenue'}</li>
			<li>{l s='Enjoy quick and convenient pay-out options' mod='ccavenue'}</li>
			<li>{l s='CCAvenue\'s expert support team is available 24/7/365' mod='ccavenue'}</li>
		</ul>
		<p class="ccavenue-call"><a href="https://mars.ccavenue.com/" target="_blank" class="ccavenue-link">{l s='Sign up!' mod='ccavenue'}</a>{l s='Securely accept online payments with CCAvenue&reg;' mod='ccavenue'}</p>
		<div class="clear"></div>
		<div class="block-ccavenue">
			<p><span class="ccavenue-big1">{l s='HIGHEST' mod='ccavenue'}</span><br />
			{l s='Number of Payment Options' mod='ccavenue'}<br />
			<span class="ccavenue-big">{l s='{ 100+ Payment Options }' mod='ccavenue'}</span></p>
		</div>
		<div class="block-ccavenue middle">
			<p><span class="ccavenue-big1">{l s='LOWEST' mod='ccavenue'}</span><br />
			{l s='Processing Rates' mod='ccavenue'}<br />
			<span class="ccavenue-big">{l s='{ Credit Cards 2.50%<br />Debit Cards 1.25% }' mod='ccavenue'}</span></p>
		</div>
		<div class="block-ccavenue">
			<p><span class="ccavenue-big1">{l s='FASTEST' mod='ccavenue'}</span><br />
			{l s='Activation Process' mod='ccavenue'}<br />
			<span class="ccavenue-big">{l s='{ One Working Day }' mod='ccavenue'}</span></p>
		</div>
	</div>
	{if !$ccAvenue_ssl}
	<div class="warn MB15">
		<p style="margin-top: 0px;"><strong>{l s='SSL is not active.' mod='ccavenue'}</strong> {l s='Even if it is not mandatory, we highly recommend you configure SSL on your shop. Most customers will not finalize their order if SSL is not enabled' mod='ccavenue'}</p>
	</div>
	{/if}
	<form action="{$ccAvenue_form|escape:'htmlall':'UTF-8'}" method="post">
		<fieldset>
			<legend><img src="{$module_dir}img/icon-config.gif" alt="" />{l s='Configuration' mod='ccavenue'}</legend>
			<p class="MB10">{l s='To use this module, please provide your CCAvenue&reg; merchant ID and Working Key. Also, you will need to choose whether your CCAvenue&reg; account is configured for US Dollars or Indian Rupees.' mod='ccavenue'}</p>
			<label for="ccAvenueMerchantID">{l s='ccAvenue Merchant ID:' mod='ccavenue'}</label>
			<div class="margin-form">
				<input type="text" name="ccAvenueMerchantID" id="ccAvenueMerchantID" value="{$ccAvenue_merchant_id|escape:'htmlall':'UTF-8'}" style="width: 230px;" /> <sup>*</sup>
			</div>
			<label for="ccAvenueWorkingKey">{l s='ccAvenue Working Key:' mod='ccavenue'}</label>
			<div class="margin-form">
				<input type="text" name="ccAvenueWorkingKey" id="ccAvenueWorkingKey" value="{$ccAvenue_working_key|escape:'htmlall':'UTF-8'}" style="width: 230px;" /> <sup>*</sup>
			</div>
			<label for="ccAvenueMerchantCurrency">{l s='ccAvenue Currency:' mod='ccavenue'}</label>
			<div class="margin-form PT03">
				<input type="radio" name="ccAvenueMerchantCurrency"{if $ccAvenue_merchant_currency == 'INR' || !$ccAvenue_merchant_currency}checked="checked"{/if} id="ccAvenueMerchantCurrency_rupee" value="INR" /> <label class="inner-label" for="ccAvenueMerchantCurrency_rupee">{l s='Indian Rupee' mod='ccavenue'}</label>
				<input type="radio" name="ccAvenueMerchantCurrency" id="ccAvenueMerchantCurrency_USD"{if $ccAvenue_merchant_currency == 'USD'}checked="checked"{/if} value="USD" /> <label class="inner-label" for="ccAvenueMerchantCurrency_USD">{l s='US Dollar' mod='ccavenue'}</label>
			</div>
			<div class="margin-form">
				<input type="submit" class="button" name="submitCCAvenue" value="{l s='Save' mod='ccavenue'}" />
			</div>
			<span class="small"><sup>*</sup> {l s='Required fields' mod='ccavenue'}</span>
		</fieldset>
	</form>
</div>
