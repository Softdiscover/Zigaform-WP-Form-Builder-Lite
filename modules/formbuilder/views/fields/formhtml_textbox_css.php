<?php
if (!defined('ABSPATH')) {exit('No direct script access allowed');}
ob_start();
?>
    #rockfm_<?php echo $id;?> .rockfm-txtbox-inp-val{
        <?php 
        //input
        ?>
        <?php if($input['size']){?>
            font-size:<?php echo $input['size'];?>px;
        <?php } ?>
        <?php if(intval($input['bold'])===1){?>
            font-weight: bold;
        <?php } ?>
        <?php if(intval($input['italic'])===1){?>
            font-style:italic;
        <?php } ?>
        <?php if(intval($input['underline'])===1){?>
            text-decoration:underline;
        <?php } ?>
        <?php if(!empty($input['color'])){?>
            color:<?php echo $input['color'];?>;
        <?php } ?>
        <?php if(isset($input['font_st']) && intval($input['font_st'])===1){?>
        <?php 
            $font_temp=json_decode($input['font'],true);
            if(isset($font_temp['family'])){
        ?>    
            font-family:<?php echo $font_temp['family'];?>;
             <?php
           //storing to global fonts
            Uiform_Form_Helper::form_store_fonts($font_temp);
            //end storing to global fonts
            ?>
            <?php } ?>
        <?php } ?>
        <?php switch (intval($input['val_align'])) {
                    case 1:
          ?>
              text-align:center;
              <?php
                        break;
                    case 2:
                       ?>
              text-align:right;
              <?php
                        break;
                    case 0:
                    default:
                        ?>
              text-align:left;
              <?php
                        break;
                }?>
         <?php 
         //el_background
         if(isset($el_background['show_st']) && intval($el_background['show_st'])===1){
             switch (intval($el_background['type'])) {
                        case 1:
                            //solid
                            if(!empty($el_background['solid_color'])){
                            ?>
                                background:<?php echo $el_background['solid_color'];?>;
                            <?php    
                            }
                            break;
                        case 2:
                            //gradient
                            if(!empty($el_background['start_color']) && !empty($el_background['end_color'])){ 
                            ?>
                                background: <?php echo $el_background['start_color'];?>;
                                background-image: -webkit-linear-gradient(top, <?php echo $el_background['start_color'];?>, <?php echo $el_background['end_color'];?>);
                                background-image: -moz-linear-gradient(top, <?php echo $el_background['start_color'];?>, <?php echo $el_background['end_color'];?>);
                                background-image: -ms-linear-gradient(top, <?php echo $el_background['start_color'];?>, <?php echo $el_background['end_color'];?>);
                                background-image: -o-linear-gradient(top, <?php echo $el_background['start_color'];?>, <?php echo $el_background['end_color'];?>);
                                background-image: linear-gradient(to bottom, <?php echo $el_background['start_color'];?>, <?php echo $el_background['end_color'];?>);
                            <?php    
                            }
                            break;
                    }
         ?>
         <?php } ?>
         <?php 
         //el_border_radius
         if(isset($el_border_radius['show_st']) && intval($el_border_radius['show_st'])===1){
             ?>
             -webkit-border-radius: <?php echo $el_border_radius['size'];?>;
                -moz-border-radius: <?php echo $el_border_radius['size'];?>;
                border-radius: <?php echo $el_border_radius['size'];?>px;    
                 <?php
         }
         ?>
        <?php 
         //el_border
         if(isset($el_border['show_st']) 
                 && intval($el_border['show_st'])===1
                 && !empty($el_border['color'])
                 ){
             if(intval($el_border['style'])===2){
             $solid_tmp='dotted';    
             }else{
             $solid_tmp='solid';    
             }
             $color_tmp=$el_border['color'];
             $size_tmp=$el_border['width'];
             ?>
                border: <?php echo $solid_tmp;?> <?php echo $color_tmp;?> <?php echo $size_tmp;?>px;
            <?php
            
         }
         ?>
    }
    <?php 
    //el_border
    if(isset($el_border['color_focus_st']) 
                 && intval($el_border['color_focus_st'])===1){
                 if(intval($el_border['style'])===2){
             $solid_tmp='dotted';    
             }else{
             $solid_tmp='solid';    
             }
             $color_tmp=$el_border['color_focus'];
             $size_tmp=$el_border['width'];
              ?>
                #rockfm_<?php echo $id;?> .rockfm-txtbox-inp-val:focus{
                   border: <?php echo $solid_tmp;?> <?php echo $color_tmp;?> <?php echo $size_tmp;?>px;
                }
              <?php  
            }
    ?>
   #rockfm_<?php echo $id;?> .rockfm-label{
    <?php
    //label
    ?>
   display:block;
   <?php if($label['size']){?>
            font-size:<?php echo $label['size'];?>px;
        <?php } ?>
        <?php if(intval($label['bold'])===1){?>
            font-weight: bold;
        <?php }else{ ?>
            font-weight: normal;
        <?php }?>  
        <?php if(intval($label['italic'])===1){?>
            font-style:italic;
        <?php } ?>
        <?php if(intval($label['underline'])===1){?>
            text-decoration:underline;
        <?php } ?>
        <?php if(!empty($label['color'])){?>
            color:<?php echo $label['color'];?>;
        <?php } ?>
        <?php if(isset($label['font_st']) && intval($label['font_st'])===1){?>
        <?php 
            $font_temp=json_decode($label['font'],true);
            if(isset($font_temp['family'])){
        ?>    
            font-family:<?php echo $font_temp['family'];?>;
             <?php
           //storing to global fonts
            Uiform_Form_Helper::form_store_fonts($font_temp);
            //end storing to global fonts
            ?>
           <?php } ?> 
        <?php } ?>
        <?php 
         //shadow
         if(isset($label['shadow_st']) 
                 && intval($label['shadow_st'])===1
                 && !empty($label['shadow_color'])
                 ){
                $x_tmp=$label['shadow_x'].'px';
                $y_tmp=$label['shadow_y'].'px';
                $blur_tmp=$label['shadow_blur'].'px';
                $color_tmp=$label['shadow_color'];
             ?>
                text-shadow: <?php echo $x_tmp.' '.$y_tmp.' '.$blur_tmp.' '.$color_tmp;?>;
            <?php
            
         }
         ?>    
   }
   
   #rockfm_<?php echo $id;?> .rockfm-sublabel{
    <?php
    //sublabel
    ?>
   <?php if($sublabel['size']){?>
            font-size:<?php echo $sublabel['size'];?>px;
        <?php } ?>
        <?php if(intval($sublabel['bold'])===1){?>
            font-weight: bold;
        <?php } ?>
        <?php if(intval($sublabel['italic'])===1){?>
            font-style:italic;
        <?php } ?>
        <?php if(intval($sublabel['underline'])===1){?>
            text-decoration:underline;
        <?php } ?>
        <?php if(!empty($sublabel['color'])){?>
            color:<?php echo $sublabel['color'];?>;
        <?php } ?>
        <?php if(isset($sublabel['font_st']) && intval($sublabel['font_st'])===1){?>
        <?php 
            $font_temp=json_decode($sublabel['font'],true);
            if(isset($font_temp['family'])){
        ?>    
            font-family:<?php echo $font_temp['family'];?>;
             <?php
           //storing to global fonts
            Uiform_Form_Helper::form_store_fonts($font_temp);
            //end storing to global fonts
            ?>
           <?php } ?> 
        <?php } ?>
        <?php 
         //shadow
         if(isset($sublabel['shadow_st']) 
                 && intval($sublabel['shadow_st'])===1
                 && !empty($sublabel['shadow_color'])
                 ){
                $x_tmp=$sublabel['shadow_x'].'px';
                $y_tmp=$sublabel['shadow_y'].'px';
                $blur_tmp=$sublabel['shadow_blur'].'px';
                $color_tmp=$sublabel['shadow_color'];
             ?>
                text-shadow: <?php echo $x_tmp.' '.$y_tmp.' '.$blur_tmp.' '.$color_tmp;?>;
            <?php
            
         }
         ?>    
   }
   #rockfm_<?php echo $id;?> .rockfm-control-label{
    <?php
    if(isset($txt_block['block_st']) && intval($txt_block['block_st'])===1){
             switch (intval($txt_block['block_align'])) {
                        case 1:
                            //center
                           ?>
                            text-align: center;
                           <?php
                            break;
                        case 2:
                            //right
                            ?>
                            text-align: right;
                           <?php
                            break;
                        case 0:
                        default:
                            //left
                            ?>
                            text-align: left;
                           <?php
                            break;
                    }
         ?>
         <?php } ?>
   }
   #rockfm_<?php echo $id;?> .rockfm-wrap-label{
        <?php
        if(intval($txt_block['block_st'])===0){
           ?>
            display:none;
           <?php 
        }
           ?>
   }
   #rockfm_<?php echo $id;?> .rockfm-help-block{
   <?php if(isset($help_block['font_st']) && intval($help_block['font_st'])===1){?>
        <?php 
            $font_temp=json_decode($help_block['font'],true);
            if(isset($font_temp['family'])){
        ?>    
            font-family:<?php echo $font_temp['family'];?>;
             <?php
           //storing to global fonts
            Uiform_Form_Helper::form_store_fonts($font_temp);
            //end storing to global fonts
            ?>
           <?php } ?> 
        <?php } ?>
   }
   /*popover custom*/
   .popover_<?php echo $id;?> { 
        <?php if(!empty($validate['tip_bg'])){?>
            background:<?php echo $validate['tip_bg'];?>!important;
        <?php } ?>
        <?php if(!empty($validate['tip_col'])){?>
            color:<?php echo $validate['tip_col'];?>;
        <?php } ?>
        
   } 
   .popover_<?php echo $id;?> .popover-arrow:after{
        <?php if(!empty($validate['tip_bg'])){?>
            border-top-color:<?php echo $validate['tip_bg'];?>!important;
        <?php } ?>
   }

<?php include('formhtml_common_css1.php');?>
   <?php include('formhtml_addon_css.php');?>
   
<?php
$cntACmp = ob_get_contents();
 /* remove comments */
$cntACmp = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $cntACmp);
 /* remove tabs, spaces, newlines, etc. */
$cntACmp = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), ' ', $cntACmp);
ob_end_clean();
echo $cntACmp;
?>