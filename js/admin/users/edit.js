var edit = {
	init: function()
        {
            this.INITdatepicker();
            this.avatartUploadInit();
            this.INITValidation();
            $.validator.addMethod(
                "checkEmail",
                function(value) {
                    return eval(
                                  $.ajax({
                                                url   : SYS.baseUrl + '/admin/users/ajax_check_email',
                                                data  : jQuery.param({id:$('input[name=id]').val(), email:value}),
                                                async : false,
                                                type  : 'post'
                                              }).responseText
                              );
                },
                "Email already exists!"
            );
	},
        
        INITValidation: function()
        {
            $('form').validate({
                rules: {
                    email: {
                        required: true,
                        email: true,
                        checkEmail: true
                        
                    },
                    firstname:{
                        required: true
                    },
                    lastname:{
                        required: true
                    },
                    phone:{
                        number: true
                    }
                }
            });
        },
        
        avatartUploadInit: function()
        {
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
        },
        
        INITdatepicker: function()
        {
          $('.date').datepicker({
            dateFormat: 'mm-dd-yy'
          });
        }
}

$(function() {
    edit.init();
});