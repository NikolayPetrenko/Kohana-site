<?php Helper_Output::renderErrors() ?>
<div class="login-form">
	<fieldset class="control-group">
		<form method="post" action="">
                  <input type="hidden" name="facebook_token" value="<?php echo isset($fbinfo->accessToken) ? $fbinfo->accessToken : $prevalue['accessToken']?>">
		<div class="">
			<label>First Name<span class="asterisk">*</span></label>
			<input type="text" name="firstname" value="<?php echo isset($fbinfo->first_name) ? $fbinfo->first_name : $prevalue['firstname'] ?>" >
		</div>
		<div class="">
			<label>Last Name</label>
			<input type="text" name="lastname" value="<?php echo isset($fbinfo->last_name) ? $fbinfo->last_name :  $prevalue['lastname'] ?>" >
		</div>
		<div class="">
			<label>Email<span class="asterisk">*</span></label>
			<input disabled="disabled" type="text" value="<?php echo isset($fbinfo->email) ? $fbinfo->email : $prevalue['email'] ?>" >
			<input type="hidden" name="email" value="<?php echo isset($fbinfo->email) ? $fbinfo->email : $prevalue['email'] ?>" >
                        <input type="hidden" name="facebook_id" value="<?php echo isset($fbinfo->id) ? $fbinfo->id : $prevalue['facebook_id'] ?>" >
		</div>
		<div class="">
			<label class="checkbox">
			    <input id="termofuse" type="checkbox" <?php echo isset($prevalue['termofuse']) ? 'checked="checked"' : '' ?> name="termofuse" value="1"> Term of Use
			</label>
		</div>
		<div class="">
			<input class="btn btn-primary" type="submit" value="Sign Up">
		</div>
		</form>
	</fieldset>
</div>

<?php Helper_IO::grab('js') ?>
<script type="text/javascript">
	$('#termofuse').focus();
</script>
<?php Helper_IO::stop() ?>