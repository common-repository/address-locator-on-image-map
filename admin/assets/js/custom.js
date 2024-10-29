// JavaScript Document
jQuery(document).ready(function() {
  jQuery('#store-map').click(function(e) {
                var offset = jQuery(this).offset();
                var x = e.pageX - offset.left;
                var y = e.pageY - offset.top;
                jQuery('.marker').show();
                var totalh = jQuery('#store-map').height();

                var totalw = jQuery('#store-map').width();
                //alert(x);
                var wpersent = (x * 100) / totalw
                var hpersent = (y * 100) / totalh
                jQuery('#marker').css("left", wpersent + "%");
                jQuery('#marker').css("top", hpersent + "%");

                jQuery('#location_x_cordinate').val(wpersent);
                jQuery('#location_y_cordinate').val(hpersent);

        });
 


		 jQuery('#pin_setting').submit(function(event) {

                var formData = jQuery('#pin_setting').serialize();
jQuery('.loading').show();
event.preventDefault();
                // process the form
                jQuery.ajax({
                        type: 'post',
                        url: ajaxurl + '?action=pin_setting',
                       data: formData, 
                        success: function(response) {
							
						
if(response=='done'){
setTimeout(function(){jQuery('.loading').hide(); jQuery('.msg').show();}, 1000);
setTimeout(function(){jQuery('.msg').hide(); }, 5000);     
}else{
jQuery('.msg').html('Error in form submit');
setTimeout(function(){jQuery('.loading').hide(); jQuery('.msg').show();}, 1000);
setTimeout(function(){jQuery('.msg').hide(); }, 5000);     
}
                                console.log(response);
						 //var obj = jQuery.parseJSON(response);

                        }
                });
                return false;
        });
		
		
        jQuery('#ink_image').submit(function(event) {
jQuery('.loading').show();
                var formData = jQuery('#image_path').val();
                var nonce=jQuery('#filter_nonce').val();
                // process the form
                jQuery.ajax({
                        type: 'post',
                        url: ajaxurl + '?action=map_image',
                        data: 'image=' + formData + '&filter_nonce=' +nonce,
                        success: function(response) {
							
if(response=='done'){
setTimeout(function(){jQuery('.loading').hide(); jQuery('.msg').show();}, 1000);
setTimeout(function(){jQuery('.msg').hide(); }, 5000);     
}else{
jQuery('.msg').html('Error in form submit');
setTimeout(function(){jQuery('.loading').hide(); jQuery('.msg').show();}, 1000);
setTimeout(function(){jQuery('.msg').hide(); }, 5000);     
}
                                console.log(response);
                                //var obj = jQuery.parseJSON(response);

                        }
                });
                return false;
        });
		
		
        jQuery('#ink_filter').submit(function(event) {

jQuery('.loading').show();
						 
                if (jQuery('#country').is(":checked")) {
                        var countrys = '1';
                } else {
                        var countrys = '0';
                }
                if (jQuery('#state').is(":checked")) {
                        var states = '1';
                } else {
                        var states = '0';
                }
                if (jQuery('#city').is(":checked")) {
                        var citys = '1';
                } else {
                        var states = '0';
                }
                if (jQuery('#address').is(":checked")) {
                        var addresss = '1';
                } else {
                        var addresss = '0';
                }
                var nonce=jQuery('#filter_nonce').val();
                // process the form
                jQuery.ajax({
                        type: 'post',
                        url: ajaxurl + '?action=map_image',
                        data: {
                                country: countrys,
                                state: states,
                                city: citys,
                                address: addresss,
                                filter_nonce:nonce
                        },
                        success: function(response) {

if(response=='done'){
setTimeout(function(){jQuery('.loading').hide(); jQuery('.msg').show();}, 1000);
setTimeout(function(){jQuery('.msg').hide(); }, 5000);     
}else{
jQuery('.msg').html('Error in form submit');
setTimeout(function(){jQuery('.loading').hide(); jQuery('.msg').show();}, 1000);
setTimeout(function(){jQuery('.msg').hide(); }, 5000);     
}

                                console.log(response);
                                //var obj = jQuery.parseJSON(response);

                        }
                });
                return false;
        });
});
/* user clicks button on custom field, runs below code that opens new window */
jQuery('#upload_image').click(function() {
        tb_show('Upload a Image', 'media-upload.php?referer=media_page&type=image&TB_iframe=true&post_id=0', false);
        return false;
});
jQuery('#upload_pin').click(function() {
        tb_show('Upload a Image', 'media-upload.php?referer=media_page&type=image&TB_iframe=true&post_id=0', false);
        return false;
});
// window.send_to_editor(html) is how WP would normally handle the received data. It will deliver image data in HTML format, so you can put them wherever you want.
window.send_to_editor = function(html) {

        var res = html.split('src="');
        var res = res[1].split('"');

        jQuery('#image_path').val(res[0]);
        tb_remove(); // calls the tb_remove() of the Thickbox plugin

}

