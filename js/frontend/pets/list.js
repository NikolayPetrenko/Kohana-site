var list = {
	init: function() {
          this.initTable();
	},
        
        initTable: function(){
            this.oTable = $('table').dataTable({
                                    "sPaginationType" : "bootstrap",
                                    "bProcessing"     : true,
                                    "bServerSide"     : true,
                                    "iDisplayLength"  : 25,
                                    "bRetrieve"       : true,
                                    "sAjaxSource"     : SYS.baseUrl + 'pets/getAjaxDataTable',
                                    "aoColumnDefs"    : [  { "iDataSort": 0, "aTargets": [ 0 ] } ]
                                  });
        
        },
        
        removeItem: function(id, el){
          if(confirm('Delete This?')){
            $.ajax({
              url: SYS.baseUrl + 'pets/ajax_remove',
              type: 'POST',
              dataType: 'json',
              data: $.param({
                             id: id
                            }),
              success: function(res){
                if(res.text == 'success'){
                  alert.setStatus('info').setMessage('Pet was remove').render();
                  list.oTable.fnDeleteRow($(el).parents('tr')[0]);
                }
              }
            })
          }
        },
        
        changeStatus: function(id, el){
          if(confirm('Are you sure?')){
                i = $(el).find('i');
                $.ajax({
                  url: SYS.baseUrl + 'pets/changeLostStatus',
                  type: 'POST',
                  dataType: 'json',
                  data: $.param({
                                id: id
                                }),
                  success: function(res){
                    if(res.text == 'success'){
                        if(res.data.status == 1){
                            i.addClass('icon-ok-circle');
                            i.removeClass('icon-ban-circle');
                        }else{
                            i.addClass('icon-ban-circle');
                            i.removeClass('icon-ok-circle');
                        }
                    }
                  }
                })
          }
        }
        
}

$(function() {
	list.init();
});