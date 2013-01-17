var gallery = {
    init: function()
    {
      url         = location.href.split('/');
      this.pet_id = url[url.length-1];
      this.spinner         =  $('#spinner');
      this.photo_container = $('div#photo-container');
      this.uploadPhotos();
      $('#more-photos').click(function(){
          show.button = $(this);
          show.addMoreFeeds(this)
      });
      
    },
    
    
    uploadPhotos: function(){
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
                    //loadingStop();
                    $.ajax({
                      url: SYS.baseUrl + 'api/pets/setPetPhotoInGallery',
                      type: 'POST',
                      dateType: 'json',
                      data: $.param({
                                     pet_id : gallery.pet_id,
                                     image  : data.result.data.file
                                    }),
                      success: function(res){
                          html = '<span class="span3 photo-item"><img style="width:150px;heidth:150px;" src="'+res.data.path+'" ><a class="btn btn-mini btn-inverse" id="more-feeds" href="javascript:void(0)" onclick="javascript:gallery.removePhoto('+ res.data.id +', this)"><i class="icon-remove icon-white"></i> Remove Photo</a></span>';
                          gallery.photo_container.append(html);
                        }
                    });
                  }
            }
        }).bind('fileuploadstart', function (e, data) {
            //loadingStart($('.thumbnail'));
        }).bind('fileuploadstop', function (e) {

        });
      
    },
    
    removePhoto: function(photo_id, el)
    {
        photo_item = $(el).parents('span.photo-item');
        $.ajax({
            url: SYS.baseUrl + 'pets/ajax_remove_photo_from_gallery',
            type: 'POST',
            dataType: 'json',
            data: $.param({id : photo_id}),
            success: function(res){
              if(res.text == 'success'){
                  photo_item.fadeOut();
              };
            }
        })
    },
    

    addMorePhotos:function(el){
        photocount  =  $('.photo-item').length;
        show.button.hide();
        show.spinner.show();
        $.ajax({
            url: SYS.baseUrl + 'pets/ajax_get_more_photo_for_gallery',
            type: 'POST',
            dataType: 'json',
            data: $.param({offset : photocount}),
            success: function(res){
              if(res.text == 'success'){
                gallery.photo_container.append(res.data.html);
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
    gallery.init();
});