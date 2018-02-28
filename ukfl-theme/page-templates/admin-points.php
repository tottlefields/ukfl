<?php
//Template Name: UKFL Points Uplaoder

global $wpdb, $current_user;
if (!is_user_logged_in()) { wp_safe_redirect('/login/'); exit; }
if (!(current_user_can('ukfl_official'))){ wp_safe_redirect('/account/'); exit; }

get_header();
get_template_part('index', 'bannerstrip');

?>
<!-- Blog & Sidebar Section -->
<section>		
	<div class="container">
		<div class="row">
			<!--Blog Posts-->
			<div class="page-content">
				<article id="post-<?php the_ID(); ?>" <?php post_class('post'); ?> > 					
					<form method="post" class="form" enctype="multipart/form-data" id="pointsForm">
						<div class="entry-content">
							<div class="col-md-4 col-xs-12">
								<div class="well">
									<div class="form-group">
										<label class="control-label" for="event_date">Event Date</label>
									        <input type="text" name="event_date" id="event_date" class="ukfl-datepicker input form-control" value="" placeholder="dd/mm/yyyy" />
									</div>
									<div class="form-group events_ok" style="display:none;" id="event_select">
										<label class="control-label" for="event">Event</label>
										<select id="event_list" name="event" class="form-control"></select>
									</div>
									<div class="alert alert-danger" style="display:none;" id="event_empty" role="alert"><strong>Sorry!</strong> No UKFL&copy; events were found for that date.</div>
									<div class="events_ok" style="display:none;">
										<div class="input-group">
		                                                                        <label class="input-group-btn">
        		                                                                        <span class="btn btn-primary" style="height:40px; padding:8px 12px;">Browse&hellip;<input type="file" name="portal_file" style="display:none;"></span>
                		                                                        </label>
                        		                                                <input type="text" class="form-control" readonly />
                                		                                </div>
										<p class="small"><em>Please select a comma separated value (CSV) file with columns for : Dog No. / Team / Team_Type / Points.</em></p>
                                        	                        	<button type="submit" class="btn btn-default btn-block" name="submit" id="submitBtn">GO!</button>
										<input type="hidden" id="action" name="action" value="upload_points" />
									</div>
								</div>
							</div>
							<div class="col-md-8 col-xs-12">
								<span class="statusMsg"></span>
							</div>
						</div>
					</form>
				</article>
			</div>
			<!--/End of Blog Posts-->
		</div>	
	</div>
</section>
<!-- End of Blog & Sidebar Section -->
 
<div class="clearfix"></div>
<?php get_footer(); ?>
<script>
jQuery(function ($) {
	$(".ukfl-datepicker").datepicker({
		format: "dd/mm/yyyy",
		weekStart: 1,
		daysOfWeekHighlighted: "0,6",
		autoclose: true,
		todayHighlight: true,
	});
	$("#event_date").datepicker().on("changeDate", function(e) {
		$('.events_ok').hide();
		$('#event_empty').hide();
		var events = findEventByDate(e.format('yyyymmdd'));
	});

	$("#pointsForm").on('submit', function(e){
	        e.preventDefault();
		$.ajax({
			type: "post",
			data: new FormData(this),
			url : ukflAjax.ajax_url, 
			contentType: false,
		        cache: false,
			processData:false,
			beforeSend: function(){
				$('#submitBtn').attr("disabled","disabled");
        			$('#pointsForm').css("opacity",".5");
			},
			success: function(msg){
				$('.statusMsg').html('');
				$('.statusMsg').html('<pre>'+msg+'</pre>');
				$('#pointsForm').css("opacity","");
		                $("#submitBtn").removeAttr("disabled");
			}
            	});	 
//		console.log(new FormData(this));
	});

	// We can attach the `fileselect` event to all file inputs on the page
	$(document).on('change', ':file', function() {
		var input = $(this), numFiles = input.get(0).files ? input.get(0).files.length : 1, label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
		input.trigger('fileselect', [ numFiles, label ]);
	});

	// We can watch for our custom `fileselect` event like this
	$(document).ready(function() 	{ 
		$(':file').on('fileselect', function(event, numFiles, label) {
			var input = $(this).parents('.input-group').find(':text'), log = numFiles > 1 ? numFiles + ' files selected' : label;
			if (input.length) {
				input.val(log);
			} else {
				if (log)
					alert(log);
			}

		});
	}); 

});
</script>
