<?php 
   echo doctype();	
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow" />
    <meta name="keywords" content="<?php echo $_config['sitename']?>, admin" />
    <?php 
           include_once("static/admin_head_part.php");
          /*Include js and css for header part*/
          // $this->load->view("admin/includes/static/admin_head_part");
            ?>
    <title><?php if(isset($title)){ echo $title.' : ';}?><?php echo $_config['sitename']?> admin</title>
    <script>
    var base_url = '<?php echo base_url();?>';
    </script>

</head>

<body>
    <!-- //////////////////////////////////////////////////////////////////////////// -->
    <!-- START TOP -->
    <div id="top" class="clearfix">
        <!-- Start App Logo -->
        <div class="applogo">
            <a href="<?php echo base_url('admin')?>" class="logo"><img src="<?php echo base_url($_config['logo-wide'])?>"
                    alt="<?php echo $_config['sitename']?>" style="width: 200px"></a>
        </div>
        <!-- End App Logo -->
        <!-- Start Sidebar Show Hide Button -->
        <a href="#" class="sidebar-open-button"><i class="fa fa-bars"></i></a>
        <a href="#" class="sidebar-open-button-mobile"><i class="fa fa-bars"></i></a>
        <!-- End Sidebar Show Hide Button -->
        <!-- page title -->
        <h1 class="title">
            <?php if(isset($icon)){ echo "<i class='".$icon." fa-lg fa-fw'></i> ";}?><?php if(isset($heading)){ echo $heading;}?>
        </h1>
        <!-- End page title -->
        <span class="pull-right logout_btn"><a class="confirmation" data-action="logout?"
                href="<?php echo base_url('logout')?>"><span class="icon color6"><i
                        class="fa fa-power-off"></i></span></a></span>
        <!-- End Top Right -->
    </div>
    <!-- END TOP -->
    <!-- //////////////////////////////////////////////////////////////////////////// -->
    <?php require('sidebar.php');?>