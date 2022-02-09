<div class="content">
<?php //if(isset($print) AND $print!='true'):?>
  <!-- Start Page breadcrumb -->
  <div class="page-header"> 
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url()?>"><i class='fa <?php echo $icon ?? "fa-dashboard";?>'></i> Dashboard</a></li>
	<?php	
	if($this->router->fetch_class()!='dashboard')
	{		
		if($this->uri->segment(1) != $this->router->fetch_class()){			
			echo "<li><a href='".base_url($this->uri->segment(1))."'>".ucfirst($this->uri->segment(1)).'</a></li>';			
		}else{
			// the controller is inside folder
			//check if not index			
			if($this->router->fetch_method() != 'index')
			{
				echo "<li><a href='".base_url($this->uri->segment(1))."'>".ucfirst($this->uri->segment(1)).'</a></li>';
				//  echo "<li><a href='".this_url()."'>".ucfirst($this->router->fetch_method()).'</a></li>';			
			}else{
							//method is index
				//class is in folder
				echo "<li><a href='".base_url($this->uri->segment(1))."'>".ucfirst($this->uri->segment(1)).'</a></li>';
				//direct class
			}
			
		}
	}

		
    ?>
		<?php 	if(isset($heading)):?>
			<li><?php echo $heading ?? '';?></li>
		<?php endif;?>
    </ol>

</div>
<?php //endif;?>