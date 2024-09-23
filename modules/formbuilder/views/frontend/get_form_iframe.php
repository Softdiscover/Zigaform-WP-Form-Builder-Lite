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
 * @link      https://softdiscover.com/zigaform/wordpress-cost-estimator
 */
if (!defined('ABSPATH')) {
    exit('No direct script access allowed');
}
ob_start();
?>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title> </title>
    <meta name="viewport" content="width=device-width, user-scalable=0">
    <meta name="author" content="Softdiscover Company">
    <meta http-equiv="X-UA-Compatible" content="IE=9">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, post-check=0, pre-check=0, private">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">

    <script type="text/javascript">
        var rockfm_vars = <?php echo json_encode($rockfm_vars_arr, JSON_PRETTY_PRINT); ?>;
    </script>

    <?php
    if (!empty($head_files)) {
        $files = apply_filters('zgfm_back_addons_load_scripts', $head_files['files']);
        foreach ($files as $value) {
            echo $value;
        }
    }
    ?>


    <?php
    if (file_exists(WP_CONTENT_DIR . '/uploads/softdiscover/' . UIFORM_SLUG . '/css/rockfm_form' . $form_id . '.css')) {
    ?>
        <link rel='stylesheet' id='ebor-fonts-css' href='<?php echo site_url(); ?>/wp-content/uploads/softdiscover/<?php echo UIFORM_SLUG; ?>/css/rockfm_form<?php echo $form_id; ?>.css?<?php echo date('Ymdgis'); ?>' type='text/css' media='all' />
    <?php
 } elseif (file_exists(WP_CONTENT_DIR . '/uploads/softdiscover/' . UIFORM_SLUG . '/rockfm_form' . $form_id . '.css')) {
    ?>
    <link rel='stylesheet' id='ebor-fonts-css' href='<?php echo site_url(); ?>/wp-content/uploads/softdiscover/<?php echo UIFORM_SLUG; ?>/rockfm_form<?php echo $form_id; ?>.css?<?php echo date('Ymdgis'); ?>' type='text/css' media='all' />
    <?php
} elseif (file_exists(UIFORM_FORMS_DIR . '/assets/frontend/css/rockfm_form' . $form_id . '.css')) {
    ?>
        <link rel='stylesheet' id='ebor-fonts-css' href='<?php echo UIFORM_FORMS_URL; ?>/assets/frontend/css/rockfm_form<?php echo $form_id; ?>.css?<?php echo date('Ymdgis'); ?>' type='text/css' media='all' />
    <?php
    }
    ?>

    <script type="text/javascript" src="<?php echo UIFORM_FORMS_URL; ?>/assets/frontend/js/iframe/4.1.1/iframeResizer.contentWindow.js"></script>


    <script type="text/javascript">
        $uifm(document).ready(function($) {

            rocketfm();
            rocketfm.initialize();
            rocketfm.setExternalVars();
            //  $('#uifm_container_<?php echo $form_id; ?>').append('<img src="<?php echo $imagesurl; ?>/loader-form.gif"/></div>');
            rocketfm.loadform_init();


        });
    </script>
</head>

<body>
    <?php
    if (!empty($form_html)) {
        echo $form_html;
    }
    ?>

    <div class="space10"></div>
</body>

</html>
<?php
$cntACmp = ob_get_contents();
// $cntACmp = str_replace("\n", '', $cntACmp);
// $cntACmp = str_replace("\t", '', $cntACmp);
// $cntACmp = str_replace("\r", '', $cntACmp);
$cntACmp = str_replace('//-->', ' ', $cntACmp);
$cntACmp = str_replace('//<!--', ' ', $cntACmp);
// $cntACmp = Uiform_Form_Helper::sanitize_output($cntACmp);
ob_end_clean();
echo $cntACmp;
?>