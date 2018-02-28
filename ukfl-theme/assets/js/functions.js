
function findEventByDate(date_lookup) {

	var data = {
		'action' : 'event_search',
		'meta_value' : date_lookup
	};

	jQuery.ajax({
		type : "post",
		dataType : "json",
		url : ukflAjax.ajax_url,
		data : data,
		success : function(results) {
			if(results.length === 0){ jQuery('#event_empty').show(); }
			else{	
				jQuery('#event_list').empty();
				for (var x=0; x<results.length; x++){
					eventDetails = results[x];
					jQuery('#event_list').append(jQuery('<option>', {value:eventDetails.event_id, text:eventDetails.event_title}));
				}
				//jQuery('#event_select').show();
				jQuery('.events_ok').show();
			}
		}
	});
}
