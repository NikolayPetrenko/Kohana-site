var user_profile = {
	init: function()
        {
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
                        $(el).remove();
                        alert.setStatus('success').setMessage('Your request was send').render();
                    }
                }
              })
            }
        }
        
        
}

$(function() {
	user_profile.init();
})