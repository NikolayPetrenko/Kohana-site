function loadingStop() {
    $('.loading').remove();
}

function loadingStart(element) {
    var loading = $('<div class="loading"></div>');
    loading.css({
        width: element.width(),
        height: element.height(),
        top: element.position().top+5,
        left: element.position().left+5,
        opacity: 0.8
    });
    $('body').append(loading);
}


var alert = {
  
  setStatus: function(status)
  {
      this.status = status;
      switch(status){
          case('success'): this.strong = 'Well done!';break;
          case('error')  : this.strong = 'Oh snap!'  ;break;
          case('info')   : this.strong = 'Heads up!' ;break;
          default        : this.strong = 'Heads up!' ;break;
      }
      return this;
  },
  
  setLayout:function(layout)
  {
      switch(layout){
          case('main')   : this.prepend_selector = 'div.maincontainer' ;break;
          case('admin')  : this.prepend_selector = 'div.span8'         ;break;
          default        : this.prepend_selector = 'div.container'     ;break;
      }
  },
  
  setMessage: function(message)
  {
      this.message = message;
      return this;
  },
  
  render: function(){
    $('.alert').remove();
    html = '<div class="alert alert-'+alert.status+'"><button type="button" class="close" data-dismiss="alert">×</button><strong>'+alert.strong+'</strong> '+alert.message+'</div>';
    $(alert.prepend_selector).prepend(html);
  }
  
}


