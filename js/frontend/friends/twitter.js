var twitter = {
	init: function() {
          $('#twitter-followers').click(function(){
            twitter.addMoreFriends(this);
          });
	},
        
        
        addMoreFriends:function(el){
          spinner =  $('#spinner');
          button  =  $(el);
          button.hide();
          spinner.show();
          $.ajax({
            url: SYS.baseUrl + 'friends/ajaxTwitterFriendsPart',
            type: 'POST',
            dataType: 'json',
            data: $.param({cursor : twitter_cursor}),
            success: function(res){
              if(res.text == 'success'){
                twitter_cursor = res.data.cursor;
                $('tbody').append(res.data.html);
                spinner.hide();
                button.show();
              };
            }
          })
        },
        
        
        invite: function(screen_name, el)
        {
            if(confirm('invite follower?')){
              $.ajax({
                  url: SYS.baseUrl + 'friends/ajax_send_twitter_invite',
                  type: 'POST',
                  dataType: 'json',
                  data: $.param({screen_name: screen_name}),
                  success: function(res){
                    if(res.text == 'success'){
                    $(el).html('<i class="icon-ok"></i> Invite was send');
                    $(el).attr('disabled', 'disabled');
                    alert.setStatus('success').setMessage('Invite was send').render();
                    $(el).removeAttr('onclick'); 
                    }
                  }
              })
            }
        },
        
        addTWTFriend: function(id, el)
        {
            if(confirm('add in friends?')){
              $.ajax({
                url: SYS.baseUrl + 'friends/add_friend',
                type: 'POST',
                dataType: 'json',
                data: $.param({twitter_id: id}),
                success: function(res){
                    if(res.text == 'success'){
                        alert.setStatus('success').setMessage('Invite was send').render();
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
	twitter.init();
});