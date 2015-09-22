jQuery(document).ready(function($) {

	"use strict";

	ci_collapsible_init();
	$('body').on('click', '.ci-collapsible legend', function() {
		var arrow = $(this).find('i');
		if( arrow.hasClass('dashicons-arrow-down') ) {
			arrow.removeClass('dashicons-arrow-down').addClass('dashicons-arrow-right');
			$(this).siblings('.elements').slideUp();
		} else {
			arrow.removeClass('dashicons-arrow-right').addClass('dashicons-arrow-down');
			$(this).siblings('.elements').slideDown();
		}
	});


	// Handle color pickers.
	ciPicker();
	$(document).ajaxSuccess(function(e, xhr, settings) {
		if(settings.data.search('action=save-widget') != -1 ) {
			ciPicker();
		}
	});


	// Post Types Widget with repeating fields
	_sortable();

	$('body').on('click', '.ci-repeating-add-field', function() {
		var repeatable_area = $(this).siblings('.inside');
		var fields = repeatable_area.children('.field-prototype').clone(true).removeClass('field-prototype').removeAttr('style').appendTo(repeatable_area);
		_sortable();
		return false;
	});

	$('body').on('click', '.ci-repeating-remove-field', function() {
		var field = $(this).parents('.post-field');
		field.remove();
		return false;
	});


	// Widget Actions on Save
	$(document).ajaxSuccess(function(e, xhr, options){
		if( options.data.search( 'action=save-widget' ) != -1 ) {
			var widget_id;

			// CI Items
			if( ( widget_id = options.data.match( /widget-id=(ci-items-\d+)/ ) ) !== null ) {
				var widget = $("input[name='widget-id'][value='" + widget_id[1] + "']").parent();
				_sortable( widget );
				ci_collapsible_init( widget );
			}

			// CI Split Content
			if( ( widget_id = options.data.match( /widget-id=(ci-split-content-\d+)/ ) ) !== null ) {
				var widget = $("input[name='widget-id'][value='" + widget_id[1] + "']").parent();
				ci_collapsible_init( widget );
			}

			// CI Latest Posts
			if( ( widget_id = options.data.match( /widget-id=(ci-latest-posts-\d+)/ ) ) !== null ) {
				var widget = $("input[name='widget-id'][value='" + widget_id[1] + "']").parent();
				ci_collapsible_init( widget );
			}

			// CI Events
			if( ( widget_id = options.data.match( /widget-id=(ci-events-\d+)/ ) ) !== null ) {
				var widget = $("input[name='widget-id'][value='" + widget_id[1] + "']").parent();
				ci_collapsible_init( widget );
			}

			// CI Top Tracks
			if( ( widget_id = options.data.match( /widget-id=(ci-top-tracks-\d+)/ ) ) !== null ) {
				var widget = $("input[name='widget-id'][value='" + widget_id[1] + "']").parent();
				_sortable( widget );
				ci_collapsible_init( widget );
			}

			// CI Tracklisting
			if( ( widget_id = options.data.match( /widget-id=(ci-tracklisting-\d+)/ ) ) !== null ) {
				var widget = $("input[name='widget-id'][value='" + widget_id[1] + "']").parent();
				ci_collapsible_init( widget );
			}

			// CI Newsletter
			if( ( widget_id = options.data.match( /widget-id=(ci-newsletter-\d+)/ ) ) !== null ) {
				var widget = $("input[name='widget-id'][value='" + widget_id[1] + "']").parent();
				ci_collapsible_init( widget );
			}

		}

	});


	// CI Items widget
	$('body').on('change', '.ci-repeating-fields .posttype_dropdown', function(){

		var fieldset = $(this).parent().parent();

		$.ajax({
			type: "post",
			url: ThemeWidget.ajaxurl,
			data: {
				action: 'ci_items_widget_post_type_ajax_get_posts',
				post_type_name: $(this).val(),
				name_field: fieldset.find('.posts_dropdown').attr('name')
			},
			dataType: 'text',
			beforeSend: function() {
				fieldset.addClass('loading');
				fieldset.find('.posts_dropdown').prop('disabled', 'disabled').css('opacity','0.5');
			},
			success: function(response){
				if(response != '') {
					fieldset.find('select.posts_dropdown').html(response).children('option:eq(1)').prop('selected', 'selected');
					fieldset.find('.posts_dropdown').removeAttr('disabled').css('opacity','1');
				}
				else {
					fieldset.find('select.posts_dropdown').html('').prop('disabled', 'disabled').css('opacity','0.5');
				}

				fieldset.removeClass('loading');

			}
		});//ajax

	});


	//CI Split Content widget
	$('.widgets-holder-wrap').on('change', 'select[id*="ci-split-content"][name*="\\[post_type_name\\]"]', function(){

		var post_type_dropdown = $(this);

		$.ajax({
			type: "post",
			url: ThemeWidget.ajaxurl,
			data: {
				action: 'ci_widget_split_content_ajax_get_posts',
				post_type_name: $(this).val(),
				name_field: post_type_dropdown.siblings('.ci_widget_post_type_posts_dropdown').children('select').attr('name')
			},
			dataType: 'text',
			beforeSend: function() {
				post_type_dropdown.siblings('.loading_posts').show();
			},
			success: function(response){
				if(response != '') {
					post_type_dropdown.siblings('.ci_widget_post_type_posts_dropdown').html(response);
				}
				else {
					post_type_dropdown.siblings('.ci_widget_post_type_posts_dropdown').children('select').html('');
				}

				post_type_dropdown.siblings('.loading_posts').hide();

			}
		});//ajax

	});


	function ciPicker() {
		var ciColorPicker = jQuery('#widgets-right .colorpckr, #wp_inactive_widgets .colorpckr');
		ciColorPicker.each(function(){
			jQuery(this).wpColorPicker();
		});
	}


});

_sortable = function( selector ) {
	if( selector === undefined ) {
		jQuery('.ci-repeating-fields .inside').sortable({ placeholder: 'ui-state-highlight' });
	} else {
		jQuery('.ci-repeating-fields .inside', selector).sortable({ placeholder: 'ui-state-highlight' });
	}
}

ci_collapsible_init = function( selector ) {
	if( selector === undefined ) {
		jQuery('.ci-collapsible .elements').hide();
		jQuery('.ci-collapsible legend i').removeClass('dashicons-arrow-down').addClass('dashicons-arrow-right');
	} else {
		jQuery('.ci-collapsible .elements', selector).hide();
		jQuery('.ci-collapsible legend i', selector).removeClass('dashicons-arrow-down').addClass('dashicons-arrow-right');
	}
}
