/*
	Scripts for alumni Profile Edit page.
*/

jQuery(document).ready(function() {
	// Get names from form fields
	function getNames() {
		var first = jQuery('#_majal_alumni_alumnus_namefirst').val();
		var second = jQuery('#_majal_alumni_alumnus_namesecond').val();
		return first + ' ' + second;
	};
	
	// Insert hidden form field and initialise with names
	// Wordpress will save custom field value as post title
	jQuery('#wp_meta_box_nonce').after(
		'<input type="hidden" name="post_title" id="post_title" value="' + getNames() + '">'
	);
	
	// Update hidden field value
	jQuery('input[id^="_majal_alumni_alumnus_name"]').blur(function() {
		jQuery('input#post_title').val(getNames());
	});
	
	// Hide Other Industry area metabox if 'Other' not ticked
	if(!jQuery('#_majal_alumni_alumnus_jobcurrent_industryareas5').is(':checked')) {
		jQuery('.cmb_id__majal_alumni_alumnus_jobcurrent_industryareas_other').hide();
	};
	
	jQuery('#_majal_alumni_alumnus_jobcurrent_industryareas5').change(function() {
		if(jQuery(this).is(':checked')) {
			jQuery('.cmb_id__majal_alumni_alumnus_jobcurrent_industryareas_other').show();
        } else {
			jQuery('#_majal_alumni_alumnus_jobcurrent_industryareas_other').val('');
			jQuery('.cmb_id__majal_alumni_alumnus_jobcurrent_industryareas_other').hide();
        };
	});
	
	// Give feedback on quote length and warn when quote exceeds max chars
	var quotemaxlength = 150;
	jQuery("p:contains('Quote from interview')").after('<p id="majal_quote_charcount" class="cmb_metabox_description"></p>');
	var quotefield = jQuery("#_majal_alumni_alumnus_quote");
	var quotecountfeedback = jQuery("#majal_quote_charcount");
	function countquote() {
		var chars = jQuery(quotefield).val().length;
		var remainingchars = quotemaxlength - chars;
		if(remainingchars < 0) {
			jQuery(quotecountfeedback).css("color", "red")
			.html("Characters remaining: " + remainingchars + "<br />The quote is too long to display correctly in the Homepage Featured Profile slider.");
		} else {
			jQuery(quotecountfeedback).css("color", "#aaa")
			.html("Characters remaining: " + remainingchars);
		};
	};
	countquote();
	jQuery(quotefield).keyup(function() { 
		countquote();
	});
});