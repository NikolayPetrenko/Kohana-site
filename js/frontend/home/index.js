var index = {
	init: function()
        {
          index.redirectPet();
	},
        
        redirectPet: function(){
          $('input[type=submit]').click(function(event){
            event.preventDefault();
            location.href = SYS.baseUrl + $('input[name=pet_id]').val();
          });
        }
        
        
}

$(function() {
      index.init();
});