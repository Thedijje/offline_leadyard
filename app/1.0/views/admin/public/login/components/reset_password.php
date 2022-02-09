<!DOCTYPE html>
<html lang="en">
  
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Login to admin panel to manage components">
  <link rel="shortcut icon" href="<?=base_url('favicon.ico')?>" type="image/x-icon" />
  <link rel="icon" href="<?php echo base_url('static/front/images/favicon.png')?>" type="image/png"> 
  <?php if(isset($_SERVER['SERVER_NAME']) AND $_SERVER['SERVER_NAME']!='app.mobi-hub.com'):?>
        <meta name="robots" content="noindex" />
    <?php endif;?>

  <meta name="keywords" content="Admin,login" />
  <title>Login</title>

  <!-- ========== Css Files ========== -->
  <link href="<?php echo base_url('static/front/css/root.css')?>" rel="stylesheet">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
  <script src="<?php echo base_url('static/front/js/bootstrap/bootstrap.min.js')?>"></script>
  
  <style type="text/css">
    body{background: #F5F5F5;}
  </style>
  </head>
  <body class="login_bg">
    <div class="login-form">
      <form action="<?php echo base_url('login/password_reset')?>" method="post">
       	<input type="hidden" name="user_token" value="<?php echo $reset_token;?>">
        <div class="top">
          <img src="<?php echo base_url($this->lib->get_settings('logo-wide'))?>" alt="<?php echo $this->lib->get_settings('sitename')?>" class="icon">
          
          <h4>Administrator panel</h4>
        </div>
        <div class="form-area">
			<?php $this->lib->alert_message();?>
			<div class="group">
				<input type="password" name="new_password" class="form-control" placeholder="Enter new password" required/>
				<i class="fa fa-key"></i>
			</div>
			<div class="group">
				<input type="password" name="re_password" class="form-control" placeholder="Confirm new password" required/>
				<i class="fa fa-key"></i>
			</div>
			<button type="submit" class="btn btn-warning btn-block"><i class="fa fa-login"></i> Reset Password</button>
        </div>
      </form>
      <div class="footer-links row">
        <div class="col-xs-12 text-right"><a href="<?php echo base_url('login/')?>"><i class="fa fa-lock"></i> Login</a></div>
      </div>
    </div>

	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel" align="center">Action</h4>
      </div>
      <div class="modal-body" id="loadData">
        <h4 align="center"><i class="fa fa-spinner fa-pulse"></i></h4>
        <h4 align="center">Please wait...</h4>
      </div>
    </div>
  </div>
</div>
	
</body>

</html>