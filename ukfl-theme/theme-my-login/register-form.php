<?php
/*
If you would like to edit this file, copy it to your current theme's directory and edit it there.
Theme My Login will always look in your theme's directory first, before using this default template.
*/
?>
<?php
wp_redirect('/membership/');
exit;
$ukfl_no = generate_ukfl_number();
?>
<div class="alert alert-warning">Your registration email should be sent within minutes of you completing this form. Please check your spam/junk folders. If you haven't received anything within 30 minutes, please <a href="/contact/">contact us</a>.</div>
<div class="tml tml-register" id="theme-my-login<?php $template->the_instance(); ?>">
	<?php #$template->the_action_template_message( 'register' ); ?>
	<?php $template->the_errors(); ?>
	<form class="form form-horizontal" name="registerform" id="registerform<?php $template->the_instance(); ?>" action="<?php $template->the_action_url( 'register', 'login_post' ); ?>" method="post">
		<div class="form-group">
			<label class="col-sm-2 control-label" for="user_login<?php $template->the_instance(); ?>"><?php _e( 'UKFL Number', 'theme-my-login' ); ?></label>
			<div class="col-sm-3">
				<input type="text" name="user_login" id="user_login<?php $template->the_instance(); ?>" class="input form-control" value="<?php echo $ukfl_no; ?>" readonly size="20" />
			</div>
			<label class="col-sm-2 control-label" for="user_email<?php $template->the_instance(); ?>"><?php _e( 'E-mail', 'theme-my-login' ); ?></label>
			<div class="col-sm-5">
				<input type="text" name="user_email" id="user_email<?php $template->the_instance(); ?>" class="input form-control" value="<?php $template->the_posted_value( 'user_email' ); ?>" size="20" />
			</div>

		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="first_name<?php $template->the_instance(); ?>"><?php _e( 'First name', 'theme-my-login' ) ?></label>
			<div class="col-sm-3">
				<input type="text" name="first_name" id="first_name<?php $template->the_instance(); ?>" class="input form-control" value="<?php $template->the_posted_value( 'first_name' ); ?>" size="20" />
			</div>
                        <label class="col-sm-2 control-label" for="last_name<?php $template->the_instance(); ?>"><?php _e( 'Last name', 'theme-my-login' ) ?></label>
			<div class="col-sm-5">
				<input type="text" name="last_name" id="last_name<?php $template->the_instance(); ?>" class="input form-control" value="<?php $template->the_posted_value( 'last_name' ); ?>" size="20" />
			</div>
                </div>
		
		<div class="form-group">
			<label class="col-sm-2 control-label" for="pass1<?php $template->the_instance(); ?>"><?php _e( 'Password', 'theme-my-login' ); ?></label>
			<div class="col-sm-3">
	                        <input autocomplete="off" name="pass1" id="pass1<?php $template->the_instance(); ?>" class="input" size="20" value="" type="password" />
			</div>
                        <label class="col-sm-2 control-label" for="pass2<?php $template->the_instance(); ?>"><?php _e( 'Confirm Password', 'theme-my-login' ); ?></label>
			<div class="col-sm-5">
	                        <input autocomplete="off" name="pass2" id="pass2<?php $template->the_instance(); ?>" class="input" size="20" value="" type="password" />
			</div>
		</div>

		<?php #do_action( 'register_form' ); ?>

		<p class="tml-registration-confirmation" id="reg_passmail<?php $template->the_instance(); ?>"><?php echo apply_filters( 'tml_register_passmail_template_message', __( 'Registration confirmation will be e-mailed to you.', 'theme-my-login' ) ); ?></p>

		<p class="tml-submit-wrap">
			<input type="submit" class="btn btn-success pull-right" name="wp-submit" id="wp-submit<?php $template->the_instance(); ?>" value="<?php esc_attr_e( 'Register', 'theme-my-login' ); ?>" />
			<input type="hidden" name="redirect_to" value="<?php $template->the_redirect_url( 'register' ); ?>" />
			<input type="hidden" name="instance" value="<?php $template->the_instance(); ?>" />
			<input type="hidden" name="action" value="register" />
		</p>
	</form>
	<?php $template->the_action_links( array( 'register' => false ) ); ?>
</div>

