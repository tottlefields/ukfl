<?php
//Template Name: UKFL Teams

function get_teams_for_region($region){
	$teams = get_posts(array(
                'post_status'   => 'publish',
                'post_type'      => 'ukfl_team',
                'posts_per_page' => -1,
                'order'          => 'ASC',
		'orderby'	=> 'post_title',
		'tax_query' => array(
			array('taxonomy' => 'team-regions', 'field' => 'slug', 'terms' => $region )
		)
	));
	return $teams;
}

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
								<h2><i class="fa fa-square scotland_ireland"></i> Scotland &amp; N. Ireland</h2>
<div class="row"><?php foreach (get_teams_for_region('scotland-n-ireland') as $team){ echo '<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3"><a href="'.get_permalink($team->ID).'">'.$team->post_title.'</a></div>'; } ?></div>
								<h2><i class="fa fa-square northwest"></i> North West</h2>
<div class="row"><?php foreach (get_teams_for_region('north-west') as $team){ echo '<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3"><a href="'.get_permalink($team->ID).'">'.$team->post_title.'</a></div>'; } ?></div>
								<h2><i class="fa fa-square northeast"></i> North East</h2>
<div class="row"><?php foreach (get_teams_for_region('north-east') as $team){ echo '<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3"><a href="'.get_permalink($team->ID).'">'.$team->post_title.'</a></div>'; } ?></div>
								<h2><i class="fa fa-square wales"></i> Wales</h2>
<div class="row"><?php foreach (get_teams_for_region('wales') as $team){ echo '<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3"><a href="'.get_permalink($team->ID).'">'.$team->post_title.'</a></div>'; } ?></div>
								<h2><i class="fa fa-square midlands"></i> Midlands</h2>
<div class="row"><?php foreach (get_teams_for_region('midlands') as $team){ echo '<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3"><a href="'.get_permalink($team->ID).'">'.$team->post_title.'</a></div>'; } ?></div>
								<h2><i class="fa fa-square southwest"></i> South West</h2>
<div class="row"><?php foreach (get_teams_for_region('south-west') as $team){ echo '<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3"><a href="'.get_permalink($team->ID).'">'.$team->post_title.'</a></div>'; } ?></div>
								<h2><i class="fa fa-square southeast"></i> South East</h2>
<div class="row"><?php foreach (get_teams_for_region('south-east') as $team){ echo '<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3"><a href="'.get_permalink($team->ID).'">'.$team->post_title.'</a></div>'; } ?></div>
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

