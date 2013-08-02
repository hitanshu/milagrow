/**
 * Created with JetBrains PhpStorm.
 * User: hitanshu
 * Date: 31/7/13
 * Time: 11:24 AM
 * To change this template use File | Settings | File Templates.
 */
$(document).ready(function () {

    function isValidEmailAddress(emailAddress) {
        var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
        return pattern.test(emailAddress);
    }

    function validatePhone(txtPhone) {

        var filter = /^\d{10}$/;
        return filter.test(txtPhone);

    }

    function validateInt(data) {
        var filter = /^[0-9]+$/;
        return filter.test(data);
    }


    $("#verifyForm").submit(function (e) {
        e.preventDefault();
        var code = $.trim($('#code').val());

        if (!code) {
            alert('Please enter the code we have sent to you');
            return;
        }
        $('#ajax-loader').show();
        $.ajax({
            type: "POST",
            url: $(this).attr('action'),
            data: $(this).serialize(),
            success: function (data) {
                $('#ajax-loader').hide();
                if (data.status == false) {
                    alert(data.message);
                }
                else {
                    window.location.href = data.url;
                }
            },
            dataType: 'json'
        });
    });

    $("#updateForm").submit(function (e) {
        e.preventDefault();
        var mobile = $.trim($('#update_mobile').val());

        if (!mobile) {
            alert('Mobile Number required');
            return;
        }

        else if (!validatePhone(mobile)) {
            alert('Please enter valid 10 digit mobile number');
            return;
        }
        $('#ajax-loader').show();
        $.ajax({
            type: "POST",
            url: $(this).attr('action'),
            data: $(this).serialize(),
            success: function (data) {
                $('#ajax-loader').hide();
                if (data.status == false) {
                    alert(data.message);
                }
                else {
                    alert(data.message);
                }
            },
            dataType: 'json'
        });
    });


    $("#b2b").submit(function (e) {
        e.preventDefault();
        var name = $.trim($('#name').val());
        var email = $.trim($('#email').val());
        var mobile = $.trim($('#mobile').val());
        var quantity = $.trim($('#quantity').val());
        var product = $.trim($("#product option:selected").val());

        if (!name) {
            alert('Name is required');
            return;
        }

        else if (!email) {
            alert('Email is Required');
            return;
        }
        else if (!isValidEmailAddress(email)) {
            alert('Please enter valid email id');
            return;
        }
        else if (!mobile) {
            alert('Mobile is required');
            return;
        }

        else if (!validatePhone(mobile)) {
            alert('Please enter valid 10 digit mobile number.');
            return;
        }
        else if (!product) {
            alert('Product is Required');
            return;
        }
        else if (!quantity) {
            alert('Quantity is required');
            return;
        }

        else if (!validateInt(quantity)) {
            alert('Please enter quanity in number');
            return;
        }

        $('#ajax-loader').show();
        $.ajax({
            type: "POST",
            url: $(this).attr('action'),
            data: $(this).serialize(),
            success: function (data) {
                $('#ajax-loader').hide();
                if (data.status == false) {
                    alert(data.message);
                }
                else {
                    window.location.href = data.url;
                }
            },
            dataType: 'json'
        });
    });

});