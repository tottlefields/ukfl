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
	wp_deregister_script ( 'jquery' );
	wp_register_script ( 'jquery', ("//code.jquery.com/jquery-2.2.4.min.js"), false, '2.2.4', true );
	wp_enqueue_script ( 'jquery' );
	
	// BS DatePicker
	wp_register_script ( 'datepicker-js', '//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js', array ('jquery',), '1.7.1', true );
	wp_enqueue_script ( 'datepicker-js' );
	
	wp_localize_script('ukfl', 'ukflAjax', array(
			'ajaxurl' => admin_url('admin-ajax.php'),
			'ajaxnonce' => wp_create_nonce('ukflajax-nonce')
	));
}

add_action( 'wp_enqueue_scripts', 'ukfl_enqueue_styles', 100);
add_action ( 'wp_enqueue_scripts', 'ukfl_enqueue_scripts' );

function debug_array($array){
	echo '<pre>';
	print_r($array);
	echo '</pre>';
}

?>
