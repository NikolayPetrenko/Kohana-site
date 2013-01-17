var registration_twitter = {
	init: function()
        {
          this.email          = $('input[name=email]');
          this.twitter_id     = $('input[name=twitter_id]');
          this.twitter_token  = $('input[name=twitter_token]');
          this.twitter_secret = $('input[name=twitter_secret]');
          this.validation();
          this.chechExistUser();
	},
        
        validation: function(){
          this.form = $('form').validate({
                rules: {
                    firstname: {
                        required: true,
                        minlength: 3,
                        maxlength: 40
                    },
                    lastname:{
                        required: true,
                        minlength: 3,
                        maxlength: 40
                    },
                    email:{
                        required :true,
                        email:true
                    }
                }
                
            });
        },
        
        
        chechExistUser: function(){
          $('input[type=submit]').click(function(event){
              event.preventDefault();
              registration_twitter.sign_up = $(this);
              registration_twitter.sign_up.hide();
              if(registration_twitter.form.valid()){
                  $.ajax({
                    url: SYS.baseUrl + 'users/check_registrate_user',
                    type: 'POST',
                    dataType: 'json',
                    data: $.param({email:registration_twitter.email.val()}),
                    success: function(res){
                      if(res.data.status == 'exist'){
                          $('#exist-container').html(res.data.html);
                      }else if (res.data.status == 'not exist'){
                          $('form').submit();
                      }
                      
                    }
                  })  
              }
              
          });
        },
        
        cancelEvent: function(){
          $('#exist-container').empty();
          registration_twitter.email.val('').focus();
          registration_twitter.sign_up.show();
          
        },
        
        checkExistUserPassword: function(){
          $.ajax({
              url: SYS.baseUrl + 'users/check_exist_user_password',
              type: 'POST',
              dataType: 'json',
              data: $.param({email          : registration_twitter.email.val(), 
                             password       : $('input[type=password]').val(), 
                             twitter_id     : registration_twitter.twitter_id.val(),
                             twitter_token  : registration_twitter.twitter_token.val(),
                             twitter_secret : registration_twitter.twitter_secret.val()
                            }),
              success: function(res){
                  if(res.text == 'success'){
                    window.location.href = SYS.baseUrl + 'users/profile';
                  }else if (res.text == 'failure') {
                    alert.setStatus('error').setMessage(res.errors[0]).render();
                  }
              }
          })
        }
        
}

$(function() {
	registration_twitter.init();
});