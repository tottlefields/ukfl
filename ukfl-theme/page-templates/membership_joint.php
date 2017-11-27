<?php
//Template Name: Membership (Joint)

if (isset($_REQUEST['add_joint_member'])){
	if ( email_exists( $_REQUEST['user_email'] ) ) {
		$error = 'A username with that email address ('.$_REQUEST['user_email'].') already exists on the system. If you want this account to be linked to your joint account, please contact UKFL to have them set this up for you.';
	}else{
		$user = create_ukfl_member($_REQUEST['first_name'], $_REQUEST['last_name'], $_REQUEST['user_email']);
		$rdf = cpg_ukfl_get_rdf($_REQUEST['redirect_flow_id']);
		add_user_meta( $user->ID, 'ukfl_date_joined', date('Y-m-d'), 1 );
		add_user_meta( $user->ID, 'ukfl_mandate_membership', $rdf->links->mandate, 1 );
		add_user_meta( $user->ID, 'ukfl_date_renewal', $rdf->upcoming_payments[1]->charge_date, 1 );
		add_user_meta( $user->ID, 'ukfl_membership_type', $rdf->name, 1 );
		$customer_ref = $rdf->links->customer;
		$user_query = new WP_User_Query( array( 'meta_key' => 'ukfl_customer_membership', 'meta_value' => $customer_ref ) );
		if (isset($user_query)){
			add_user_meta( $user->ID, 'ukfl_membership_link', $user_query->ID, 1 );
			add_user_meta( $user_query->ID, 'ukfl_membership_link', $user->ID, 1 );
		}
		wp_safe_redirect('/login/');
		exit;
	}
}

get_header();
get_template_part('index', 'bannerstrip');

?>
<!-- Blog & Sidebar Section -->
<section>		
	<div class="container">
		<div class="row">
			<!--Blog Posts-->
			<div class="col-md-12 col-xs-12">
				<div class="page-content">					
						<article id="post-<?php the_ID(); ?>" <?php post_class('post'); ?> > 					
						<div class="entry-content">
							<p>Many thanks for joining UKFL and selecting a Joint Membership. Your direct debit mandate and payment plan have been successfully setup (see reference below). Your new UKFL membership number and password should have been e-mailed to you, using the email address you have provided. If you haven't received this within the next 30 mins please <a href="/contact/">contact us</a>.</p>
							<div class="alert alert-success" role="alert"><?php the_post(); the_content(); ?></div>
							<p>Please fill in the details below for the other party on your joint membership to set them up their own account on the UKFL website. Their password and UKFL membership number will be emailed to them separately. Once completed, you will be redirected to the login page.</p>
							<div class="tml tml-register" id="theme-my-login">
								<form class="form form-horizontal" name="registerform" id="registerform" method="post">
									<div class="form-group">
										<label class="col-sm-2 control-label" for="first_name">Name</label>
										<div class="col-sm-2">
											<input type="text" placeholder="First Name" name="first_name" id="first_name" class="input form-control" value="" >
										</div>
										<div class="col-sm-2">
											<input type="text" placeholder="Last Name" name="last_name" id="last_name" class="input form-control" value="">
										</div>
										<label class="col-sm-2 control-label" for="user_email">E-mail</label>
										<div class="col-sm-4">
											<input type="text" name="user_email" id="user_email" class="input form-control" value="">
										</div>
								    </div>
									<p class="tml-submit-wrap">
										<input type="hidden" name="redirect_flow_id" id="redirect_flow_id" value="<?php echo $_GET['redirect_flow_id']; ?>" />
										<input type="submit" class="btn btn-success btn-busiprof pull-right" name="add_joint_member" id="add_joint_member" value="Continue">
									</p>
								</form>
							</div>
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
