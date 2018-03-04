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
if (class_exists('Uiform_Fb_Controller_Records')) {
    return;
}

/**
 * Controller Records class
 *
 * @category  PHP
 * @package   Rocket_form
 * @author    Softdiscover <info@softdiscover.com>
 * @copyright 2013 Softdiscover
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version   Release: 1.00
 * @link      http://wordpress-form-builder.zigaform.com/
 */
class Uiform_Fb_Controller_Records extends Uiform_Base_Module {

    const VERSION = '0.1';

    private $model_record = "";
    private $model_fields = "";
    private $formsmodel = "";
    private $pagination = "";
    var $per_page = 5;
    private $wpdb = "";
    protected $modules;

    /*
     * Magic methods
     */

    /**
     * Constructor
     *
     * @mvc Controller
     */
    protected function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->model_record = self::$_models['formbuilder']['form_records'];
        $this->formsmodel = self::$_models['formbuilder']['form'];
        $this->model_fields = self::$_models['formbuilder']['fields'];

        // ajax for deleting form
        add_action('wp_ajax_rocket_fbuilder_delete_records', array(&$this, 'ajax_delete_record'));
        // ajax for loading forms
        add_action('wp_ajax_rocket_fbuilder_load_records_byform', array(&$this, 'ajax_load_record_byform'));
        // custom report
        add_action('wp_ajax_rocket_fbuilder_creport_byform', array(&$this, 'ajax_load_customreport'));
        // save custom report
        add_action('wp_ajax_rocket_fbuilder_creport_savefields', array(&$this, 'ajax_load_savereport'));
        // view charts
        add_action('wp_ajax_rocket_fbuilder_loadchart_byform', array(&$this, 'ajax_load_viewchart'));
    }

    public function ajax_load_viewchart() {
        
        check_ajax_referer( 'zgfm_ajax_nonce', 'zgfm_security' );
        
        $form_id = (isset($_POST['form_id']) && $_POST['form_id']) ? Uiform_Form_Helper::sanitizeInput($_POST['form_id']) : 0;

        $data_chart = $this->model_record->getChartDataByIdForm($form_id);

        $data = array();
        $data['data'] = $data_chart;
        header('Content-Type: application/json');
        echo json_encode($data);
        wp_die();
    }

    public function ajax_load_savereport() {
        
        check_ajax_referer( 'zgfm_ajax_nonce', 'zgfm_security' );
        
        $form_id = (isset($_POST['form_id']) && $_POST['form_id']) ? Uiform_Form_Helper::sanitizeInput($_POST['form_id']) : 0;
        $data_fields = (isset($_POST['data']) && !empty($_POST['data'])) ? array_map(array('Uiform_Form_Helper', 'sanitizeRecursive'), $_POST['data']) : array();
        $data_fields2 = (isset($_POST['data2']) && !empty($_POST['data2'])) ? array_map(array('Uiform_Form_Helper', 'sanitizeRecursive'), $_POST['data2']) : array();
        
        /* update all fields by form */
        $where = array('form_fmb_id' => $form_id);
        $data = array('fmf_status_qu' => 0);
        $this->wpdb->update($this->model_fields->table, $data, $where);
        if (!empty($data_fields)) {
            foreach ($data_fields as $value) {
                $where = array('form_fmb_id' => $form_id, 'fmf_uniqueid' => $value);
                $data = array('fmf_status_qu' => 1);
                $this->wpdb->update($this->model_fields->table, $data, $where);
            }
            
            //update order for all fields according to form
            if (!empty($data_fields2)) {
                foreach ($data_fields2 as $value) {
                    $where = array('form_fmb_id' => $form_id, 'fmf_uniqueid' => $value['name']);
                    $data = array('order_rec' => $value['value']);
                    
                    $this->wpdb->update($this->model_fields->table, $data, $where);
                     
                }
            }
        }
        wp_die();
    }

    public function ajax_load_customreport() {
        
        check_ajax_referer( 'zgfm_ajax_nonce', 'zgfm_security' );
        
        $form_id = (isset($_POST['form_id']) && $_POST['form_id']) ? Uiform_Form_Helper::sanitizeInput($_POST['form_id']) : 0;

        $all_fields = $this->model_record->getAllFieldsForReport($form_id);

        $data = array();
        $data['list_fields'] = $all_fields;

        $textfield_tmp = self::render_template('formbuilder/views/records/custom_report_getAllfields.php', $data);
        echo $textfield_tmp;
        wp_die();
    }

    public function view_charts() {
        $data = array();
        $data['list_forms'] = $this->formsmodel->getListForms();
        echo self::loadPartial('layout.php', 'formbuilder/views/records/view_charts.php', $data);
    }

    public function custom_report() {
        $data = array();
        $data['list_forms'] = $this->formsmodel->getListForms();
        echo self::loadPartial('layout.php', 'formbuilder/views/records/custom_report.php', $data);
    }

    public function ajax_load_record_byform() {
        
        check_ajax_referer( 'zgfm_ajax_nonce', 'zgfm_security' );
        
        $form_id = (isset($_POST['form_id']) && $_POST['form_id']) ? Uiform_Form_Helper::sanitizeInput($_POST['form_id']) : 0;
        
        //records to show
        $name_fields = $this->model_record->getNameFieldEnabledByForm($form_id);
        $data = array();
        $data['datatable_head'] = $name_fields;
        $data['datatable_body'] = $this->model_record->getDetailRecord($name_fields,$form_id);
        
        $textfield_tmp = self::render_template('formbuilder/views/records/list_records_getdatatable.php', $data);
        echo $textfield_tmp;
        wp_die();
    }

    public function ajax_delete_record() {
        
        check_ajax_referer( 'zgfm_ajax_nonce', 'zgfm_security' );
        
        $form_id = (isset($_POST['rec_id']) && $_POST['rec_id']) ? Uiform_Form_Helper::sanitizeInput($_POST['rec_id']) : 0;
        $where = array(
            'fbh_id' => $form_id
        );
        $data = array(
            'flag_status' => 0
        );
        $this->wpdb->update($this->model_record->table, $data, $where);
    }

    public function info_records_byforms() {
        $data = array();
        $data['list_forms'] = $this->formsmodel->getListForms();
        echo self::loadPartial('layout.php', 'formbuilder/views/records/list_records_byforms.php', $data);
    }

    public function info_record() {
        $id_rec = (isset($_GET['id_rec']) && $_GET['id_rec']) ? Uiform_Form_Helper::sanitizeInput($_GET['id_rec']) : 0;
        $name_fields = $this->model_record->getNameField($id_rec);
        $form_data = $this->model_record->getFormDataById($id_rec);
        
        $name_fields_check = array();
        foreach ($name_fields as $value) {
            $name_fields_check[$value->fmf_uniqueid] = $value->fieldname;
        }
        $data_record = $this->model_record->getRecordById($id_rec);
        $record_user = json_decode($data_record->fbh_data, true);
        $new_record_user = array();
        foreach ($record_user as $key => $value) {
            if (isset($name_fields_check[$key])) {
                $key = $name_fields_check[$key];
            }
            
                $new_record_user[] = array('field' => $value['label'], 'value' => $value['input']);
            
            
        }
        $data = array();
        $data2=array();
        $data['record_id'] = $id_rec;
        $data['record_info'] = $data2['record_info']=$new_record_user;
        $data['info_date'] = $data2['info_date']=date("F j, Y, g:i a", strtotime($data_record->created_date));
        $data['info_ip'] = $data2['info_ip']=$data_record->created_ip;
        require_once( UIFORM_FORMS_DIR . '/helpers/clientsniffer.php' );
        $data['info_useragent'] = $data2['info_useragent'] = zgfm_clientSniffer::test(array($data_record->fbh_user_agent));
        $data['info_referer'] = $data2['info_referer'] = $data_record->fbh_referer;
        $data['form_name'] = $data2['form_name'] = $form_data->fmb_name;
        $data2['info_labels']=array(
            'title'=>__('Entry information','FRocket_admin'),
            'info_submitted'=>__('Submitted form data','FRocket_admin'),
            'info_additional'=>__('Additional info','FRocket_admin'),
            'info_date'=>__('Date','FRocket_admin'),
            'info_ip'=>__('IP','FRocket_admin'),
            'info_pc'=>__('Client PC info','FRocket_admin'),
            'info_frmurl'=>__('Form URL','FRocket_admin'),
            'form_name'=>__('Form name','FRocket_admin')
        );
        $data['info_export']= Uiform_Form_Helper::base64url_encode(json_encode($data2));
        
        echo self::loadPartial('layout.php', 'formbuilder/views/records/info_record.php', $data);
    }
    
    public function list_records() {

        require_once( UIFORM_FORMS_DIR . '/classes/Pagination.php');
        $this->pagination = new CI_Pagination();
        $offset = (isset($_GET['offset']) && $_GET['offset']) ? Uiform_Form_Helper::sanitizeInput($_GET['offset']) : 0;
        //list all forms
        $data = $config = array();
        $config['base_url'] = admin_url() . '?page=zgfm_form_builder&mod=formbuilder&controller=records&action=list_records';
        $config['total_rows'] = $this->model_record->CountRecords();
        $config['per_page'] = $this->per_page;
        $config['first_link'] = 'First';
        $config['last_link'] = 'Last';
        $config['full_tag_open'] = '<ul class="pagination pagination-sm">';
        $config['full_tag_close'] = '</ul>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li><span>';
        $config['cur_tag_close'] = '</span></li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['page_query_string'] = true;
        $config['query_string_segment'] = 'offset';

        $this->pagination->initialize($config);
        // If the pagination library doesn't recognize the current page add:
        $this->pagination->cur_page = $offset;
        $data['query'] = $this->model_record->getListRecords($this->per_page, $offset);
        $data['pagination'] = $this->pagination->create_links();

        echo self::loadPartial('layout.php', 'formbuilder/views/records/list_records.php', $data);
    }

    
    public function csv_showAllForms($form_id){
        require_once(UIFORM_FORMS_DIR . '/helpers/exporttocsv.php');
        $name_fields = $this->model_record->getNameFieldEnabledByForm($form_id);
        $tmp_data = array();
        $tmp_data['datatable_head'] = $name_fields;
        $tmp_data['datatable_body'] = $this->model_record->getDetailRecord($name_fields,$form_id);
       
       $data=array();
       $tmp_ar=array();
       foreach ($tmp_data['datatable_head'] as $value) {
           $tmp_ar[]=$value->fieldname;
       }
       $data[]=$tmp_ar;
       
       foreach ($tmp_data['datatable_body'] as $key => $value) {
           $tmp_ar=array();
            foreach ($value as $key=>$value2){
                if($key!='fbh_id'){
                    $tmp_ar[]=$value2;
                }
                
            }
            $data[]=$tmp_ar;
       }
       
        $tsv = new ExportDataCSV('browser');
        $tsv->filename = "csv_".$form_id.".csv";
        
        $tsv->initialize();
        foreach($data as $row) {
                $tsv->addRow($row);
        }
        $tsv->finalize();
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
