<article class="post">
        <span class="site-author">
                <figure class="avatar">
                <?php $author_id=$post->post_author; ?>
                        <a data-tip="<?php the_author() ;?>" href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) );?>" data-toggle="tooltip" title="<?php echo the_author_meta( 'display_name' , $author_id ); ?>"><?php echo get_avatar( get_the_author_meta( 'ID' ), 32 ); ?></a>
                </figure>
        </span>
        <div class="row">
		<div class="col-md-8 col-xs-12">
                <header class="entry-header">
			<h3  class="entry-title">Team Captain: <em><?php echo the_author_meta( 'display_name' , $author_id ); ?></em></h3>
                </header>

                <div class="entry-meta">

                        <span class="entry-date">Established: <a href="<?php the_permalink(); ?>"><time datetime=""><?php the_time('M j,Y');?></time></a></span>

                        <?php if( get_the_tags() ) { ?>
                        <span class="tag-links"><a href="<?php the_permalink(); ?>"><?php the_tags('', ', ', ''); ?></a></span>
                        <?php } ?>

<!--			<span class="entry-venue">Location: <a href="https://maps.google.co.uk/?q=Horsely Fen Farm, Chatteris, Cambridgeshire, PE16 6SH" target="_blank"><address>Horsely Fen Farm, Chatteris, Cambridgeshire, PE16 6SH</address></a></span>	-->
                </div>
		</div>
               <div class="col-md-4 hidden-sm hidden-xs">
                          <div style="float:right;"><a  href="<?php the_permalink(); ?>" class="post-thumbnail"><?php the_post_thumbnail(); ?></a></div>
                </div>
	</div>
        <div class="row">
		<div class="col-md-6 col-xs-12">
			<h4>Team Data</h4>
				<table class="table responsive table-striped">
<?php $teams = get_sub_teams_for_team(get_the_ID());
foreach($teams as $team){
	echo '<tr><td>'.$team->post_title.'</td></tr>';
}
?>
				</table>
		</div>
		<div class="col-md-6 col-xs-12">
			<h4>Our Dogs</h4>
                                <table id="table_team_dogs" class="table responsive table-striped table-condensed">
					<thead><tr><th>Name</th><th>No.</th><th>Breed</th><th class="text-center">Points</th><th class="text-center">Height</th></tr></thead><tbody>
<?php $dogs = get_dogs_for_team(get_the_ID());
foreach ($dogs as $dog){
	$breed_tags = get_the_terms($dog, 'dog-breeds');
	$ukfl_points = 0;
	$ukfl_height = get_ukfl_height_for_dog($dog->post_title);
	echo '<tr>
		<td><a href="'.get_permalink($dog->ID).'">'.get_post_meta($dog->ID, 'ukfl_dog_name', 1).'</a></td>
		<td>'.$dog->post_title.'</td>
		<td>'.$breed_tags[0]->name.'</td>
		<td class="text-center"><i class="fa fa-star-o" aria-hidden="true"></i>&nbsp;'.$ukfl_points.'</td>
		<td class="text-center">'.$ukfl_height.'</td>
	</tr>';
}
?>
				</tbody></table>
		</div>
        </div>
</article>

<script type="text/javascript">
jQuery(function ($){
//	$('#table_team_dogs').DataTable();
});
</script>
