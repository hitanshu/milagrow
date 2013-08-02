<script type="text/javascript" src="{$jsSource}"></script>
<h1>{l s='Verify Mobile' mod='b2b'}</h1>
<p>You have to verify your mobile to confirm your order</p>
<form method="post" id="verifyForm" data-ajax="true">
    <p>
        <label for="code">Enter your verification Code</label>
        <input type="hidden" name="key" value="{$rowId}"/>
        <input type="hidden" name="verify" value="verify"/>
        <input id="code" type="text" name="code"/>
        <input type="submit" value="verify"/><span id="ajax-loader" style="display: none"><img
                    src="{$this_path}ajax-loader.gif" alt="{l s='ajax-loader' mod='b2b'}"/></span>
    </p>
</form>

<h3>Need to update your mobile phone</h3>
<form method="post" id="updateForm" novalidate="" data-ajax=true>
    <p>
        <label for="mobile">Enter Your Mobile Number</label>
        <input type="text" name="mobile" id="update_mobile">
        <input type="hidden" name="update" value="update"/>
        <input type="hidden" name="key" value="{$rowId}"/>
        <input type="submit" value="update"/><span id="ajax-loader" style="display: none"><img
                    src="{$this_path}ajax-loader.gif" alt="{l s='ajax-loader' mod='b2b'}"/></span>
    </p>
</form>

