<?php
if (!defined('ABSPATH')) {
    exit('No direct script access allowed');
}
ob_start();
?>
<iframe src="<?php echo $url_form;?>" 
        scrolling="no" 
        id="zgfm-iframe-<?php echo $form_id;?>"
        frameborder="0" 
        style="border:none;width:100%;" 
        allowTransparency="true"></iframe>
<?php
$cntACmp = ob_get_contents();
$cntACmp = Uiform_Form_Helper::sanitize_output($cntACmp);
ob_end_clean();
echo $cntACmp;
?>