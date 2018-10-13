$(document).ready(function () {

});

$(document).on("click", "#loginModal .btn", function (e) {
	e.preventDefault()
	console.log('The form submitted');
	$.ajax({
		type: "POST",
		url: "/user/login_user",
		data: {
			user_email: $('input[name=user_email]').val(),
			user_password: $('input[name=user_password]').val(),
		},
		success: function () {
			$('.messages .alert-success').toggle();
		},
		error: function () {
			$('.messages .alert-danger').toggle();
		},
	});
});
