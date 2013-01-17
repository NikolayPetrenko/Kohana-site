var show = {
	init: function()
        {
          this.spinner     =  $('#spinner');
          $('#more-feeds').click(function(){
              show.button = $(this);
              show.addMoreFeeds(this)
          });
	},
        
        addMoreFeeds:function(el){
            feedscount  =  $('.feed-item').length;
            show.button.hide();
            show.spinner.show();
            $.ajax({
                url: SYS.baseUrl + 'pets/ajax_get_more_feeds',
                type: 'POST',
                dataType: 'json',
                data: $.param({offset : feedscount}),
                success: function(res){
                  if(res.text == 'success'){
                    $('div#feeds-container').append(res.data.html);
                    show.spinner.hide();
                    if(res.data.count < SYS.total_list)
                      $('#controls').hide();
                    else
                      show.button.show();
                  };
                }
            })
        }
}

$(function() {
	show.init();
});