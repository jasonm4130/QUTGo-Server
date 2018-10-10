<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds.
	<?php echo  (ENVIRONMENT === 'development') ?  'CodeIgniter Version <strong>' . CI_VERSION . '</strong>' : '' ?>
</p>

<!--[if lt IE 7]>
    <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="#">upgrade your browser</a> to improve your experience.</p>
<![endif]-->

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
					<?php
                    $success_msg= $this->session->flashdata('success_msg');
                    $error_msg= $this->session->flashdata('error_msg');
 
                  if($success_msg){
                    ?>
					<div class="alert alert-success">
						<?php echo $success_msg; ?>
					</div>
					<?php
                  }
                  if($error_msg){
                    ?>
					<div class="alert alert-danger">
						<?php echo $error_msg; ?>
					</div>
					<?php
                  }
                  ?>

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
