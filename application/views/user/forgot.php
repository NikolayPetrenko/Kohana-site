<fieldset class="control-group">
	<form method="post" action="" id="formforgotpassword">
		<h2>Forgot your password?</h2>
		<p>Enter your login below to reset your password</p>
		<div class="input-row">
			<label>Enter adress</label>
			<input class="span3" type="text" name="email" value="" >
			<input id="sendemial" class="btn btn-primary" type="submit" value="Send">
		</div>
		<?php Helper_Output::renderErrors() ?>
		<div class="alert alert-error" style="display:none"></div>
	</form>
</fieldset>