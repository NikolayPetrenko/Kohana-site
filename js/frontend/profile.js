var profile = {
	init: function() {
		$('.date').datepicker();
                this.uploadAvatar();
	},
        
        uploadAvatar: function(){
          $('input[type=file]').fileupload({
                url: SYS.baseUrl + 'upload/images',
                maxFileSize: 5000000,
                acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
                placeholder: '',
                process: [
                    {
                        action: 'load',
                        fileTypes: /^image\/(gif|jpeg|png)$/,
                        maxFileSize: 20000000 // 20MB
                    },
                    {
                        action: 'resize',
                        maxWidth: 1440,
                        maxHeight: 900
                    },
                    {
                        action: 'save'
                    }
                ],

                dataType: 'json',
                done: function (e, data) {
                      if(data.result.text == 'success'){
                        loadingStop();
                        $('div.span3').find('img').attr({src: SYS.baseUrl + 'images/temp/' + data.result.data.file})
                        $('input[name=avatar]').val(data.result.data.file);
                      }
                }
            }).bind('fileuploadstart', function (e, data) {
                loadingStart($('.thumbnail'));
            }).bind('fileuploadstop', function (e) {

            });
        }
}

$(function() {
	profile.init();
        setTimezone();
})