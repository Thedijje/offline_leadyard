<div class="login-form">
    <form action="<?php echo base_url('login/validate')?>" method="post">

        <div class="top">
            <img src="<?php echo base_url($config['logo-wide'])?>"
                alt="<?php echo $this->lib->get_settings('sitename')?>" class="icon">

            <h4>Inventory manager's panel</h4>
        </div>
        <div class="form-area">


            <?php $this->lib->alert_message();?>


            <div class="group">
                <input type="text" class="form-control" name="email" placeholder="Email" required>
                <i class="fa fa-at"></i>
            </div>


            <div class="group">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
                <input type="hidden" name="redirect"
                    value="<?php echo $redirect;?>">
                <i class="fa fa-key"></i>
            </div>

            <input type="hidden" class="hidden user_timezone" value="" name="user_timezone">

            <button type="submit" class="btn btn-warning btn-block"><i class="fa fa-login"></i> Login</button>

        </div>
    </form>



    <div class="footer-links row">

        <div class="col-xs-12 text-right"><a href="#"
                onclick="$('#loadData').load('<?php echo base_url('login/forget_password/')?>')"
                data-toggle="modal" data-target="#myModal"><i class="fa fa-lock"></i> Forgot password</a>
        </div>
    </div>
</div>