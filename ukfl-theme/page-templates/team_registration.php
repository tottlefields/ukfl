<?php
//Template Name: Team Registration

global $wpdb, $current_user;

$args = array(
	'author'        =>  $current_user->ID,
	'post_type'		=> 'ukfl_team',
	'orderby'       =>  'ID',
	'order'         =>  'DESC',
	'post_status'	=>  'pending',
	'posts_per_page' => 1 
);
$teams = get_posts( $args );
$team = $teams[0];

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
								<p>Many thanks for registering <strong><?php echo $team->post_title ;?></strong> as a new UKFL&copy; Team. Your request has been forwarded to the UKFL&copy; Secretary for approval. We will be in contact with you as soon as possible.</p>
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
