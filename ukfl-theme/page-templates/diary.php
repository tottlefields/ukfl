<?php
//Template Name: UKFL Diary

$all_events = get_posts(array(
		'post_status'	=> 'publish',
		'post_type'		=> 'ukfl_event',
		'posts_per_page' => -1,
		'order'			=> 'ASC',
		'meta_key'		=> 'event_start_date',
		'orderby'   	=> 'meta_value_num',
		'meta_query' 	=> array(
				array('key' => 'event_start_date', 'value' => date('Ymd'), 'compare' => '>=')
		)
));


get_header();
get_template_part('index', 'bannerstrip');
?>
<section>		
	<div class="container">
		<div class="row">
			<!--Blog Posts-->
			<div class="col-md-12 col-xs-12">
				<div class="page-content">
					<article id="page-<?php the_ID(); ?>" <?php post_class('page'); ?> > 					
						<div class="entry-content">
							<div class="row">
								<div class="col-md-12">
									<table class="events-list table table-condensed">
<?php foreach($all_events as $event) : setup_postdata($event);
	$start_date = DateTime::createFromFormat('Ymd', get_post_meta( $event->ID, 'event_start_date', true )); 
	$format_labels = '&nbsp;';
//	if (get_post_meta( $event->ID, 'event_', true)
?>
										<tr>
											<td>
	    											<div class="event-date">
						    							<div class="event-day"><?php echo $start_date->format('j'); ?></div>
						    							<div class="event-month"><?php echo strtoupper($start_date->format('M')); ?></div>
	    											</div>
						    					</td>
											<td></td>
											<td><div class="img-div">
												<?php echo get_the_post_thumbnail( $event->post_parent, array(175, 75) ); ?>
											</div></td>
											<td>
												<div class="event-details">
													<div class="event-title"><?php echo $event->post_title; ?></div>
													<div class="event-venue hidden-xs"></div>
												</div>
											</td>
											<td class="event-venue hidden-xs"><a href="https://www.google.co.uk/maps/preview?q=<?php echo get_post_meta( $event->ID, 'event_lat', true); ?>,<?php echo get_post_meta( $event->ID, 'event_long', true); ?>" target="_blank"><i class="fa fa-map-marker"></i> <?php echo get_post_meta( $event->ID, 'event_postcode', true ) ?></a></td>
											<td class="hidden-xs text-center"><?php echo $format_labels; ?></td>
										</tr>
<?php endforeach;?>									
									
									</table>								
								</div>
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
