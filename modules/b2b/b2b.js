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
        $('#ajax-loader-verify').show();
        $.ajax({
            type: "POST",
            url: $(this).attr('action'),
            data: $(this).serialize(),
            success: function (data) {
                $('#ajax-loader-verify').hide();
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
        $('#ajax-loader-update').show();
        $.ajax({
            type: "POST",
            url: $(this).attr('action'),
            data: $(this).serialize(),
            success: function (data) {
                $('#ajax-loader-update').hide();
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

    $("#category").on('change', function () {
        var category = $.trim($("#category option:selected").val());
        if (category) {
            $('#ajax-loader-category').show();
            $.ajax({
                type: "POST",
                url: '/modules/b2b/productlist.php',
                data: {category: category},
                success: function (data) {
                    $('#ajax-loader-category').hide();
                    var option = '<option value="">Select Product</option>';
                    $.each(data, function (i, el) {
                        option += '<option value="' + el.id_product + '">' + el.name + '</option>'
                    });
                    $('#product')
                        .find('option')
                        .remove()
                        .end()
                        .append(option)
                    ;
                },
                dataType: 'json'
            });
        }
        else {
            $('#product')
                .find('option')
                .remove()
                .end()
                .append('<option value="">Select Product</option>')
            ;
        }
    });

    $("#b2b").submit(function (e) {
        e.preventDefault();
        var name = $.trim($('#name').val());
        var email = $.trim($('#email').val());
        var mobile = $.trim($('#mobile').val());
        var city = $.trim($('#city').val());
        var state = $.trim($('#state').val());
        var quantity = $.trim($('#quantity option:selected').val());
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
        else if (!city) {
            alert('City is required');
            return;
        }
        else if (!state) {
            alert('State is required');
            return;
        }
        else if (!category) {
            alert('Category is Required');
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