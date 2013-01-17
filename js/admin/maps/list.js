var list = {
	init: function() {
          this.initTable();
	},
        
        initTable: function(){
            this.oTable = $('#locations').dataTable({
                                    "sPaginationType" : "bootstrap",
                                    "bProcessing"     : true,
                                    "bServerSide"     : true,
                                    "iDisplayLength"  : 25,
                                    "bRetrieve"       : true,
                                    "sAjaxSource"     : SYS.baseUrl + 'admin/maps/getAjaxTable'
                                  });
        },
        
        
        removeItem: function(id, el){
          if(confirm('Delete This Location?')){
            $.ajax({
              url: SYS.baseUrl + 'admin/maps/removeItem',
              type: 'POST',
              dataType: 'json',
              data: $.param({
                             id: id
                            }),
              success: function(res){
                if(res.text == 'success'){
                    list.oTable.fnDeleteRow($(el).parents('tr')[0]);
                    alert.setStatus('info').setMessage('Location was remove').render();
                }
              }
            })
          }
        },
        
        changeStatus: function(id, el){
            $.ajax({
              url: SYS.baseUrl + 'admin/maps/changeLocationStatus',
              type: 'POST',
              dataType: 'json',
              data: $.param({
                            id: id
                            }),
              success: function(res){
                if(res.text == 'success'){
                    if(res.data.status == 1){
                        $(el).addClass('btn-success');
                        $(el).text('Active');
                    }else{
                        $(el).text('Not Active');
                        $(el).removeClass('btn-success');
                    }
                }
              }
            })
        },
        
        changeConfirmStatus: function(id, el){
            $.ajax({
              url: SYS.baseUrl + 'admin/maps/changeLocationConfirm',
              type: 'POST',
              dataType: 'json',
              data: $.param({
                            id: id
                            }),
              success: function(res){
                if(res.text == 'success'){
                    if(res.data.status == 1){
                        $(el).addClass('btn-success');
                        $(el).text('Confirm');
                    }else{
                        $(el).text('Not Confirm');
                        $(el).removeClass('btn-success');
                    }
                }
              }
            })
        }
}

$(function() {
    list.init();
});