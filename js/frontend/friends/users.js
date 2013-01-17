var users = {
	init: function()
        {
          this.button      = $('#controls');
          this.spinner     = $('#spinner');
          this.searchField = $('input[name=q]');
          this.addInitEvents();
	},
        
        addInitEvents: function()
        {
            users.button.click(function(){
                users.addMoreFriends(this);
            });
            users.searchField.keyup(function(ev){
              users.addMoreFriendsSearch($(this).val());
            });
        },
        
        addMoreFriendsSearch:function(t){
          offset  =  $('tbody > tr').length;
          users.button.hide();
          users.spinner.show();
          $.ajax({
            url: SYS.baseUrl + 'friends/get_more_users',
            type: 'POST',
            dataType: 'json',
            data: $.param({offset : offset, q : t}),
            success: function(res){
              if(res.text == 'success'){
                $('tbody').html(res.data.html);
                users.spinner.hide();
                if(!res.data.flag)
                  users.button.show();
              };
            }
          })
        },
        
        
        addMoreFriends:function(el, t){
          offset  =  $('tbody > tr').length;
          if(users.searchField)
            q = users.searchField.val();
          else
            q = '';
          users.button.hide();
          users.spinner.show();
          $.ajax({
            url: SYS.baseUrl + 'friends/get_more_users',
            type: 'POST',
            dataType: 'json',
            data: $.param({offset : offset, q:q}),
            success: function(res){
              if(res.text == 'success'){
                $('tbody').append(res.data.html);
                users.spinner.hide();
                if(!res.data.flag)
                  users.button.show();
              };
            }
          })
        },
        
        addINFriend: function(id, el)
        {
            if(confirm('add in friends?')){
              $.ajax({
                url: SYS.baseUrl + 'friends/add_friend',
                type: 'POST',
                dataType: 'json',
                data: $.param({unleashed_id: id}),
                success: function(res){
                    if(res.text == 'success'){
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
	users.init();
});