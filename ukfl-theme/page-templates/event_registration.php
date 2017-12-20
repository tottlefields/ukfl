<?php
//Template Name: Event Registration

global $wpdb, $current_user;

$args = array(
	'author'        =>  $current_user->ID,
	'post_type'		=> 'ukfl_event',
	'orderby'       =>  'ID',
	'order'         =>  'DESC',
	'post_status'	=>  'draft',
	'posts_per_page'=> 1 
);
$events = get_posts( $args );
$event = $events[0];

// Update the team into the database - payment setup so convert form draft to pending
wp_update_post( array('ID' => $event->ID, 'post_status' => 'pending') );

$admin_msg = 'New event registration on '.get_bloginfo('name').':<br /><br />
	Team Captain: <strong>'.$current_user->user_firstname.' '.$current_user->user_lastname.'</strong><br />
	Email Address: <strong>'.$current_user->user_email.'</strong><br />
	UKFL Number: <strong>'.$current_user->user_login.'</strong><br /><br />
	Host Team: <strong>'.$team_name.'</strong><br /><br />
	Venue: <strong>'.$_POST['ukfl_event_venue'].', '.$_POST['ukfl_event_postcode'].'</strong><br /><br />';
$headers = array('Content-Type: text/html; charset=UTF-8', 'Cc:'.get_option('admin_email'));
//wp_mail('secretary@ukflyball.org.uk', '['.get_bloginfo('name').'] New Event Registration', $admin_msg, $headers);
wp_mail('online@ukflyball.org.uk', '['.get_bloginfo('name').'] New Event Registration', $admin_msg, $headers);

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
								<p>Many thanks for registering <strong><?php echo get_post_meta( $event->ID, 'ukfl_dog_name', true ); ?></strong> as a new UKFL&copy; Event. Your request has been forwarded to the UKFL&copy; Secretary for approval. We will be in contact with you as soon as possible.</p>
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
