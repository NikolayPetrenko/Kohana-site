var index = {
	init: function()
        {
	},
        
        addMoreAlerts: function(el, status){
          offset  =  $('tbody#'+status+' > tr').length;
          spinner =  $('#spinner');
          button  =  $(el);
          button.hide();
          spinner.show();
          $.ajax({
            url: SYS.baseUrl + 'alerts/getMoreAlerts',
            type: 'POST',
            dataType: 'json',
            data: $.param({offset : offset, status : status}),
            success: function(res){
              if(res.text == 'success'){
                $('tbody#'+status).append(res.data.html);
                spinner.hide();
                if(!res.data.flag)
                  button.show();
              };
            }
          })
        },
}

$(function() {
      index.init();
});