<?php
//Template Name: UKFL Account - Dog

global $wpdb, $current_user;

if (!is_user_logged_in()) { wp_safe_redirect('/login/'); exit; }
if (!(current_user_can('ukfl_member'))){ wp_safe_redirect('/account/'); exit; }


if (isset($_POST['add_dog'])){
	$content = do_shortcode("[gcp_redirect_flow ref=5]"); 
	$js_for_footer = '
<script type="text/javascript">
        jQuery(function ($) {
		console.log($("a.gcp_redirect_flow6"));
		$("a.gcp_redirect_flow5")[0].click();
	 } );
</script>';
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
<form method="post" class="form form-horizontal">
        <div class="form-group">
		<label class="col-sm-2 control-label" for="ukfl_no">UKFL No.</label>
                <div class="col-sm-2">
                        <input type="text" name="ukfl_no" id="ukfl_no" class="input form-control" value="<?php echo $ukfl_no; ?>" readonly />
			<input type="hidden" name="dog_ukfl" id="dog_ukfl" value="<?php echo $dog_letter; ?>" />
                </div>
                <label class="col-sm-2 control-label" for="dog_name">Dog's Name</label>
                <div class="col-sm-2">
                        <input type="text" name="dog_name" id="dog_name" class="input form-control" value=""  />
                </div>
                <label class="col-sm-2 control-label" for="birth_date">Birth Date</label>
                <div class="col-sm-2">
                        <input type="text" name="birth_date" id="birth_date" class="datepicker input form-control" value="" />
                </div>
        </div>
	<div class="form-group">
                <label class="col-sm-2 control-label" for="breed">Breed</label>
		<div class="col-sm-6">
			<select name="breed" class="form-control">
				<option value="">Select Breed...</option>
				<?php echo get_options_for_breeds('dog-breeds', $dogBreeds, $dog['breed']); ?>
			</select>
		</div>
                <label class="col-sm-2 control-label" for="microchip">Microchip</label>
		<div class="col-sm-2">
			<input type="text" name="microchip" id="microchip" class="input form-control" value=""  />
                </div>
        </div>
	<div class="form-group">
                <label class="col-sm-2 control-label" for="current_club">Flyball Club</label>
		<div class="col-sm-6">
                        <select name="current_club" class="form-control">
                                <option value="0">Select Current Club...</option>
                        </select>
                </div>
                <label class="col-sm-2 control-label" for="sex">Sex</label>
                <div class="col-sm-2">
			<label class="radio-inline"><input type="radio" name="sex" value="Dog" <?php if($ukfl_dog['sex'] == 'Dog') { echo 'checked="checked"'; } ?>> Dog</label>
                        <label class="radio-inline"><input type="radio" name="sex" value="Bitch" <?php if($ukfl_dog['sex'] == 'Bitch') { echo 'checked="checked"'; } ?>> Bitch</label>	
                </div>
	</div>
<?php if(!isset($_GET['edit'])){ ?>
<!--	<div class="panel panel-danger col-sm-offset-2">
		<div class="panel-body">
			<p><em>Please enter your BFA details here if you wish us to transfer your current points...</em></p>
			<label class="col-sm-2 control-label" for="bfa_no">BFA No.</label>
			<div class="col-sm-2">
				<input type="text" name="bfa_no" id="bfa_no" class="input form-control" value=""  />
			</div>
			<div class="col-sm-1 control-label">                            		
		         	<input type="checkbox" data-toggle="toggle" data-on="Yes" data-off="No" data-size="small" name="bfa_points" id="bfa_points" />
		        </div>
		        <div class="col-sm-7">
		         	<label>As the owner of this dog, I give my consent for their current points total to be downloaded from the BFA website and used as the starting point for their future UKFL racing career.</label>

			</div>
		</div>
	</div> -->
<?php } ?>
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
						
