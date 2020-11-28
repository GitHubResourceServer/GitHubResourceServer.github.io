function getContent(timestamp)
{
    var queryString = {'timestamp' : timestamp};

    $.ajax(
        {
            type: 'GET',
            url: 'server.php',
            data: queryString,
            success: function(data){
                var obj = jQuery.parseJSON(data);
                $('#response').html(obj.data_from_file);
                getContent(obj.timestamp);

                //var div = $(document);
                //div.scrollTop(div.prop('scrollHeight'));

                $(document).scrollTop('9999999');
            }
        }
    );
}
$(document).ready(function(){
    getContent();
});