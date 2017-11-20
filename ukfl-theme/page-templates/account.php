<?php
//Template Name: UKFL Account Page

if (!is_user_logged_in()) { wp_safe_redirect('/login/'); exit; }

get_header();
get_template_part('index', 'bannerstrip');
?>
<?
global $wpdb, $current_user;
?>
<!-- Blog & Sidebar Section -->
<section>		
	<div class="container">
		<div class="row">
			<!--Blog Posts-->
			<div class="col-md-12 col-xs-12">
				<div class="page-content">
					<article id="post-<?php the_ID(); ?>" <?php post_class('post'); ?> > 					
						<div class="entry-content">
							<?php if (current_user_can('ukfl_member')){ ?>
								<h3>Your Details <a class="btn btn-default pull-right">Edit</a></h3>
								<h3>Your Dogs <a class="btn btn-default pull-right">Add a Dog</a></h3>
								<h3>Your Teams <a class="btn btn-default pull-right">Add a Team</a></h3>
								<h3>Your Events <a class="btn btn-default pull-right">Add an Event</a></h3>
							<?php } else { ?>
							<p>Thank you for creating an account on the UKFL website, however to be able to enjoy playing flyball with us you need to become a member. All memberships run on a yearly basis and are payable via direct debit. Please select your required membership option below and you will be forwarded to <a href="https://gocardless.com/" target="_blank">GoCardless</a> to setup a payment.</p>
<div class="row">
							<div class="col-md-4 col-sm-12 service-box">
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
							<div class="col-md-4 col-sm-12 service-box">
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
							<div class="col-md-4 col-sm-12 service-box">
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
							</div>
</div>
		
							<?php } ?>
							<?php the_post(); the_content(); ?>
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
