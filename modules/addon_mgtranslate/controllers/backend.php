<?php
use Gettext\Loader\PoLoader;
use Gettext\Generator\MoGenerator;

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
if (! defined('ABSPATH')) {
    exit('No direct script access allowed');
}
if (class_exists('zfaddn_mgtranslate_back')) {
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
class zfaddn_mgtranslate_back extends Uiform_Base_Module
{

    const VERSION       = '0.1';
    private $pagination = '';
    var $per_page       = 5;
    private $wpdb       = '';
    
    /**
     * Constructor
     *
     * @mvc Controller
     */
    protected function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        // Composer autoload
            $composer_path = dirname(__FILE__).'/../vendor/autoload.php';
        if (file_exists($composer_path)) {
            require_once $composer_path;
        }
        add_action('admin_menu', array( &$this, 'load_menu' ));
        
        // admin resources
        add_action('admin_enqueue_scripts', array( &$this, 'load_dependencies' ), 20, 1);
        
        // ajax for preview_clogic_graph
        add_action('wp_ajax_zgfm_mgtranslate_loadPo', array( &$this, 'ajax_load_pofile' ));
        
        // ajax for saving po file changes
        add_action('wp_ajax_zgfm_mgtranslate_savePo', array( &$this, 'ajax_save_pofile' ));
        
        // ajax for creating new language
        add_action('wp_ajax_zgfm_mgtranslate_newlang', array( &$this, 'ajax_new_lang' ));
        
        //ajax process create language
        add_action('wp_ajax_zgfm_mgtranslate_createlang', array( &$this, 'ajax_create_lang' ));
        
        
        // add class to body if translator page is selected
        add_filter('admin_body_class', array( &$this, 'filter_body_class' ));
    }
    
    /**
     * ajax_create_lang
     *
     * @author  Unknown
     * @since   v0.0.1
     * @version v1.0.0  Thursday, October 1st, 2020.
     * @access  public
     * @return  void
     */
    public function ajax_create_lang()
    {
        check_ajax_referer('zgfm_ajax_nonce', 'zgfm_security');
        
        $resp=array();
        $resp['html_title']=__('Translation Manager', 'FRocket_admin');
            
        try{
            $newlangname = ( $_POST['newlangname'] ) ? Uiform_Form_Helper::sanitizeInput(trim($_POST['newlangname'])) : '';
         
            $resp['html_footer']='<button type="button" class="btn btn-info" data-dismiss="modal">'.__('Close', 'FRocket_admin').'</button>';
            if (preg_match("/^[a-zA-Z0-9_]+$/", $newlangname)) {
                
                $file = UIFORM_FORMS_DIR.'/i18n/languages/backend/wprockf-en_US.po';
                $newfile = UIFORM_FORMS_DIR.'/i18n/languages/backend/wprockf-'.$newlangname.'.po';
                if(!copy($file,$newfile)){
                    throw new Exception("Error Processing Request", 1);
                } 
                else{
                    $file = UIFORM_FORMS_DIR.'/i18n/languages/backend/wprockf-en_US.mo';
                    $newfile = UIFORM_FORMS_DIR.'/i18n/languages/backend/wprockf-'.$newlangname.'.mo';
                    if(!copy($file,$newfile)){
                        throw new Exception("Error Processing Request", 1);
                    }
                    else{
                        $file = UIFORM_FORMS_DIR.'/i18n/languages/front/wprockf-en_US.mo';
                        $newfile = UIFORM_FORMS_DIR.'/i18n/languages/front/wprockf-'.$newlangname.'.mo';
                        if(!copy($file,$newfile)){
                            throw new Exception("Error Processing Request", 1);
                        }
                        else{
                            $file = UIFORM_FORMS_DIR.'/i18n/languages/front/wprockf-en_US.po';
                            $newfile = UIFORM_FORMS_DIR.'/i18n/languages/front/wprockf-'.$newlangname.'.po';
                            if(!copy($file,$newfile)){
                                throw new Exception("Error Processing Request", 1);
                            }
                            else{
                                $resp['html']=__('New translation created successfully', 'FRocket_admin');
                            }
                        }
                    }
                }
                wp_send_json_success($resp);
               
            } else {
               throw new Exception("Error Processing Request", 1);
               
            }
        }
        catch (Exception $e) {
            $resp['html']=__('Error! An error ocurred during the process', 'FRocket_admin');
            wp_send_json_error($resp);
        }
        
       
    }
    
    /**
     * ajax_new_lang.
     *
     * @author  Unknown
     * @since   v0.0.1
     * @version v1.0.0  Thursday, October 1st, 2020.
     * @access  public
     * @return  void
     */
    public function ajax_new_lang()
    {
        check_ajax_referer('zgfm_ajax_nonce', 'zgfm_security');
        $resp=array();
        $resp['html_title']=__('Translation Manager', 'FRocket_admin');
        $resp['html']=self::render_template('addon_mgtranslate/views/backend/ajax_new_lang.php', array());
        $resp['html_buttons'] = '<button id="zgfm-mgtranslation-btn-createNewLang" class="btn btn-success btn-lg float-right">'.__('Create New', 'FRocket_admin').'</button>';
        wp_send_json_success($resp);
    }
      /**
       * add class to body
       *
       * @access public
       * @since 1.0.0
       * @return void
       */
    public function filter_body_class($classes)
    {
        $customClass='';
        if (isset($_GET['page']) && $_GET['page']=='zigaform-translation') {
            // id index exists
            $customClass='sfdc-wrap sfdclauncher';
        }
        return $classes.' '.$customClass;
    }
    
    /**
     * ajax_save_pofile.
     *
     * @author  Unknown
     * @since   v0.0.1
     * @version v1.0.0  Thursday, October 1st, 2020.
     * @access  public
     * @return  void
     */
    public function ajax_save_pofile()
    {
        check_ajax_referer('zgfm_ajax_nonce', 'zgfm_security');
        $translation           = ( isset($_POST['translation']) && $_POST['translation'] ) ? array_map(array( 'Uiform_Form_Helper', 'sanitizeRecursive_html' ), $_POST['translation']) : array();
        $lang               = ( $_POST['lang'] ) ? Uiform_Form_Helper::sanitizeInput(trim($_POST['lang'])) : '';
        $side               = ( $_POST['side'] ) ? Uiform_Form_Helper::sanitizeInput(trim($_POST['side'])) : '';
        
        $url = sprintf(UIFORM_FORMS_DIR.'/i18n/languages/%s/wprockf-%s.po', $side, $lang);
        $urlMo = sprintf(UIFORM_FORMS_DIR.'/i18n/languages/%s/wprockf-%s.mo', $side, $lang);
        $resp=array();
        if ($lang=='' && !file_exists($url)) {
            wp_send_json_error($resp);
        }
        
        //process
        $parser = new PoParser\Parser();
        $parser->read($url);
        $entries = $parser->getEntriesAsArrays();
        
        $count=1;
        $newArr=array();
        foreach ($translation as $key => $value) {
            $count2=1;
            foreach ($entries as $key2 => $value2) {
                if ($key===$count2) {
                    $newArr[]=array(
                    'original'=>$key2,
                    'replace'=>$value
                    );
                    break;
                }
                $count2++;
            }
        }
        
        //updating po file
        $parser = new PoParser\Parser();
        $parser->read($url);
        // updating changes
        foreach ($newArr as $key => $value) {
            $parser->updateEntry($value['original'], $value['replace']);
        }
        $parser->write($url);
        
        //update po to mo
        //import from a .po file:
        $loader = new PoLoader();
        $translations = $loader->loadFile($url);
        
        //export to a .mo file:
        $generator = new MoGenerator();
        $generator->generateFile($translations, $urlMo);

        $resp['lang']=$lang;
        $resp['side']=$side;
        $resp['translation']=$translation;
        $resp['replace']=$newArr;
        $resp['html_title']=__('Translation Manager', 'FRocket_admin');
        $resp['html']=__('Translation saved successfully', 'FRocket_admin');
        wp_send_json_success($resp);
    }
    
    /**
     * load pofile
     *
     * @author  Unknown
     * @since   v0.0.1
     * @version v1.0.0  Wednesday, September 30th, 2020.
     * @access  public
     * @return  void
     */
    public function ajax_load_pofile()
    {
        check_ajax_referer('zgfm_ajax_nonce', 'zgfm_security');
        
        $lang = ( isset($_POST['lang']) ) ? Uiform_Form_Helper::sanitizeInput(trim($_POST['lang'])) : '';
        $side = ( isset($_POST['side']) ) ?  Uiform_Form_Helper::sanitizeInput(trim($_POST['side']))  : 'backend';
        $resp = array();
        $url = sprintf(UIFORM_FORMS_DIR.'/i18n/languages/%s/wprockf-%s.po', $side, $lang);
        
        if ($lang=='' && !file_exists($url)) {
            wp_send_json_error($resp);
        }
        
        $parser = new PoParser\Parser();
        $parser->read($url);
        $entries = $parser->getEntriesAsArrays();
        $data=array();
        $data['lang']=$lang;
        $data['side']=$side;
        $data['entries'] =  $entries;
        $resp['content'] = self::render_template('addon_mgtranslate/views/backend/ajax_load_pofile.php', $data);
        wp_send_json_success($resp);
    }
    
    /**
     * load submenu
     *
     * @return void
     */
    public function load_menu()
    {
        $perms = 'manage_options';
        add_submenu_page('zgfm_form_builder', __('Translation', 'FRocket_admin'), __('Translation', 'FRocket_admin'), $perms, 'zigaform-translation', array( &$this, 'get_menu' ));
    }
    
    /**
     *  Redirects the clicked menu item to the correct location
     *
     * @return null
     */
    public function get_menu()
    {
        $current_page = isset($_REQUEST['page']) ? esc_html($_REQUEST['page']) : 'zgfm_form_builder';

        switch ($current_page) {
            case 'zigaform-translation':
                $this->show_list();
                break;
         
            default:
                break;
        }
    }
    
    /*
    * load css, and javascript files
    */
    public function load_dependencies()
    {
    
        if (UIFORM_DEBUG===1) {
            // css
            wp_enqueue_style('zgfm-mgtranslate-style', UIFORM_FORMS_URL . '/modules/addon_mgtranslate/assets/backend/css/style.debug.css');
        // load
            wp_enqueue_script('zgfm_back_mgtranslate_js', UIFORM_FORMS_URL . '/modules/addon_mgtranslate/assets/backend/js/admin.debug.js', array( 'wp-i18n', 'wp-hooks' ), date('YmdHis'), true);
        } else {
           // css
            wp_enqueue_style('zgfm-mgtranslate-style', UIFORM_FORMS_URL . '/modules/addon_mgtranslate/assets/backend/css/style.min.css');
        // load
            wp_enqueue_script('zgfm_back_mgtranslate_js', UIFORM_FORMS_URL . '/modules/addon_mgtranslate/assets/backend/js/admin.min.js', array( 'wp-i18n', 'wp-hooks' ), 1, true);
        }
    }
    
    /**
     * show list of languages
     */
    public function show_list()
    {
        $data=array();
        
        $pofilespath=UIFORM_FORMS_DIR.'/i18n/languages/backend/';
        $data['language']='en_US';
        $data['lang_list']=Uiform_Form_Helper::getLanguageList($pofilespath);
        
        echo self::render_template('addon_mgtranslate/views/backend/show_list.php', $data);
    }
    
    /**
     * Register callbacks for actions and filters
     *
     * @mvc Controller
     */
    public function register_hook_callbacks()
    {
    }

    /**
     * Initializes variables
     *
     * @mvc Controller
     */
    public function init()
    {

        try {
            // $instance_example = new WPPS_Instance_Class( 'Instance example', '42' );
            // add_notice('ba');
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
    public function activate($network_wide)
    {

        return true;
    }

    /**
     * Rolls back activation procedures when de-activating the plugin
     *
     * @mvc Controller
     */
    public function deactivate()
    {
        return true;
    }

    /**
     * Checks if the plugin was recently updated and upgrades if necessary
     *
     * @mvc Controller
     *
     * @param string $db_version
     */
    public function upgrade($db_version = 0)
    {
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
    protected function is_valid($property = 'all')
    {
        return true;
    }
}
