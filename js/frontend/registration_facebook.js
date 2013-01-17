var registration = {
	init: function() {
		this.facebookAuth();
                this.validation();
                $.validator.addMethod(
                "checkEmail",
                function(value) {
                    return eval(
                                  $.ajax({
                                                url   : SYS.baseUrl + '/users/ajax_check_email',
                                                data  : jQuery.param({email:value}),
                                                async : false,
                                                type  : 'post'
                                              }).responseText
                              );
                },
                "Email already exists!"
                );
	},
        
        validation: function(){
          $('form').validate({
                rules: {
                    firstname: {
                        required: true
                    },
                    email:{
                        required: true,
                        email:true,
                        checkEmail: true
                    },
                    password:{
                        required :true,
                        minlength: 6
                    },
                    password_confirm:{
                        equalTo: 'input[name=password]',
                        minlength: 6
                    },
                    termofuse:{
                        required: true
                    }
                }
                
            });
            $.extend($.validator.messages, {
                minlength: "Min 6 simbols",
                equalTo: "not the same"
            });
          
        },

	facebookAuth: function() {
		$('#uxunnu_1').click(function() {
			window.location.href = $(this).attr('rel-data');
		});
	}
}

$(function() {
	registration.init();
});