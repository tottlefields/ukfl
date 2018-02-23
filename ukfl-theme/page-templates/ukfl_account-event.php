<?php
//Template Name: UKFL Account - Event

global $wpdb, $current_user;

if (!is_user_logged_in()) { wp_safe_redirect('/login/'); exit; }
if (!(current_user_can('ukfl_member'))){ wp_safe_redirect('/account/'); exit; }

$js_for_footer = '';

if (isset($_POST['add_event'])){
	
	require_once get_stylesheet_directory().'/assets/inc/Postcodes-IO-PHP.php';
	
	$postcode = new Postcode();
	$lookup = $postcode->lookup($_POST['ukfl_event_postcode']);
	
	$start_date = dateToSQL($_POST['ukfl_event_start_date']);
	$team_name = get_the_title($_POST['host_team']);
	$event_title = str_replace(" ", "-", $team_name)."_".SQLToDate($start_date, 'jMy');
	
	$open_date = new DateTime($start_date);
	$open_date->sub(new DateInterval('P60D'));
	$interval = 4 - $open_date->format('N');
	if ($interval > 0){ $open_date->add(new DateInterval('P'.$interval.'D')); }
	elseif ($interval < 0){ $interval *= -1; $open_date->sub(new DateInterval('P'.$interval.'D')); }
	
	$event_post = array(
			'post_parent'	=> $_POST['host_team'],
			'post_title'    => $event_title,
			'post_status'   => 'draft',
			'post_author'	=> $current_user->ID,
			'post_type'		=> 'ukfl_event',
			'meta_input'   => array(
					'ukfl_event_open_date' => $open_date->format('Ymd'),
					'ukfl_event_start_date' => $start_date,
					'ukfl_event_end_date' => dateToSQL($_POST['ukfl_event_end_date']),
					'ukfl_event_postcode' => $_POST['ukfl_event_postcode'],
					'ukfl_event_lat' => $lookup->latitude,
					'ukfl_event_long' => $lookup->longitude,
					//'ukfl_event_title' => $team_name." - ".$_POST['ukfl_event_title'],
					'ukfl_event_title' => $_POST['ukfl_event_title'],
					'ukfl_event_venue' => $_POST['ukfl_event_venue'],
					'ukfl_event_notes' => $_POST['tourn_notes']
			)
	);

	$event_id = wp_insert_post( $event_post );
	$current_user->add_role('tournament_organiser');
	
	$content = do_shortcode("[gcp_redirect_flow ref=6]"); 
	$js_for_footer = '
<script type="text/javascript">
        jQuery(function ($) {
		$("a.gcp_redirect_flow6")[0].click();
	 } );
</script>';
}
else{
	$js_for_footer = '
<script type="text/javascript">
        jQuery(function ($) {
			$.validate();
			
			$(".ukfl-datepicker").datepicker({
				format: "dd/mm/yyyy",
				weekStart: 1,
				daysOfWeekHighlighted: "0,6",
				autoclose: true,
				todayHighlight: true,
				orientation: "bottom auto"
			});
			$("#ukfl_event_start_date").datepicker().on("changeDate", function(e) {
        		// `e` here contains the extra attributes
				$("#ukfl_event_end_date").datepicker("setDate", e.date);
    		});
			$("#ukfl_event_start_date").datepicker({
				onSelect: function(dateText, inst) {
					var date = $("#ukfl_event_start_date").datepicker("getDate");
					$("#ukfl_event_end_date").datepicker("setDate", date);
					//date.setMonth(date.getMonth() - 1)
					//$("#close_date").datepicker("setDate", date);
				}
			});
	 } );	
</script>';
}

get_header();
include(locate_template('index-bannerstrip.php'))
?>
<!-- Blog & Sidebar Section -->
<section>		
	<div class="container">
		<div class="row">
			<!--Blog Posts-->
			<div class="col-md-12 col-xs-12">
				<div class="page-content">
					<article id="page-<?php the_ID(); ?>" <?php post_class('page'); ?> > 					
						<div class="entry-content">
						<?php if (isset($_POST['add_event'])){ echo $content; } else {?>
							<?php the_post(); the_content(); ?>
							<form method="post" class="form form-horizontal">
							
										<div class="panel panel-default">					
											<div class="panel-heading">
												<h3 class="panel-title">Host Details</h3>
											</div>
											<div class="panel-body">
												<div class="form-group">
													<label class="col-sm-2 control-label" for="host_team">Host Team</label>
													<div class="col-sm-10">
														<?php echo get_club_dropdown_menu("host_team", "Select Host Team...", null); ?>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-2 control-label" for="to_name">Organiser</label>
													<div class="col-sm-4">
														<input type="text" name="to_name" id="to_name" class="input form-control" value="<?php echo $current_user->user_firstname.' '.$current_user->user_lastname; ?>" readonly />
													</div>
													<div class="col-sm-6">
														<input type="text" name="to_email" id="to_email" class="input form-control" value="<?php echo $current_user->user_email; ?>" readonly />
													</div>
												</div>
											</div>
										</div>
										
										<div class="panel panel-default">					
											<div class="panel-heading">
												<h3 class="panel-title">Event Details</h3>
											</div>
											<div class="panel-body">						
												<div class="form-group">
													<label for="ukfl_event_venue" class="col-sm-2 control-label">Venue Address</label>
													<div class="col-sm-10">
														<input class="form-control" type="text" id="ukfl_event_venue" name="ukfl_event_venue" required="required">
													</div>
												</div>						
												<div class="form-group">
													<label for="ukfl_event_postcode" class="col-sm-2 control-label">Postcode</label>
													<div class="col-sm-4">
														<input class="postcode form-control" type="text" id="ukfl_event_postcode" name="ukfl_event_postcode" maxlength="10" required="required">
													</div>
													<label for="ukfl_event_title" class="col-sm-2 control-label">Short Title</label>
													<div class="col-sm-4">
														<input type="text" class="form-control" id="ukfl_event_title" name="ukfl_event_title" required="required">
													</div>
												</div>
												<div class="form-group">
													<label for="ukfl_event_start_date" class="col-sm-2 control-label">Start Date</label>
													<div class="col-sm-4">
														<input class="form-control ukfl-datepicker" type="text" id="ukfl_event_start_date" name="ukfl_event_start_date" required="required" placeholder="dd/mm/yyyy">
													</div>
													<label for="ukfl_event_end_date" class="col-sm-2 control-label">End Date</label>
													<div class="col-sm-4">
														<input class="form-control ukfl-datepicker" type="text" id="ukfl_event_end_date" name="ukfl_event_end_date" required="required" placeholder="dd/mm/yyyy">
													</div>
												</div>						
												<div class="form-group">
													<label for="tourn_notes" class="col-sm-2 control-label">Notes<br /><small><em>Please fill in team types being offered.</em></small></label>
													<div class="col-sm-10">
														<textarea class="form-control" rows="4" name="tourn_notes" id="tourn_notes"></textarea>
													</div>
												</div>
											</div>
										</div>										
									<div class="alert alert-info">On clicking the "Register Event" button, you will be redirected to the external GoCardless website to create a one-off pyament for this event and the event request will be forwarded to the UKFL&copy; secretary who will contact you in due time.</div>	
					    				<div class="form-group">
											<div class="controls">
												<input type="submit" name="add_event" id="add_event" value="Register Event" class="btn btn-success btn-busiprof pull-right" />
											</div>
										</div>   
								 
							</form>
							<?php } ?>
						</div>
					</article>
				</div>
			</div>
			<!--/End of Blog Posts-->
		</div>	
	</div>
</section>
<!-- End of Blog & Sidebar Section -->
 
<div class="clearfix"></div>
<?php get_footer(); ?>
<?php echo $js_for_footer; ?>
						
