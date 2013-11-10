function linkedin_addTag() {   
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
        content = content.replace('#linkedin ','');
        content = '#linkedin '+content;

        jQuery('.linkedin_share_counter').show();
        LinkedinCountMessage(field);

        jQuery(field).val(content);
    });
}

function LinkedinCountMessage(field) {
   jQuery(document).ready(function() {
       
        linkedinCountListner();
       
        text = jQuery(field).val();
        text = text.replace('#linkedin','');

        var textlength = parseInt(text.length);
        if (textlength >= 700) {
            jQuery('.linkedin_share_counter').html('You reached the maximum allowed characters for LinkedIn.');
        } else {
            jQuery('.linkedin_share_counter').html((700-textlength));
            return true;
        }
    });
}

function linkedinCountListner(){
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
            text = text.replace('#linkedin','');
            var textlength = parseInt(text.length);

            var patt1=/#linkedin/gi;
            if(jQuery(field).val().match(patt1)){
                jQuery('.linkedin_share_counter').show();

                if(textlength > 700){
                    jQuery('.linkedin_share_counter').html('You reached the maximum allowed characters for twitter.');
                }else{
                    jQuery('.linkedin_share_counter').html((700-textlength));
                    if(700-textlength < 10){
                        jQuery('.linkedin_share_counter').addClass('twitter_share_counter_red');
                    }else{
                        jQuery('.linkedin_share_counter').removeClass('twitter_share_counter_red');
                    }
                return true;
                }
            }else{
                jQuery('.linkedin_share_counter').removeClass('twitter_share_counter_red');
                jQuery('.linkedin_share_counter').html('700');
                jQuery('.linkedin_share_counter').hide();
            }
        });
    });
}