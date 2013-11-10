function facebook_addTag() {   
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
        content = content.replace('#facebook ','');
        content = '#facebook '+content;

        jQuery(field).val(content);
    });
}