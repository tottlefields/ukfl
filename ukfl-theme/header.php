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

<?php $favicon_dir = get_stylesheet_directory_uri() . '/assets/favicon'; ?>

	<link rel="apple-touch-icon" sizes="57x57" href="<?php echo $favicon_dir; ?>/apple-icon-57x57.png">
	<link rel="apple-touch-icon" sizes="60x60" href="<?php echo $favicon_dir; ?>/apple-icon-60x60.png">
	<link rel="apple-touch-icon" sizes="72x72" href="<?php echo $favicon_dir; ?>/apple-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="76x76" href="<?php echo $favicon_dir; ?>/apple-icon-76x76.png">
	<link rel="apple-touch-icon" sizes="114x114" href="<?php echo $favicon_dir; ?>/apple-icon-114x114.png">
	<link rel="apple-touch-icon" sizes="120x120" href="<?php echo $favicon_dir; ?>/apple-icon-120x120.png">
	<link rel="apple-touch-icon" sizes="144x144" href="<?php echo $favicon_dir; ?>/apple-icon-144x144.png">
	<link rel="apple-touch-icon" sizes="152x152" href="<?php echo $favicon_dir; ?>/apple-icon-152x152.png">
	<link rel="apple-touch-icon" sizes="180x180" href="<?php echo $favicon_dir; ?>/apple-icon-180x180.png">
	<link rel="icon" type="image/png" sizes="192x192" href="<?php echo $favicon_dir; ?>/android-icon-192x192.png">
	<link rel="icon" type="image/png" sizes="32x32" href="<?php echo $favicon_dir; ?>/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="96x96" href="<?php echo $favicon_dir; ?>/favicon-96x96.png">
	<link rel="icon" type="image/png" sizes="16x16" href="<?php echo $favicon_dir; ?>/favicon-16x16.png">
	<link rel="manifest" href="<?php echo $favicon_dir; ?>/manifest.json">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-TileImage" content="<?php echo $favicon_dir; ?>/ms-icon-144x144.png">
	<meta name="theme-color" content="#ffffff">
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
			<div class="col-xs-4">	
			<?php if( is_active_sidebar('home-header-sidebar_left') ) { ?>
			<?php  dynamic_sidebar( 'home-header-sidebar_left' ); ?>
			<?php } ?>
			</div>
			<div class="col-md-4 hidden-xs hidden-sm">
			<?php  if( is_active_sidebar('home-header-sidebar_right') ) { ?>
			<?php dynamic_sidebar( 'home-header-sidebar_right' ); ?>
			<?php } ?>
			</div>
			<div class="col-xs-8 col-md-4">
				<aside id="ukfl-member-links" class="widget busiprof_header_info_widget"><ul class="header-contact-info pull-right">
<?php if ( is_user_logged_in() ) { ?>
    <li style="display:inline;"><i class="fa fa-user-circle"></i><a href="/account/">My Account</a></li>
    <span style="padding:0px 10px;font-size:150%">|</span>
<?php if (current_user_can('ukfl_official')){ ?>
    <li style="display:inline;"><i class="fa fa-lock"></i><a href="/administration/">Administration</a></li>
    <span style="padding:0px 10px;font-size:150%">|</span>
<?php } ?>
    <li style="display:inline;"><i class="fa fa-sign-out"></i><a href="<?php echo wp_logout_url(); ?>">Logout</a></li>
<?php } else { ?>
    <li style="display:inline;"><i class="fa fa-sign-in"></i><a href="/login/">Login</a></li>
    <span style="padding:0px 10px;font-size:150%">|</span>
    <li style="display:inline;"><a href="/membership/">Register</a></li>
<?php } ?>
				</ul></aside>
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
