var pet_code = {
	init: function()
        {
	},
        
        generateCode: function(pet_id, el){
          if(confirm('You want to generate QR code?')){
            $(el).attr('disabled', 'disabled');
            $.ajax({
              url: SYS.adminBaseUrl + 'tags/ajax_generate_and_upload_code',
              type: 'POST',
              dataType: 'json',
              data: $.param({
                             pet_id: pet_id
                            }),
              success: function(res){
                if(res.text == 'success')
                {
                    $('.qr-code').find('img').attr('src', SYS.baseUrl + res.data.qr_code);
                    $(el).remove();
                }else{
                    $(el).attr('disabled', '');
                }
              }
            })
          }
        }
}

$(function() {
    pet_code.init();
});