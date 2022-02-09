<h3 class="text-c">Forget password assistant</h3>
<div class="alert alert-info text-c">
	<h4><i class="fa fa-info-circle"></i> Make sure following</h4>
	<p>You must be an admin of this system.</p>
	<p>You must have a valid and accessible email account as admin email</p>
	<p>Password instruction will be sent u in email</p>
</div>
			<form class="form" action="<?php echo base_url('login/send_reset_link')?>" method="post">
				
				<div class="form-group">
					<label>Email as registered</label>
					<input type="email" class="form-control" name="email" required placeholder="Entered email">
					<p class="help-block"><i class="fa fa-info-circle"></i> In case you don't remember email or problem in login to your email account? Please contact admin</p>
				</div>
					
			
				<div class="form-group">
					<button class="btn btn-primary btn-icon"><i class="fa fa-save"></i> Initiate resetting </button>
				</div>
				
				
			</form>