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
  <script>
    $(document).ready(function(){
        $('.user_timezone').val(moment.tz.guess());
        
    });
  </script>
  <script src="<?php echo base_url("/static/front/js/moment/moment.min.js"); ?>"></script>
<script src="<?php echo base_url("/static/front/js/moment/moment-timezone-with-data.js"); ?>"></script>

</body>

</html>