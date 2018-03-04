<?php

/**
 * Frontend
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   sfdc_theme
 * @author    Softdiscover <info@softdiscover.com>
 * @copyright 2015 Softdiscover
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link      http://wordpress-cost-estimator.zigaform.com
 */
if (!defined('ABSPATH')) {
    exit('No direct script access allowed');
}
if (class_exists('zgfm_core_addon')) {
    return;
}

class zgfm_core_addon extends Uiform_Base_Module {
    
    protected $modules;
    protected $models;
    
    /**
     * The Plug-in version.
     *
     * @var string
     * @since 1.0
     */
    public $version = '1.0';
        
    public $theme_options = array();
    
    
   public function __construct(){
       
       //load library
      /* require_once( UIFORM_FORMS_DIR . '/modules/addon/controllers/common.php');
        $this->modules = array(
            'formbuilder' => array('frontend' => Uiform_Fb_Controller_Frontend::get_instance(),
                                    'records' => Uiform_Fb_Controller_Records::get_instance())
        );
       self::$_modules = $this->modules;*/
       
       var_dump($this->modules);
       die();
   }
   
   public function get_addons(){
       
   }
   
   public function get_vars(){
       $data_options = array();
        $option_files = array(
                'option_menu',
                'option_footer',
                'option_main',
                'option_header',
                'option_blog',
                'option_portfolio'
        );
        
        foreach($option_files as $file){
                    
            $page_dir=SFDC_A_THM_DIR . "/core/options/" . $file.'.php';
             if ( is_readable($page_dir) ){
                $page = include ($page_dir);

            $data_options[$page['name']] = array();
            foreach($page['options'] as $option) {
                    if (isset($option['default'])) {
                            $data_options[$page['name']][$option['id']] = $option['default'];
                    }
            }
            
            //check if variable is installed
            if(get_option(SFDC_A_PREFIX. $page['name'])){
                $data_options[$page['name']] = array_merge((array) $data_options[$page['name']], (array) get_option(SFDC_A_PREFIX. $page['name']));
            }
            
            
            }
	}
        
        return $data_options;
      
   }  
   
   public function get_option($option, $option_field){
       
        $data_options = $this->theme_options;
        
        if (empty($option_field)) {
            return false;
        }else{
           if (isset($data_options[$option][$option_field])) {
			return $data_options[$option][$option_field];
		} else {
			return false;
		} 
        }
   }
   
   public function install_options(){
       
       //installing default options
       foreach ($this->theme_options as $key => $value) {
            update_option(SFDC_A_PREFIX.$key , $value);
        } 
       
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
    public function activate($network_wide = false) {
        
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
