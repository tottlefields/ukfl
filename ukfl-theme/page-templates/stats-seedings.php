<?php
//Template Name: Stats - Seedings

get_header();
get_template_part('index', 'bannerstrip');

global $wpdb;
$list_type = get_post_meta(get_the_ID(), "type", 1);

$sql = "select t3.ID as club_id, event_date, fastest_time, t2.post_title as team_name, e.meta_value as event_title, t3.post_name as club
from ukfl_event_results t1 left outer join $wpdb->posts t2 on t1.team=t2.post_name 
inner join $wpdb->postmeta e on e.post_id=t1.event_id
inner join $wpdb->posts t3 on t2.post_parent=t3.ID
where team_type='".$list_type."' and t2.post_type='ukfl_sub-team' and fastest_time>0 and e.meta_key='ukfl_event_title' 
order by fastest_time";
$seedings = $wpdb->get_results($sql);
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
							<?php 
if ( $seedings ) { ?>
							<table class="table-responsive table-striped" id="seeding_list">
								<thead>
									<th></th>
									<th class="text-center">Position</th>
									<th colspan="2">Team Name</th>
									<th class="text-center">Time</th>
									<th>Event</th>								
								</thead>
								<tbody>
<?php 
							$position = 1;
							foreach ( $seedings as $team ){
								//$club = get_post($team->club_id);
								$seed_date = new DateTime($team->event_date);
								echo '<tr><td>'.$position.'</td>';
								echo '<td class="text-center">'.ordinal($position).'</td>';
								echo '<td style="border-right-width:0px;width:75px;"><div class="img-div">'.get_the_post_thumbnail( $team->club_id, array(175, 75) ).'</div></td>';
								echo '<td style="border-left-width:0px;"><a href="'.get_permalink($team->club_id).'">'.$team->team_name.'</a></td>';
								echo '<td class="text-center">'.$team->fastest_time.'</td>';
								echo '<td>'.$team->event_title.' ('.$seed_date->format('d/m/Y').')</td></tr>';							
								$position++;
							}?>
								</tbody>
							</table>
						
<?php }
else{
	
}
							?>
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
	$('#seeding_list').DataTable({
		"ordering": false,
		"columnDefs": [
			{ "visible": false, "targets": 0 }
		],
		dom : '<"toolbar">frtip',
		pageLength: 20,
		paging : true,
		lengthChange: false,
	});
});
</script>
<?php } ?>
