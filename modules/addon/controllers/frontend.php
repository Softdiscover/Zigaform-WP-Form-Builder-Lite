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
if (class_exists('zgfm_mod_addon_controller_front')) {
    return;
}

/**
 * Controller Settings class
 *
 * @category  PHP
 * @package   Rocket_form
 * @author    Softdiscover <info@softdiscover.com>
 * @copyright 2013 Softdiscover
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version   Release: 1.00
 * @link      http://wordpress-cost-estimator.zigaform.com
 */
class zgfm_mod_addon_controller_front extends Uiform_Base_Module {

    const VERSION = '0.1';
    private $pagination = "";
    var $per_page = 5;
    private $wpdb = "";
    protected $modules;
    private $model_addon = "";
    
    /**
     * Constructor
     *
     * @mvc Controller
     */
    protected function __construct() {
        global $wpdb;
      $this->wpdb = $wpdb; 
      $this->model_addon = self::$_models['addon']['addon'];
    }
    
    
    
    public function load_addonsByFront(){
        
      //get addons
      $tmp_addons=$this->model_addon->getListAddonsByFront();
     
      //flag variables
      $tmp_addons_arr=array();
      $tmp_modules_arr=self::$_addons;
      
     
      foreach ($tmp_addons as $key => $value) {
          //$tmp_addons_arr[$value->add_section][$value->add_name]=$tmp_addons[$key];
          
          //load addons
          require_once( UIFORM_FORMS_DIR . '/modules/addon_'.$value->add_name.'/controllers/frontend.php');
           
           //$tmp_modules_arr['addon_'.$value->add_name]=array( 'back' => call_user_func( array( 'zgfm_addon_back_'.$value->add_name, 'get_instance' ) ));
           
          $tmp_add_new_contr=array();
          $tmp_add_new_contr['frontend'] = call_user_func(array('zgfm_addon_front_'.$value->add_name, 'get_instance'));
          $tmp_add_new_flag = array();
          $tmp_add_new_flag = call_user_func(array($tmp_add_new_contr['frontend'], 'add_controllers'));
          
          $tmp_add_new_contr = array_merge($tmp_add_new_contr,$tmp_add_new_flag);
          
          $tmp_modules_arr['addon_'.$value->add_name] = $tmp_add_new_contr;
          
      }
      
      self::$_addons = $tmp_modules_arr;
      //self::$_addons = $tmp_addons_arr; 
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
