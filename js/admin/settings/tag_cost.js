var tag_cost = {
	init: function() {
          this.validatorInit();
	},
        
        validatorInit: function(){
            $('form').validate({
                rules: {
                    dollars: {
                        required: true,
                        number: true
                    },
                    cents:{
                        required: true,
                        number: true
                    }
                }
            });
        }
}

$(function() {
    tag_cost.init();
});