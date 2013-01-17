var unleashed = {
	init: function() {
            $('#unleashed-friends').click(function(){
                unleashed.addMoreUnleashedFriends(this);
            });

            $('#invites-for-me').click(function(){
                unleashed.addMoreFriendsWhoInvitesMe(this);
            });

            $('#my-invites').click(function(){
                unleashed.addMoreMyInvites(this);
            });
	},
        
        
        
        addMoreUnleashedFriends:function(el){
          offset  =  $('#unleashed-friends-table').find('tbody > tr').length;
          spinner =  $('#spinner');
          button  =  $(el);
          button.hide();
          spinner.show();
          $.ajax({
            url: SYS.baseUrl + 'friends/ajax_get_more_unleashed_friends',
            type: 'POST',
            dataType: 'json',
            data: $.param({offset : offset}),
            success: function(res){
              if(res.text == 'success'){
                $('#unleashed-friends-table').find('tbody').append(res.data.html);
                spinner.hide();
                if(!res.data.flag)
                  button.show();
              };
            }
          })
        },
        
        addMoreFriendsWhoInvitesMe:function(el){
          offset  =  $('#invites-for-me-table').find('tbody > tr').length;
          spinner =  $('#spinner');
          button  =  $(el);
          button.hide();
          spinner.show();
          $.ajax({
            url: SYS.baseUrl + 'friends/ajax_get_more_invites_for_me_friends',
            type: 'POST',
            dataType: 'json',
            data: $.param({offset : offset}),
            success: function(res){
              if(res.text == 'success'){
                $('#invites-for-me-table').find('tbody').append(res.data.html);
                spinner.hide();
                if(!res.data.flag)
                  button.show();
              };
            }
          })
        },
        
        addMoreMyInvites:function(el){
          offset  =  $('#my-invites-table').find('tbody > tr').length;
          spinner =  $('#spinner');
          button  =  $(el);
          button.hide();
          spinner.show();
          $.ajax({
            url: SYS.baseUrl + 'friends/ajax_get_more_my_invites',
            type: 'POST',
            dataType: 'json',
            data: $.param({offset : offset}),
            success: function(res){
              if(res.text == 'success'){
                $('#my-invites-table').find('tbody').append(res.data.html);
                spinner.hide();
                if(!res.data.flag)
                  button.show();
              };
            }
          })
        },
        
        
        
        
        removeFromFriends: function(id, el)
        {
            $.ajax({
              url: SYS.baseUrl + 'friends/delete_friend',
              type: 'POST',
              dataType: 'json',
              data: $.param({id : id}),
              success: function(res){
                  if(res.text == 'success'){
                      alert.setStatus('success').setMessage('Friend was remove').render();
                      $(el).parents('tr').remove();
                  }
              }
            })
            
        },
        
        removeMyReuest: function(id, el)
        {
            $.ajax({
              url: SYS.baseUrl + 'friends/reject_friend',
              type: 'POST',
              dataType: 'json',
              data: $.param({id : id}),
              success: function(res){
                  if(res.text == 'success'){
                      alert.setStatus('success').setMessage('Invite was remove').render();
                      $(el).parents('tr').remove();
                  }
              }
            })
          
        },
        
        acceptFriend: function(id, el)
        {
             $.ajax({
              url: SYS.baseUrl + 'friends/accept_friend',
              type: 'POST',
              dataType: 'json',
              data: $.param({id : id}),
              success: function(res){
                  if(res.text == 'success'){
                      $(el).parents('tr').remove();
                      location.reload();
                  }
              }
            })
        },
        
        cancelFriendRequest: function(id, el)
        {
             $.ajax({
              url: SYS.baseUrl + 'friends/cancel_friend',
              type: 'POST',
              dataType: 'json',
              data: $.param({id : id}),
              success: function(res){
                  if(res.text == 'success'){
                      alert.setStatus('success').setMessage('Invite was remove').render();
                      $(el).parents('tr').remove();
                  }
              }
            })
        }
        
        
        
        
}

$(function() {
	unleashed.init();
});