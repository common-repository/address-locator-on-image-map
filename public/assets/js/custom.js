// JavaScript Document
jQuery(document).ready(function() {

 	jQuery('.office-dots a').click(function() {
		var rel_val = jQuery(this).attr('rel');
		jQuery('#' + rel_val).addClass("abcd");
		if (jQuery('#' + rel_val).css('display') == 'none') {
			jQuery('#' + rel_val).fadeIn('slow');
		}
	});
	jQuery('.close').click(function() {
		var rel_val = jQuery(this).attr('rel');
		jQuery(this).parent('.mid-america-div').fadeOut('slow');
		jQuery('#' + rel_val).removeClass("abcd");
	});
	jQuery('.office-dots a').mouseover(function() {
		jQuery('.mid-america-div').fadeOut("slow");
		jQuery('.mid-america-div').removeClass("abcd");
		var rel_val = jQuery(this).attr('rel');
		if (jQuery('#' + rel_val).css('display') == 'none') {
			jQuery('#' + rel_val).fadeIn('slow');
		}
	});
	jQuery('.office-dots a').mouseout(function() {
		var rel_val = jQuery(this).attr('rel');
		if (jQuery('#' + rel_val).hasClass("abcd")) {} else {
			jQuery('#' + rel_val).fadeOut("slow");
			jQuery('#' + rel_val).removeClass("abcd");
		}
	});
	jQuery('#store-map').click(function(e) {
		var offset = jQuery(this).offset();
		var x = e.pageX - offset.left;
		var y = e.pageY - offset.top;
		jQuery('.marker').show();
		var totalh = jQuery('#store-map').height();
		var totalw = jQuery('#store-map').width();
		var wpersent = (x * 100) / totalw
		var hpersent = (y * 100) / totalh
		jQuery('#marker').css("left", wpersent + "%");
		jQuery('#marker').css("top", hpersent + "%");
		jQuery('#location_x_cordinate').val(wpersent);
		jQuery('#location_y_cordinate').val(hpersent);
	});
	jQuery( "#selectcontry" ).change(function() {
		var formData = this.value;
		jQuery.ajax({
			type: 'post',
			url: ajaxurl.ajaxurl + '?action=office_state',
			data: 'country=' + formData,
			success: function(response) {
				var select = jQuery('#selectState');
				select.empty();
				jQuery("#selectState").append(response);
				console.log(response);
			}
		});
		return false;
	});
	jQuery( "#selectState" ).change(function() {
		var formData = this.value;
		jQuery.ajax({
			type: 'post',
			url: ajaxurl.ajaxurl + '?action=office_city',
			data: 'country=' + formData,
			success: function(response) {
				var select = jQuery('#selectCity');
				select.empty();	
				jQuery("#selectCity").append(response);
				console.log(response);
			}
		});
		return false;
	});	
	jQuery( "#selectCity" ).change(function() {
		var formData = this.value;
		jQuery.ajax({
			type: 'post',
			url: ajaxurl.ajaxurl + '?action=office_address',
			data: 'country=' + formData,
			success: function(response) {
				var select = jQuery('#selectCompany');
				select.empty();
				jQuery("#selectCompany").append(response);
				console.log(response);
			}
		});
		return false;
	});
});


