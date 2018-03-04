<?php
if (!defined('ABSPATH')) {exit('No direct script access allowed');}
ob_start();
?>
<div id="rockfm_<?php echo $id;?>"  
     data-idfield="<?php echo $id;?>"
     data-typefield="32" 
     class="rockfm-divider rockfm-field 
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
                         <div class="rockfm-divider-bar" 
                              data-uifm-tabnum="<?php echo $tab_num;?>"
                              >
                             <?php if(!empty($input11['text_val'])){?>
                            <span 
                                <?php
                                if(isset($input11['font_st']) && intval($input11['font_st'])===1){
                                    if(!empty($input11['font'])){
                                        $font_tmp=json_decode($input11['font'],true);
                                            if(isset($font_tmp['import_family'])){
                                        ?>
                                            data-rockfm-gfont="<?php echo $font_tmp['import_family'];?>"
                                        <?php
                                            }
                                        }   
                                }
                                ?>
                                class="rockfm-divider-text"><?php echo $input11['text_val'];?></span>
                            <?php }?>
                        </div>
                            
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