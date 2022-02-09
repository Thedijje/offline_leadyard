
<!-- START SIDEBAR -->
<div class="sidebar clearfix">
<div class="dropdown link">
  <a href="#" data-toggle="dropdown" class="dropdown-toggle profilebox">
   <div class="user-name"><b>Hi Admin</b></div>
  </a>
</div>
<ul class="sidebar-panel nav">
  <li class="sidetitle">Main Menu</li>
  
  <li><a class="<?php if(strtolower($current_class)=='dashboard' && strtolower($current_method)=='index'){ echo 'active';}?>" href="<?php  echo base_url('dashboard');  ?>" ><span class="icon color5"><i class="fa fa-dashboard"></i></span>Dashboard</a></li>
  
  <li><a class="<?php if(strtolower($current_class)=='brands' && strtolower($current_method)=='index'){ echo 'active';}?>" href="<?php  echo base_url('brands');  ?>" ><span class="icon color5"><i class="fa fa-building-o"></i></span>Company <span class="label label-info show_count company"></span></a></li>
  
  

  
  <li class="sidetitle">Account Management</li>


  
    <li><a href="#"><span class="icon color6"><i class="fa fa-list fa-fw"></i></span>Sttings</a>

    <li><a class="<?php if(strtolower($current_class)=='account' && strtolower($current_method)=='settings'){ echo 'active';}?>" href="<?php  echo base_url('account/settings');  ?>" ><span class="icon color6"><i class="fa fa-list fa-fw"></i></span>Account settings</a></li>
      
    <li><a class="<?php if(strtolower($current_class)=='admin' && strtolower($current_method)=='index'){ echo 'active';}?>" href="<?php  echo base_url('admin');  ?>" ><span class="icon color6"><i class="fa fa-users  fa-fw"></i></span>Manage admin</a></li>
    <li><a onclick="return confirm('Are you sure want to logout?')" href="<?php echo base_url('logout')?>"><span class="icon color6"><i class="fa fa-power-off  fa-fw"></i></span>logout</a></li>
   
    <li class="sidetitle">Developers option</li>
   
    <li><a class="<?php if(strtolower($current_class)=='developers' && strtolower($current_method)=='index'){ echo 'active';}?>" href="<?php  echo base_url('developers');  ?>" ><span class="icon color6"><i class="fa fa-code  fa-fw"></i></span>Developer Tools</a></li>

 
  
</ul>

</div>
<!-- END SIDEBAR -->
<!-- //////////////////////////////////////////////////////////////////////////// --> 
<?php include_once('breadcrumb.php');?>