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
if (!defined('ABSPATH')) {
    exit('No direct script access allowed');
}
ob_start();
?>
 <table class="zgfm-mail-tmp-table"  cellpadding="0" cellspacing="0" >
     
     <?php if(!empty($data)){
     foreach ($data as $key => $value) {
         ?>
     
        <tr>
            <td width="50%" >
              <div  > <?php echo $value['label'];?></div>
            </td>
            <td width="50%" >
                <?php if(is_array($value['input'])){
                    ?>
                    <ul> 
                        <?php foreach ($value['input'] as $key2 => $value2) { ?>
                         <li> <?php echo $value2['label'];?></li>
                        <?php                     
                        }?>
                    </ul>
              <?php
                }else{
                    ?>
                   <ul> 
                       <li> <?php echo $value['input'];?></li>
                   </ul>
                <?php
                }?>
               

            </td>
        </tr>
     
     <?php
     }
     }?>
     
 
  
</table>
<?php
$cntACmp = ob_get_contents();
ob_end_clean();
echo $cntACmp;
?>