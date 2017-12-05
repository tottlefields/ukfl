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

debug_array($all_events);

exit;

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
