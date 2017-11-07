<article class="post">
        <span class="site-author">
                <figure class="avatar">
                <?php $author_id=$post->post_author; ?>
                        <a data-tip="<?php the_author() ;?>" href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) );?>" data-toggle="tooltip" title="<?php echo the_author_meta( 'display_name' , $author_id ); ?>"><?php echo get_avatar( get_the_author_meta( 'ID' ), 32 ); ?></a>
                </figure>
        </span>
		<div class="col-md-8 col-xs-12">
                <header class="entry-header">
			<h3  class="entry-title">Team Captain: <em><?php echo the_author_meta( 'display_name' , $author_id ); ?></em></h3>
                </header>

                <div class="entry-meta">

                        <span class="entry-date">Established: <a href="<?php the_permalink(); ?>"><time datetime=""><?php the_time('M j,Y');?></time></a></span>

                        <?php if( get_the_tags() ) { ?>
                        <span class="tag-links"><a href="<?php the_permalink(); ?>"><?php the_tags('', ', ', ''); ?></a></span>
                        <?php } ?>

			<span class="entry-venue">Location: <a href="https://maps.google.co.uk/?q=Horsely Fen Farm, Chatteris, Cambridgeshire, PE16 6SH" target="_blank"><address>Horsely Fen Farm, Chatteris, Cambridgeshire, PE16 6SH</address></a></span>
                </div>
		</div>
               <div class="col-md-4 hidden-sm hidden-xs">
                          <div style="float:right;"><a  href="<?php the_permalink(); ?>" class="post-thumbnail"><?php the_post_thumbnail(); ?></a></div>
                </div>
        <div class="entry-content">
		team tables
                <?php the_content( __('Read More','busiprof') ); ?>
        </div>
</article>

