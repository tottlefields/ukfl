<?php
//Template Name: UKFL Account - Team

global $wpdb, $current_user;

if (!is_user_logged_in()) { wp_safe_redirect('/login/'); exit; }
if (!(current_user_can('ukfl_member'))){ wp_safe_redirect('/account/'); exit; }

$team_types = array('league' => "League Team", 'multibreed' => 'Multibreed Team');

if (isset($_POST['add_team'])){
	
	$team_post = array(
			'post_title'    => wp_strip_all_tags( $_POST['team_name'] ),
			'post_status'   => 'pending',
			'post_author'	=> $current_user->ID,
			'post_type'		=> 'ukfl_team'
	);
	$team_id = wp_insert_post( $team_post );
	
	$teams_by_type = array();
	for ($i=0; $i<5; $i++){
		if (isset($_POST['sub_team'][$i]) && $_POST['sub_team'][$i] != ''){
			wp_insert_post(
				array(
						'post_parent'	=> $team_id,
						'post_title'    => wp_strip_all_tags( $_POST['sub_team'][$i] ),
						'post_status'   => 'pending',
						'post_author'	=> $current_user->ID,
						'post_type'		=> 'ukfl_sub-team',
						'meta_input'   => array(
							'ukfl_team_type' => $_POST['sub_team_type'][$i],
						)
				)
			);
			if (!isset($teams_by_type[$team_types($_POST['sub_team_type'][$i])])){ $teams_by_type[$team_types($_POST['sub_team_type'][$i])] = array(); }
			array_push($teams_by_type[$_POST['sub_team_type'][$i]], $_POST['sub_team'][$i]);
		}
	}
	
	$admin_msg = 'New team registration on '.get_bloginfo('name').':<br /><br />
	Team Captain: <strong>'.$current_user->user_firstname.' '.$current_user->user_lastname.'</strong><br />
	UKFL Number: <strong>'.$current_user->user_login.'</strong><br /><br />
	Team Name: <strong>'.wp_strip_all_tags( $_POST['team_name'] ).'</strong><br />
	';
	foreach ($teams_by_type as $team_type => $sub_teams){
		echo $team_type.'<br /><ul>';
		foreach ($sub_teams as $sub_team){
			echo '<li>'.$sub_team.'</li>';
		}
		echo '</ul>';
	}
	wp_mail(get_option('admin_email'), '['.get_bloginfo('name').'] New Team Registration', $admin_msg, $headers);
	
	$content = do_shortcode("[gcp_redirect_flow ref=4]"); 
	$js_for_footer = '
<script type="text/javascript">
        jQuery(function ($) {
		console.log($("a.gcp_redirect_flow4"));
		$("a.gcp_redirect_flow4")[0].click();
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
						<?php if (isset($_POST['add_team'])){ echo $content; } else {?>
							<?php the_post(); the_content(); ?>
							<form method="post" class="form form-horizontal">
								<div class="form-group well well-lg">
									<label class="col-sm-2 control-label" for="team_name">Team Name</label>
                					<div class="col-sm-10">
                						<input type="text" name="team_name" id="team_name" class="input form-control" value="" />
                					</div>
                				</div>
                				<h4>Secondary Team Names</h4>
								<p>Please list up to 5 secondary team names that you wish to request as the same time as your team application. Once approved, you will have the option to add as many more secondary teams as you wish, subject to committee approval.</p>
								<p>These names may or may not include the above requested team name but they will be your racing team names. Therefore, if you wish to race under the above Team Name, please include it below as well.</p>
								<?php 
                				for ($i=1; $i<=5; $i++){ ?>
                					<div class="form-group"><div class="col-sm-8">
	                					<div class="input-group">
	                						<div class="input-group-addon"><?php echo $i.'.'; ?></div>
	                						<input type="text" name="sub_team[]" class="input form-control" value="" placeholder="Secondary Team Name" />
	                					</div>
	                				</div>
	                				<div class="col-sm-4">
		                				<select class="form-control" name=sub_team_type[]">
		                					<option value="N/A">Select Team Type</option>
		                					<?php foreach ($team_types as $key => $value) { 
		                						echo '<option value="'.$key.'">'.$value.'</option>';
		                					} ?>
		                				</select>  
		                			</div></div>           						
								<?php } ?>
								<div class="form-group">
									<div class="controls">
										<input type="submit" name="add_team" id="add_team" value="Register Team" class="btn btn-success btn-busiprof pull-right" />
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
						
