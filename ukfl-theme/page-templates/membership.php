<?php
//Template Name: UKFL Memberships

get_header();
get_template_part('index', 'bannerstrip');
?>
<section>		
	<div class="container">
		<div class="row">
			<!--Blog Posts-->
			<div class="col-md-12 col-xs-12">
				<div class="page-content">
					<article id="post-<?php the_ID(); ?>" <?php post_class('page'); ?> > 					
						<div class="entry-content">
							<div class="row">
								<div class="col-md-6 col-sm-12 service-box">
									<div class="box">
										<div class="service-icon">
											<i class="fa fa-user"></i>
										</div>
										<div class="entry-header">
											<h4 class="entry-title">Individual Membership</h4>
										</div>
										<div class="entry-content">
											<?php echo do_shortcode("[gcp_redirect_flow ref=1]"); ?>
										</div>
									</div>
								</div>
								<div class="col-md-6 col-sm-12 service-box">
	                                <div class="box">
										<div class="service-icon">
											<i class="fa fa-users"></i>
										</div>
										<div class="entry-header">
											<h4 class="entry-title">Joint Membership</h4>
										</div>
										<div class="entry-content">
											<?php echo do_shortcode("[gcp_redirect_flow ref=2]"); ?>
										</div>
	                                </div>
								</div>
								<!-- <div class="col-md-4 col-sm-12 service-box">
									<div class="box">
										<div class="service-icon">
											<i class="fa fa-user"></i>
										</div>
										<div class="entry-header">
											<h4 class="entry-title">Junior Membership</h4>
										</div>
										<div class="entry-content">
											<?php echo do_shortcode("[gcp_redirect_flow ref=3]"); ?>
										</div>
									</div>
								</div> -->
							</div>
						</div>
					</article>
				</div>
			</div>
		</div>	
	</div>
</section>
 
<div class="clearfix"></div>
<?php get_footer(); ?>
