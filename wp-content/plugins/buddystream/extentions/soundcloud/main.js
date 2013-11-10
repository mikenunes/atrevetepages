function buddystream_addTwitterTag() {   
    jQuery(document).ready(function() {

        if(jQuery('#whats-new').length){
            var field = '#whats-new';
        }
        else if(jQuery('#topic_title').length){
            var field = '#topic_title';
        }
        else if(jQuery('#reply_text').length){
            var field = '#reply_text';
        }

        content = jQuery(field).val();
        content = content.replace('#twitter ','');
        content = '#twitter '+content;

        jQuery('.bs_share_counter').show();
        countMessage(field);

        jQuery(field).val(content);
    });
}


function countMessage(field) {
    text = jQuery(field).val();
    text = text.replace('#twitter','');
    
    var textlength = parseInt(text.length);
    if (textlength > 140) {
        jQuery('.bs_share_counter').html('You reached the maximum allowed characters for twitter. ');
    } else {
        jQuery('.bs_share_counter').html('<b>'+(140-textlength)+'</b>');
        return true;
    }
}

jQuery(document).ready(function() {
    if(jQuery('#whats-new').length){
        var field = '#whats-new';
    }

    else if(jQuery('#topic_title').length){
        var field = '#topic_title';
    }

    else if(jQuery('#reply_text').length){
        var field = '#reply_text';
    }

    jQuery(field).keyup(function(){
        text = jQuery(field).val();
        text = text.replace('#twitter','');
        var textlength = parseInt(text.length);

        var patt1=/#twitter/gi;
        if(jQuery(field).val().match(patt1)){
            jQuery('.bs_share_counter').show();

            if(textlength > 140){
                jQuery('.bs_share_counter').html('');
            }else{
                jQuery('.bs_share_counter').html('<b>'+(140-textlength)+'</b>');
            return true;
            }
        }else{
            jQuery('.bs_share_counter').html('<b>140</b>');
            jQuery('.bs_share_counter').hide();
        }
    });
});