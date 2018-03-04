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
 * @link      http://wordpress-form-builder.zigaform.com/
 */
if (!defined('ABSPATH')) {
    exit('No direct script access allowed');
}
if (class_exists('Uiform_Fb_Controller_Recaptcha')) {
    return;
}

/**
 * Controller Recaptcha class
 *
 * @category  PHP
 * @package   Rocket_form
 * @author    Softdiscover <info@softdiscover.com>
 * @copyright 2013 Softdiscover
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version   Release: 1.00
 * @link      http://wordpress-form-builder.zigaform.com/
 */
class Uiform_Fb_Controller_Recaptcha extends Uiform_Base_Module {

    const VERSION = '0.1';

    private $model_fields = "";

    /*
     * Magic methods
     */

    /**
     * Constructor
     *
     * @mvc Controller
     */
    protected function __construct() {
        $this->model_fields = self::$_models['formbuilder']['fields'];
    }

    
    
    /**
     * Recaptcha::front_verify_recaptcha()
     * 
     * @return 
     */
    public function front_verify_recaptcha() {
        
        require_once(UIFORM_FORMS_LIBS . '/recaptcha2/autoload.php');
        
        $uid_field = (isset($_POST['rockfm-uid-field'])) ? Uiform_Form_Helper::sanitizeInput($_POST['rockfm-uid-field']) : '';
        $form_id = (isset($_POST['form_id'])) ? Uiform_Form_Helper::sanitizeInput($_POST['form_id']) : 0;
        $fmf_json = $this->model_fields->getDataByUniqueId($uid_field, $form_id);
        $secret = '';
        $success=false;
        if (!empty($fmf_json)) {
            $fmf_data = json_decode($fmf_json->fmf_data, true);

            $secret = (isset($fmf_data['input5']['g_key_secret'])) ? $fmf_data['input5']['g_key_secret'] : '';
            $siteKey = (isset($fmf_data['input5']['g_key_public'])) ? $fmf_data['input5']['g_key_public'] : '';
            
            if ($siteKey === '' || $secret === ''){
            
            }elseif (isset($_POST["rockfm-code-recaptcha"])){
                
                if(is_callable('curl_init')){
                    $recaptcha = new \ReCaptcha\ReCaptcha($secret, new \ReCaptcha\RequestMethod\CurlPost());
                }else{
                    $recaptcha = new \ReCaptcha\ReCaptcha($secret);
                }
                
               $resp = $recaptcha->verify($_POST["rockfm-code-recaptcha"], $_SERVER['REMOTE_ADDR']);

                if ($resp->isSuccess()):
                    $success=true;
                else:
                    $success=false;
                endif; 
            }else{

            }
            
        }
        
        
        
        
        $json=array();
        $json['success']=$success;
        //return data to ajax callback
        header('Content-Type: application/json');
        echo json_encode($json);
        die();
    }

    /**
     * Register callbacks for actions and filters
     *
     * @mvc Controller
     */
    public function register_hook_callbacks() {
        
    }

    /**
     * Initializes variables
     *
     * @mvc Controller
     */
    public function init() {

        try {
            //$instance_example = new WPPS_Instance_Class( 'Instance example', '42' );
            //add_notice('ba');
        } catch (Exception $exception) {
            add_notice(__METHOD__ . ' error: ' . $exception->getMessage(), 'error');
        }
    }

    /*
     * Instance methods
     */

    /**
     * Prepares sites to use the plugin during single or network-wide activation
     *
     * @mvc Controller
     *
     * @param bool $network_wide
     */
    public function activate($network_wide) {

        return true;
    }

    /**
     * Rolls back activation procedures when de-activating the plugin
     *
     * @mvc Controller
     */
    public function deactivate() {
        return true;
    }

    /**
     * Checks if the plugin was recently updated and upgrades if necessary
     *
     * @mvc Controller
     *
     * @param string $db_version
     */
    public function upgrade($db_version = 0) {
        return true;
    }

    /**
     * Checks that the object is in a correct state
     *
     * @mvc Model
     *
     * @param string $property An individual property to check, or 'all' to check all of them
     * @return bool
     */
    protected function is_valid($property = 'all') {
        return true;
    }

}

?>
