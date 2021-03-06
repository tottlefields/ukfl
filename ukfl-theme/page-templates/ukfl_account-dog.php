<?php
//Template Name: UKFL Account - Dog

global $wpdb, $current_user;

if (!is_user_logged_in()) { wp_safe_redirect('/login/'); exit; }
if (!(current_user_can('ukfl_member'))){ wp_safe_redirect('/account/'); exit; }

if (isset($_POST['add_dog'])){

	$dog_post = array(
			'post_title'  	=> $_POST['ukfl_no'],
			'post_name'  	=> $_POST['ukfl_no'],
			'post_status' 	=> 'draft',
			//'post_author' 	=> $_POST['owner_id'],
			'post_author' 	=> $current_user->ID,
			'post_parent'	=> $_POST['current_team'],
			'post_type'		=> 'ukfl_dog',
			'tax_input'		=> array(
					'dog-breeds' => array(intval($_POST['breed'])),
			),
			'meta_input'   => array(
					'ukfl_dog_owner'	=> $_POST['owner_ukfl'],
					'ukfl_dog_name'		=> $_POST['dog_name'],
					'ukfl_dog_letter'	=> $_POST['dog_ukfl'],
					'ukfl_dog_dob'		=> dateToSQL($_POST['birth_date']),
					'ukfl_dog_microchip'=> $_POST['microchip'],
					'ukfl_dog_sex'		=> $_POST['sex'],
			),
	);
	$dog_id = wp_insert_post( $dog_post );

	$content = do_shortcode("[gcp_redirect_flow ref=5]");
	$js_for_footer = '
<script type="text/javascript">
        jQuery(function ($) {
		$("a.gcp_redirect_flow5")[0].click();
	 } );
</script>';
}else{
	$js_for_footer = '
<script type="text/javascript">
        jQuery(function ($) {
			$(".ukfl-datepicker").datepicker({
				format: "dd/mm/yyyy",
				weekStart: 1,
				daysOfWeekHighlighted: "0,6",
				autoclose: true,
				todayHighlight: true,
				orientation: "bottom"
			});
		$("#owner_ukfl").on("change", function() {
			$("#dog_ukfl").val(juniors[$(this).val()]["dog_letter"]);
			$("#owner_id").val(juniors[$(this).val()]["owner_ukfl"]);
			$("#ukfl_no").val(juniors[$(this).val()]["ukfl_no"]);
		});
	 } );
</script>';
}

$juniors = array();
$junior_ids = get_user_meta( $current_user->ID, "ukfl_juniors", 1);
if($junior_ids != ''){
	foreach (explode(',', get_user_meta( $current_user->ID, "ukfl_juniors", 1)) as $ukfl){
		$junior = get_user_by('login', $ukfl);
		$dog_letter = generate_ukfl_dog_number($ukfl);
		$juniors[$ukfl] = array('name' => $junior->display_name, 'owner_ukfl' => $junior->ID, 'dog_letter' => $dog_letter, 'ukfl_no' => $ukfl.$dog_letter);
	}
}
if (count($juniors) > 0){
        $js_for_footer = '
<script type="text/javascript">
	var juniors = '.json_encode($juniors).'
</script>'.$js_for_footer;

}

if(isset($_GET['edit']) && isset($_GET['dogID'])) { $TITLE = "Edit Dog"; }
else{
	$TITLE = "Add a New Dog";
	$dog_letter = generate_ukfl_dog_number($current_user->user_login);
	$ukfl_no = $current_user->user_login.$dog_letter;
}

get_header();
include(locate_template('index-bannerstrip.php'));

$breeds = get_terms('dog-breeds', array('hide_empty' => false));
$dogBreeds = array();
foreach($breeds as $b) {
	$dogBreeds[$b->term_id] = array('name' => $b->name, 'slug' => $b->slug);
}

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
							<?php if (isset($_POST['add_dog'])){ echo $content; } else {?>
							<div class="alert alert-info">Registration is &pound;2.50 per dog per year. Please fill in the payment details requested after adding your dog to complete your dog's UKFL&copy; registration.<br/>
							Please be aware that entering the dog's microchip number is not mandatory. You can always add this at a later stage if we require it.</div>
							<form method="post" class="form form-horizontal">
								<div class="form-group">
									<label class="col-sm-2 control-label" for="owner">Owner</label>
									<div class="col-sm-2">
									<?php if (count($juniors) == 0){ ?>
										<input type="text" name="owner" id="owner" class="input form-control" value="<?php echo $current_user->user_firstname.' '.$current_user->user_lastname; ?>" readonly />
                                                                                <input type="hidden" name="owner_ukfl" value="<?php echo $current_user->user_login; ?>" />
									<?php } else { ?>
										<select name="owner_ukfl" id="owner_ukfl" class="form-control">
											<option value="<?php echo  $current_user->user_login; ?>"><?php echo $current_user->user_firstname.' '.$current_user->user_lastname; ?></option>
									<?php foreach ($juniors as $jn => $j){ ?>
											<option value="<?php echo $jn; ?>"><?php echo $j['name']; ?></option>
									<?php } ?>
										</select>
									<?php } ?>
										<input type="hidden" name="owner_id" id="owner_id" value="<?php echo $current_user->ID; ?>" />
									</div>
									<label class="col-sm-2 control-label" for="ukfl_no">UKFL No.</label>
									<div class="col-sm-2">
										<input type="text" name="ukfl_no" id="ukfl_no" class="input form-control" value="<?php echo $ukfl_no; ?>" readonly />
										<input type="hidden" name="dog_ukfl" id="dog_ukfl" value="<?php echo $dog_letter; ?>" />
									</div>
									<label class="col-sm-2 control-label" for="dog_name">Dog's Name</label>
									<div class="col-sm-2">
									        <input type="text" name="dog_name" id="dog_name" class="input form-control" value=""  />
									</div>
							    </div>
								<div class="form-group">
									<label class="col-sm-2 control-label" for="birth_date">Birth Date</label>
									<div class="col-sm-2">
									        <input type="text" name="birth_date" id="birth_date" class="ukfl-datepicker input form-control" value="" />
									</div>
									<label class="col-sm-2 control-label" for="microchip">Microchip</label>
									<div class="col-sm-2">
										<input type="text" name="microchip" id="microchip" class="input form-control" value=""  />
									</div>
									<label class="col-sm-2 control-label" for="sex">Sex</label>
									<div class="col-sm-2">
										<label class="radio-inline"><input type="radio" name="sex" value="Dog" <?php if($ukfl_dog['sex'] == 'Dog') { echo 'checked="checked"'; } ?>> Dog</label>
										<label class="radio-inline"><input type="radio" name="sex" value="Bitch" <?php if($ukfl_dog['sex'] == 'Bitch') { echo 'checked="checked"'; } ?>> Bitch</label>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label" for="breed">Breed</label>
									<div class="col-sm-4">
										<select name="breed" class="form-control">
											<option value="">Select Breed...</option>
											<?php echo get_options_for_breeds('dog-breeds', $dogBreeds, $dog['breed']); ?>
										</select>
									</div>
									<label class="col-sm-2 control-label" for="current_team">Flyball Club</label>
									<div class="col-sm-4">
										<?php echo get_club_dropdown_menu("current_team", "Select Current Team...", $dog->post_parent); ?>
									</div>
								</div>
								<div class="form-group">
									<div class="controls">
							                 	<input type="submit" name="add_dog" id="add_dog" value="Add Dog" class="btn btn-success btn-busiprof pull-right" />
							                </div>
							        </div>    
							</form>
							<?php } ?>
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
<?php echo $js_for_footer; ?>
						
