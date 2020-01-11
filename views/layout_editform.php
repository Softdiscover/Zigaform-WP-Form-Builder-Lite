<?php
if (!defined('ABSPATH')) {exit('No direct script access allowed');}
?>

    <form id="zgfm_edit_panel"
          onsubmit="return false;"
          enctype="multipart/form-data" 
          action="" 
          accept-charset="UTF-8" 
          name="" 
          method="post" 
          autocomplete="off">
   <div id="rocketform-bk-dashboard" class="sfdc-wrap uiform-wrap" style="display:none;" >
   
    <div id="rocketform-bk-header">
        <?php include('header.php');?>
    </div>
    <div id="rocketform-bk-content">
        <div id="uiform-panel-loadingst">
    <div class="uifm-loader-header-wrap">
        <div class="icon-uifm-logo-black"></div>
        <div class="uifm-loader-header-1"></div>
    </div>
</div>
        <?php echo $content;?>
        
    </div>
    <div id="rocketform-bk-footer">
        <?php include('footer.php');?>
    </div>
</div>
<?php include('captions.php');?> 


</form> 