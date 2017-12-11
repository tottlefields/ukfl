<?php
//Template Name: UKFL Account - Event

global $wpdb, $current_user;

if (!is_user_logged_in()) { wp_safe_redirect('/login/'); exit; }
if (!(current_user_can('ukfl_member'))){ wp_safe_redirect('/account/'); exit; }

$js_for_footer = '';

if (isset($_POST['add_event'])){
	
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
													<label class="col-sm-2 control-label" for="to_name">TO Name</label>
													<div class="col-sm-4">
														<input type="text" name="to_name" id="to_name" class="input form-control" value="<?php echo $current_user->user_firstname.' '.$current_user->user_lastname; ?>" readonly />
													</div>
													<label class="col-sm-2 control-label" for="to_email">TO Email</label>
													<div class="col-sm-4">
														<input type="text" name="to_email" id="to_email" class="input form-control" value="<?php echo $current_user->user_email; ?>" readonly />
														<input type="hidden" name="owner_ukfl" id="owner_ukfl" value="<?php echo $current_user->user_login; ?>" />
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
							
							
								<div class="form-group well well-lg">
									<label class="col-sm-2 control-label" for="team_name">Team Name</label>
                					<div class="col-sm-10">
                						<input type="text" name="team_name" id="team_name" class="input form-control" value="" />
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
						
