<?php Helper_Output::renderErrors() ?>
<div class="login-form">
	<fieldset class="control-group">
		<form method="post" action="">
                  <input type="hidden" name="twitter_token" value="<?php echo Cookie::get('oauth_token') ?>">
                  <input type="hidden" name="twitter_secret" value="<?php echo Cookie::get('oauth_token_secret')?>">
		<div>
			<label>First Name</label>
			<input type="text" name="firstname" value="<?php $res=isset($twitterinfo->name) ? explode(' ', $twitterinfo->name) : array(); echo isset($res[0]) ? $res[0] : $prevalue['firstname'] ?>" >
		</div>
		<div>
			<label>Last Name</label>
			<input type="text" name="lastname" value="<?php $res=isset($twitterinfo->name) ? explode(' ', $twitterinfo->name) : array(); echo isset($res[1]) ? $res[1] : $prevalue['lastname']; ?>" >
		</div>
		<div>
			<label>Email<span class="asterisk">*</span></label>
			<input type="text" id="email" name="email" value="<?php echo isset($prevalue['email']) ? $prevalue['email'] : '' ?>" >
			<input type="hidden" name="twitter_id" value="<?php echo isset($twitterinfo->id) ? $twitterinfo->id : $prevalue['twitter_id'] ?>" >
		</div>
		<div>
			<label class="checkbox">
			    <input id="termofuse" type="checkbox" <?php echo isset($prevalue['termofuse']) ? 'checked="checked"' : '' ?> name="termofuse" value="1"> Term of Use
			</label>
		</div>
		<div>
			<input class="btn btn-primary" type="submit" value="Sign Up">
		</div>
		</form>
	</fieldset>
</div>

<div class="login-form" id="exist-container">
</div>

<?php Helper_IO::grab('js') ?>
<script type="text/javascript">
	$('#email').focus();
</script>
<?php Helper_IO::stop() ?>