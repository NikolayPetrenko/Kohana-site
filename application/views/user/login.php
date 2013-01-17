<?php Helper_Output::renderErrors() ?>
<div class="login-form">
	<div class="socials">
		<div class="fb-login">
			<div rel-data="<?php echo FacebookAuth::factory()->login_url() ?>" class="pluginFaviconButton pluginFaviconButtonXlarge" id="uxunnu_1">
				<i class="pluginFaviconButtonIcon img sp_9kaipv sx_440ac4"></i>
				<span class="pluginFaviconButtonBorder">
					<span class="pluginFaviconButtonText fwb">Login With Facebook</span>
				</span>
			</div>
		</div>

 		<div class="fb-twitter">
 			<span class="twitter-follow-btn twitter-follow-btn-default" style="display: inline-block; ">
                            <a href="<?php echo Helper_iAuth::getTwitter()-> getAuthenticateUrl() ?>">Login with Twitter</a>
                        </span>
                </div>
	</div>
	<fieldset class="control-group">
		<form method="post" action="">
			<div class="">
				<label>Email</label>
				<input class="span3" type="text" name="email" value="<?php echo Helper_Output::getval($prevalue['email']) ?>" >
			</div>
			<div class="">
				<label>Password</label>
				<input class="span3" type="password" name="password" >
			</div>
			<p>Not Registered Yet? <a href="<?php echo URL::base() . 'users/registrate' ?>">Create Account Now</a></p>
			<p><a href="<?php echo URL::base() . 'users/forgot' ?>">Forgot your password?</a></p>
			<div class="">
				<input class="btn btn-primary" type="submit" value="Sign in">
			</div>
		</form>
	</fieldset>
</div>
