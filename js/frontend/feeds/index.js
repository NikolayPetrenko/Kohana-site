var show = {
	init: function()
        {
          this.spinner     =  $('#spinner');
          $('#more-feeds').click(function(){
              show.button = $(this);
              show.addMoreFeeds(this)
          });
          this.caruselInit();
	},
        
        
        caruselInit: function(){
          $('#petFeedsCarousel').carousel({
            interval: 10000
          });
        },
        
        addMoreFeeds:function(el){
            feedscount  =  $('.feed-item').length;
            show.button.hide();
            show.spinner.show();
            $.ajax({
                url: SYS.baseUrl + 'feeds/ajax_get_more_feeds',
                type: 'POST',
                dataType: 'json',
                data: $.param({offset : feedscount}),
                success: function(res){
                  if(res.text == 'success'){
                    $('div#feeds-container.span16').append(res.data.html);
                    show.spinner.hide();
                    if(!res.data.flag)
                      show.button.show();
                  };
                }
            })
        }
}

$(function() {
	show.init();
});