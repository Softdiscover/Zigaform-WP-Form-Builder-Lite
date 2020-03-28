<?php
/*
 * Plugin Name: Zigaform Form Builder Lite
 * Plugin URI: https://wordpress-form-builder.zigaform.com/
 * Description: The ZigaForm Wordpress form builder is the ultimate form creation solution for Wordpress.
 * Version: 4.6.4
 * Author: ZigaForm.Com
 * Author URI: https://wordpress-form-builder.zigaform.com/
 */

if (!defined('ABSPATH')) {
    die('Access denied.');
}
if (!class_exists('UiformFormbuilderLite')) {

    final class UiformFormbuilderLite {

        /**
         * The only instance of the class
         *
         * @var RocketForm
         * @since 1.0
         */
        private static $instance;

        /**
         * The Plug-in version.
         *
         * @var string
         * @since 1.0
         */
        public $version = '4.6.4';

        /**
         * The minimal required version of WordPress for this plug-in to function correctly.
         *
         * @var string
         * @since 1.0
         */
        public $wp_version = '3.6';

        /**
         * The minimal required version of WordPress for this plug-in to function correctly.
         *
         * @var string
         * @since 1.0
         */
        public $php_version = '5.3';
        
        
        /**
         * Class name
         *
         * @var string
         * @since 1.0
         */
        public $class_name;

        /**
         * An array of defined constants names
         *
         * @var array
         * @since 1.0
         */
        public $defined_constants;

        /**
         * Create a new instance of the main class
         *
         * @since 1.0
         * @static
         * @return RocketForm
         */
        public static function instance() 
        {
            $class_name = get_class(); 
            if (!isset(self::$instance) && !( self::$instance instanceof $class_name )) {
                self::$instance = new $class_name; 
            }
            return self::$instance;
        }

        public function __construct() 
        {
            // Save the class name for later use
            $this->class_name = get_class();
             //
            //  Plug-in requirements
            //
            $this->define_constants();
            $this->load_dependencies();
            
            register_activation_hook(__FILE__, array(&$this, 'activate'));
            register_deactivation_hook(__FILE__, array(&$this, 'deactivate'));
            
            if (is_admin()) {
                if (!$this->check_requirements()) {
                    add_action('admin_notices', array(&$this, 'uiform_requirements_error'));

                    return false;
                }
            }
             
            //
            // Declare constants and load dependencies
            //
            
            
            $this->check_updateChanges();
            try {
                if (class_exists('Uiform_Bootstrap')) {
                    $GLOBALS['wprockf'] = Uiform_Bootstrap::get_instance();
                    
                }
            } catch (exception $e) {
                $error = $e->getMessage() . "\n";
                echo $error;
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
           $zgfm_is_installed = UiformFormbuilderLite::another_zgfm_isInstalled();

           if($zgfm_is_installed['result']){
               wp_die($zgfm_is_installed['message2']);
           }

           require_once( UIFORM_FORMS_DIR . '/classes/uiform-installdb.php');
           $installdb = new Uiform_InstallDB();
           $installdb->install($network_wide);
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
       
        /*
        *  check if another wp zigaform is installed
        */
       public static function another_zgfm_isInstalled() {

             /*if (is_plugin_active( 'uiform-form-builder/uiform-form-builder.php' ) ) {
                 return true;
             }*/
           $check_is_lite=true;
           $check_slug="Zigaform - Wordpress Form Builder Lite";
             

             $output=array();  
             $output['result']=false;
             $pluginList = get_option( 'active_plugins' );
             
             if(is_array($pluginList))
             foreach ($pluginList as $key => $value) {
                 if (strpos($value, 'zigaform-cost-estimator-lite.php') !== false) {
                     $output['message']=__('Zigaform alert', 'FRocket_admin');
                     $output['message2']=__('Found zigaform Cost Estimator lite. Deactivate it before installing '.$check_slug, 'FRocket_admin');
                     $output['result']=true;
                     return $output;
                 }

                 if (strpos($value, 'zigaform-wp-estimator.php') !== false) {
                     $output['message']=__('Zigaform alert', 'FRocket_admin');
                     $output['message2']=__('Found zigaform cost estimation installed. Deactivate it before installing '.$check_slug, 'FRocket_admin');
                     $output['result']=true;
                     return $output;
                 }

                 if($check_is_lite){
                     if (strpos($value, 'zigaform-wp-form-builder.php') !== false) {
                           $output['message']=__('Zigaform alert', 'FRocket_admin');
                           $output['message2']=__('Found zigaform form builder installed. Deactivate it before installing '.$check_slug, 'FRocket_admin');
                           $output['result']=true;
                      }
                 }else{
                      if (strpos($value, 'zigaform-form-builder-lite.php') !== false) {
                           $output['message']=__('Zigaform alert', 'FRocket_admin');
                           $output['message2']=__('Found zigaform form builder lite installed. Deactivate it before installing '.$check_slug, 'FRocket_admin');
                           $output['result']=true;
                           return $output;
                      }
                 }
             }

           return $output;
       }
        /**
        * check_requirements()
        * Checks that the WordPress setup meets the plugin requirements
        * 
        * @return boolean
        */
        private function check_requirements() {
            global $wp_version;
            if (!version_compare($wp_version, $this->wp_version, '>=')) {
                add_action('admin_notices', array(&$this, 'display_req_notice'));
                return false;
            }

            if (version_compare(PHP_VERSION, $this->php_version, '<')) {
                return false;
            }
            
            
                include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
                 $zgfm_is_installed = UiformFormbuilderLite::another_zgfm_isInstalled();
                if($zgfm_is_installed['result']){
                   return false;
                }   
                
            
             
            return true;
        }

        public function uiform_requirements_error() {
            global $wp_version;
            require_once dirname(__FILE__) . '/views/requirements-error.php';
        }

        /**
         * Define constants needed across the plug-in.
         */
        private function define_constants() {
            $this->define('UIFORM_FILE', __FILE__);
            $this->define('UIFORM_FOLDER', plugin_basename(dirname(__FILE__)));
            $this->define('UIFORM_BASENAME', plugin_basename(__FILE__));
            $this->define('UIFORM_ABSFILE', __FILE__);
            $this->define('UIFORM_ADMINPATH', get_admin_url());
            $this->define('UIFORM_APP_NAME', "Zigaform - Wordpress Form Builder Lite");
            $this->define('UIFORM_VERSION', $this->version);
            $this->define('UIFORM_FORMS_DIR', dirname(__FILE__));
            $this->define('UIFORM_FORMS_URL', plugins_url() . '/'.UIFORM_FOLDER);
            $this->define('UIFORM_FORMS_LIBS', UIFORM_FORMS_DIR . '/libraries');
            $this->define('UIFORM_DEMO', 0);
            $this->define('UIFORM_DEV', 0);
            
            
             
            $this->define('ZIGAFORM_F_LITE', 1);
            $this->define('UIFORM_DEBUG', 0);
            if (UIFORM_DEBUG == 1) {
                error_reporting(E_ALL);
                ini_set('display_errors', 1);
            }
            
        }

        /**
         * Define constant if not already set
         * @param  string $name
         * @param  string|bool $value
         */
        private function define($name, $value) 
        {
            if (!defined($name)) {
                define($name, $value);
                $this->defined_constants[] = $name;
            }
        }

        /**
         * Loads PHP files that required by the plug-in
         */
        private function load_dependencies() {
            // Admin Panel
            if (is_admin()) {
                require_once UIFORM_FORMS_DIR . '/classes/uiform-base-module.php';
                require_once UIFORM_FORMS_DIR . '/classes/uiform-form-helper.php';
                require_once UIFORM_FORMS_DIR . '/classes/uiform-bootstrap.php';
                //include UIFORM_FORMS_DIR . '/helpers/styles-font-menu/plugin.php';
                
                require_once UIFORM_FORMS_DIR . '/classes/zigaform-notice.php';
                
            }

            // Front-End Site
            if (!is_admin()) {
                require_once UIFORM_FORMS_DIR . '/classes/uiform-base-module.php';
                require_once UIFORM_FORMS_DIR . '/classes/uiform-form-helper.php';
                require_once UIFORM_FORMS_DIR . '/classes/uiform-bootstrap.php';
            }
        }
        
        /**
         * Loads PHP files that required by the plug-in
         */
        private function check_updateChanges() {
            global $wpdb;
            $version=UIFORM_VERSION;
            $install_ver = get_option("uifmfbuild_version");
            
            require_once UIFORM_FORMS_DIR . '/libraries/updates.php';
        }

    }

}

function uiform_uninstallLite()
{
    $zgfm_is_installed = UiformFormbuilderLite::another_zgfm_isInstalled();
            if($zgfm_is_installed['result']){

            } else{
                require_once( UIFORM_FORMS_DIR . '/classes/uiform-installdb.php');
                $installdb = new Uiform_InstallDB();
                $installdb->uninstall();

            }

    return true;
}

function wpRFRMLite() {
    register_uninstall_hook(__FILE__, 'uiform_uninstallLite');
    return UiformFormbuilderLite::instance();
}


wpRFRMLite();
?>
