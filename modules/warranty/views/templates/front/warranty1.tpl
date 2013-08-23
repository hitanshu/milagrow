<script type="text/javascript" src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
<script type="text/javascript" src="http://code.jquery.com/ui/1.10.3/jquery-ui.min.js"></script>
<script type="text/javascript" src="{$jsSource}warranty.js"></script>
<link rel="stylesheet" media="all" type="text/css" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<link rel="stylesheet" media="all" type="text/css" href="{$jsSource}jquery-ui-timepicker-addon.css" />

<div class="container">
    <div class="contain-size">
        <div class="main">
            <div class="main-inner">
                <div class="col-main">
                    <div class="page-title">
                        <h1>Register Product</h1>
                    </div>
                    <p>
                        Register your product to get fast technical support and better warranty claims
                    </p>

                    <div class="col2-set">
                        <div class="col-1 box-in">
                            <div class="content">
                                <form id="warranty" action="{$form_action}" method="post" data-ajax="true" novalidate="">

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
                                            <label for="product" class="required"><em>*</em>Select Product</label>

                                            <div class="input-box">
                                                <select name="product" id="product">
                                                    <option value="">Select Product</option>
                                                    {foreach from=$products key=myId item=i}
                                                        <option value="{$i.id}">{$i.name}</option>
                                                    {/foreach}
                                                    <option value='-1'>Other</option>
                                                </select>
                                            </div>
                                        </li>

                                        <li>
                                            <label for="productNumber" class="required"><em>*</em>Product Number</label>

                                            <div class="input-box">
                                                <input type="text" id="productNumber" name="productNumber"/>
                                            </div>
                                        </li>

                                        <li>
                                            <label for="date" class="required"><em>*</em>Date of Purchase</label>

                                            <div class="input-box">
                                                <input type="text" id="date" name="date" readonly />
                                            </div>
                                        </li>
                                        <li>
                                            <label for="storeName" class="required"><em>*</em>Store Name</label>

                                            <div class="input-box">
                                                <input type="text" id="storeName" name="storeName"/>
                                            </div>
                                        </li>
                                        <li>
                                            <label for="address1" >Address</label>

                                            <div class="input-box">
                                                <textarea name="address" id="address"></textarea>
                                            </div>
                                        </li>



                                        <li>
                                            <label>City</label>

                                            <div class="input-box">
                                                <input type="text" id="city" name="city"/>
                                            </div>
                                        </li>
                                        <li>
                                            <label>State</label>

                                            <div class="input-box">
                                                <input type="text" id="state" name="state"/>
                                            </div>
                                        </li>



                                        <li>
                                            <p class="required">*Required Fields</p>
                                            <button id="warrantyButton" type="button" name="submit" class="button">
                                                <span><span>Submit</span></span>
                                            </button><span id="ajax-loader" style="display: none"><img
                                                        src="{$this_path}loader.gif" alt="{l s='loader' mod='warranty'}"/></span>

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

    <script type="text/javascript" src="{$jsSource}jquery-ui-timepicker-addon.js"></script>
    <script type="text/javascript" src="{$jsSource}jquery-ui-sliderAccess.js"></script>
    <script>$('#date').datepicker({
            dateFormat:'yy-mm-dd'
        });</script>
</div>
