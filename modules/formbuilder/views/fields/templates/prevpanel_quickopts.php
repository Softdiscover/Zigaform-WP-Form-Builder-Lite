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
 
<?php
$cntACmp = ob_get_contents();
$cntACmp = Uiform_Form_Helper::sanitize_output($cntACmp);
ob_end_clean();
echo $cntACmp;

