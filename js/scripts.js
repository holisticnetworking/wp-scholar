/* Scripts for WP-Scholar Admin functions */

jQuery(document).ready(function($) {
	// Add new video input:
	$(document).on('click', ".scholar_new", function() {
		$().addFields(this);
		return false;
	});	
});

jQuery.fn.addFields     = function(elm) {
    var parent      = jQuery(elm).parents('.postbox');
    var prototype   = jQuery('.scholar_prototype', parent);
    var rows        = jQuery('.scholar_row', parent);
    var newrow      = jQuery('<div />')
        .addClass("scholar_row")
        .html(prototype.html())
        .insertAfter(rows.last());
}