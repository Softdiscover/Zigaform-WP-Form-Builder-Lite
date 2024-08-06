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
 * @link      https://wordpress-form-builder.zigaform.com/
 */
if (! defined('ABSPATH')) {
    exit('No direct script access allowed');
}
ob_start();
?>
 #rockfm_<?php echo $id; ?> {
        <?php
         // padding
        if (isset($skin['padding']['show_st']) && intval($skin['padding']['show_st']) === 1) {
            ?>
             padding: <?php echo $skin['padding']['top']?:'0'; ?>px <?php echo $skin['padding']['right']?:'0'; ?>px <?php echo $skin['padding']['bottom']?:'0'; ?>px <?php echo $skin['padding']['left']?:'0'; ?>px!important;
                
            <?php
        } else {
            ?>
             padding:0px 0px 0px 0px;
        <?php } ?>
        <?php
         // margin
        if (isset($skin['margin']['show_st']) && intval($skin['margin']['show_st']) === 1) {
            ?>
             margin: <?php echo $skin['margin']['top']?:'0'; ?>px <?php echo $skin['margin']['right']?:'0'; ?>px <?php echo $skin['margin']['bottom']?:'0'; ?>px <?php echo $skin['margin']['left']?:'0'; ?>px!important;
                
            <?php
        } else {
            ?>
             margin:0px 0px 0px 0px;
        <?php } ?>
   }   
   
<?php
$cntACmp = ob_get_contents();
 /* remove comments */
$cntACmp = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $cntACmp);
 /* remove tabs, spaces, newlines, etc. */
$cntACmp = str_replace(array( "\r\n", "\r", "\n", "\t", '  ', '    ', '    ' ), ' ', $cntACmp);
ob_end_clean();
echo $cntACmp;
?>
