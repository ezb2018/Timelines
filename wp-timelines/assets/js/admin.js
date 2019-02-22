;(function($){
	$(document).ready(function() {
		/*-Remove schema-*/
		if(jQuery(".post-type-wp-timeline #review_metabox").length>0){
			jQuery('.post-type-wp-timeline #review_metabox').remove();
		}
		
		if(jQuery("#wpex_pkdate input[type=text]").length>0){
			date_fm = "mm/dd/yyyy";
			jQuery("#wpex_pkdate input[type=text]").extl_datepicker({
					"todayHighlight" : true,
					"startDate": "01/01/1000",
					"autoclose": true,
					"format":date_fm
			});
		}
		
		/*-ajax save meta-*/
		jQuery('input[name="wpex_timeline_sort"]').change(function() {
			var $this = $(this);
			var post_id = $this.attr('data-id');
			var valu = $this.val();
           	var param = {
	   			action: 'wpex_change_timeline_sort',
	   			post_id: post_id,
				value: valu
	   		};
	   		$.ajax({
	   			type: "post",
	   			url: wpex_timeline.ajaxurl,
	   			dataType: 'html',
	   			data: (param),
	   			success: function(data){
	   				return true;
	   			}	
	   		});
		});
		/*-ajax save meta-*/
		jQuery('input[name="wpex_timeline_date"]').change(function() {
			var $this = $(this);
			var post_id = $this.attr('data-id');
			var valu = $this.val();
           	var param = {
	   			action: 'wpex_change_timeline_date',
	   			post_id: post_id,
				value: valu
	   		};
	   		$.ajax({
	   			type: "post",
	   			url: wpex_timeline.ajaxurl,
	   			dataType: 'html',
	   			data: (param),
	   			success: function(data){
	   				return true;
	   			}	
	   		});
		});
		/*-- Timeline shortcode builder --*/
		var _tlsc_layout = jQuery('.postbox-container #_tlsc_layout select').val();
		var _tlsc_layout_obj = jQuery('.postbox-container #_tlsc_layout select');
		var tl_list = jQuery('#timeline-listing.postbox');
		var tl_hoz = jQuery('#timeline-hozizontal.postbox');
		var tl_list_multi = jQuery('#timeline-hozizontal-multi.postbox');
		jQuery('.post-type-wptl_scbd #post').submit(function(event) {
			event.preventDefault();
			_tlsc_layout = jQuery('.postbox-container #_tlsc_layout select').val();
			if(typeof(_tlsc_layout)!='undefined'){
				if(_tlsc_layout == 'hoz'){
					tl_list.remove();
					tl_list_multi.remove();
				}else if(_tlsc_layout == 'hoz-multi'){
					tl_hoz.remove();
					tl_list.remove();
				}else {
					tl_hoz.remove();
					tl_list_multi.remove();
				}
			}
			jQuery(this).unbind('submit').submit();
			
		});
		
		jQuery(".post-type-wptl_scbd #post").on('click', '#post-preview',function() {
			if($('#post #timeline-listing.postbox').length == 0) {
				jQuery(tl_list).insertAfter("#general.postbox");
			}
			if($('#post #timeline-hozizontal.postbox').length == 0) {
				jQuery(tl_hoz).insertAfter("#general.postbox");
			}
			if($('#post #timeline-hozizontal-multi.postbox').length == 0) {
				jQuery(tl_list_multi).insertAfter("#general.postbox");
			}
		});
		if(typeof(_tlsc_layout)!='undefined'){
			if(_tlsc_layout == 'hoz'){
				tl_hoz.addClass('active-box');
				tl_list.removeClass('active-box');
				tl_list_multi.removeClass('active-box');
			}else if(_tlsc_layout == 'hoz-multi'){
				tl_list_multi.addClass('active-box');
				tl_hoz.removeClass('active-box');
				tl_list.removeClass('active-box');
			}else {
				tl_list.addClass('active-box');
				tl_hoz.removeClass('active-box');
				tl_list_multi.removeClass('active-box');
			}
		}
		_tlsc_layout_obj.change(function(event) {
			var $val_t = jQuery(this).val();
			if( $val_t == 'hoz'){
				tl_hoz.addClass('active-box');
				tl_list.removeClass('active-box');
				tl_list_multi.removeClass('active-box');
			}else if( $val_t == 'hoz-multi'){
				tl_list_multi.addClass('active-box');
				tl_hoz.removeClass('active-box');
				tl_list.removeClass('active-box');
			}else {
				tl_list.addClass('active-box');
				tl_hoz.removeClass('active-box');
				tl_list_multi.removeClass('active-box');
			}
		});
	});
}(jQuery));