<?php

require_once 'gocardless-pro.php';
require 'general.php';
require 'ajax.php';

function my_custom_login() {
        echo '<link rel="stylesheet" type="text/css" href="' . get_bloginfo ( 'stylesheet_directory' ) . '/assets/css/custom-login-styles.css" />';
}
function my_login_logo_url() {
        return get_bloginfo ( 'url' );
}
function my_login_logo_url_title() {
        return 'UK Flyball League';
}
function admin_login_redirect($redirect_to, $request, $user) {
        global $user;
        if (isset ( $user->roles ) && is_array ( $user->roles )) {
                if (in_array ( "administrator", $user->roles )) {
                        return $redirect_to;
                } else {
                        return home_url ();
                }
        } else {
                return $redirect_to;
        }
}
function login_checked_remember_me() {
        add_filter( 'login_footer', 'rememberme_checked' );
}
function rememberme_checked() {
        echo "<script>document.getElementById('rememberme').checked = true;</script>";
}
add_action ( 'login_head', 'my_custom_login' );
add_filter ( 'login_headerurl', 'my_login_logo_url' );
add_filter ( 'login_headertitle', 'my_login_logo_url_title' );
add_filter ( "login_redirect", "admin_login_redirect", 10, 3 );
add_action( 'init', 'login_checked_remember_me' );


function ukfl_enqueue_styles() {
	$parent_style = 'busiprof-pro-style'; // This is 'twentyfifteen-style' for the Twenty Fifteen theme.
	wp_enqueue_style( 'child-style',
		get_stylesheet_directory_uri() . '/style.css',
		array( 'style' ),
		wp_get_theme()->get('Version')
	);
}

function ukfl_enqueue_scripts() {	
	// BS DatePicker
	wp_register_script ( 'datepicker-js', '//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js', array (), '1.7.1', false );
	wp_enqueue_script ( 'datepicker-js' );
	//JQuery Form Validator
	wp_register_script ( 'form-validator-js', '//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.3.77/jquery.form-validator.min.js', array ('jquery'), '2.3.77', false );
	wp_enqueue_script ( 'form-validator-js' );
	//Bootstrap Toggle
	wp_register_script ( 'bs-toggle-js', '//cdnjs.cloudflare.com/ajax/libs/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js', array ('jquery'), '2.2.2', false );
	wp_enqueue_script ( 'fbs-toggle-js' );
}

add_action( 'wp_enqueue_scripts', 'ukfl_enqueue_styles', 100);
add_action( 'wp_enqueue_scripts', 'ukfl_enqueue_scripts' );

function fontawesome_dashboard() {
	wp_enqueue_style('fontawesome', '//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css', '', '4.7.0', 'all');
}
add_action('admin_init', 'fontawesome_dashboard');

function add_admin_menu_separator( $position ) {
	global $menu;
	$menu[ $position ] = array(
			0	=>	'',
			1	=>	'read',
			2	=>	'separator' . $position,
			3	=>	'',
			4	=>	'wp-menu-separator'
	);
}
add_action( 'admin_init', 'add_admin_menu_separator' );

function dateToSQL($date){
	if ($date == ""){ return ""; }
	return date_format(DateTime::createFromFormat('d/m/Y', $date), 'Ymd');
}

function SQLToDate($date, $format='d/m/Y'){
	if ($date == ""){ return ""; }
	return date_format(DateTime::createFromFormat('Ymd', $date), $format);
}

function debug_array($array){
	echo '<pre>';
	print_r($array);
	echo '</pre>';
}

?>
