<?php
//Template Name: UKFL Account - Team

global $wpdb, $current_user;

if (!is_user_logged_in()) { wp_safe_redirect('/login/'); exit; }
if (!(current_user_can('ukfl_member'))){ wp_safe_redirect('/account/'); exit; }

debug_array($_POST);

if (isset($_POST['add_team'])){
	
	$team_post = array(
			'post_title'    => wp_strip_all_tags( $_POST['team_name'] ),
			'post_status'   => 'pending',
			'post_author'	=> $current_user->ID,
			'post_type'		=> 'ukfl_team'
	);
	//$team_id = wp_insert_post( $team_post );
	$team_id = 177;
	
	
	$content = do_shortcode("[gcp_redirect_flow ref=4]"); 
	$js_for_footer = '
<script type="text/javascript">
        jQuery(function ($) {
		console.log($("a.gcp_redirect_flow4"));
		//$("a.gcp_redirect_flow4")[0].click();
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
								<div class="form-group">
									<label class="col-sm-2 control-label" for="team_name">Team Name</label>
                					<div class="col-sm-10">
                						<input type="text" name="team_name" id="team_name" class="input form-control" value="" />
                					</div>
                				</div>
                				<div class="panel panel-default">
                					<div class="panel-heading"><h3 class="panel-title">Secondary Team Names</h3></div>
                					<div class="panel-body">
                						<p>Please list up to 5 secondary team names that you wish to request as the same time as your team application. Once approved, you will have the option to add as many more secondary teams as you wish, subject to committee approval.</p><p>These names may or may not include the above requested team name but they will be your racing team names. Therefore, if you wish to race under the above Team Name, please include it below as well.</p>
                					</div>
                					<table class="table">
                						<thead><tr><th>#</th><th>Secondary Team Name</th><th>Team Type</th></tr></thead><tbody>            						
                						<?php 
                						$team_types = array('league' => "League Team", 'multibreed' => 'Multibreed Team');
                						for ($i=1; $i<=5; $i++){ ?>
                							<tr>
                								<td><?php echo $i.'.'; ?></td>
                								<td><div class="form-group"><div class="col-sm-10"><input type="text" name="sub_team[]" class="input form-control" value="" /></div></div></td>
                								<td><select class="form-control" name=sub_team_type[]">
                									<option value="N/A">Select type</option>
                								<?php foreach ($team_types as $key => $value) { 
                									echo '<option value="'.$key.'">'.$value.'</option>';
                								} ?>
                								</select></td>                							
                							</tr>                						
										<?php } ?>
									</tbody></table>
                				</div>
								<div class="form-group">
									<div class="controls">
										<input type="submit" name="add_team" id="add_team" value="Add Team" class="btn btn-success btn-busiprof pull-right" />
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
						
