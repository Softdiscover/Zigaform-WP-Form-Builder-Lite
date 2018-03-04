<?php 
if (!defined('ABSPATH')) {exit('No direct script access allowed');}
ob_start();?>                              
<button 
    data-uifm-tabnum="<?php echo $tab_num;?>"
    disabled="disabled"
    data-val-btn="<?php echo $input13['value_lbl'];?>"
    <?php if(isset($form_wizard['enable_st'])&& intval($form_wizard['enable_st'])===1&& count($form_wizard['tabs'])>1){?>
    onclick="javascript:rocketfm.wizard_prevButton(this);return false;"
    <?php }else{ ?>
    <?php }?>
    class="sfdc-btn rockfm-btn-wizprev">
        <i class="fa fa-arrow-left"></i>
        <div class="rockfm-inp-lbl"><?php echo $input13['value_lbl'];?></div>
</button>
<button 
    data-uifm-tabnum="<?php echo $tab_num;?>"
    data-value-last="<?php echo $input12['value_lbl_last'];?>"
    data-value-next="<?php echo $input12['value_lbl'];?>"
    <?php if(isset($form_wizard['enable_st'])&& intval($form_wizard['enable_st'])===1 && count($form_wizard['tabs'])>1){?>
    onclick="javascript:rocketfm.wizard_nextButton(this);return false;"
    <?php }else{ ?>
    type="submit"
    data-val-subm="<?php echo __('Sending','FRocket_admin'); ?>"
    onclick="javascript:rocketfm.submitForm(this);return false;"
    <?php }?>
    class="sfdc-btn rockfm-btn-wiznext">
        <div class="rockfm-inp-lbl"><?php echo $input12['value_lbl'];?></div>
        <i class="fa fa-arrow-right"></i>
</button>								
                        
<?php $tmp_label_inner_html = ob_get_contents();
ob_end_clean();?>
<?php
ob_start();
?>
<?php 
    if(isset($help_block['show_st']) && intval($help_block['show_st'])===1){
       switch ($help_block['pos']) {
            case 1:
                //top
                ?>
                <div class="rockfm-help-block"><?php echo urldecode($help_block['text']);?></div>
                <div class="rockfm-input-container">
                   <?php echo $tmp_label_inner_html;?>
                </div>
                <?php
                break;
            case 2:
                //tooltip
                ?>
                <div class="rockfm-input-container">
                   <?php echo $tmp_label_inner_html;?>
                </div>
                <?php
                break;
            case 3:
                //popup
                ?>
                <div class="rockfm-input-container">
                    <?php echo $tmp_label_inner_html;?>
                </div>
                <?php
                break;
            case 0:
            default:
                //bottom
                ?>
                <div class="rockfm-input-container">
                    <?php echo $tmp_label_inner_html;?>
                </div>
                <div class="rockfm-help-block"><?php echo urldecode($help_block['text']);?></div>
                <?php
                break;
        } 
    }else{
        ?>
                <div class="rockfm-input-container">
                   <?php echo $tmp_label_inner_html;?>
                </div>
        <?php
    }
    

?>
                        
<?php
$tmp_input_html = ob_get_contents();
ob_end_clean();
?>
<?php
ob_start();
?>
<div id="rockfm_<?php echo $id;?>"  
     data-idfield="<?php echo $id;?>"
     data-typefield="39"
     class="rockfm-wizardbtn rockfm-field 
     <?php if(isset($clogic) && intval($clogic['show_st'])===1){?>
     rockfm-clogic-fcond
     <?php } ?>
     <?php echo $addon_extraclass;?>
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