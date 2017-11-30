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
							<div class="row">
								<div class="col-md-6 col-xs-12">
									<div class="panel panel-default">
										<div class="panel-heading"><h3>Your Details<a class="btn btn-sm btn-default pull-right">Edit</a></h3></div>
										<div class="panel-body"></div>
									</div>
								</div>
								<div class="col-md-6 col-xs-12">
									<div class="panel panel-default">
										<div class="panel-heading"><h3>Your Team(s)<a class="btn btn-sm btn-default pull-right" href="/account/teams">Add a Team</a></h3></div>
										<div class="panel-body">
<?php
$team_ids = array(); 
$teams = get_teams_for_user(); 
foreach ( $teams as $post ) : setup_postdata( $post ); 
	array_push($team_ids, $post->ID); ?>
	<div class="col-sm-12 col-md-6">
<?php	if ( has_post_thumbnail() ) { 
	if (get_post_status($post->ID) == 'publish'){ ?>
		<a  href="<?php the_permalink(); ?>" class="post-thumbnail"><?php the_post_thumbnail(); ?></a>
	<?php  } else { ?>
		<span class="post-thumbnail team-pending"><?php the_post_thumbnail(); ?></span>
	<?php }
} 
else { ?>
	<a  href="<?php the_permalink(); ?>" class="post-thumbnail"><img src="<?php get_bloginfo( 'stylesheet_directory' ) ?>/images/thumbnail-default.jpg" /></a>
<?php } ?>
	</div>
<?php endforeach; 
wp_reset_postdata(); ?>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6 col-xs-12">
									<div class="panel panel-default">
										<div class="panel-heading"><h3>Your Dogs<a class="btn btn-sm btn-default pull-right" href="/account/dogs">Add a Dog</a></h3></div>
										<div class="panel-body">
<?php 
$dogs = get_dogs_for_account(); 
if (count($dogs) > 0){ ?>
	<div class="row">
		<div class="col-md-12">
			<table class="events-list table table-condensed"><tbody>
<?php foreach ( $dogs as $post ) : setup_postdata( $post ); 
	$ukfl_points = get_post_meta(get_the_ID(), 'ukfl_dog_points', true);
	debug_array(get_post_meta(get_the_ID(), 'ukfl_dog_points', true));
	$ukfl_height = get_post_meta(get_the_ID(), 'ukfl_dog_height', true);
	if (!isset($ukfl_points)){ $ukfl_points = 0; }
	if (!isset($ukfl_height)){ $ukfl_height = "FH"; }
	echo $ukfl_points;
	wp_die();
	?>
				<tr class='clickable-row' data-href='<?php the_permalink(); ?>'>
					<td><?php echo get_post_meta(get_the_ID(), 'ukfl_dog_name', true); ?></td>
					<td><?php the_title(); ?></td>
					<td><i class="fa fa-star-o" aria-hidden="true"></i>&nbsp;<?php echo $ukfl_points; ?></td>
					<td><?php echo $ukfl_height; ?></td>
				</tr>
<?php endforeach;
wp_reset_postdata(); ?>	
			</tbody></table>
		</div>
	</div>
<?php } ?>									
										</div>
									</div>
								</div>
								<div class="col-md-6 col-xs-12">
									<div class="panel panel-default">
										<div class="panel-heading"><h3>Your Events<a class="btn btn-sm btn-default pull-right" style="display:none;">Add an Event</a></h3></div>
										<div class="panel-body">
<?php
if (count($team_ids) > 0){
$events = get_events_for_teams($team_ids);
	if (count($events) > 0){ ?>
	<div class="row">
		<div class="col-md-12">
			<table class="events-list table table-condensed"><tbody>
	<?php foreach ( $events as $post ) : setup_postdata( $post ); 
		$start_date = DateTime::createFromFormat('Ymd', get_post_meta( $post->ID, 'event_start_date', true ));
	?>
				<tr class='clickable-row' data-href='<?php the_permalink(); ?>'>
					<td>
						<div class="event-date">
							<div class="event-day"><?php echo $start_date->format('j'); ?></div>
							<div class="event-month"><?php echo strtoupper($start_date->format('M')); ?></div>
						</div>
					</td>
					<td><?php the_title(); ?></td>
					<td class="event-venue hidden-xs"><a href="https://www.google.co.uk/maps/preview?q=<?php echo get_post_meta( $post->ID, 'event_lat', true); ?>,<?php echo get_post_meta( $post->ID, 'event_long', true); ?>" target="_blank"><i class="fa fa-map-marker"></i> <?php echo get_post_meta( $post->ID, 'event_postcode', true ) ?></a></td>
				</tr>
	<?php endforeach;
	wp_reset_postdata(); ?>
			</tbody></table>
		</div>
	</div>
<?php } 
}?>
										</div>
									</div>
								</div>
							</div>
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
							<?php #the_post(); the_content(); ?>
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
