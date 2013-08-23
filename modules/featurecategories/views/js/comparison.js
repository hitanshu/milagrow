$(document).ready(function()
    {
        var baseDir = window.location.pathname.split('/modules')[0];
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

        $('.remove').click(function(e)
        {	
            e.preventDefault();
            var productId = $(this).attr('href');
		
            $.ajax({
                type: 'POST',
                url: baseDir + '/index.php?fc=module&module=featurecategories&controller=compare',
                async: true,
                cache: false,
                data: 'action=delete&productId='+productId,
                success: function(jsonData,textStatus,jqXHR)
                {	
                    window.location.replace(window.location);
                }
            });
		
        });	
    });

function handleCategoryClick(element)
{
    var category = element.attr('id');
    if($('.'+category).css('display')!= 'none')
    {
        $('.img_'+category).attr('src', 'modules/featurecategories/views/img/expand.png');
		
        $('.'+category).each(function()
        {
            $(this).slideUp(0);
        });
    }
		
    else
    {
        $('.img_'+category).attr('src', 'modules/featurecategories/views/img/collapse.png');
        $('.'+category).each(function()
        {
            $(this).slideDown(0);
        });
    }
}