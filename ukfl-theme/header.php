<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>	
	<meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
	
	<?php if ( is_singular() && pings_open( get_queried_object() ) ) : ?>
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php endif; ?>

<?php wp_head(); ?>	
</head>
<?php
	$current_options = wp_parse_args(  get_option( 'busiprof_pro_theme_options', array() ), theme_data_setup() );
	if($current_options['layout_selector'] == "boxed")
	{ $class="boxed"; }
	else
	{ $class="wide"; }
	?>
<body <?php body_class($class); ?> >
<div id="wrapper">
<!--Header Top Info-->
<?php if (( is_active_sidebar('home-header-sidebar_left') || is_active_sidebar('home-header-sidebar_right') )) {?>	
<section class="top-header-widget">
	<div class="container">
		<div class="row">
			<div class="col-md-4">	
			<?php if( is_active_sidebar('home-header-sidebar_left') ) { ?>
			<?php  dynamic_sidebar( 'home-header-sidebar_left' ); ?>
			<?php } ?>
			</div>
			<div class="col-md-8" style="text-align:right;padding:0px;">
			<?php  if( is_active_sidebar('home-header-sidebar_right') ) { ?>
			<?php dynamic_sidebar( 'home-header-sidebar_right' ); ?>
			<?php } ?>
			</div>
		</div>	
	</div>
</section>
<?php } ?>
<!--End of Header Top Info-->
<!-- Navbar -->	
<nav class="navbar navbar-default">
	<div class="container">
		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<a class="navbar-brand" href="<?php echo home_url( '/' ); ?>" class="brand">
				<?php
				if( $current_options['enable_logo_text'] == true ){
					bloginfo('name');
				}else{
				?>
				<img alt="<?php bloginfo("name"); ?>" src="<?php echo ( esc_url($current_options['upload_image']) ? $current_options['upload_image'] : get_template_directory_uri() . '/images/logo.png' ); ?>" 
				alt="<?php bloginfo("name"); ?>"
				class="logo_imgae" style="width:<?php echo esc_html($current_options['width']).'px'; ?>; height:<?php echo esc_html($current_options['height']).'px'; ?>;">
				<?php } ?>
			</a>
			
				
			
			
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
		</div>

		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<?php 
				wp_nav_menu( array(
				'theme_location' => 'primary',
				'container'  => 'nav-collapse collapse navbar-inverse-collapse',
				'menu_class' => 'nav navbar-nav navbar-right',
				'fallback_cb' => 'busiprof_fallback_page_menu',
				'walker' => new busiprof_nav_walker()) 
				); 
			?>			
		</div>
	</div>
</nav>	
<!-- End of Navbar -->
