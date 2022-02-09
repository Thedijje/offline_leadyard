<?php 
     include_once("static/admin_footer_part.php");
    /*Include js and css for header part*/
    // $this->load->view("admin/includes/static/admin_footer_part");
      ?>
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i
                        class="fa fa-times"></i></button>
                <h4 class="modal-title" id="myModalLabel" align="center">Action</h4>
            </div>
            <div class="modal-body" id="loadData">
                <h4 align="center"><i class="fa fa-spinner fa-pulse"></i></h4>
                <h4 align="center">Please wait...</h4>
            </div>
        </div>
    </div>
</div>
<div class="pop_alert"></div>

</body>

</html>