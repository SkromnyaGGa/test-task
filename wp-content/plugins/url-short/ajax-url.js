jQuery(function($){
    $('#shorturl-form').submit(function(event){
        event.preventDefault();

        var data = {
            action: 'url_short',
            url: $('#url').val()
        };

        $.post(ajax_object.ajaxurl, data, function(response){
            let url = response.url;
            $('#shorturl-result').html(url);
        });
    });
});