<?php
//Template Name: Membership (Individual)

the_post(); the_content();



/*$current_user = wp_get_current_user();
if (isset($_REQUEST['add_joint_member'])){
	if ( email_exists( $_REQUEST['user_email'] ) ) {
		$error = 'A username with that email address ('.$_REQUEST['user_email'].') already exists on the system. If you want this account to be linked to your joint account, please contact UKFL to have them set this up for you.';
	}else{
		$password = wp_generate_password( 12, true );
		$user_id = wp_create_user ( $_REQUEST['user_login'], $password, $_REQUEST['user_email'] );
		wp_update_user(
			array(
				'ID'		=> $user_id,
				'first_name'	=> $_REQUEST['first_name'],
				'last_name'	=> $_REQUEST['last_name'],
				'nickname'	=> $_REQUEST['first_name'].' '.$_REQUEST['last_name']
			)
		);
		$user = new WP_User($user_id);
		$user->set_role('subscriber');
		$user->add_role('ukfl_member');
		$msg = 'Dear '.$_REQUEST['first_name'].',
You have had an account set up for you on the <a href="'.home_url().'">UKFL website</a> as part of '.$current_user->display_name.'\'s joint membership. You do not need to do anthing further to be able to enjoy UKFL.

Please feel free to <a href="'.esc_url( wp_login_url() ).'">login to your account</a> and start adding your dogs.

Your password is: <strong>'.$password.'</strong>
Your UKFL Number is: <strong>'.$_REQUEST['user_login'].'</strong>

We look forward to racing with you in UKFL.';
		$headers = array('Content-Type: text/html; charset=UTF-8');
		wp_mail( $_REQUEST['user_email'], '[UK Flyball League] Welcome to UKFL!', $msg, $headers );
		wp_safe_redirect('/account/');
		exit;
	}
}

add_user_meta( $current_user->ID, 'ukfl_membership_type', 'joint', 1 );
$ukfl_no = generate_ukfl_number();*/

?>