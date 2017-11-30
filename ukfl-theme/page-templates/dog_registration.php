<?php
//Template Name: Team Registration

global $wpdb, $current_user;

$args = array(
	'author'        =>  $current_user->ID,
	'post_type'		=> 'ukfl_dog',
	'orderby'       =>  'ID',
	'order'         =>  'DESC',
	'post_status'	=>  'draft',
	'posts_per_page'=> 1 
);
$dogs = get_posts( $args );
$dog = $dogs[0];

// Update the dog into the database - payment setup so publish
wp_publish_post( $dog->ID );


$admin_msg = 'New dog registration on '.get_bloginfo('name').':<br /><br />
	Owner: <strong>'.$current_user->user_firstname.' '.$current_user->user_lastname.'</strong><br />
	Email Address: <strong>'.$current_user->user_email.'</strong><br />
	UKFL Number: <strong>'.$current_user->user_login.'</strong><br /><br />
	Dog Name/Number: <strong>'.get_post_meta( $dog->ID, 'ukfl_dog_name', true ).' / '.$dog->post_title.'</strong><br /><br />';
$headers = array('Content-Type: text/html; charset=UTF-8');
wp_mail(get_option('admin_email'), '['.get_bloginfo('name').'] New Dog Registration', $admin_msg, $headers);

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
								<p>Many thanks for registering <strong><?php echo get_post_meta( $dog->ID, 'ukfl_dog_name', true ) ?></strong> with the UKFL&copy;. Your registration has been approved and <?php echo get_post_meta( $dog->ID, 'ukfl_dog_name', true ) ?> has been given the UKFL&copy; Number <strong><?php echo $dog->post_title; ?></strong>. We hope you both enjoy racing with us.</p>
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
