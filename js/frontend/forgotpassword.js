var forgot = {
	init:function() {
		this.emailValidation();
		this.checkAdress();
	},

	emailValidation: function() {
		$('#formforgotpassword').validate({
			rules: {
				email: {
					required	: true,
					email 		: true
				}
			},

			wrapper : 'p',
			errorLabelContainer: '.alert-error',
		});
	},

	checkAdress: function() {
		$('#sendemial').click(function(e) {
			e.preventDefault();
			if($('#formforgotpassword').valid()) {
				$.ajax({
					url 		: SYS.baseUrl + 'users/ajax_email_check',
					type 		: 'POST',
					dataType	: 'json',
					data: $('#formforgotpassword').serialize(),
					success: function(response) {
		                if(response.text == 'success') {
		                	$('#formforgotpassword').submit();
		                } else {
		                	$('.alert-error').html('<label for="email" generated="true" class="error" style="display: block; ">' + response.errors[0] + '</label>');
		                	$('.alert-error').show();
		                }
		            }
				});
			}
		})
		
	}
}

$(function() {
	forgot.init();
});