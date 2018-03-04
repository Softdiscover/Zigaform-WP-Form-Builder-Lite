<?php 
if (!defined('ABSPATH')) {exit('No direct script access allowed');}
ob_start();?>
<input 
       data-uifm-tabnum="<?php echo $tab_num;?>"
       type="hidden" 
       name="uiform_fields[<?php echo $id;?>]"
       value="<?php echo $input8['value'];?>"
       class="rockfm-txtbox-inp8-val sfdc-form-control" >

<?php $tmp_input_html = ob_get_contents();
ob_end_clean();?>
<?php
ob_start();
?>
<div id="rockfm_<?php echo $id;?>"  
     data-idfield="<?php echo $id;?>"
     data-typefield="21" 
     class="rockfm-hiddeninput rockfm-field 
     <?php if(isset($clogic) && intval($clogic['show_st'])===1){?>
     rockfm-clogic-fcond
     <?php } ?>
     "
     <?php if(isset($clogic) && intval($clogic['show_st'])===1&& intval($clogic['f_show'])===1){?>
      style="display:none;"
     <?php } ?>
     >
            <div class="rockfm-field-wrap ">
                <div class="rkfm-row">
                    <div class="rkfm-col-sm-12">
                            <?php echo $tmp_input_html;?>
                        </div>
                </div>
            </div>
        </div>
<?php
$cntACmp = ob_get_contents();
$cntACmp = Uiform_Form_Helper::sanitize_output($cntACmp);
ob_end_clean();
echo $cntACmp;
?>