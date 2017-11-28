<?php
//Template Name: UKFL Account - Team

global $wpdb, $current_user;

if (!is_user_logged_in()) { wp_safe_redirect('/login/'); exit; }
if (!(current_user_can('ukfl_member'))){ wp_safe_redirect('/account/'); exit; }


if (isset($_POST['add_team'])){
	$content = do_shortcode("[gcp_redirect_flow ref=4]"); 
	$js_for_footer = '
<script type="text/javascript">
        jQuery(function ($) {
		console.log($("a.gcp_redirect_flow6"));
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
							<form method="post" class="form form-horizontal">
								<div class="form-group">
									<div class="controls">
										<input type="submit" name="add_team" id="add_team" value="Add Team" class="btn btn-success btn-busiprof pull-right" />
									</div>
								</div>    
							</form>
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
						
