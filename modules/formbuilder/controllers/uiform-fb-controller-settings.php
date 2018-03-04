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
if (class_exists('Uiform_Fb_Controller_Settings')) {
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
 * @link      http://wordpress-form-builder.zigaform.com/
 */
class Uiform_Fb_Controller_Settings extends Uiform_Base_Module {

    const VERSION = '0.1';

    private $wpdb = "";
    protected $modules;
    private $model_settings = "";

    /**
     * Constructor
     *
     * @mvc Controller
     */
    protected function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->model_settings = self::$_models['formbuilder']['settings'];
        // save settings options
        add_action('wp_ajax_rocket_fbuilder_setting_saveOpts', array(&$this, 'ajax_save_options'));
        // create backup
        add_action('wp_ajax_uiform_fbuilder_setting_backup', array(&$this, 'ajax_backup_create'));
        // Delete file
        add_action('wp_ajax_uiform_fbuilder_setting_delbackupfile', array(&$this, 'ajax_backup_deletefile'));
        // Delete file
        add_action('wp_ajax_uiform_fbuilder_setting_restorebkpfile', array(&$this, 'ajax_backup_restorefile'));
        
        //blocked message
        add_action('wp_ajax_uiform_fbuilder_blocked_getmessage', array(&$this, 'ajax_blocked_getmessage'));
        
        if(isset($_POST['_uifm_bkp_submit_file']) && intval($_POST['_uifm_bkp_submit_file'])===1){
            $this->backup_upload_file();
        }
    }
    
    public function ajax_blocked_getmessage() {
         check_ajax_referer( 'zgfm_ajax_nonce', 'zgfm_security' );
        $message = (isset($_POST['message']) && $_POST['message']) ? Uiform_Form_Helper::sanitizeInput($_POST['message']) : '';
        
        $data=array();
        $data['message'] = $message;
        $json=array();
        $json['msg'] = self::render_template('formbuilder/views/settings/blocked_getmessage.php', $data);
         header('Content-Type: application/json');
        echo json_encode($json);
        wp_die();
    }
    
    public function backup_upload_file() {
        
        require_once( UIFORM_FORMS_DIR . '/classes/uiform_backup.php');
        $dbBackup = new Uiform_Backup();
        $dbBackup->uploadBackupFile();
    }
    
    public function ajax_backup_create() {
        
        check_ajax_referer( 'zgfm_ajax_nonce', 'zgfm_security' );
        
        $json=array();
        require_once( UIFORM_FORMS_DIR . '/classes/uiform_backup.php');
        $dbBackup = new Uiform_Backup();
        $name_bkp = (isset($_POST['uifm_frm_namebackup']) && $_POST['uifm_frm_namebackup']) ? Uiform_Form_Helper::sanitizeInput($_POST['uifm_frm_namebackup']) : '';
        $dbBackup->makeDbBackup($name_bkp);
        header('Content-Type: application/json');
        echo json_encode($json);
        wp_die();
    }
    
    public function ajax_backup_restorefile(){
        
        check_ajax_referer( 'zgfm_ajax_nonce', 'zgfm_security' );
        
        $json=array();
        $uifm_frm_resfile = (isset($_POST['uifm_frm_resfile']) && $_POST['uifm_frm_resfile']) ? Uiform_Form_Helper::sanitizeInput($_POST['uifm_frm_resfile']) : '';
        require_once( UIFORM_FORMS_DIR . '/classes/uiform_backup.php');
        $dbBackup = new Uiform_Backup();
        $dbBackup->restoreBackup($uifm_frm_resfile);
        header('Content-Type: application/json');
        echo json_encode($json);
        wp_die();
    }
    
    public function ajax_backup_deletefile(){
        
        check_ajax_referer( 'zgfm_ajax_nonce', 'zgfm_security' );
        
        $json=array();
        $uifm_frm_delfile = (isset($_POST['uifm_frm_delfile']) && $_POST['uifm_frm_delfile']) ? Uiform_Form_Helper::sanitizeInput($_POST['uifm_frm_delfile']) : '';
        $dir = UIFORM_FORMS_DIR . '/backups/';
        @unlink($dir.$uifm_frm_delfile);
        header('Content-Type: application/json');
        echo json_encode($json);
        wp_die();
    }
    
    public function ajax_save_options() {
        
        check_ajax_referer( 'zgfm_ajax_nonce', 'zgfm_security' );
        
        $opt_language = (isset($_POST['language']) && $_POST['language']) ? Uiform_Form_Helper::sanitizeInput($_POST['language']) : '';
        
        $opt_modalmode = (isset($_POST['uifm_frm_main_modalmode']) && $_POST['uifm_frm_main_modalmode']) ? Uiform_Form_Helper::sanitizeInput($_POST['uifm_frm_main_modalmode']) : 0;
        
        if((string)$opt_modalmode ==='on'){
            update_option( 'zgfm_b_modalmode', 1);
        }else{
            update_option( 'zgfm_b_modalmode', 0);
        }
        
        
        $data = array();
        $data['language'] = $opt_language;
        $where = array(
            'id' => 1
        );
        $result = $this->wpdb->update($this->model_settings->table, $data, $where);
        $json = array();
        if ($result > 0) {
            $json['success'] = 1;
        } else {
            $json['success'] = 0;
        }

        header('Content-Type: application/json');
        echo json_encode($json);
        wp_die();
    }

    public function view_settings() {
        $data = array();
        $query = $this->model_settings->getOptions();

        $list_lang = array();
        $list_lang[] = array('val' => '', 'label' => __('Select language', 'FRocket_admin'));
        $list_lang[] = array('val' => 'en_US', 'label' => __('english', 'FRocket_admin'));
        $list_lang[] = array('val' => 'es_ES', 'label' => __('spanish', 'FRocket_admin'));
        $list_lang[] = array('val' => 'fr_FR', 'label' => __('french', 'FRocket_admin'));
        $list_lang[] = array('val' => 'de_DE', 'label' => __('german', 'FRocket_admin'));
        $list_lang[] = array('val' => 'it_IT', 'label' => __('italian', 'FRocket_admin'));
        $list_lang[] = array('val' => 'pt_BR', 'label' => __('portuguese', 'FRocket_admin'));
        $list_lang[] = array('val' => 'ru_RU', 'label' => __('russian', 'FRocket_admin'));
        $list_lang[] = array('val' => 'zh_CN', 'label' => __('chinese', 'FRocket_admin'));
        $data['language'] = $query->language;
        $data['lang_list'] = $list_lang;
        
        $data['modalmode'] = get_option( 'zgfm_b_modalmode', 0 );
        

        echo self::loadPartial('layout.php', 'formbuilder/views/settings/view_settings.php', $data);
    }
    
    public function system_check() {
        $data = array();
      
        $all_tables=$this->model_settings->getAllDatabases();
        $all_tables_tmp=array();
        foreach ($all_tables as $value) {
            $all_tables_tmp[] = $value[0];
        }
        
        
        $uiform_tbs=array();
        $uiform_tbs[] = $this->wpdb->prefix . "uiform_form";
        $uiform_tbs[] = $this->wpdb->prefix . "uiform_form_records";
        $uiform_tbs[] = $this->wpdb->prefix . "uiform_fields";
        $uiform_tbs[] = $this->wpdb->prefix . "uiform_fields_type";
        $uiform_tbs[] = $this->wpdb->prefix . "uiform_settings";
        
        
        //tables
        $name_tb=array();
        $name_tb[$this->wpdb->prefix . "uiform_form"]="Forms";
        $name_tb[$this->wpdb->prefix . "uiform_form_records"]="Records";
        $name_tb[$this->wpdb->prefix . "uiform_fields"]="Fields";
        $name_tb[$this->wpdb->prefix . "uiform_fields_type"]="Types";
        $name_tb[$this->wpdb->prefix . "uiform_settings"]="Settings";

        
        
        $uiform_tbs_tmp=array();
        foreach ($uiform_tbs as $value) {
           $tmp_tb=array();
            $tmp_tb['table']=$name_tb[$value];
            (in_array($value, $all_tables_tmp))?$tmp_tb['status']=1:$tmp_tb['status']=0;
            $uiform_tbs_tmp[]=$tmp_tb; 
        }
        
        $data['database_int']=$uiform_tbs_tmp;
            
        echo self::loadPartial('layout.php', 'formbuilder/views/settings/system_check.php', $data);
    }
    
    
    public function backup_settings() {
        $data = array();
        $dir = UIFORM_FORMS_DIR . '/backups/';
        $data_files=array();
        if (is_dir($dir)){
            $getDir = dir($dir);
            while (false !== ($file = $getDir->read())){
                
                if ($file != "." && $file != ".." && $file != "index.php"){
                $temp_file=array();    
                $temp_file['file_name']=$file;
                $temp_file['file_url']=UIFORM_FORMS_URL . '/backups/' . $file;
                $temp_file['file_date']=date("F d Y H:i:s.", filemtime($dir.$file));
                $temp_file['file_size']=Uiform_Form_Helper::human_filesize(filesize($dir.$file));
                
                $data_files[]=$temp_file;
                }
            }
        }
        $data['files'] =$data_files;
        echo self::loadPartial('layout.php', 'formbuilder/views/settings/backup_settings.php', $data);
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
