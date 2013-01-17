<?php Helper_Output::renderErrors() ?>
<div class="login-form">
	<div class="socials">
		<div class="fb-login">
			<div rel-data="<?php echo FacebookAuth::factory()->login_url() ?>" class="pluginFaviconButton pluginFaviconButtonXlarge" id="uxunnu_1">
				<i class="pluginFaviconButtonIcon img sp_9kaipv sx_440ac4"></i>
				<span class="pluginFaviconButtonBorder">
					<span class="pluginFaviconButtonText fwb">Connect With Facebook</span>
				</span>
			</div>
		</div>

 		<div class="fb-twitter">
 			<span class="twitter-follow-btn twitter-follow-btn-default" style="display: inline-block; "><i><b></b></i><a href="<?php echo Helper_iAuth::getTwitter()-> getAuthenticateUrl() ?>">Login with Twitter</a></span>
        </div>
	</div>
	<fieldset class="control-group">
		<form method="post" action="">
		<div class="">
			<label>First Name<span class="asterisk">*</span></label>
			<input type="text" name="firstname" value="<?php echo Helper_Output::getval($prevalue['firstname']) ?>" >
		</div>
		<div class="">
			<label>Last Name</label>
			<input type="text" name="lastname" value="<?php echo Helper_Output::getval($prevalue['lastname']) ?>" >
		</div>
		<div class="">
			<label>Email<span class="asterisk">*</span></label>
			<input type="text" name="email" value="<?php echo Helper_Output::getval($prevalue['email']) ?>" >
		</div>
		<div class="">
			<label>Password<span class="asterisk">*</span></label>
			<input type="password" name="password"  >
		</div>
		<div class="">
			<label>Confirm Password</label>
			<input  type="password" name="password_confirm" >
		</div>
		<div class="">
			<label class="checkbox">
                          <input type="checkbox" <?php echo isset($prevalue['termofuse']) ? 'checked="checked"' : '' ?> name="termofuse" value="1"> <a href="<?php echo URL::site('info/term_of_use')?>">Term of Use</a><span class="asterisk">*</span>
			</label>
		</div>
		<p>Already a member? <a href="<?php echo URL::base() . 'users/login' ?>">Login Now</a></p>
		<div class="">
			<input class="btn btn-primary" type="submit" value="Sign Up">
		</div>
		</form>
	</fieldset>
</div>