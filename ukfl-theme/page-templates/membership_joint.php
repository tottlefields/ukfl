<?php
//Template Name: Membership (Joint)

$current_user = wp_get_current_user();
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

get_header();
get_template_part('index', 'bannerstrip');

add_user_meta( $current_user->ID, 'ukfl_membership_type', 'joint', 1 );
$ukfl_no = generate_ukfl_number();
?>
<!-- Blog & Sidebar Section -->
<section>		
	<div class="container">
		<div class="row">
			<!--Blog Posts-->
			<div class="col-md-12 col-xs-12">
				<div class="page-content">
					<p>Many thanks for joining UKFL and selecting a Joint Membership. Please fill in the details below for the other party on your joint membership to set them up their own account on the UKFL website. Their password and UKFL membership number will be emailed to them using the email address you have provided. THank you.</p>
					<div class="tml tml-register" id="theme-my-login">
						<form class="form form-horizontal" name="registerform" id="registerform" method="post">
							<div class="form-group">
								<label class="col-sm-2 control-label" for="user_login">UKFL Number</label>
								<div class="col-sm-3">
									<input type="text" name="user_login" id="user_login" class="input form-control" value="170002" readonly="" size="20">
								</div>
								<label class="col-sm-2 control-label" for="user_email">E-mail</label>
								<div class="col-sm-5">
									<input type="text" name="user_email" id="user_email" class="input form-control" value="" size="20">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="first_name">First name</label>
								<div class="col-sm-3">
									<input type="text" name="first_name" id="first_name" class="input form-control" value="" size="20">
								</div>
						                       <label class="col-sm-2 control-label" for="last_name">Last name</label>
								<div class="col-sm-5">
									<input type="text" name="last_name" id="last_name" class="input form-control" value="" size="20">
								</div>
						        </div>
							<p class="tml-submit-wrap">
								<input type="submit" class="btn btn-success pull-right" name="add_joint_member" id="add_joint_member" value="Continue">
							</p>
						</form>
					</div>
						<article id="post-<?php the_ID(); ?>" <?php post_class('post'); ?> > 					
						<div class="entry-content">
							<?php the_post(); the_content(); ?>
						</div>
					</article>
				</div>
			</div>
			<!--/End of Blog Posts-->
		</div>	
	</div>
</section>
<!-- End of Blog & Sidebar Section -->
 
<div class="clearfix"></div>
<?php get_footer(); ?>
