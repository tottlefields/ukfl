<?php
//Template Name: UKFL Account - Team

global $wpdb, $current_user;

if (!is_user_logged_in()) { wp_safe_redirect('/login/'); exit; }
if (!(current_user_can('ukfl_member'))){ wp_safe_redirect('/account/'); exit; }

debug_array($_POST);

if (isset($_POST['add_team'])){
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
						
