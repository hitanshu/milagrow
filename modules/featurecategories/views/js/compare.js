$(document).ready(function()
{
    $('.question_image').each(function()
    {
        $(this).qtip({
            content: $(this).parent().children('.tooltip_val')[0].innerHTML,
            show: 'mouseover',
            hide: 'mouseout',
            style: {
                width: 500,
                padding: 5,
                background: '#ffffca',
                color: 'black',
                textAlign: 'center',
                border: {
                    width: 7,
                    radius: 5,
                    color: '#ffffca'
                }},
            position:
            {
                corner:
                {
                    target: 'topRight',
                    tooltip: 'bottomLeft'
                }
            }
        });
    });

	$.ajax({
			type: 'POST',
			url: baseDir + 'index.php?fc=module&module=featurecategories&controller=compare',
			async: true,
			cache: false,
			data: 'action=show',
			success: function(jsonData,textStatus,jqXHR)
			{	
				$('#fc_comparison').empty().html(jsonData);
			}
			});

	$('.fc_btn_compare').click(function(e)
	{	
		e.preventDefault();
		var productId = $(this).attr('name');//.split('&productId=')[1];
		$.ajax({
				type: 'POST',
				url: baseDir + 'index.php?fc=module&module=featurecategories&controller=compare',
				async: true,
				cache: false,
				data: 'action=add&productId='+productId,
				success: function(jsonData,textStatus,jqXHR)
				{	
					$('#fc_comparison').empty().html(jsonData);
					$('html, body').animate({
					scrollTop: $("#fc_comparison").offset().top-100}, 1000);
				}
			});
		return false;
	});
	
});

function deleteItem(data)
{
	var productId = data.attr('href').split('&productId=')[1];
	$.ajax({
			type: 'POST',
			url: baseDir + 'index.php?fc=module&module=featurecategories&controller=compare',
			async: true,
			cache: false,
			data: 'action=delete&productId='+productId,
			success: function(jsonData,textStatus,jqXHR)
			{	
				$('#fc_comparison').empty().html(jsonData);
			}
		});
}

function deleteAllItems()
{
	$.ajax({
			type: 'POST',
			url: baseDir + 'index.php?fc=module&module=featurecategories&controller=compare',
			async: true,
			cache: false,
			data: 'action=deleteall',
			success: function(jsonData,textStatus,jqXHR)
			{	
				$('#fc_comparison').empty().html(jsonData);
			}
		});
}