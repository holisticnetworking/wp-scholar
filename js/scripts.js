/* Scripts for WP-Scholar Admin functions */

jQuery(document).ready(function() {
	// Add new video input:
	jQuery("#new-title").click(function() {
		jQuery().addTitleField();
		return false;
	});	
});

jQuery.fn.addTitleField     = function() {
    
}