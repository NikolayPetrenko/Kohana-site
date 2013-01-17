var facebook_list = {
	init: function() {
          FB.init({ appId: '307951789291958', status: true, cookie: true, xfbml : true });
          
          $('#facebook-friends').click(function(){
            facebook_list.addMoreFriends(this);
          });
          
	},
        
        
        addMoreFriends:function(el){
          offset  =  $('tbody > tr').length;
          spinner =  $('#spinner');
          button  =  $(el);
          button.hide();
          spinner.show();
          $.ajax({
            url: SYS.baseUrl + 'friends/ajaxFacebookFriendsPart',
            type: 'POST',
            dataType: 'json',
            data: $.param({offset : offset}),
            success: function(res){
              if(res.text == 'success'){
                $('tbody').append(res.data.html);
                spinner.hide();
                if(res.data.flag)
                  $('#controls').hide();
                else
                  button.show();
              };
            }
          })
        },
        
        massInvite: function(){
                FB.ui({method: 'apprequests', 
                       message: 'You should learn more about this awesome site.', 
                       data: 'tracking information for the user'}, 
                       function(response){
                          console.log(response); 
                       });
                return false;
        },
        
        invite: function(id, el)
        {
                FB.ui({method: 'apprequests', 
                       to: id, 
                       message: 'You should learn more about this awesome site.', 
                       data: 'tracking information for the user'}, 
                       function(response){
                          if(response.request){
                              alert.setStatus('success').setMessage('Invite was send').render();
                              $(el).attr('disabled', 'disabled');
                              $(el).html('<i class="icon-ok"></i> Invite was send');
                          }
                     });
                return false;
            
        },
        
        addFBFriend: function(id, el)
        {
            if(confirm('add in friends?')){
              $.ajax({
                url: SYS.baseUrl + 'friends/add_friend',
                type: 'POST',
                dataType: 'json',
                data: $.param({facebook_id: id}),
                success: function(res){
                    if(res.text == 'success'){
                        alert.setStatus('success').setMessage('Friend was invite').render();
                        $(el).attr('disabled', 'disabled');
                        $(el).html('<i class="icon-question-sign"></i>Waiting...');
                        $(el).removeAttr('onclick'); 
                    }
                }
              })
            }
        }
        
        
}

$(function() {
	facebook_list.init();
});