<article class="post">
        <span class="site-author">
                <figure class="avatar">
                	<?php $author_id=$post->post_author; ?>
                	<?php echo get_avatar( get_the_author_meta( 'ID' ) ); ?></a>
                </figure>
        </span>
		<div class="col-md-8 col-xs-12">
                <header class="entry-header">
			<h3  class="entry-title">Owner: <em><?php echo the_author_meta( 'display_name' , $author_id ); ?></em></h3>
                </header>
                
                <?php $breeds = get_the_terms( get_the_ID(), 'dog-breeds' ); $breed = $breeds[0]; ?>

                <div class="entry-meta">
                        <!-- <span class="entry-date">Registered: <time datetime=""><?php the_time('M j,Y');?></time></span> -->
                        <span class="entry-ukfl">UKFL No: <?php get_the_title(); ?></span>
                        <span class="entry-breed">Breed: <?php echo $breed->name; ?></span>
				</div>
		</div>
		<div class="col-md-4 hidden-sm hidden-xs">
			<div style="float:right;"><a  href="<?php the_permalink(); ?>" class="post-thumbnail"><?php the_post_thumbnail(); ?></a></div>
		</div>
        <div class="entry-content">
        </div>
</article>

