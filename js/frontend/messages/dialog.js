var dialog = {
	init: function()
        {
          this.hoverMyRemoveItems();
	},
        
        hoverMyRemoveItems: function(){
          $('blockquote').hover(function(){
            $(this).find('small.remove-items').show();
          }, function(){
            $(this).find('small.remove-items').hide();
          })
        },
        
        removeItem: function(id, el){
          $.ajax({
            url: SYS.baseUrl + 'messages/removeItem',
            type: 'POST',
            dataType: 'json',
            data: $.param({id:id}),
            success: function(res){
              if(res.text == 'success'){
                $(el).parents('div.row').fadeOut('fast');
              }
            }
          })
          
          
        }
        
        
}

$(function() {
	dialog.init();
});