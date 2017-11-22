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


function my_theme_enqueue_styles() {
	$parent_style = 'busiprof-pro-style'; // This is 'twentyfifteen-style' for the Twenty Fifteen theme.

	//wp_dequeue_style( 'style' );
//	wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'child-style',
		get_stylesheet_directory_uri() . '/style.css',
		array( 'style' ),
		wp_get_theme()->get('Version')
	);
}
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles', 100); 

?>
