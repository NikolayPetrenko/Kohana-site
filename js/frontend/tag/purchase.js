var purchase = {
	init: function()
        {
          Stripe.setPublishableKey('pk_JHkYV6HEUJFQJRwhQxCZJ7aq4LQY1');
          this.stripeSendButton = $('.submit-button');
          this.tokenField       = $('input[name=stripe_token]');
          this.form             = $('form');
          this.validatorInit();
          this.initPaymentForm();
	},
        
        initPaymentForm: function(){
            purchase.stripeSendButton.click(function(event) {
                event.preventDefault(); // disable the submit button to prevent repeated clicks
                
                if(purchase.tokenField.val() == ''){
                
                    purchase.stripeSendButton.attr("disabled", "disabled");
                    Stripe.createToken({
                        number: $('.card-number').val(),
                        cvc: $('.card-cvc').val(),
                        name: $('.card-name').find('option:selected').val(),
                        exp_month: $('.card-expiry-month').val(),
                        exp_year: $('.card-expiry-year').val()
                    }, purchase.stripeResponseHandler);
                }else{
                  if(purchase.form.valid())
                    purchase.form.submit();
                }

            });
        },
        
        stripeResponseHandler: function(res, data){
          if(res == 200){
            purchase.tokenField.val(data.id);
            if(purchase.form.valid())
              purchase.form.submit();
          }else{
            purchase.stripeSendButton.removeAttr("disabled");
            alert.setStatus('error').setMessage(data.error.message).render() ;
          }
        },
        
        validatorInit: function(){
          purchase.form.validate({
                rules: {
                    address: {
                        required: true
                    },
                    city:{
                        required: true
                    },
                    state:{
                        required: true
                    },
                    zip:{
                        required: true
                    },
                    primary_phone:{
                        required: true
                    }
                }
            });
        }
        
        

        
}

$(function() {
	purchase.init();
});