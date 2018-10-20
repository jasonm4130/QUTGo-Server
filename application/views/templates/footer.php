<!--[if lt IE 7]>
    <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="#">upgrade your browser</a> to improve your experience.</p>
<![endif]-->

<script>
$(document).on("click", "#loginModal .btn", function (event) {
	event.preventDefault();
	console.log('The form submitted');
	$.ajax({
		type: "POST",
		url: "<?= base_url(); ?>user/login_user",
		data: {
			user_email: $('input[name=user_email]').val(),
			user_password: $('input[name=user_password]').val(),
		},
		success: function (response) {
			console.log(response);
			if (response == 'success') {
				$('.messages .alert-success').show();
				$('.messages .alert-danger').hide();
				$('.login-btn').text('Logout');
				$('.login-btn').attr("href", "/user/user_logout");
				$('.login-btn').removeAttr("data-toggle");
				$('#loginModal').modal('toggle');
				console.log('user logged in');
			} else {
				$('.messages .alert-danger').show();
				$('.messages .alert-success').hide();
				console.log('login unsuccessful');
			}
		},
	});
});
</script>

<!-- Modal -->
<div class="modal fade" id="loginModal" role="dialog">
	<div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
            <h3 class="panel-title">Login</h3>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
            <div class="panel-heading">
					</div>
					<div class="messages">
						<div class="alert alert-success">
							Login Successful
						</div>
						<div class="alert alert-danger">
							Error, please check your email and password
						</div>
					</div>

					<div class="panel-body">
						<form role="form">
							<fieldset>
								<div class="form-group">
									<input class="form-control" placeholder="E-mail" name="user_email" type="email" autofocus>
								</div>
								<div class="form-group">
									<input class="form-control" placeholder="Password" name="user_password" type="password" value="">
								</div>

								<input class="btn btn-lg btn-success btn-block" type="submit" value="login" name="login">

							</fieldset>
						</form>
					</div>
			</div>
			<div class="footer row justify-content-center">
                <p class="col-12 text-center">Not a member? <a href="<?php echo base_url('user'); ?>">Sign Up</a></p>
                <!-- <p>Forgot <a href="#">Password?</a></p> -->
			</div>
		</div>
	</div>
</div>

</body>

</html>
