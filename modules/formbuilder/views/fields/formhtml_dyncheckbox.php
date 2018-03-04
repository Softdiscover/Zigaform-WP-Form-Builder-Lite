<?php
/**
 * Intranet
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   Rocket_form
 * @author    Softdiscover <info@softdiscover.com>
 * @copyright 2015 Softdiscover
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link      http://wordpress-cost-estimator.zigaform.com
 */
if (!defined('ABSPATH')) {exit('No direct script access allowed');}
ob_start();
?>
<div class="rockfm-control-label">
    <label class="sfdc-control-label">
    
        <span  
                class="rockfm-label"><?php 
        if(isset($help_block['show_st']) && intval($help_block['show_st'])===1){
        switch ($help_block['pos']) {
                case 2:
                    //tooltip
                    ?>
                    <span 
                        data-toggle="tooltip"
                        data-placement="top"
                        data-original-title="<?php 
                        if(isset($help_block['text'])){
                            echo htmlentities(urldecode($help_block['text']), ENT_QUOTES);
                        }
                        ?>"
                        data-field-option="rockfm-help-block"
                        class="rockfm-label-helpblock">
                        <span class="fa fa-question-circle"></span>
                    </span>
                    <?php
                    break;
                case 3:
                    //popup
                    ?>
                    <a role="button"
                        data-toggle="modal"
                        href="#modaltemplate_<?php echo $id;?>"
                        data-field-option="rockfm-help-block"
                        class="rockfm-label-helpblock">
                        <span class="fa fa-question-circle"></span>
                    </a>
      <!-- Modal -->
            <div  class="sfdc-modal sfdc-fade"  id="modaltemplate_<?php echo $id;?>">
            <div class="sfdc-modal-dialog">
                <div class="sfdc-modal-content">
                    <div class="sfdc-modal-header">
                        <button type="button" class="close" 
                        data-dismiss="modal" aria-hidden="true">
                            &times;
                        </button>
                        <h4 class="sfdc-modal-title" id="myModalLabel">
                        <span class=" fa fa-question-circle"></span>
                        </h4>
                    </div>
                    <div class="sfdc-modal-body">
                            <?php 
                        if(isset($help_block['text'])){
                           echo urldecode($help_block['text']);
                        }
                        ?>
                    </div>
                    <div class="sfdc-modal-footer">
                        <button type="button" class="sfdc-btn sfdc-btn-default" 
                        data-dismiss="modal"><?php echo __('Close','FRocket_admin'); ?>
                        </button>

                    </div>
                </div><!-- /.sfdc-modal-content -->
            </div>
            </div><!-- /.modal --> 
                    <?php
                    break;

            } 
        }
            ?><?php echo $label['text']; ?></span>
        <span data-field-store="sublabel-text"
                data-field-option="rockfm-sublabel"
                class="rockfm-sublabel"><?php echo $sublabel['text']; ?></span>
    </label>

</div>

<?php
$tmp_label_html = ob_get_contents();
ob_end_clean();
?>
<?php ob_start();
$layout_type=isset($input17['thopt_mode'])?intval($input17['thopt_mode']):1;
?>
<div data-uifm-tabnum="<?php echo $tab_num;?>"
     data-opt-laymode='<?php echo $layout_type;?>'
     data-thopt-height="<?php echo $input17['thopt_height'];?>"
     data-thopt-width="<?php echo $input17['thopt_width'];?>"
     data-thopt-zoom="<?php echo $input17['thopt_zoom'];?>"
     data-thopt-showhvrtxt="<?php echo isset($input17['thopt_showhvrtxt'])?$input17['thopt_showhvrtxt']:0;?>"
     data-thopt-showcheckb="<?php echo isset($input17['thopt_showcheckb'])?$input17['thopt_showcheckb']:0;?>"
     class="rockfm-input17-wrap">    
<?php

foreach ($input17['options'] as $key => $value) {
    $checked='';
    if(isset($value['checked']) && intval($value['checked'])===1){
        $checked='checked="checked"';    
    }
    ?>
    <div 
        data-backend="0"
        data-gal-id="blueimp-gallery<?php echo $form_id;?>"
        data-opt-label="<?php echo $value['label'];?>"
        data-opt-checked="<?php echo $value['checked'];?>"
        data-opt-price="<?php echo $value['price'];?>"
        data-opt-qtyst="<?php echo $value['qty_st'];?>"
        data-opt-qtymax="<?php echo $value['qty_max'];?>"
        data-inp17-opt-index="<?php echo $key;?>"
        <?php if(isset($input17['thopt_showhvrtxt']) && intval($input17['thopt_showhvrtxt'])===1){?>
        data-toggle="tooltip" 
        title="<?php echo $value['label'];?>"
        <?php } ?>
        data-placement="bottom" 
        data-html="true"
        class="uifm-dcheckbox-item">
        <div class="uifm-dcheckbox-item-wrap">
            <div class="uifm-dcheckbox-item-chkst sfdc-btn-default">
                <i class="fa fa-square-o"></i>
            </div>
           <?php if(isset($input17['thopt_zoom']) && intval($input17['thopt_zoom'])===1 && $layout_type!=2){?>
            <div class="uifm-dcheckbox-item-showgallery  sfdc-btn-primary">
                <i class="sfdc-glyphicon sfdc-glyphicon-search"></i>
            </div>
            <?php } ?>
            <?php if( $layout_type!=2){?>
            <div class="uifm-dcheckbox-item-nextimg sfdc-btn-primary">
                <i class="fa fa-chevron-right"></i>
            </div>
            <div class="uifm-dcheckbox-item-previmg sfdc-btn-primary">
                <i class="fa fa-chevron-left"></i>
            </div>
            <?php } ?>
            <div style="display: none;">
                <input class="uifm-dcheckbox-item-chkval"
                       name="uiform_fields[<?php echo $id;?>][<?php echo $key;?>]"
                       type="checkbox"  value="" <?php echo $checked;?> >
            </div>
            <!-- image gallery -->
            <div style="display:none;">
                <div class="uifm-dcheckbox-item-gal-imgs">
                      <?php 
                    switch (intval($layout_type)) {
                        case 2:
                        if(!empty($value['img_list_2'])){
                                foreach ($value['img_list_2'] as $key2 => $value2) {
                                   if(!empty($value2['img_full']) ){
                                    ?>
                                    <a 
                                        data-inp17-opt2-index="<?php echo $key2;?>"
                                        href="<?php echo isset($value2['img_full'])?$value2['img_full']:'';?>" 
                                        class="uifm-dcheckbox-item-imgsrc"
                                        title="" data-gallery="">
                                        <img src="<?php  
                                            echo isset($value2['img_full'])?$value2['img_full']:'';
                                        ?>"></a>
                                <?php
                                    }else{
                                        ?>
                                        <a 
                                            data-inp17-opt2-index="<?php echo $key2;?>"
                                        href="<?php echo UIFORM_FORMS_URL; ?>/assets/common/imgs/uifm-question-mark.png" 
                                        class="uifm-dcheckbox-item-imgsrc"
                                        title="unknown" data-gallery="">
                                        <img src="<?php echo UIFORM_FORMS_URL; ?>/assets/common/imgs/uifm-question-mark.png"></a>

                                        <?php
                                    }
                                }
                            }
                            break;
                        case 1:
                        default:
                         if(!empty($value['img_list'])){
                                foreach ($value['img_list'] as $key2 => $value2) {
                                   if(!empty($value2['img_full']) || !empty($value2['img_th_150x150'])){
                                    ?>
                                    <a 
                                        data-inp17-opt2-index="<?php echo $key2;?>"
                                        href="<?php echo isset($value2['img_full'])?$value2['img_full']:$value2['img_th_150x150'];?>" 
                                        class="uifm-dcheckbox-item-imgsrc"
                                        title="<?php echo $value2['title'];?>" data-gallery="">
                                        <img src="<?php  
                                        if(isset($input17['thopt_usethmb']) && intval($input17['thopt_usethmb'])===1){
                                            echo $value2['img_th_150x150'];
                                        }else{
                                            echo isset($value2['img_full'])?$value2['img_full']:$value2['img_th_150x150'];
                                        }


                                        ?>"></a>
                                <?php
                                    }else{
                                        ?>
                                        <a 
                                        data-inp17-opt2-index="0"
                                        href="<?php echo UIFORM_FORMS_URL; ?>/assets/common/imgs/uifm-question-mark.png" 
                                        class="uifm-dcheckbox-item-imgsrc"
                                        title="unknown" data-gallery="">
                                        <img src="<?php echo UIFORM_FORMS_URL; ?>/assets/common/imgs/uifm-question-mark.png"></a>

                                        <?php
                                    }
                                }
                            }
                            break;
                    }
                    
                    
                   
                    ?>
                </div>
            </div>
            <canvas 
                data-uifm-nro="0"
                width="100" height="100" class="uifm-dcheckbox-item-viewport"></canvas>
        </div>
    </div>
<?php
}


?>
</div>
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
     data-typefield="41" 
     class="rockfm-dyncheckbox rockfm-field 
     <?php if(isset($clogic) && intval($clogic['show_st'])===1){?>
     rockfm-clogic-fcond
     <?php } ?>
     <?php if(isset($price['enable_st']) && intval($price['enable_st'])===1){?>
     rockfm-costest-field
     <?php } ?>
     <?php echo $addon_extraclass;?>
     "
    <?php if(isset($clogic) && intval($clogic['show_st'])===1&& intval($clogic['f_show'])===1){?>
      style="display:none;"
     <?php } ?>
     
     >
            <div class="rockfm-field-wrap ">
                <div class="rkfm-row">
                    <?php 
                    if(intval($txt_block['block_st'])===1){
                    ?>  
                        <?php
                        switch (intval($txt_block['block_pos'])) {
                                case 1:
                                    //top
                                    ?>
                                    <div class="rkfm-col-sm-12 rockfm-wrap-label">
                                        <?php echo $tmp_label_html;?>
                                    </div>
                                    <div class="rkfm-col-sm-12">
                                        <?php echo $tmp_input_html;?>
                                    </div>
                                    <?php
                                    break;
                                case 2:
                                    //right
                                    ?>
                                    <div class="rkfm-col-sm-10">
                                        <?php echo $tmp_input_html;?>
                                    </div>
                                    <div class="rkfm-col-sm-2 rockfm-wrap-label">
                                        <?php echo $tmp_label_html;?>
                                    </div>
                                    <?php
                                    break;
                                case 3:
                                    //bottom
                                    ?>
                                    <div class="rkfm-col-sm-12">
                                        <?php echo $tmp_input_html;?>
                                    </div>
                                    <div class="rkfm-col-sm-12 rockfm-wrap-label">
                                        <?php echo $tmp_label_html;?>
                                    </div>
                                    <?php
                                    break;
                                case 0:
                                default:
                                    //left
                                    ?>
                                    <div class="rkfm-col-sm-2 rockfm-wrap-label">
                                        <?php echo $tmp_label_html;?>
                                    </div>
                                    <div class="rkfm-col-sm-10">
                                        <?php echo $tmp_input_html;?>
                                    </div>
                                    <?php
                                    break;
                            }
                        ?>
                    <?php } else{ ?>
                        <div class="rkfm-col-sm-12">
                            <?php echo $tmp_input_html;?>
                        </div>
                    <?php } ?>
                </div>
            </div>
    <!-- hidden data --> 
    <div class="rockfm-fld-data-hidden" style="display:none;">
        <div class="rockfm-fld-data-field_name"><?php echo $field_name;?></div>
    </div>
    <!--/ hidden data --> 
        </div>
<?php
$cntACmp = ob_get_contents();
$cntACmp = Uiform_Form_Helper::sanitize_output($cntACmp);
ob_end_clean();
echo $cntACmp;
?>