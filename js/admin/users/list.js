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
                                    "sAjaxSource"     : SYS.baseUrl + 'admin/users/getAjaxData'
                                  });
        },
        
        removeItem: function(id, el){
          if(confirm('Delete This User?')){
            $.ajax({
              url: SYS.adminBaseUrl + 'users/ajax_remove',
              type: 'POST',
              dataType: 'json',
              data: $.param({
                             id: id
                            }),
              success: function(res){
                if(res.text == 'success'){
                  list.oTable.fnDeleteRow($(el).parents('tr')[0]);
                  alert.setStatus('info').setMessage('User was remove').render();
                }else if(res.text == 'failure'){
                  alert.setStatus('error').setMessage(res.errors[0]).render();
                }
              }
            })
          }
        }
}

$(function() {
    list.init();
});