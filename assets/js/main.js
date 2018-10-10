$(document).ready(function () {

});

$(document).on("click", "#loginModal .btn", function () {
	$.ajax({
		type: "POST",
		url: "user/login_user",
		data: {
			user_email: $()
		},
		success: function (data) {

			//and from data parse your json data and show error message in the modal
			var obj = $.parseJSON(data);
			if (obj != null) {
				$('#err_mssg').html(obj['error_message']);
			}
		}
	});
});
