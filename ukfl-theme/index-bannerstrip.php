<!-- Page Title -->
<?php 
if (!isset($TITLE)){
	if( is_archive() ){ $TITLE = get_the_archive_title(); }
	else if( is_home() ){ $TITLE = wp_title(' ', false); }
	else if (is_singular( "ukfl_dog" )) { $TITLE = get_post_meta(get_the_ID(), 'ukfl_dog_name', true); }
	else{ $TITLE = get_the_title(); }
}
?>
<section class="page-header">
	<div class="container">
		<div class="row">
			<div class="col-sm-6 col-xs-12">
				<div class="page-title">
					<h2><?php echo $TITLE ?></h2>
					<p><?php bloginfo('description');?></p>
				</div>
			</div>
			<div class="col-sm-6 hidden-xs">
				<ul class="page-breadcrumb">
					<?php if (function_exists('busiprof_custom_breadcrumbs')) busiprof_custom_breadcrumbs();?>
				</ul>
			</div>
		</div>
	</div>	
</section>
<!-- End of Page Title -->
<div class="clearfix"></div>
