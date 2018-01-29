<?php
//Template Name: Stats - Seedings

get_header();
get_template_part('index', 'bannerstrip');

$list_type = get_post_meta(the_ID(), "type", 1);
global $wpdb;
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
<script type="text/javascript">
jQuery(function ($) {
	$('#seeding_list').DataTable();
});
</script>
