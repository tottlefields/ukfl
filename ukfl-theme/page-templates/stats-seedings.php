<?php
//Template Name: Stats - Seedings

get_header();
get_template_part('index', 'bannerstrip');

global $wpdb;
$list_type = get_post_meta(the_ID(), "type", 1);

$sql = "select team, event_date, fastest_time, t2.post_title, e.meta_value, t3.post_name 
from ukfl_event_results t1 left outer join $wpdb->posts t2 on t1.team=t2.post_name 
inner join $wpdb->postmeta e on e.post_id=t1.event_id
inner join $wpdb->posts t3 on t2.post_parent=t3.ID
where team_type='".$list_type."' and t2.post_type='ukfl_sub-team' and fastest_time>0 and e.meta_key='ukfl_event_title' 
order by fastest_time";
debug_array($sql);
$seedings = $wpdb->get_results($sql);
debug_array($seedings);
?>
<!-- Blog & Sidebar Section -->
<section>		
	<div class="container">
		<div class="row">
			<!--Blog Posts-->
			<div class="col-md-12 col-xs-12">
				<div class="page-content">
					<article id="page-<?php get_the_ID(); ?>" <?php post_class('page'); ?> > 					
						<div class="entry-content">
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
<?php 
if ( $seedings ) { ?>
<script type="text/javascript">
jQuery(function ($) {
	$('#seeding_list').DataTable();
});
</script>
<?php } ?>
