<?php
//Template Name: UKFL Account - Event

global $wpdb, $current_user;

if (!is_user_logged_in()) { wp_safe_redirect('/login/'); exit; }
if (!(current_user_can('ukfl_member'))){ wp_safe_redirect('/account/'); exit; }

$js_for_footer = '';

if (isset($_POST['add_event'])){
	
	require_once '../assets/inc/Postcodes-IO-PHP.php';
	
	debug_array($_POST);
	exit;
	
	$event_post = array(
			'post_parent'	=> $_POST['team_id'],
			'post_title'    => wp_strip_all_tags( $_POST['team_name'] ),
			'post_status'   => 'draft',
			'post_author'	=> $current_user->ID,
			'post_type'		=> 'ukfl_event',
			'meta_input'   => array(
					'ukfl_event_start_date' => '',
					'ukfl_event_postcode' => '',
					'ukfl_event_lat' => '',
					'ukfl_event_long' => '',
					'ukfl_event_title' => '',
					'ukfl_event_venue' => ''
			)
	);
	$event_id = wp_insert_post( $event_post );
	
	
/*	$admin_msg = 'New team registration on '.get_bloginfo('name').':<br /><br />
	Team Captain: <strong>'.$current_user->user_firstname.' '.$current_user->user_lastname.'</strong><br />
	Email Address: <strong>'.$current_user->user_email.'</strong><br />
	UKFL Number: <strong>'.$current_user->user_login.'</strong><br /><br />
	Team Name: <strong>'.wp_strip_all_tags( $_POST['team_name'] ).'</strong><br /><br />
	Secondary Team Names:<br />
	<ul>'.implode("\n", $secondary_teams).'</ul>';
	$headers = array('Content-Type: text/html; charset=UTF-8', 'Cc:'.get_option('admin_email'));
	wp_mail('secretary@ukflyball.org.uk', '['.get_bloginfo('name').'] New Team Registration', $admin_msg, $headers); */
	
	$content = do_shortcode("[gcp_redirect_flow ref=6]"); 
	$js_for_footer = '
<script type="text/javascript">
        jQuery(function ($) {
		$("a.gcp_redirect_flow6")[0].click();
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
							
					    		<div class="row">
					    			<div class="col-md-7 col-sm-12">
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
														<input type="hidden" name="owner_ukfl" id="owner_ukfl" value="<?php echo $current_user->user_login; ?>" />
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
													<label for="ukfl_event_venue" class="col-sm-2 control-label">Venue</label>
													<div class="col-sm-10">
														<input class="form-control" type="text" id="ukfl_event_venue" name="ukfl_event_venue" required="required">
													</div>
												</div>						
												<div class="form-group">
													<label for="ukfl_event_postcode" class="col-sm-2 control-label">Postcode</label>
													<div class="col-sm-4">
														<input class="postcode form-control" type="text" id="ukfl_event_postcode" name="ukfl_event_postcode" maxlength="10" required="required">
													</div>
													<label for="tourn_title" class="col-sm-2 control-label">Short Title</label>
													<div class="col-sm-4">
														<input type="text" class="form-control" id="tourn_title" name="tourn_title" required="required">
													</div>
												</div>
												<div class="form-group">
													<label for="ukfl_event_start_date" class="col-sm-2 control-label">Start Date</label>
													<div class="col-sm-4">
														<input class="form-control datepicker" type="text" id="ukfl_event_start_date" name="ukfl_event_start_date" data-validation="date" data-validation-format="dd/mm/yyyy" required="required" placeholder="dd/mm/yyyy">
													</div>
													<label for="ukfl_event_end_date" class="col-sm-2 control-label">End Date</label>
													<div class="col-sm-4">
														<input class="form-control datepicker" type="text" id="ukfl_event_end_date" name="ukfl_event_end_date" data-validation="date" data-validation-format="dd/mm/yyyy" required="required" placeholder="dd/mm/yyyy">
													</div>
												</div>						
												<div class="form-group">
													<label for="tourn_notes" class="col-sm-2 control-label">Notes</label>
													<div class="col-sm-10">
														<textarea class="form-control" rows="4" name="tourn_notes" id="tourn_notes"></textarea>
													</div>
												</div>
											</div>
										</div>										
										
									</div>
					    			<div class="col-md-5 col-sm-12">
					    				<div class="form-group">
											<div class="controls">
												<input type="submit" name="add_event" id="add_event" value="Register Event" class="btn btn-success btn-busiprof pull-right" />
											</div>
										</div>   
					    			</div>
								</div> <!--  end of the row  -->
								 
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
						
