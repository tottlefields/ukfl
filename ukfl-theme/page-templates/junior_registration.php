<?php
//Template Name: Registration - Junior

global $wpdb, $current_user;

if (!is_user_logged_in()) { wp_safe_redirect('/login/'); exit; }
if (!(current_user_can('ukfl_member'))){ wp_safe_redirect('/account/'); exit; }

$junior_login = get_user_meta( $current_user->ID, "ukfl_junior_scheme", 1);
delete_user_meta($current_user->ID, "ukfl_junior_scheme");
$junior = get_user_by('login', $junior_login);


get_header();
get_template_part('index', 'bannerstrip');

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
								<p>Many thanks for registering <strong><?php echo $junior->display_name ?></strong> with the UKFL&copy;. Their registration has been approved and they have been given the UKFL&copy; Number <strong><?php echo $junior->user_login; ?></strong>. We hope you all enjoy racing with us.</p>
								<p>Your direct debit mandate and payment plan have been successfully setup (see reference below).</p>
								<div class="alert alert-success" role="alert"><?php the_post(); the_content(); ?></div>
								<p>Please <a href="/account/">click here</a> to return to your account.</p>
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
