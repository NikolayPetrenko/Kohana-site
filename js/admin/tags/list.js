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
                                    "sAjaxSource"     : SYS.baseUrl + 'admin/tags/getAjaxData',
                                    "bSort"           : false,
                                    "aaSorting"       :      [[ 6, "desc" ]]
                                  });
        },
        
        refundCharge: function(charge_id, el){
          if(confirm('You want refund money?')){
            $.ajax({
              url: SYS.adminBaseUrl + 'tags/refunding_charge',
              type: 'POST',
              dataType: 'json',
              data: $.param({
                             charge_id: charge_id
                            }),
              success: function(res){
                if(res.text == 'success'){
                  location.reload();
                }
              }
            })
          }
        }
        
//        getChargeInfo: function(charge_id){
//          $.ajax({
//            url: 'https://api.stripe.com/v1/charges/'+charge_id,
//            type: 'GET',
//            dataType: 'json',
//            success: function(res){
//              console.log(res);
//            }
//          })
//        }
        
        
        
}

$(function() {
    list.init();
});