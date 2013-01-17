var login = {
	init: function() {
		this.facebookAuth();
	},

	facebookAuth: function() {
		$('#uxunnu_1').click(function() {
			window.location.href = $(this).attr('rel-data');
		});
	}
}

$(function() {
	login.init();
});