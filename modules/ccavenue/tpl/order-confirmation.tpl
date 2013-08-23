{if $ccAvenue_order.valid == 1}
	<div class="conf confirmation">{l s='Congratulations!' mod='ccavenue'} 
	{if $statePending}
		{l s='Your payment is pending verification,' mod='ccavenue'}
	{else}
		{l s='Your payment has been approved,' mod='ccavenue'}
	{/if} {l s='and your order has been saved under ' mod='ccavenue'}
	{if isset($ccAvenue_order.reference)} 
		{l s='the reference' mod='ccavenue'} <b>{$ccAvenue_order.reference|escape:html:'UTF-8'}</b>
	{else} 
		{l s='the ID' mod='ccavenue'} <b>{$ccAvenue_order.id|escape:html:'UTF-8'}</b>
	{/if}.
	</div>
	{else}
		<div class="error">{l s='Unfortunately, an error occurred during the transaction.' mod='ccavenue'}<br /><br />
		{l s='Please double-check your credit card details and try again. If you need further assistance, feel free to contact us anytime.' mod='ccavenue'}<br /><br />
		{if isset($ccAvenue_order.reference)}
			({l s='Your Order\'s Reference' mod='ccavenue'} <b>{$ccAvenue_order.reference|escape:html:'UTF-8'}</b>)
		{else}
			({l s='Your Order\'s ID:' mod='ccavenue'} <b>{$ccAvenue_order.id|escape:html:'UTF-8'}</b>)
		</div>
	{/if}	
{/if}