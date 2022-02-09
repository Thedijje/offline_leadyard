<div class="container-default">
	<div class="col-lg-12">
		<?php echo $this->lib->alert_message();?>

		
	</div>
	<div class="clearfix"></div>

</div>

<?php
$js_component   =   __DIR__.'/components/'.basename(dirname(__FILE__)).'.js';
$css_component   =   __DIR__.'/components/'.basename(dirname(__FILE__)).'.css';
// echo $js_component;


if(file_exists($js_component)):
    
    ?>
    <script>
    <?php include_once($js_component);?>
    </script>
<?php
endif;

if(file_exists($css_component)):
    ?>
    <style>
    <?php include_once($css_component);?>
    </style>
<?php
endif;


?>