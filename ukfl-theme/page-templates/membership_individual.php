<?php
//Template Name: Membership (Individual)

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
								<p>Many thanks for joining UKFL and selecting an Individual Membership. Your direct debit mandate and payment plan have been successfully setup (see reference below). Your new UKFL membership number and password should have been e-mailed to you, using the email address you have provided. If you haven't received this within the next 30 mins please <a href="/contact/">contact us</a>.</p>
							<div class="alert alert-success" role="alert"><?php the_post(); the_content(); ?></div>
								<p>Please <a href="/login/">click here</a> to login to your new account.</p>
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
