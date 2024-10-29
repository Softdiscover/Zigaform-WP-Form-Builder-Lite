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
if (!defined('ABSPATH')) {
    exit('No direct script access allowed');
}
if (class_exists('Uiform_Fb_Controller_Forms')) {
    return;
}

use \Zigaform\Admin\List_data;

/**
 * Controller Form class
 *
 * @category  PHP
 * @package   Rocket_form
 * @author    Softdiscover <info@softdiscover.com>
 * @copyright 2013 Softdiscover
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version   Release: 1.00
 * @link      https://wordpress-form-builder.zigaform.com/
 */
class Uiform_Fb_Controller_Forms extends Uiform_Base_Module
{

    const VERSION = '0.1';

    private $formsmodel     = '';
    private $model_fields   = '';
    private $model_form_log = '';
    private $field_contr    = '';
    private $pagination     = '';
    private $per_page           = 20;
    private $wpdb           = '';
    protected $modules;
    private $saved_form_id         = '';
    private $current_data_addon    = array();
    private $current_data_form     = array();
    private $current_data_num_tabs = array();
    private $current_data_tab_cont = array();
    private $current_data_steps    = array();
    private $current_data_skin     = array();
    private $current_data_wizard   = array();
    private $current_data_onsubm   = array();
    private $current_data_main     = array();
    private $current_data_progressbar = [];
    private $saveform_clogic       = array();
    private $is_multistep         = false;
    private $current_data_conn = [];
    private $model_addon_details;

    private $current_mm_form_init;
    // private $current_mm_form_child_id;
    private $current_mm_children;

    /**
     * @var
     */
    public $gen_post_src;

    /*
     * Magic methods
     */

    /**
     * Constructor
     *
     * @mvc Controller
     */
    protected function __construct()
    {

        /*
         require_once( UIFORM_FORMS_DIR . '/modules/formbuilder/models/uiform-model-form.php');
          $this->modules = array(
          'formbuilder'    => array('contr'=>array('fields'=>Uiform_Fb_Controller_Fields::get_instance()),
          'model'=>array('form'=>new Uiform_Model_Form()))
          ); */
        $this->formsmodel     = self::$_models['formbuilder']['form'];
        $this->model_fields   = self::$_models['formbuilder']['fields'];
        $this->model_form_log = self::$_models['formbuilder']['form_log'];
        $this->model_addon_details = self::$_models['addon']['addon_details'];
        // $uibootstrap=new Uiform_Bootstrap();
        // $this->fields_contr=$uibootstrap->modules['formbuilder']['fields'];

        global $wpdb;
        $this->wpdb = $wpdb;

        add_action('wp_ajax_rocket_fbuilder_multistep_save_childform', array(&$this, 'ajax_save_childform'));
        add_action('wp_ajax_rocket_fbuilder_multistep_save_parentform', array(&$this, 'ajax_save_parentform'));
        add_action('wp_ajax_rocket_fbuilder_multistep_build_front', array(&$this, 'ajax_build_multistep'));


        // ajax for saving form
        add_action('wp_ajax_rocket_fbuilder_save_form', array(&$this, 'ajax_save_form'));

        // ajax for saving form
        add_action('wp_ajax_rocket_fbuilder_save_newform', array(&$this, 'ajax_save_newform'));

        // ajax for saving form
        // add_action('wp_ajax_rocket_fbuilder_save_form_updopts', array(&$this, 'ajax_save_form_updateopts'));

        // ajax for preview_clogic_graph
        add_action('wp_ajax_rocket_fbuilder_preview_clogic_graph', array(&$this, 'ajax_preview_clogic_graph'));

        // refreshing duplication
        add_action('wp_ajax_rocket_fbuilder_refreshpreviewpanel', array(&$this, 'ajax_refresh_previewpanel'));

        // load child form
        add_action('wp_ajax_rocket_fbuilder_mm_load_childform', array(&$this, 'ajax_mm_load_childform'));

        // load form
        add_action('wp_ajax_rocket_fbuilder_load_form', array(&$this, 'ajax_load_form'));
        // load form
        add_action('wp_ajax_rocket_fbuilder_loadtemplate', array(&$this, 'ajax_load_templateform'));
        // get image thumbnail
        add_action('wp_ajax_rocket_fbuilder_getthumbimg', array(&$this, 'ajax_load_getthumbimg'));
        // load preview form
        add_action('wp_ajax_rocket_fbuilder_load_preview_form', array(&$this, 'ajax_load_preview_form'));
        // delete form
        add_action('wp_ajax_rocket_fbuilder_delete_form', array(&$this, 'ajax_delete_form_byid'));

        // delete form
        add_action('wp_ajax_rocket_fbuilder_delete_trashform', array(&$this, 'ajax_delete_trashform_byid'));

        // list form update status
        add_action('wp_ajax_rocket_fbuilder_listform_updatest', array(&$this, 'ajax_listform_updatest'));

        // list form update status
        add_action('wp_ajax_rocket_fbuilder_list_trashform_updatest', array(&$this, 'ajax_list_trashform_updatest'));

        // dupicate form
        add_action('wp_ajax_rocket_fbuilder_listform_duplicate', array(&$this, 'ajax_listform_duplicate'));
        // export form
        add_action('wp_ajax_rocket_fbuilder_export_form', array(&$this, 'ajax_load_export_form'));

        // import form
        add_action('wp_ajax_rocket_fbuilder_import_code_form', array(&$this, 'ajax_load_import_code_form'));
        add_action('wp_ajax_rocket_fbuilder_import_template_form', array(&$this, 'ajax_load_import_template_form'));

        // import form (deprecated)
        add_action('wp_ajax_rocket_fbuilder_import_form', array(&$this, 'ajax_load_import_form'));

        // modal get shortcodes
        add_action('wp_ajax_rocket_fbuilder_modal_form_getshorcodes', array(&$this, 'ajax_modal_form_getshorcodes'));
        // modal show success message
        add_action('wp_ajax_rocket_fbuilder_form_showmodalsuccess', array(&$this, 'ajax_modal_form_showmodalsuccess'));

        // rollback modal
        add_action('wp_ajax_rocket_fbuilder_rollback_openmodal', array(&$this, 'ajax_rollback_openmodal'));
        // rollback process
        add_action('wp_ajax_rocket_fbuilder_rollback_process', array(&$this, 'ajax_rollback_process'));
        // show variables
        add_action('wp_ajax_rocket_fbuilder_variables_openmodal', array(&$this, 'ajax_variables_openmodal'));

        // show variables email page
        add_action('wp_ajax_rocket_fbuilder_variables_emailpage', array(&$this, 'ajax_variables_emailpage'));

        // integrity
        add_action('wp_ajax_rocket_fbuilder_integrity_openmodal', array(&$this, 'ajax_integrity_openmodal'));

        // email send sample
        add_action('wp_ajax_rocket_fbuilder_email_sendsample', array(&$this, 'ajax_email_sendsample'));

        // pdf show sample
        add_action('wp_ajax_rocket_fbuilder_pdf_showsample', array(&$this, 'ajax_pdf_showsample'));

        // handle form list
        add_action('wp_ajax_zgfm_fbuilder_formlist_filter', array(&$this, 'ajax_formlist_sendfilter'));

        // handle trash form list
        add_action('wp_ajax_zgfm_fbuilder_trashformlist_filter', array(&$this, 'ajax_trashformlist_sendfilter'));

        // refresh list form table
        add_action('wp_ajax_zgfm_fbuilder_formlist_refresh', array(&$this, 'ajax_formlist_sendfilter'));

        //ms filters
        add_filter('uifm_ms_render_field_front', [&$this, 'ms_filter_render_field_front'], 1, 4);

        add_filter('zgfm_front_ms_form_outertop', [&$this, 'ms_filter_form_outertop'], 1, 1);
        add_filter('zgfm_front_ms_form_innertop', [&$this, 'ms_filter_form_innertop'], 1, 1);


        add_filter('zgfm_front_ms_aditional_css', [&$this, 'ms_filter_form_css'], 1, 1);
        add_filter('zgfm_front_ms_aditional_js', [&$this, 'ms_filter_form_js'], 1, 1);
        
    }
    public function ms_filter_form_js($default)
    {
        $steps = $this->current_data_progressbar['steps'];
        $newArr = [];
        foreach ($steps as $key => $value) {
            foreach ($value['forms'] as $key2 => $value2) {
                $newArr[$value2] = $value['id'];
            }
        }


        $default['progressbar'] = [
            'steps' => $this->current_data_progressbar['steps'],
            'forms' => $newArr
        ];
        return $default;
    }
    public function ms_filter_form_css($formid)
    {

        $data           = array();
        $data['idform'] = $formid;
        $data['wizard'] = $this->current_data_progressbar;

        return self::render_template('formbuilder/views/forms/formhtml_css_progressbar.php', $data);
    }

    private function progressBarGenerateFront($progressBar)
    {
        $content = '<div class="zgfm-progress-bar">';
        switch ($progressBar['theme_type']) {
            case 'numbers':
                $type = 'uiform-wiztheme1';
                break;
            case 'numbers2':
                $type = 'uiform-wiztheme3';
                break;
            case 'default':
            default:
                $type = 'uiform-wiztheme0';
                break;
        }
        $content .= sprintf('<div class="%s">', $type);
        $content .= '<ul class="zgfm-pbar-steps">';
        $counter = 1;
        foreach ($progressBar['steps'] as $key => $value) {
            $className = "";

            if ($counter === 1) {
                $className = "uifm-current";
            }


            switch ($progressBar['theme_type']) {

                case 'numbers2':
                    $content .= sprintf('
                    <li data-index="%s" class="%s"></li>
                    ', $value['id'], $className);

                    break;

                default:
                    $content .= sprintf('
                    <li data-index="%s" class="%s">
                            <a>
                                <span class="uifm-number">%s</span>
                                <span class="uifm-title">%s</span>
                            </a>
                        </li>
                    ', $value['id'], $className, $counter, $value['title']);
                    break;
            }

            $counter++;
        }
        $content .= '</ul>';
        $content .= '</div>';

        $content .= '</div>';



        return $content;
    }
    public function ms_filter_form_innertop($default)
    {

        $progressBar = $this->current_data_progressbar;
        if (intval($progressBar['enable_st']) !== 1) {
            return $default;
        }

        if (strval($progressBar['position']) !== "innertop") {
            return $default;
        }

        return $this->progressBarGenerateFront($progressBar);
    }
    public function ms_filter_form_outertop($default)
    {

        $progressBar = $this->current_data_progressbar;
        if (intval($progressBar['enable_st']) !== 1) {
            return $default;
        }

        if (strval($progressBar['position']) !== "outertop") {
            return $default;
        }

        return $this->progressBarGenerateFront($progressBar);
    }



    public function ms_filter_render_field_front($default, $field_id = 0, $type = 0, $value = '')
    {

        if ($this->is_multistep === true) {

            switch (intval($type)) {
                case 9:
                    $default = sprintf("uiform_fields[%s][%s][%s]", $this->saved_form_id, $field_id, $value);
                    break;
                case 11:
                    $default = sprintf("uiform_fields[%s][%s][]", $this->saved_form_id, $field_id);
                    break;

                default:
                    $default = sprintf("uiform_fields[%s][%s]", $this->saved_form_id, $field_id);
                    break;
            }
        }

        return $default;
    }

    public function ajax_pdf_showsample()
    {

        check_ajax_referer('zgfm_ajax_nonce', 'zgfm_security');

        $full_page = (isset($_POST['full_page'])) ? Uiform_Form_Helper::sanitizeInput(trim($_POST['full_page'])) : '';
        $form_id   = (isset($_POST['form_id'])) ? Uiform_Form_Helper::sanitizeInput(trim($_POST['form_id'])) : '';
        $message   = (isset($_POST['message'])) ? Uiform_Form_Helper::sanitizeInput_html($_POST['message']) : '';
        $message   = urldecode($message);

        $data2 = array();

        $pdf_paper_size = 'a4';
        $pdf_paper_orie = 'landscape';

        if (intval($form_id) > 0) {
            $form_data        = $this->formsmodel->getFormById_2($form_id);
            $form_data_onsubm = json_decode($form_data->fmb_data2, true);
            $pdf_charset      = (isset($form_data_onsubm['main']['pdf_charset'])) ? $form_data_onsubm['main']['pdf_charset'] : '';
            $pdf_font         = (isset($form_data_onsubm['main']['pdf_font'])) ? urldecode($form_data_onsubm['main']['pdf_font']) : '';
            $pdf_paper_size   = (isset($form_data_onsubm['main']['pdf_paper_size'])) ? $form_data_onsubm['main']['pdf_paper_size'] : 'a4';
            $pdf_paper_orie   = (isset($form_data_onsubm['main']['pdf_paper_orie'])) ? $form_data_onsubm['main']['pdf_paper_orie'] : 'landscape';

            $data2['font']    = $pdf_font;
            $data2['charset'] = $pdf_charset;
        } else {
            $data2['font']    = '2';
            $data2['charset'] = 'UTF-8';
        }

        $data2['head_extra'] = '';
        $data2['content']    = $message;

        $pos  = strpos($message, '</body>');
        $pos2 = strpos($message, '</html>');

        if ($pos === false && $pos2 === false) {
            $full_page = 0;
        } else {
            $full_page = 1;
        }

        $data2['html_wholecont'] = $full_page;
        $content                 = self::render_template('formbuilder/views/forms/pdf_global_template.php', $data2);

        $file_name = 'zgfm_pdf_sample';

        // remove previous pdf sample
        @unlink(UIFORM_FORMS_DIR . '/temp/' . $file_name . '.pdf');

        $output = uifm_generate_pdf($content, $file_name, $pdf_paper_size, $pdf_paper_orie, false);
        $status = '0';
        if (file_exists($output)) {
            $status = '1';
        } else {
            $status = '0';
        }

        $json             = array();
        $json['status']   = $status;
        $json['pdf_name'] = $file_name;
        $json['pdf_dir']  = $output;
        $json['dir']      = UIFORM_FORMS_DIR;
        $json['pdf_url']  = UIFORM_FORMS_URL . '/temp/' . $file_name . '.pdf';

        // return data to ajax callback
        header('Content-Type: application/json');
        echo json_encode($json);
        wp_die();
    }

    public function ajax_email_sendsample()
    {

        check_ajax_referer('zgfm_ajax_nonce', 'zgfm_security');

        $full_page = (isset($_POST['full_page'])) ? Uiform_Form_Helper::sanitizeInput(trim($_POST['full_page'])) : '';
        $message   = (isset($_POST['message'])) ? Uiform_Form_Helper::sanitizeInput_html($_POST['message']) : '';
        $message   = urldecode($message);
        $email_to  = (isset($_POST['email_to'])) ? Uiform_Form_Helper::sanitizeInput($_POST['email_to']) : '';

        $mail_template_msg = self::render_template(
            'formbuilder/views/frontend/mail_global_template.php',
            array(
                'content'        => $message,
                'html_wholecont' => $full_page,
            ),
            'always'
        );

        $data_mail                 = array();
        $data_mail['from_mail']    = $email_to;
        $data_mail['from_name']    = 'test';
        $data_mail['message']      = $mail_template_msg;
        $data_mail['subject']      = 'Zigaform - this is just a test';
        $data_mail['attachments']  = array();
        $data_mail['to']           = $email_to;
        $data_mail['cc']           = '';
        $data_mail['bcc']          = '';
        $data_mail['mail_replyto'] = '';
        // $mail_errors=$this->process_mail($data_mail);
        $mail_errors = self::$_modules['formbuilder']['frontend']->process_mail($data_mail);

        $json           = array();
        $json['status'] = ($mail_errors === true) ? 1 : 0;

        // return data to ajax callback
        header('Content-Type: application/json');
        echo json_encode($json);
        wp_die();
    }

    public function ajax_rollback_process()
    {

        check_ajax_referer('zgfm_ajax_nonce', 'zgfm_security');

        $log_id = (isset($_POST['log_id'])) ? Uiform_Form_Helper::sanitizeInput(trim($_POST['log_id'])) : '';
        $isMultistep = (isset($_POST['is_multistep'])) ? Uiform_Form_Helper::sanitizeInput(trim($_POST['is_multistep'])) : '0';

        $query_obj = $this->model_form_log->getLogById($log_id);

        if (intval($isMultistep) === 1) {
            $children = $this->model_form_log->getLogChildrenByParentId($log_id);
            $data                     = array();
            $data['parent']         = [
                'data' => json_decode($query_obj->log_frm_data, true),
                'name' => $query_obj->log_frm_name
            ];

            $childrenFiltered = [];
            foreach ($children as $key => $value) {
                $childrenFiltered[] = [
                    'data' => json_decode($value->log_frm_data, true),
                    'name' => $value->log_frm_name,
                    'id' => $value->log_frm_id,
                    'preview' => $value->log_frm_html_backend

                ];
            }

            $data['children'] = [
                'data' => $childrenFiltered
            ];
        } else {
            $json = array();
            /*
             $json['log_frm_data'] =  $query_obj->log_frm_data;
            $json['log_frm_name'] =  $query_obj->log_frm_name;
            $json['log_frm_html_backend'] =  $query_obj->log_frm_html_backend;
            $json['log_frm_id'] =  $query_obj->log_frm_id;  */

            $data                     = array();
            $data['fmb_data']         = json_decode($query_obj->log_frm_data, true);
            $data['fmb_name']         = $query_obj->log_frm_name;
            $data['fmb_html_backend'] = Uiform_Form_Helper::encodeHex($query_obj->log_frm_html_backend);
        }



        $json['data'] = $data;

        // return data to ajax callback
        header('Content-Type: application/json');
        echo json_encode($json);
        wp_die();
    }


    public function ajax_rollback_openmodal()
    {

        check_ajax_referer('zgfm_ajax_nonce', 'zgfm_security');

        $form_id = (isset($_POST['form_id'])) ? Uiform_Form_Helper::sanitizeInput(trim($_POST['form_id'])) : '';

        $data = array();

        $query_obj = $this->model_form_log->getAvailableLogById($form_id);

        $log_array = array();
        foreach ($query_obj as $key => $value) {
            $temp                 = array();
            $temp['form_name']    = $value->log_frm_name;
            $temp['created_date'] = date('d-m-Y h:m:s', strtotime($value->updated_date));
            $temp['log_id']       = $value->log_id;
            $log_array[]          = $temp;
        }

        $data['logs'] = $log_array;

        $json                 = array();
        $json['modal_header'] = '<h3>' . __('Rollback Form', 'FRocket_admin') . '</h3>';
        $json['modal_body']   = self::render_template('formbuilder/views/forms/ajax_rollback_openmodal.php', $data, 'always');
        $json['modal_footer'] = self::render_template('formbuilder/views/forms/modal1_footer.php', $data);

        // return data to ajax callback
        header('Content-Type: application/json');
        echo json_encode($json);
        wp_die();
    }


    public function ajax_integrity_openmodal()
    {

        // check_ajax_referer( 'zgfm_ajax_nonce', 'zgfm_security' );

        $form_id = (isset($_POST['form_id'])) ? Uiform_Form_Helper::sanitizeInput(trim($_POST['form_id'])) : '';

        $data = array();

        $json                 = array();
        $json['modal_header'] = '<h3>' . __('Error message', 'FRocket_admin') . '</h3>';

        $json['modal_body']   = self::render_template('formbuilder/views/forms/ajax_integrity_openmodal.php', $data, 'always');
        $json['modal_footer'] = self::render_template('formbuilder/views/forms/modal1_footer.php', $data);

        // return data to ajax callback
        header('Content-Type: application/json');
        echo json_encode($json);
        die();
    }

    public function ajax_variables_openmodal()
    {

        check_ajax_referer('zgfm_ajax_nonce', 'zgfm_security');
        $data     = array();
        $form_id  = (isset($_POST['form_id'])) ? Uiform_Form_Helper::sanitizeInput(trim($_POST['form_id'])) : '';
        $fmb_data = (isset($_POST['form_data'])) ? urldecode(Uiform_Form_Helper::sanitizeInput_html($_POST['form_data'])) : '';
        // $fmb_data = str_replace("\'", "'",$fmb_data);
        $fmb_data         = (isset($fmb_data) && $fmb_data) ? array_map(array('Uiform_Form_Helper', 'sanitizeRecursive_html'), json_decode($fmb_data, true)) : array();
        $data['fmb_data'] = $fmb_data;

        $json                 = array();
        $json['modal_header'] = '<h3>' . __('Form variables', 'FRocket_admin') . '</h3>';
        $json['modal_body']   = self::render_template('formbuilder/views/forms/ajax_variables_openmodal.php', $data, 'always');
        $json['modal_footer'] = self::render_template('formbuilder/views/forms/modal1_footer.php', array());

        // return data to ajax callback
        header('Content-Type: application/json');
        echo json_encode($json);
        wp_die();
    }

    public function ajax_variables_emailpage()
    {

        check_ajax_referer('zgfm_ajax_nonce', 'zgfm_security');
        $data     = array();
        $form_id  = (isset($_POST['form_id'])) ? Uiform_Form_Helper::sanitizeInput(trim($_POST['form_id'])) : '';
        $fmb_data = (isset($_POST['form_data'])) ? urldecode(Uiform_Form_Helper::sanitizeInput_html($_POST['form_data'])) : '';
        if (!empty($fmb_data)) {
            $fmb_data = (isset($fmb_data) && $fmb_data) ? array_map(array('Uiform_Form_Helper', 'sanitizeRecursive_html'), json_decode($fmb_data, true)) : array();
        }

        $data['fmb_data'] = $fmb_data;

        $json            = array();
        $json['message'] = self::render_template('formbuilder/views/forms/ajax_variables_emailpage.php', $data, 'always');

        // return data to ajax callback
        header('Content-Type: application/json');
        echo json_encode($json);
        wp_die();
    }

    public function ajax_load_templateform()
    {

        check_ajax_referer('zgfm_ajax_nonce', 'zgfm_security');

        $number        = ($_POST['number']) ? Uiform_Form_Helper::sanitizeInput(trim($_POST['number'])) : '';
        $fallback_file = file_get_contents(UIFORM_FORMS_DIR . '/assets/backend/json/template_' . $number . '.json');
        header('Content-Type: application/json');
        echo $fallback_file;
        wp_die();
    }

    public function ajax_load_getthumbimg()
    {

        check_ajax_referer('zgfm_ajax_nonce', 'zgfm_security');

        $id_img            = ($_POST['img_id']) ? Uiform_Form_Helper::sanitizeInput($_POST['img_id']) : '';
        $img_full          = ($_POST['img_src_full']) ? Uiform_Form_Helper::sanitizeInput_html($_POST['img_src_full']) : '';
        $json              = array();
        $json['img_full']  = $img_full;
        $thumb             = wp_get_attachment_image_src($id_img, array(150, 150));
        $json['img_thumb'] = (!empty($thumb[0])) ? $thumb[0] : $img_full;
        header('Content-Type: application/json');
        echo json_encode($json);
        wp_die();
    }

    public function ajax_load_import_template_form()
    {
        check_ajax_referer('zgfm_ajax_nonce', 'zgfm_security');
        $slug                      = (isset($_POST['slug']) && $_POST['slug']) ? Uiform_Form_Helper::sanitizeInput($_POST['slug']) : '';

        $codeStored = file_get_contents(UIFORM_FORMS_DIR . '/assets/backend/templates/' . $slug . '.txt');
        $dump_form                     = unserialize(Uiform_Form_Helper::base64url_decode($codeStored));

        $this->processImportExportCode($dump_form, true);
    }

    private function processImportExportCode($dump_form, $is_template = false )
    {
        $redirectUrl = '';
        if ((isset($dump_form['app_ver']) && $dump_form['app_ver'] == '7.0.0') ||

        $is_template === true ||

            isset($dump_form['app_ver']) && version_compare($dump_form['app_ver'], '7.0.0') >= 0
        ) {


            $data_form                     = [];

            $data_form['fmb_html_backend'] = @$dump_form['form']['fmb_html_backend'];
            $data_form['fmb_name']         = @$dump_form['form']['fmb_name'].' - copy';
            $data_form['fmb_rec_tpl_html'] = @$dump_form['form']['fmb_rec_tpl_html'];
            $data_form['fmb_rec_tpl_st']   = @$dump_form['form']['fmb_rec_tpl_st'];
            $data_form['fmb_type']   = @$dump_form['form']['fmb_type'];
            $data_form['fmb_parent']   = @$dump_form['form']['fmb_parent'];

            if (isset($dump_form['type']) && intval($dump_form['type']) === 0) {
                //multistep
                $data_form['fmb_data']         = @$dump_form['form']['fmb_data'];
            }



            $this->wpdb->insert($this->formsmodel->table, $data_form);
            $idCreated     = $this->wpdb->insert_id;


            if (isset($dump_form['type']) && intval($dump_form['type']) === 1) {
                //multistep
                $storeChildIds = [];
                if (!empty(@$dump_form['children'])) {
                    foreach ($dump_form['children'] as $key2 => $child) {
                        $data_form                     = [];
                        $data_form['fmb_data']         = $child['fmb_data'];
                        $data_form['fmb_html_backend'] = $child['fmb_html_backend'];
                        $data_form['fmb_name']         = $child['fmb_name'];
                        $data_form['fmb_rec_tpl_html'] = $child['fmb_rec_tpl_html'];
                        $data_form['fmb_rec_tpl_st']   = $child['fmb_rec_tpl_st'];
                        $data_form['fmb_type']   = $child['fmb_type'];
                        $data_form['fmb_parent']   = $idCreated;
                        $this->wpdb->insert($this->formsmodel->table, $data_form);
                        $childIdCreated     = $this->wpdb->insert_id;
                        $storeChildIds[$child['fmb_id']] = $childIdCreated;
                    }
                }

                //save addons
                if (!empty($dump_form['addons'])) {

                    foreach ($dump_form['addons'] as $key => $value) {
                        $data_form2 = [];
                        $data_form2['add_name'] = $value['add_name'];
                        $data_form2['fmb_id'] = $idCreated;

                        if ($value['add_name'] == 'func_anim') {
                            $adetDataTmp = json_decode($value['adet_data'], true);
                            $newArr = [];
                            foreach ($adetDataTmp as $key2 => $value2) {

                                $newArr[$storeChildIds[$key2]] = $value2;
                            }
                            $data_form2['adet_data'] = json_encode($newArr);
                        } else {
                            $data_form2['adet_data'] = $value['adet_data'];
                        }

                        $data_form2['flag_status'] = $value['flag_status'];
                        $this->wpdb->insert($this->model_addon_details->table, $data_form2);
                    }
                }

                //update parent with new children ids
                $data_form = [];

                $tmpData1 = json_decode(@$dump_form['form']['fmb_data'], true);
                foreach ($tmpData1['data'] as $key3 => $value3) {
                    $tmpData1['data'][$key3]['data']['id'] = $storeChildIds[$tmpData1['data'][$key3]['data']['id']];
                }
                @$dump_form['form']['fmb_data'] = $tmpData1;
                $data_form['fmb_data']         = json_encode($dump_form['form']['fmb_data']);

                //multistep 
                $tmpData2 = json_decode($dump_form['form']['fmb_data2'], true);
                $tmpData2['initial'] = $storeChildIds[intval($tmpData2['initial'])];

                if (!empty($tmpData2['availableConnections'])) {
                    $newArr = new stdClass();
                    foreach ($tmpData2['availableConnections'] as $key => $value) {
                        $tmpkeys =  explode('__', $key);
                        $newKey = $storeChildIds[$tmpkeys[0]] . '__' . $storeChildIds[$tmpkeys[1]];

                        $value['start']['id'] = $storeChildIds[$value['start']['id']];
                        $value['end']['id'] = $storeChildIds[$value['end']['id']];

                        $newArr->$newKey = $value;
                    }
                    $tmpData2['availableConnections'] = $newArr;
                }

                if (!empty($tmpData2['availableNodes'])) {
                    $newArr = new stdClass();
                    foreach ($tmpData2['availableNodes'] as $key => $value) {
                        $newKey = $storeChildIds[$key];
                        $newArr->$newKey = $value;
                    }
                    $tmpData2['availableNodes'] = $newArr;
                }

                if (!empty($tmpData2['connections'])) {
                    $newArr = new stdClass();
                    foreach ($tmpData2['connections'] as $key => $value) {
                        $newKey = $storeChildIds[$key];

                        if (isset($value['inputs']) && count($value['inputs']) > 0) {
                            foreach ($value['inputs'] as $key2 => $value2) {

                                $value['inputs'][$key2]['form'] = $storeChildIds[$value2['form']];
                                $tmpkeys =  explode('__', $value2['conn']);
                                $value['inputs'][$key2]['conn'] = $storeChildIds[$tmpkeys[0]] . '__' . $storeChildIds[$tmpkeys[1]];
                            }
                        }
                        if (isset($value['outputs']) && count($value['outputs']) > 0) {
                            foreach ($value['outputs'] as $key2 => $value2) {

                                $value['outputs'][$key2]['form'] = $storeChildIds[$value2['form']];
                                $tmpkeys =  explode('__', $value2['conn']);
                                $value['outputs'][$key2]['conn'] = $storeChildIds[$tmpkeys[0]] . '__' . $storeChildIds[$tmpkeys[1]];
                            }
                        }

                        $newArr->$newKey = $value;
                    }
                    $tmpData2['connections'] =  $newArr;
                }
                if (!empty($tmpData2['progressBar'])) {
                    if (!empty($tmpData2['progressBar']['steps'])) {
                        //$newArr = new stdClass();
                        foreach ($tmpData2['progressBar']['steps'] as $key => $value) {
                            $newArr=[];
                            foreach ($value['forms'] as $key2 => $value2 ) {
                                $newArr[]= strval($storeChildIds[$value2]);
                            }
                           
                            $tmpData2['progressBar']['steps'][$key]['forms'] = $newArr;
                        }
                        
                    }   
                    
                    
                    if (!empty($tmpData2['progressBar']['progressBarAssigned'])) {
                        $newArr =[];
                        foreach ($tmpData2['progressBar']['progressBarAssigned'] as  $value) {
                             
                            $newArr[] = strval($storeChildIds[$value]);
                        
                            
                        }
                        $tmpData2['progressBar']['progressBarAssigned'] = $newArr;
                    }  
                }
                $dump_form['form']['fmb_data2'] =  $tmpData2;
                $data_form['fmb_data2']   = json_encode($dump_form['form']['fmb_data2']);
                $where = array(
                    'fmb_id' => $idCreated,
                );
                $this->wpdb->update($this->formsmodel->table, $data_form, $where);


                $redirectUrl = admin_url() . '?page=zgfm_form_builder&zgfm_mod=formbuilder&zgfm_contr=forms&zgfm_action=create_uiform&is_multistep=yes&form_id=' . $idCreated;
            } else {
                //single form new version

                //save addons
                if (!empty($dump_form['addons'])) {

                    foreach ($dump_form['addons'] as $key => $value) {
                        $data_form2 = [];
                        $data_form2['add_name'] = $value['add_name'];
                        $data_form2['fmb_id'] = $idCreated;

                        if ($value['add_name'] == 'func_anim') {
                            $adetDataTmp = json_decode($value['adet_data'], true);
                            $newArr = [];
                            foreach ($adetDataTmp as $key2 => $value2) {
                                $newIndex = $idCreated;
                                $newArr[$newIndex] = $value2;
                            }
                            $data_form2['adet_data'] = json_encode($newArr);
                        } else {

                            $data_form2['adet_data'] = $value['adet_data'];
                        }

                        $data_form2['flag_status'] = $value['flag_status'];
                        $this->wpdb->insert($this->model_addon_details->table, $data_form2);
                    }
                }
                $redirectUrl = admin_url() . '?page=zgfm_form_builder&zgfm_mod=formbuilder&zgfm_contr=forms&zgfm_action=create_uiform&form_id=' . $idCreated;
            }
        } else {
            //old
            $data_form                     = array();
            $data_form['fmb_data']         = @$dump_form['fmb_data'];
            $data_form['fmb_html_backend'] = @$dump_form['fmb_html_backend'];
            $data_form['fmb_name']         = @$dump_form['fmb_name'] . ' - copy';
            $data_form['fmb_rec_tpl_html'] = @$dump_form['fmb_rec_tpl_html'];
            $data_form['fmb_rec_tpl_st']   = @$dump_form['fmb_rec_tpl_st'];
            $data_form['fmb_type']   = 0;
            $data_form['fmb_parent']   = 0;

            $this->wpdb->insert($this->formsmodel->table, $data_form);
            $idCreated     = $this->wpdb->insert_id;
            $redirectUrl = admin_url() . '?page=zgfm_form_builder&zgfm_mod=formbuilder&zgfm_contr=forms&zgfm_action=create_uiform&form_id=' . $idCreated;
        }



        $json                          = array();
        $json['redirect_url'] = $redirectUrl;
        header('Content-Type: application/json');
        echo json_encode($json);
        wp_die();
    }

    public function ajax_load_import_code_form()
    {

        check_ajax_referer('zgfm_ajax_nonce', 'zgfm_security');

        $imp_form                      = (isset($_POST['importcode']) && $_POST['importcode']) ? Uiform_Form_Helper::sanitizeInput($_POST['importcode']) : '';
        $dump_form                     = unserialize(Uiform_Form_Helper::base64url_decode($imp_form));

        $this->processImportExportCode($dump_form);
    }

    public function ajax_load_import_form()
    {

        check_ajax_referer('zgfm_ajax_nonce', 'zgfm_security');

        $imp_form                      = (isset($_POST['importcode']) && $_POST['importcode']) ? Uiform_Form_Helper::sanitizeInput($_POST['importcode']) : '';
        $dump_form                     = unserialize(Uiform_Form_Helper::base64url_decode($imp_form));
        $data_form                     = array();
        $data_form['fmb_data']         = json_decode($dump_form['fmb_data']);
        $data_form['fmb_html_backend'] = @$dump_form['fmb_html_backend'];
        $data_form['fmb_name']         = @$dump_form['fmb_name'];
        $data_form['fmb_rec_tpl_html'] = @$dump_form['fmb_rec_tpl_html'];
        $data_form['fmb_rec_tpl_st']   = @$dump_form['fmb_rec_tpl_st'];
        $json                          = array();
        $json['data']                  = $data_form;
        header('Content-Type: application/json');
        echo json_encode($json);
        wp_die();
    }

    public function ajax_preview_clogic_graph()
    {

        check_ajax_referer('zgfm_ajax_nonce', 'zgfm_security');

        $saveform_clogic = array();
        $fmb_data        = (isset($_POST['form_data'])) ? urldecode(Uiform_Form_Helper::sanitizeInput_html($_POST['form_data'])) : '';
        $fmb_data        = str_replace("\'", "'", $fmb_data);
        $fmb_data        = (isset($fmb_data) && $fmb_data) ? array_map(array('Uiform_Form_Helper', 'sanitizeRecursive_html'), json_decode($fmb_data, true)) : array();

        // creating again
        $steps_src        = $fmb_data['steps_src'];
        $tmp_var_typename = array();
        $tmp_var_fname    = array();
        $tmp_var_fstep    = array();
        if (!empty($steps_src)) {
            foreach ($steps_src as $tabindex => $fields) {
                if (!empty($fields)) {
                    foreach ($fields as $key => $value) {
                        $data                 = array();
                        $data['fmf_uniqueid'] = $value['id'];

                        $data['fmf_fieldname'] = isset($value['field_name']) ? $value['field_name'] : 'not defined';
                        $data['fmf_type_n']    = isset($value['type_n']) ? $value['type_n'] : 'not defined';

                        $data['type_fby_id'] = $value['type'];

                        $tmp_var_typename[$value['id']] = $data['fmf_type_n'];
                        $tmp_var_fname[$value['id']]    = $data['fmf_fieldname'];
                        $tmp_var_fstep[$value['id']]    = intval($tabindex) + 1;

                        if (isset($value['clogic']) && intval($value['clogic']['show_st']) === 1) {
                            $tmp_clogic                     = array();
                            $tmp_clogic['field_cond']       = $value['id'];
                            $tmp_clogic['field_cond_fname'] = $data['fmf_fieldname'];
                            $tmp_clogic['field_type_n']     = $data['fmf_type_n'];

                            $tmp_clogic['action'] = $value['clogic']['f_show'];

                            foreach ($value['clogic']['list'] as $key2 => $value2) {
                                if (empty($value2)) {
                                    unset($value['clogic']['list'][$key2]);
                                }
                            }
                            $tmp_clogic['list']        = array_filter($value['clogic']['list']);
                            $tmp_clogic['req_match']   = (intval($value['clogic']['f_all']) === 1) ? count($value['clogic']['list']) : 1;
                            $saveform_clogic['cond'][] = $tmp_clogic;
                        }
                    }
                }
            }
        }

        $clogic_src = $saveform_clogic;
        if (!empty($clogic_src)) {
            // get fires
            $fields_fire = array();
            foreach ($clogic_src['cond'] as $key => $value) {
                foreach ($value['list'] as $key2 => $value2) {
                    if (!empty($value2)) {
                        if (!isset($fields_fire[$value2['field_fire']]['list'][$value['field_cond']])) {
                            $fields_fire[$value2['field_fire']]['list'][] = $value['field_cond'];
                        }
                    } else {
                        unset($clogic_src['cond'][$key]['list'][$key2]);
                    }
                }
            }
            $saveform_clogic = $clogic_src;
            // field fires
            $logic_field_fire = array();
            foreach ($fields_fire as $key => $value) {
                $temp_logic                     = array();
                $temp_logic['field_fire']       = $key;
                $temp_logic['field_fire_typen'] = isset($tmp_var_typename[$key]) ? $tmp_var_typename[$key] : 'undefined';
                $temp_logic['field_fire_fname'] = isset($tmp_var_fname[$key]) ? $tmp_var_fname[$key] : 'undefined';
                $temp_logic['field_fire_fstep'] = isset($tmp_var_fstep[$key]) ? $tmp_var_fstep[$key] : 'undefined';

                $tmp_list = array();
                foreach ($value['list'] as $value2) {
                    $tmp_list[] = array(
                        'field_cond'       => $value2,
                        'field_cond_typen' => isset($tmp_var_typename[$value2]) ? $tmp_var_typename[$value2] : 'undefined',
                        'field_cond_fname' => isset($tmp_var_fname[$value2]) ? $tmp_var_fname[$value2] : 'undefined',
                        'field_cond_fstep' => isset($tmp_var_fstep[$value2]) ? $tmp_var_fstep[$value2] : 'undefined',
                    );
                }
                $temp_logic['list']       = $tmp_list;
                $logic_field_fire[$key] = $temp_logic;
            }

            $clogic_src['fire'] = $logic_field_fire;
            $saveform_clogic    = $clogic_src;
        }

        $data2           = array();
        $data2['clogic'] = $saveform_clogic;
        $output          = self::render_template('formbuilder/views/forms/preview_clogic_graph.php', $data2);

        $json         = array();
        $json['html'] = $output;
        header('Content-Type: text/html; charset=UTF-8');
        echo json_encode($json);
        wp_die();
    }

    public function ajax_listform_duplicate()
    {
        check_ajax_referer('zgfm_ajax_nonce', 'zgfm_security');

        $list_ids = (isset($_POST['id']) && $_POST['id']) ? array_map(array('Uiform_Form_Helper', 'sanitizeRecursive'), $_POST['id']) : array();

        if ($list_ids) {
            foreach ($list_ids as $value) {
                $data_form                = $this->formsmodel->getFormById($value);
                
                $exportCode = $this->load_export_form_get_code($data_form);
                $this->processImportExportCode($exportCode, true);
            }
        }
        wp_die();
    }

    public function ajax_listform_updatest()
    {

        check_ajax_referer('zgfm_ajax_nonce', 'zgfm_security');

        $list_ids = (isset($_POST['id']) && $_POST['id']) ? array_map(array('Uiform_Form_Helper', 'sanitizeRecursive'), $_POST['id']) : array();
        $form_st  = (isset($_POST['form_st']) && $_POST['form_st']) ? Uiform_Form_Helper::sanitizeInput($_POST['form_st']) : '';
        if ($list_ids) {
            foreach ($list_ids as $value) {
                $where = array(
                    'fmb_id' => $value,
                );
                $data  = array(
                    'flag_status' => intval($form_st),
                );
                $this->wpdb->update($this->formsmodel->table, $data, $where);
            }
        }
    }

    public function ajax_list_trashform_updatest()
    {

        check_ajax_referer('zgfm_ajax_nonce', 'zgfm_security');
        $list_ids = (isset($_POST['id']) && $_POST['id']) ? array_map(array('Uiform_Form_Helper', 'sanitizeRecursive'), $_POST['id']) : array();
        $form_st  = (isset($_POST['form_st']) && $_POST['form_st']) ? Uiform_Form_Helper::sanitizeInput($_POST['form_st']) : '';
        if ($list_ids) {
            switch (intval($form_st)) {
                case 1:
                case 2:
                    foreach ($list_ids as $value) {
                        $where = array(
                            'fmb_id' => $value,
                        );
                        $data  = array(
                            'flag_status' => intval($form_st),
                        );
                        $this->wpdb->update($this->formsmodel->table, $data, $where);
                    }
                    break;
                case 0:
                    foreach ($list_ids as $value) {
                        $this->delete_form_process($value);
                    }

                    break;
                default:
                    # code...
                    break;
            }
        }
    }

    private function delete_form_process($value)
    {
        //remove from log form
        $where = array(
            'log_frm_id' => $value,
        );
        $this->wpdb->delete($this->model_form_log->table, $where);

        //remove from fields
        $where = array(
            'form_fmb_id' => $value,
        );
        $this->wpdb->delete($this->model_fields->table, $where);

        //remove from addons logs
        $where = array(
            'fmb_id' => $value,
        );
        $this->wpdb->delete(self::$_models['addon']['addon_details_log']->table, $where);

        //remove from addons
        $where = array(
            'fmb_id' => $value,
        );
        $this->wpdb->delete(self::$_models['addon']['addon_details']->table, $where);

        //remove from records
        $where = array(
            'form_fmb_id' => $value,
        );
        $this->wpdb->delete(self::$_models['formbuilder']['form_records']->table, $where);

        //remove from form
        $where = array(
            'fmb_id' => $value,
        );
        $this->wpdb->delete($this->formsmodel->table, $where);
    }


    /**
     * delete trash form by form id
     *
     * @return void
     */
    public function ajax_delete_trashform_byid()
    {
        check_ajax_referer('zgfm_ajax_nonce', 'zgfm_security');
        $form_id = (isset($_POST['form_id']) && $_POST['form_id']) ? Uiform_Form_Helper::sanitizeInput($_POST['form_id']) : 0;

        $this->delete_form_process($form_id);
    }

    public function ajax_delete_form_byid()
    {

        check_ajax_referer('zgfm_ajax_nonce', 'zgfm_security');

        $form_id = (isset($_POST['form_id']) && $_POST['form_id']) ? Uiform_Form_Helper::sanitizeInput($_POST['form_id']) : 0;
        $where   = array(
            'fmb_id' => $form_id,
        );
        $data    = array(
            'flag_status' => 0,
        );
        $this->wpdb->update($this->formsmodel->table, $data, $where);
    }

    public function ajax_load_preview_form()
    {

        check_ajax_referer('zgfm_ajax_nonce', 'zgfm_security');

        $form_id = (isset($_POST['form_id'])) ? Uiform_Form_Helper::sanitizeInput(trim($_POST['form_id'])) : '';
        header('Content-type: text/html');
        ob_start();
?>
        <div id="uifm_frm_modal_html_loader"><img src="<?php echo UIFORM_FORMS_URL . '/assets/backend/image/ajax-loader-black.gif'; ?>"></div>
        <iframe src="<?php echo site_url(); ?>/?uifm_fbuilder_api_handler&zgfm_action=uifm_fb_api_handler&uifm_action=1&uifm_mode=lmode&id=<?php echo $form_id; ?>" scrolling="no" id="zgfm-iframe-<?php echo $form_id; ?>" frameborder="0" style="border:none;width:100%;" allowTransparency="true"></iframe>

        <script type="text/javascript">
            document.getElementById('zgfm-iframe-<?php echo $form_id; ?>').onload = function() {
                document.getElementById("uifm_frm_modal_html_loader").style.display = 'none';
                iFrameResize({
                    log: false,
                    onScroll: function(coords) {
                        /*console.log("[OVERRIDE] overrode scrollCallback x: " + coords.x + " y: " + coords.y);*/
                    }
                }, '#zgfm-iframe-<?php echo $form_id; ?>');
            };
        </script>

        <?php
        $output = ob_get_clean();
        echo $output;
        wp_die();
    }


    public function ajax_modal_form_showmodalsuccess()
    {

        check_ajax_referer('zgfm_ajax_nonce', 'zgfm_security');

        $form_id             = (isset($_POST['form_id'])) ? Uiform_Form_Helper::sanitizeInput(trim($_POST['form_id'])) : '';
        $data                = array();
        $data['content_top'] = __('Success! The form was created. Now just copy and paste the shortcode to your content', 'FRocket_admin');
        $data['form_id']     = $form_id;
        $json                = array();


        $data_form = $this->formsmodel->getFormById($form_id);
        if (intval($data_form->fmb_type) === 1) {
            //multistep
            $onclick = "rocketform.goToMultiStepForm('" . $form_id . "');";
        } elseif (intval($data_form->fmb_type) === 0) {
            $onclick = "rocketform.goToSingleForm('" . $form_id . "')";
        }

        $json['header'] = sprintf('<h4 class="sfdc-modal-title">%s</h4>', __('Success', 'FRocket_admin'));
        $json['html']        = self::render_template('formbuilder/views/forms/form_show_shortcodes.php', $data, 'always');
        $json['footer'] = sprintf('<button type="button" onclick="rocketform.goToList();" class="btn btn-warning" >%s</button> <button type="button" onclick="%s" class="btn btn-info" >%s</button>', __('Go to List', 'FRocket_admin'), $onclick, __('Back to Form', 'FRocket_admin'));
        // return data to ajax callback
        header('Content-type: text/html');
        echo json_encode($json);
        wp_die();
    }

    public function ajax_modal_form_getshorcodes()
    {

        check_ajax_referer('zgfm_ajax_nonce', 'zgfm_security');

        $form_id            = (isset($_POST['form_id'])) ? Uiform_Form_Helper::sanitizeInput(trim($_POST['form_id'])) : '';
        $data               = array();
        $data['form_id']    = $form_id;
        $json               = array();
        $json['html_title'] = __('Shortcodes', 'FRocket_admin');
        $json['html']       = self::render_template('formbuilder/views/forms/form_show_shortcodes.php', $data, 'always');

        // return data to ajax callback
        header('Content-type: text/html');
        echo json_encode($json);
        wp_die();
    }

    public function ajax_refresh_previewpanel()
    {

        check_ajax_referer('zgfm_ajax_nonce', 'zgfm_security');

        $data     = array();
        $fmb_data = (!empty($_POST['form_data'])) ? Uiform_Form_Helper::sanitizeInput_html($_POST['form_data']) : '';
        if(!Uiform_Form_Helper::isJson($fmb_data)){
            $fmb_data = urldecode($fmb_data);    
        }
        
        $fmb_data = (!empty($fmb_data)) ? array_map(array('Uiform_Form_Helper', 'sanitizeRecursive_html'), json_decode($fmb_data, true)) : array();

        $data['fmb_data'] = $fmb_data;
        $data['fmb_name'] = (!empty($_POST['uifm_frm_main_title'])) ? Uiform_Form_Helper::sanitizeInput(trim($_POST['uifm_frm_main_title'])) : '';

        // in case title is empty
        if (empty($data['fmb_name']) && !empty($_POST['uifm_frm_main_id']) && intval($_POST['uifm_frm_main_id']) > 0) {
            $tmp_form_id      = (!empty($_POST['uifm_frm_main_id'])) ? Uiform_Form_Helper::sanitizeInput(trim($_POST['uifm_frm_main_id'])) : '';
            $tmp_form_title   = $this->formsmodel->getTitleFormById($tmp_form_id);
            $data['fmb_name'] = $tmp_form_title->fmb_name;
        }

        $json                     = array();
        $tmp_html                 = $this->generate_previewpanel_html($data);
        $data['fmb_html_backend'] = Uiform_Form_Helper::encodeHex($tmp_html['output_html']);
        $json['data']             = $data;
        // return data to ajax callback
        header('Content-Type: application/json');
        echo json_encode($json);
        wp_die();
    }
    public function ajax_save_form_updateopts()
    {

        check_ajax_referer('zgfm_ajax_nonce', 'zgfm_security');

        $data                     = array();
        $fmb_id                   = ($_POST['uifm_frm_main_id']) ? Uiform_Form_Helper::sanitizeInput(trim($_POST['uifm_frm_main_id'])) : 0;
        $data['fmb_html_backend'] = ($_POST['form_html_backend']) ? Uiform_Form_Helper::sanitizeInput_html($_POST['form_html_backend']) : '';
        $json                     = array();
        if (intval($fmb_id) > 0) {
            $where = array(
                'fmb_id' => $fmb_id,
            );
            $this->wpdb->update($this->formsmodel->table, $data, $where);
            $json['status'] = 'updated';
            $json['id']     = $fmb_id;
        }
        // return data to ajax callback
        header('Content-Type: application/json');
        echo json_encode($json);
        wp_die();
    }

    public function ajax_save_newform()
    {

        check_ajax_referer('zgfm_ajax_nonce', 'zgfm_security');
        $json = array();
        try {
            if (!Uiform_Form_Helper::check_User_Access()) {
                throw new Exception(__('Error! User has no permission to edit this form', 'FRocket_admin'));
            }
            $data             = array();
            $data['fmb_name'] = (!empty($_POST['uifm_frm_main_title'])) ? Uiform_Form_Helper::sanitizeInput(trim($_POST['uifm_frm_main_title'])) : '';
            $isMultiStep = (!empty($_POST['uifm_frm_main_ismultistep'])) ? Uiform_Form_Helper::sanitizeInput(trim($_POST['uifm_frm_main_ismultistep'])) : '';

            $fmb_data = (isset($_POST['form_data'])) ? urldecode(Uiform_Form_Helper::sanitizeInput_html($_POST['form_data'])) : '';
            $fmb_data         = (isset($fmb_data) && $fmb_data) ? array_map(array('Uiform_Form_Helper', 'sanitizeRecursive_html'), json_decode($fmb_data, true)) : array();
            $data['fmb_data'] = json_encode($fmb_data);

            $data['fmb_type'] = 0;
            $data['fmb_parent'] = 0;

            if ($isMultiStep === 'yes') {

                $fmb_data = (isset($_POST['form_data2'])) ? urldecode(Uiform_Form_Helper::sanitizeInput_html($_POST['form_data2'])) : '';
                $fmb_data         = (isset($fmb_data) && $fmb_data) ? array_map(array('Uiform_Form_Helper', 'sanitizeRecursive_html'), json_decode($fmb_data, true)) : array();
                $data['fmb_data2'] = json_encode($fmb_data);

                $data['fmb_type'] = 1;
            }  

            $this->wpdb->insert($this->formsmodel->table, $data);
            $idActivate = $this->wpdb->insert_id;

            $json['status'] = 'created';
            $json['id']     = $idActivate;
        } catch (Exception $e) {
        }
        // return data to ajax callback
        header('Content-Type: application/json');
        echo json_encode($json);
        wp_die();
    }

    public function ajax_build_multistep()
    {

        try {
            if (!Uiform_Form_Helper::check_User_Access()) {
                throw new Exception(__('Error! User has no permission to edit this form', 'FRocket_admin'));
            }

            ob_start();
            $formId               = (isset($_POST['form_id'])) ? Uiform_Form_Helper::sanitizeInput(trim($_POST['form_id'])) : 0;
            if ($formId === 0) {
                throw new Exception(__('form id not found', 'FRocket_admin'));
            }
            /*global for fonts*/
            global $global_fonts_stored;
            $global_fonts_stored = array();

            $parentForm = $this->formsmodel->getFormById($formId);
            $fmb_data2  = json_decode($parentForm->fmb_data2, true);


            $formInitId = $fmb_data2['initial'];
            
            if(intval($formInitId) ===  0){
                $json = [];
                $json['success'] = true;
                $json['id'] = $formId;
                header('Content-Type: application/json');
                echo json_encode($json);
                die();
            }
            
            
            $this->current_mm_form_init = $this->formsmodel->getFormById($formInitId);
            //$formInitObj_fmb_data2  = json_decode($formInitObj->fmb_data2, true);
            $this->current_mm_children =  $this->formsmodel->getChildFormByParentId($formId);

            $this->current_data_skin     = $fmb_data2['skin'];
            $this->current_data_onsubm   = ($fmb_data2['onsubm']) ? $fmb_data2['onsubm'] : array();
            $this->current_data_main     = ($fmb_data2['main']) ? $fmb_data2['main'] : array();
            $this->current_data_conn     = [
                'conns' => ($fmb_data2['availableConnections']) ? $fmb_data2['availableConnections'] : array(),
                'conns_route' => ($fmb_data2['connections']) ? $fmb_data2['connections'] : array()
            ];
            $this->current_data_progressbar = ($fmb_data2['progressBar']) ? $fmb_data2['progressBar'] : array();
            // generate form html
            $gen_return = $this->generate_form_html_multistep($formId);
            $data4                     = array();
            // get global style
            $data2                     = array();
            $data2['idform']           = $formId;
            $data2['addition_css']     = $this->current_data_main['add_css'];
            $data2['skin']             = $this->current_data_skin;
            $gen_return['output_css'] .= self::render_template('formbuilder/views/forms/formhtml_css_global.php', $data2);
            $data3                     = array();
            $data3['fonts']            = $global_fonts_stored;
            $gen_return['output_css']  = self::render_template('formbuilder/views/forms/formhtml_css_init.php', $data3) . $gen_return['output_css'];
            $data4['fmb_html_css']     = $gen_return['output_css'];
            $data4['fmb_html']         = $gen_return['output_html'];
            $where = array(
                'fmb_id' => $formId,
            );
            $this->wpdb->update($this->formsmodel->table, $data4, $where);

            //attaching css content of children
            foreach ($this->current_mm_children as $key => $child) {
                $gen_return['output_css'] .= $child->fmb_html_css;
            }

            if (Uiform_Form_Helper::createCustomFolder()) {
                $newPublicDir = WP_CONTENT_DIR . '/uploads/softdiscover/' . UIFORM_SLUG.'/css';
            } else {
                $newPublicDir = UIFORM_FORMS_DIR . '/assets/frontend/css/';
            }

            // generate form css
            ob_start();
            $pathCssFile = $newPublicDir . '/rockfm_form' . $formId . '.css';
            $f           = fopen($pathCssFile, 'w');
            fwrite($f, $gen_return['output_css']);
            fclose($f);
            ob_end_clean();


            // checking errors
            $output_error = ob_get_contents();
            ob_end_clean();
            if (!empty($output_error)) {
                throw new Exception($output_error);
            }

            $json = [];
            $json['success'] = true;
            $json['id'] = $formId;
        } catch (Exception $e) {
            $json                 = array();
            $json['status']       = 'failed';
            $json['modal_header'] = __('Error on saving form', 'FRocket_admin');
            $json['modal_footer'] = '';
            $json['Message']      = $e->getMessage();
        }

        // return data to ajax callback
        header('Content-Type: application/json');
        echo json_encode($json);
        wp_die();
    }
    public function generate_form_html_multistep($form_id = null)
    {

        // all fields position


        // generating

        $str_output_2   = '';
        $str_output_tab = '';


        // generate form css
        $str_output_2 .= $this->generate_form_css($form_id);

        //generate css hook
        $str_output_2 .= apply_filters('zgfm_front_ms_aditional_css', $form_id);

        $return                = array();
        $return['output_html'] = $this->generate_form_container_multistep($form_id);
        $return['output_css']  = $str_output_2;

        return $return;
    }


    public function generate_form_container_multistep($id)
    {
        $data = array();
        $data['form_id']      = $id;
        $data['formInitHtml'] =  $this->current_mm_form_init->fmb_html;
        $data['formInit'] =  $this->current_mm_form_init->fmb_id;
        $data['onsubm']       = $this->current_data_onsubm;
        $data['main']         = $this->current_data_main;
        $data['connections'] = $this->current_data_conn;
        $data['outertop'] = apply_filters('zgfm_front_ms_form_outertop', '');
        $data['innertop'] = apply_filters('zgfm_front_ms_form_innertop', '');
        return self::render_template('formbuilder/views/forms/formhtml_form_parent.php', $data);
    }

    public function ajax_save_parentform()
    {

        check_ajax_referer('zgfm_ajax_nonce', 'zgfm_security');

        try {
            if (!Uiform_Form_Helper::check_User_Access()) {
                throw new Exception(__('Error! User has no permission to edit this form', 'FRocket_admin'));
            }

            ob_start();

            $data     = array();
            $fmb_data = (isset($_POST['form_data'])) ? urldecode(Uiform_Form_Helper::sanitizeInput_html($_POST['form_data'])) : '';
            $fmb_data         = (isset($fmb_data) && $fmb_data) ? array_map(array('Uiform_Form_Helper', 'sanitizeRecursive_html'), json_decode($fmb_data, true)) : array();
            $data['fmb_data'] = json_encode($fmb_data);

            $fmb_data = (isset($_POST['form_data2'])) ? urldecode(Uiform_Form_Helper::sanitizeInput_html($_POST['form_data2'])) : '';
            $fmb_data         = (isset($fmb_data) && $fmb_data) ? array_map(array('Uiform_Form_Helper', 'sanitizeRecursive_html'), json_decode($fmb_data, true)) : array();
            $data['fmb_data2'] = json_encode($fmb_data);

            // form_inputs
            //$fmb_data['fm_inputs'] = ( isset($_POST['form_inputs']) ) ? urldecode(Uiform_Form_Helper::sanitizeInput_html($_POST['form_inputs'])) : '';

            // more options
            $new_hash = (isset($_POST['hash_data'])) ? urldecode(Uiform_Form_Helper::sanitizeInput_html($_POST['hash_data'])) : '';

            // addon data
            $fmb_addon_data = (isset($_POST['addon_data'])) ? urldecode(Uiform_Form_Helper::sanitizeInput_html($_POST['addon_data'])) : '';
            $fmb_addon_data = (isset($fmb_addon_data) && $fmb_addon_data) ? array_map(array('Uiform_Form_Helper', 'sanitizeRecursive_html'), json_decode($fmb_addon_data, true)) : array();


            $data['fmb_rec_tpl_html'] = (isset($_POST['uifm_frm_rec_tpl_html'])) ? urldecode(Uiform_Form_Helper::sanitizeInput_html($_POST['uifm_frm_rec_tpl_html'])) : '';
            $data['fmb_rec_tpl_st']   = (isset($_POST['uifm_frm_rec_tpl_st'])) ? urldecode(Uiform_Form_Helper::sanitizeInput_html($_POST['uifm_frm_rec_tpl_st'])) : '';


            //$tmp_data2            = array();
            //$tmp_data2['onsubm']  = isset($fmb_data['onsubm']) ? $fmb_data['onsubm'] : '';
            //$tmp_data2['main']    = isset($fmb_data['main']) ? $fmb_data['main'] : '';
            //$data['fmb_data2']    = ! empty($tmp_data2) ? json_encode($tmp_data2) : '';
            $data['fmb_name']     = (!empty($_POST['name'])) ? Uiform_Form_Helper::sanitizeInput(trim($_POST['name'])) : '';
            $data['created_ip']   = $_SERVER['REMOTE_ADDR'];
            $data['created_by']   = 1;
            $data['created_date'] = date('Y-m-d h:i:s');
            $fmb_id               = (isset($_POST['form_id'])) ? Uiform_Form_Helper::sanitizeInput(trim($_POST['form_id'])) : 0;

            /*global for fonts*/
            global $global_fonts_stored;
            $global_fonts_stored = array();

            $json = array();
            if (intval($fmb_id) > 0) {
                $where = array(
                    'fmb_id' => $fmb_id,
                );
                $this->wpdb->update($this->formsmodel->table, $data, $where);
                $json['status'] = 'updated';
                $json['id']     = $fmb_id;
            } else {
                $this->wpdb->insert($this->formsmodel->table, $data);
                $idActivate     = $this->wpdb->insert_id;
                $json['status'] = 'created';
                $json['id']     = $idActivate;
            }

            $data_form = $this->formsmodel->getFormById($json['id']);
            $fmb_data  = json_decode($data_form->fmb_data2, true);

            // all data fields
            $fmb_data['addons'] = $fmb_addon_data;

            if (intval($json['id']) === 0) {
                throw new Exception('Form id error');
            }
            $where = array(
                'fmb_id' => $json['id'],
            );

            // process addons
            do_action('zgfm_saveForm_store', $fmb_data, $json['id']);

            // add to log
            $save_log_st   = false;
            $count_log_rec = $this->model_form_log->CountLogsByFormId($json['id']);

            if (intval($count_log_rec) > 0) {
                $last_rec = $this->model_form_log->getLastLogById($json['id']);

                $old_hash = $last_rec->log_frm_hash;
                if ($new_hash != $old_hash) {
                    $save_log_st = true;
                }
            } else {
                $save_log_st = true;
            }

            if ($save_log_st) {
                $data5                         = array();
                $data5['log_frm_data']         = json_encode(
                    [
                        'data' => json_decode($data['fmb_data'], true),
                        'data2' => json_decode($data['fmb_data2'], true)
                    ]
                );
                $data5['log_frm_name']         = $data['fmb_name'];
                $data5['log_frm_html']         = '';
                $data5['log_frm_html_backend'] = '';
                $data5['log_frm_html_css']     = '';
                $data5['log_frm_id']           = $json['id'];
                $data5['log_frm_hash']         = $new_hash;
                $data5['created_ip']           = $_SERVER['REMOTE_ADDR'];
                $data5['created_by']           = 1;
                $data5['created_date']         = date('Y-m-d h:i:s');

                $this->wpdb->insert($this->model_form_log->table, $data5);
                $lastLogId = $this->wpdb->insert_id;
                $json['log_id'] = $lastLogId;
                //save child forms
                /*$childForms = $this->formsmodel->getChildFormByParentId($json['id']);
                foreach ($childForms as $key => $child) {
                    $data5                         = array();
                    $data5['log_frm_data']         = $child->fmb_data;
                    $data5['log_frm_name']         = $child->fmb_name;
                    $data5['log_frm_html']         = '';
                    $data5['log_frm_html_backend'] = $child->fmb_html_backend;
                    $data5['log_frm_html_css']     = '';
                    $data5['log_frm_id']           = $child->fmb_id;
                    $data5['log_frm_parent']       = $lastLogId;
                    $data5['log_frm_hash']         = $new_hash;
                    $data5['created_ip']           = $_SERVER['REMOTE_ADDR'];
                    $data5['created_by']           = 1;
                    $data5['created_date']         = date('Y-m-d h:i:s');

                    $this->wpdb->insert($this->model_form_log->table, $data5);
                }*/

                //$log_lastid = $this->wpdb->insert_id;
                // remove oldest if limit is exceeded
                if (intval($count_log_rec) > 50) {
                    $tmp_log = $this->model_form_log->getOldLogById($json['id']);

                    $where = array(
                        'log_frm_hash' => $tmp_log->log_frm_hash,
                    );
                    $this->wpdb->delete($this->model_form_log->table, $where);
                }
            }

            // process addons
            //do_action('zgfm_OnSaveForm_saveLog', $json['id'], $save_log_st, $log_lastid, $this->current_data_addon[ 'OnSaveForm_saveLog' ]['data']);

            // checking errors
            $output_error = ob_get_contents();
            ob_end_clean();
            if (!empty($output_error)) {
                throw new Exception($output_error);
            }
        } catch (Exception $e) {
            $json                 = array();
            $json['status']       = 'failed';
            $json['modal_header'] = __('Error on saving form', 'FRocket_admin');
            $json['modal_footer'] = '';
            $json['Message']      = $e->getMessage();
        }

        // return data to ajax callback
        header('Content-Type: application/json');
        echo json_encode($json);
        wp_die();
    }

    public function ajax_save_childform()
    {

        check_ajax_referer('zgfm_ajax_nonce', 'zgfm_security');

        try {
            if (!Uiform_Form_Helper::check_User_Access()) {
                throw new Exception(__('Error! User has no permission to edit this form', 'FRocket_admin'));
            }
            $this->is_multistep = true;
            ob_start();

            $data     = array();
            $fmb_data = (isset($_POST['form_data'])) ? urldecode(Uiform_Form_Helper::sanitizeInput_html($_POST['form_data'])) : '';

            $fmb_data         = (isset($fmb_data) && $fmb_data) ? array_map(array('Uiform_Form_Helper', 'sanitizeRecursive_html'), json_decode($fmb_data, true)) : array();
            $data['fmb_data'] = json_encode($fmb_data);


            // form_inputs
            //$fmb_data['fm_inputs'] = ( isset($_POST['form_inputs']) ) ? urldecode(Uiform_Form_Helper::sanitizeInput_html($_POST['form_inputs'])) : '';

            // more options
            $hash_data = (isset($_POST['hash_data'])) ? Uiform_Form_Helper::sanitizeInput_html($_POST['hash_data']) : '';
            $log_id   = (isset($_POST['log_id'])) ? Uiform_Form_Helper::sanitizeInput($_POST['log_id']) : '';

            //$tmp_data2            = array();
            //$tmp_data2['onsubm']  = isset($fmb_data['onsubm']) ? $fmb_data['onsubm'] : '';
            //$tmp_data2['main']    = isset($fmb_data['main']) ? $fmb_data['main'] : '';
            //$data['fmb_data2']    = ! empty($tmp_data2) ? json_encode($tmp_data2) : '';
            $data['fmb_name']     = (!empty($_POST['uifm_frm_main_title'])) ? Uiform_Form_Helper::sanitizeInput(trim($_POST['uifm_frm_main_title'])) : '';
            $data['created_ip']   = $_SERVER['REMOTE_ADDR'];
            $data['created_by']   = 1;
            $data['created_date'] = date('Y-m-d h:i:s');
            $fmb_id               = (isset($_POST['uifm_frm_main_id'])) ? Uiform_Form_Helper::sanitizeInput(trim($_POST['uifm_frm_main_id'])) : 0;

            // $this->current_mm_form_child_id = $fmb_id;

            /*global for fonts*/
            global $global_fonts_stored;
            $global_fonts_stored = array();

            $json = array();
            if (intval($fmb_id) > 0) {
                $where = array(
                    'fmb_id' => $fmb_id,
                );
                $this->wpdb->update($this->formsmodel->table, $data, $where);
                $json['status'] = 'updated';
                $json['id']     = $fmb_id;
            } else {
                $this->wpdb->insert($this->formsmodel->table, $data);
                $idActivate     = $this->wpdb->insert_id;
                $json['status'] = 'created';
                $json['id']     = $idActivate;
            }
            $this->saved_form_id = $json['id'];

            $data_form = $this->formsmodel->getFormById($json['id']);
            $fmb_data  = json_decode($data_form->fmb_data, true);


            // addon data
            $fmb_addon_data =  apply_filters('zgfm_back_addon_obtain_data', [], $data_form->fmb_parent);

            // all data fields
            $fmb_data['addons'] = $fmb_addon_data;


            // all data fields
            //$fmb_data['addons'] = $fmb_addon_data;

            if (intval($json['id']) === 0) {
                throw new Exception('Form id error');
            }
            $where = array(
                'fmb_id' => $json['id'],
            );

            // process addons
            $fmb_data = apply_filters('zgfm_back_animtocore', $fmb_data, $json['id']);

            // all data fields
            //$this->current_data_addon    = $fmb_data['addons'];
            $this->current_data_form     = $fmb_data['steps_src'];
            $this->current_data_num_tabs = $fmb_data['num_tabs'];
            $this->current_data_tab_cont = $fmb_data['steps']['tab_cont'];
            $this->current_data_steps    = $fmb_data['steps'];
            $this->current_data_skin     = $fmb_data['skin'];
            $this->current_data_wizard   = ($fmb_data['wizard']) ? $fmb_data['wizard'] : array();
            $this->current_data_onsubm   = ($fmb_data['onsubm']) ? $fmb_data['onsubm'] : array();
            $this->current_data_main     = ($fmb_data['main']) ? $fmb_data['main'] : array();

            // save fields to table

            $this->save_data_fields($json['id']);
            // save fields to table
            $this->save_form_clogic();
            // generate form html
            $gen_return = $this->generate_form_html($json['id']);

            $data4                     = array();
            $data4['fmb_html']         = $gen_return['output_html'];
            $data4['fmb_html_backend'] = $this->generate_admin_form_html($json['id']);

            // get global style
            $data2                     = array();
            $data2['idform']           = $json['id'];
            $data2['addition_css']     = $this->current_data_main['add_css'];
            $data2['skin']             = $this->current_data_skin;
            $gen_return['output_css'] .= self::render_template('formbuilder/views/forms/formhtml_css_global.php', $data2);
            $data3                     = array();
            $data3['fonts']            = $global_fonts_stored;
            $gen_return['output_css']  = self::render_template('formbuilder/views/forms/formhtml_css_init.php', $data3) . $gen_return['output_css'];
            $data4['fmb_html_css']     = $gen_return['output_css'];
            $this->wpdb->update($this->formsmodel->table, $data4, $where);

           

            $data5                         = array();
            $data5['log_frm_data']         = $data['fmb_data'];
            $data5['log_frm_name']         = $data['fmb_name'];
            $data5['log_frm_html']         = '';
            $data5['log_frm_html_backend'] = $data4['fmb_html_backend'];
            $data5['log_frm_html_css']     = '';
            $data5['log_frm_id']           = $json['id'];
            $data5['log_frm_parent']           = $log_id;
            $data5['log_frm_hash']         = $hash_data;
            $data5['created_ip']           = $_SERVER['REMOTE_ADDR'];
            $data5['created_by']           = 1;
            $data5['created_date']         = date('Y-m-d h:i:s');

            $this->wpdb->insert($this->model_form_log->table, $data5);

            // process addons
            //do_action('zgfm_OnSaveForm_saveLog', $json['id'], $save_log_st, $log_lastid, $this->current_data_addon[ 'OnSaveForm_saveLog' ]['data']);

            // checking errors
            $output_error = ob_get_contents();
            ob_end_clean();
            if (!empty($output_error)) {
                throw new Exception($output_error);
            }
        } catch (Exception $e) {
            $json                 = array();
            $json['status']       = 'failed';
            $json['modal_header'] = __('Error on saving form', 'FRocket_admin');
            $json['modal_footer'] = '';
            $json['Message']      = $e->getMessage();
        }

        // return data to ajax callback
        header('Content-Type: application/json');
        echo json_encode($json);
        wp_die();
    }

    public function ajax_save_form()
    {

        check_ajax_referer('zgfm_ajax_nonce', 'zgfm_security');

        try {
            if (!Uiform_Form_Helper::check_User_Access()) {
                throw new Exception(__('Error! User has no permission to edit this form', 'FRocket_admin'));
            }

            ob_start();

            $data     = array();
            $fmb_data = (isset($_POST['form_data'])) ? urldecode(Uiform_Form_Helper::sanitizeInput_html($_POST['form_data'])) : '';
            // $fmb_data = str_replace("\'", "'",$fmb_data);
            $fmb_data         = (isset($fmb_data) && $fmb_data) ? array_map(array('Uiform_Form_Helper', 'sanitizeRecursive_html'), json_decode($fmb_data, true)) : array();

            $data['fmb_data'] = json_encode($fmb_data);

            // addon data
            $fmb_addon_data = (isset($_POST['addon_data'])) ? urldecode(Uiform_Form_Helper::sanitizeInput_html($_POST['addon_data'])) : '';
            $fmb_addon_data = (isset($fmb_addon_data) && $fmb_addon_data) ? array_map(array('Uiform_Form_Helper', 'sanitizeRecursive_html'), json_decode($fmb_addon_data, true)) : array();

            // form_inputs
            $fmb_data['fm_inputs'] = (isset($_POST['form_inputs'])) ? urldecode(Uiform_Form_Helper::sanitizeInput_html($_POST['form_inputs'])) : '';

            // more options
            $data['fmb_rec_tpl_html'] = (isset($_POST['uifm_frm_rec_tpl_html'])) ? urldecode(Uiform_Form_Helper::sanitizeInput_html($_POST['uifm_frm_rec_tpl_html'])) : '';
            $data['fmb_rec_tpl_st']   = (isset($_POST['uifm_frm_rec_tpl_st'])) ? urldecode(Uiform_Form_Helper::sanitizeInput_html($_POST['uifm_frm_rec_tpl_st'])) : '';

            $tmp_data2            = array();
            $tmp_data2['onsubm']  = isset($fmb_data['onsubm']) ? $fmb_data['onsubm'] : '';
            $tmp_data2['main']    = isset($fmb_data['main']) ? $fmb_data['main'] : '';
            $data['fmb_data2']    = !empty($tmp_data2) ? json_encode($tmp_data2) : '';
            $data['fmb_name']     = (!empty($_POST['uifm_frm_main_title'])) ? Uiform_Form_Helper::sanitizeInput(trim($_POST['uifm_frm_main_title'])) : '';
            $data['created_ip']   = $_SERVER['REMOTE_ADDR'];
            $data['created_by']   = 1;
            $data['created_date'] = date('Y-m-d h:i:s');
            $fmb_id               = (isset($_POST['uifm_frm_main_id'])) ? Uiform_Form_Helper::sanitizeInput(trim($_POST['uifm_frm_main_id'])) : 0;

            /*global for fonts*/
            global $global_fonts_stored;
            $global_fonts_stored = array();

            $json = array();
            if (intval($fmb_id) > 0) {
                $where = array(
                    'fmb_id' => $fmb_id,
                );
                $this->wpdb->update($this->formsmodel->table, $data, $where);
                $json['status'] = 'updated';
                $json['id']     = $fmb_id;
            } else {
                $this->wpdb->insert($this->formsmodel->table, $data);
                $idActivate     = $this->wpdb->insert_id;
                $json['status'] = 'created';
                $json['id']     = $idActivate;
            }

            $data_form = $this->formsmodel->getFormById($json['id']);
            $fmb_data  = json_decode($data_form->fmb_data, true);

            // all data fields
            $fmb_data['addons'] = $fmb_addon_data;

            if (intval($json['id']) === 0) {
                throw new Exception('Form id error');
            }
            $where = array(
                'fmb_id' => $json['id'],
            );

            // process addons
            $fmb_data = apply_filters('zgfm_saveForm_store', $fmb_data, $json['id']);

            // all data fields
            $this->current_data_addon    = $fmb_data['addons'];
            $this->current_data_form     = $fmb_data['steps_src'];
            $this->current_data_num_tabs = $fmb_data['num_tabs'];
            $this->current_data_tab_cont = $fmb_data['steps']['tab_cont'];
            $this->current_data_steps    = $fmb_data['steps'];
            $this->current_data_skin     = $fmb_data['skin'];
            $this->current_data_wizard   = ($fmb_data['wizard']) ? $fmb_data['wizard'] : array();
            $this->current_data_onsubm   = ($fmb_data['onsubm']) ? $fmb_data['onsubm'] : array();
            $this->current_data_main     = ($fmb_data['main']) ? $fmb_data['main'] : array();

            // save fields to table
            $this->saved_form_id = $json['id'];
            $this->save_data_fields($json['id']);
            // save fields to table
            $this->save_form_clogic();
            // generate form html
            $gen_return = $this->generate_form_html($json['id']);

            $data4                     = array();
            $data4['fmb_html']         = $gen_return['output_html'];
            $data4['fmb_html_backend'] = $this->generate_admin_form_html($json['id']);

            // get global style
            $data2                     = array();
            $data2['idform']           = $json['id'];
            $data2['addition_css']     = $this->current_data_main['add_css'];
            $data2['skin']             = $this->current_data_skin;
            $gen_return['output_css'] .= self::render_template('formbuilder/views/forms/formhtml_css_global.php', $data2);
            $data3                     = array();
            $data3['fonts']            = $global_fonts_stored;
            $gen_return['output_css']  = self::render_template('formbuilder/views/forms/formhtml_css_init.php', $data3) . $gen_return['output_css'];
            $data4['fmb_html_css']     = $gen_return['output_css'];
            $this->wpdb->update($this->formsmodel->table, $data4, $where);

            if (Uiform_Form_Helper::createCustomFolder()) {
                $newPublicDir = WP_CONTENT_DIR . '/uploads/softdiscover/' . UIFORM_SLUG.'/css';
            } else {
                $newPublicDir = UIFORM_FORMS_DIR . '/assets/frontend/css/';
            }

            // generate form css
            ob_start();
            $pathCssFile = $newPublicDir . '/rockfm_form' . $json['id'] . '.css';
            $f           = fopen($pathCssFile, 'w');
            fwrite($f, $gen_return['output_css']);
            fclose($f);
            ob_end_clean();

            // add to log
            $save_log_st   = false;
            $count_log_rec = $this->model_form_log->CountLogsByFormId($json['id']);

            if (intval($count_log_rec) > 0) {
                $last_rec = $this->model_form_log->getLastLogById($json['id']);
                $new_hash = md5($data_form->fmb_data);
                $old_hash = $last_rec->log_frm_hash;
                if ($new_hash != $old_hash) {
                    $save_log_st = true;
                }
            } else {
                $save_log_st = true;
            }

            $log_lastid = 0;
            if ($save_log_st) {
                $data5                         = array();
                $data5['log_frm_data']         = $data['fmb_data'];
                $data5['log_frm_name']         = $data['fmb_name'];
                $data5['log_frm_html']         = '';
                $data5['log_frm_html_backend'] = $data4['fmb_html_backend'];
                $data5['log_frm_html_css']     = '';
                $data5['log_frm_id']           = $json['id'];
                $data5['log_frm_hash']         = md5($data_form->fmb_data);
                $data5['created_ip']           = $_SERVER['REMOTE_ADDR'];
                $data5['created_by']           = 1;
                $data5['created_date']         = date('Y-m-d h:i:s');

                $this->wpdb->insert($this->model_form_log->table, $data5);
                $log_lastid = $this->wpdb->insert_id;
                // remove oldest if limit is exceeded
                if (intval($count_log_rec) > 50) {
                    $tmp_log = $this->model_form_log->getOldLogById($json['id']);

                    $where = array(
                        'log_id' => $tmp_log->log_id,
                    );
                    $this->wpdb->delete($this->model_form_log->table, $where);
                }
            }

            // process addons
            //do_action('zgfm_OnSaveForm_saveLog', $json['id'], $save_log_st, $log_lastid, $this->current_data_addon[ 'OnSaveForm_saveLog' ]['data']);

            // checking errors
            $output_error = ob_get_contents();
            ob_end_clean();
            if (!empty($output_error)) {
                throw new Exception($output_error);
            }
        } catch (Exception $e) {
            $json                 = array();
            $json['status']       = 'failed';
            $json['modal_header'] = __('Error on saving form', 'FRocket_admin');
            $json['modal_footer'] = '';
            $json['Message']      = $e->getMessage();
        }

        // return data to ajax callback
        header('Content-Type: application/json');
        echo json_encode($json);
        wp_die();
    }

    

    protected function generate_form_getField($child_field)
    {
        $str_output   = '';
        $str_output_3 = '';

        $data = array();
        $data = $this->current_data_form[intval($child_field['num_tab'])][$child_field['id']];

        $data['addon_extraclass'] = '';

        // process addons
        $data = apply_filters('zgfm_field_addon_extraclass', $data);

        switch (intval($child_field['type'])) {
            case 6:
                // textbox
                $str_output   .= self::$_modules['formbuilder']['fields']->formhtml_textbox($data, $child_field['num_tab']);
                $str_output_3 .= self::$_modules['formbuilder']['fields']->formhtml_textbox_css($data);
                break;
            case 7:
                // textarea
                $str_output   .= self::$_modules['formbuilder']['fields']->formhtml_textarea($data, $child_field['num_tab']);
                $str_output_3 .= self::$_modules['formbuilder']['fields']->formhtml_textarea_css($data);
                break;
            case 8:
                // radio button
                $data['main']  = $this->current_data_main;
                $str_output   .= self::$_modules['formbuilder']['fields']->formhtml_radiobtn($data, $child_field['num_tab']);
                $str_output_3 .= self::$_modules['formbuilder']['fields']->formhtml_radiobtn_css($data);
                break;
            case 9:
                // checkbox
                $data['main']  = $this->current_data_main;
                $str_output   .= self::$_modules['formbuilder']['fields']->formhtml_checkbox($data, $child_field['num_tab']);
                $str_output_3 .= self::$_modules['formbuilder']['fields']->formhtml_checkbox_css($data);
                break;
            case 10:
                // select
                $data['main']  = $this->current_data_main;
                $str_output   .= self::$_modules['formbuilder']['fields']->formhtml_select($data, $child_field['num_tab']);
                $str_output_3 .= self::$_modules['formbuilder']['fields']->formhtml_select_css($data);
                break;
            case 11:
                // multiselect
                $data['main']  = $this->current_data_main;
                $str_output   .= self::$_modules['formbuilder']['fields']->formhtml_multiselect($data, $child_field['num_tab']);
                $str_output_3 .= self::$_modules['formbuilder']['fields']->formhtml_multiselect_css($data);
                break;
            case 12:
                // fileupload
                $str_output   .= self::$_modules['formbuilder']['fields']->formhtml_fileupload($data, $child_field['num_tab']);
                $str_output_3 .= self::$_modules['formbuilder']['fields']->formhtml_fileupload_css($data);
                break;
            case 13:
                // imageupload
                $str_output   .= self::$_modules['formbuilder']['fields']->formhtml_imageupload($data, $child_field['num_tab']);
                $str_output_3 .= self::$_modules['formbuilder']['fields']->formhtml_imageupload_css($data);
                break;
            case 14:
                // custom html
                $str_output   .= self::$_modules['formbuilder']['fields']->formhtml_customhtml($data, $child_field['num_tab']);
                $str_output_3 .= self::$_modules['formbuilder']['fields']->formhtml_customhtml_css($data);
                break;
            case 15:
                // password
                $str_output   .= self::$_modules['formbuilder']['fields']->formhtml_password($data, $child_field['num_tab']);
                $str_output_3 .= self::$_modules['formbuilder']['fields']->formhtml_password_css($data);
                break;
            case 16:
                // slider
                $data['main']  = $this->current_data_main;
                $str_output   .= self::$_modules['formbuilder']['fields']->formhtml_slider($data, $child_field['num_tab']);
                $str_output_3 .= self::$_modules['formbuilder']['fields']->formhtml_slider_css($data);
                break;
            case 17:
                // range
                $str_output   .= self::$_modules['formbuilder']['fields']->formhtml_range($data, $child_field['num_tab']);
                $str_output_3 .= self::$_modules['formbuilder']['fields']->formhtml_range_css($data);
                break;
            case 18:
                // spinner
                $data['main']  = $this->current_data_main;
                $str_output   .= self::$_modules['formbuilder']['fields']->formhtml_spinner($data, $child_field['num_tab']);
                $str_output_3 .= self::$_modules['formbuilder']['fields']->formhtml_spinner_css($data);
                break;
            case 19:
                // captcha
                $str_output   .= self::$_modules['formbuilder']['fields']->formhtml_captcha($data, $child_field['num_tab']);
                $str_output_3 .= self::$_modules['formbuilder']['fields']->formhtml_captcha_css($data);
                break;
            case 20:
                // submit button
                $str_output   .= self::$_modules['formbuilder']['fields']->formhtml_submitbtn($data, $child_field['num_tab']);
                $str_output_3 .= self::$_modules['formbuilder']['fields']->formhtml_submitbtn_css($data);
                break;
            case 21:
                // hidden field
                $str_output .= self::$_modules['formbuilder']['fields']->formhtml_hiddeninput($data, $child_field['num_tab']);
                break;
            case 22:
                // star rating
                $str_output   .= self::$_modules['formbuilder']['fields']->formhtml_ratingstar($data, $child_field['num_tab']);
                $str_output_3 .= self::$_modules['formbuilder']['fields']->formhtml_ratingstar_css($data);
                break;
            case 23:
                // color picker
                $str_output   .= self::$_modules['formbuilder']['fields']->formhtml_colorpicker($data, $child_field['num_tab']);
                $str_output_3 .= self::$_modules['formbuilder']['fields']->formhtml_colorpicker_css($data);
                break;
            case 24:
                // date picker
                $str_output   .= self::$_modules['formbuilder']['fields']->formhtml_datepicker($data, $child_field['num_tab']);
                $str_output_3 .= self::$_modules['formbuilder']['fields']->formhtml_datepicker_css($data);
                break;
            case 25:
                // time picker
                $str_output   .= self::$_modules['formbuilder']['fields']->formhtml_timepicker($data, $child_field['num_tab']);
                $str_output_3 .= self::$_modules['formbuilder']['fields']->formhtml_timepicker_css($data);
                break;
            case 26:
                // date time
                $str_output   .= self::$_modules['formbuilder']['fields']->formhtml_datetime($data, $child_field['num_tab']);
                $str_output_3 .= self::$_modules['formbuilder']['fields']->formhtml_datetime_css($data);
                break;
            case 27:
                // recaptcha
                $str_output   .= self::$_modules['formbuilder']['fields']->formhtml_recaptcha($data, $child_field['num_tab']);
                $str_output_3 .= self::$_modules['formbuilder']['fields']->formhtml_recaptcha_css($data);
                break;
            case 28:
                // prepended text
                $str_output   .= self::$_modules['formbuilder']['fields']->formhtml_preptext($data, $child_field['num_tab']);
                $str_output_3 .= self::$_modules['formbuilder']['fields']->formhtml_preptext_css($data);
                break;
            case 29:
                // appended text
                $str_output   .= self::$_modules['formbuilder']['fields']->formhtml_appetext($data, $child_field['num_tab']);
                $str_output_3 .= self::$_modules['formbuilder']['fields']->formhtml_appetext_css($data);
                break;
            case 30:
                // prep app text
                $str_output   .= self::$_modules['formbuilder']['fields']->formhtml_prepapptext($data, $child_field['num_tab']);
                $str_output_3 .= self::$_modules['formbuilder']['fields']->formhtml_prepapptext_css($data);
                break;
            case 31:
                // panel
                $str_output   .= self::$_modules['formbuilder']['fields']->formhtml_panelfld($data, $child_field['num_tab']);
                $str_output_3 .= self::$_modules['formbuilder']['fields']->formhtml_panelfld_css($data);
                break;
            case 32:
                // divider
                $str_output       .= self::$_modules['formbuilder']['fields']->formhtml_divider($data, $child_field['num_tab']);
                $data['form_skin'] = $this->current_data_skin;
                $str_output_3     .= self::$_modules['formbuilder']['fields']->formhtml_divider_css($data);
                break;
            case 33:
            case 34:
            case 35:
            case 36:
            case 37:
            case 38:
                // heading
                $str_output   .= self::$_modules['formbuilder']['fields']->formhtml_heading($data, $child_field['num_tab']);
                $str_output_3 .= self::$_modules['formbuilder']['fields']->formhtml_heading_css($data);
                break;
            case 39:
                // wizard buttons
                $data['form_wizard'] = $this->current_data_wizard;
                $data['tab_count']   = $this->current_data_steps;
                $str_output         .= self::$_modules['formbuilder']['fields']->formhtml_wizardbtn($data, $child_field['num_tab']);
                $str_output_3       .= self::$_modules['formbuilder']['fields']->formhtml_wizardbtn_css($data);
                break;
            case 40:
                // switch
                $data['main']  = $this->current_data_main;
                $str_output   .= self::$_modules['formbuilder']['fields']->formhtml_switch($data, $child_field['num_tab']);
                $str_output_3 .= self::$_modules['formbuilder']['fields']->formhtml_switch_css($data);
                break;
            case 41:
                // dyn checkbox
                $data['main']    = $this->current_data_main;
                $data['form_id'] = $this->saved_form_id;
                $str_output     .= self::$_modules['formbuilder']['fields']->formhtml_dyncheckbox($data, $child_field['num_tab']);
                $str_output_3   .= self::$_modules['formbuilder']['fields']->formhtml_dyncheckbox_css($data);
                break;
            case 42:
                // dyn radiobtn
                $data['main']    = $this->current_data_main;
                $data['form_id'] = $this->saved_form_id;
                $str_output     .= self::$_modules['formbuilder']['fields']->formhtml_dynradiobtn($data, $child_field['num_tab']);
                $str_output_3   .= self::$_modules['formbuilder']['fields']->formhtml_dynradiobtn_css($data);
                break;
            case 43:
                // date 2
                $data['main']    = $this->current_data_main;
                $data['form_id'] = $this->saved_form_id;
                $str_output     .= self::$_modules['formbuilder']['fields']->formhtml_date2($data, $child_field['num_tab']);
                $str_output_3   .= self::$_modules['formbuilder']['fields']->formhtml_date2_css($data);
                break;
            default:
                break;
        }

        $return                = array();
        $return['output_html'] = $str_output;

        // add css inside namespace of the form
        require_once UIFORM_FORMS_DIR . '/libraries/lesslib/lessc.inc.php';
        $less      = new lessc();
        $css_store = '';
        try {
            $css_store = $less->compile('#rockfm_form_' . $this->saved_form_id . ' {' . $str_output_3 . '}');
        } catch (exception $e) {
            $css_store = $str_output_3;
        }

        $return['output_css'] = $css_store;

        return $return;
    }


    /*
    * Search field on core data if exists
    */
    private function isField_OnCoreData($core_data, $field_search)
    {

        foreach ($core_data as $key => $field) {
            if (isset($core_data[$key][$field_search]) && !empty($core_data[$key][$field_search])) {
                return $core_data[$key][$field_search];
            }
        }

        return false;
    }
    /**
     * Forms::generate_previewpanel_getField()
     *
     * @return
     */
    protected function generate_previewpanel_getField($child_field)
    {
        $str_output = '';

        $data = array();

        if (empty($this->current_data_form[intval($child_field['num_tab'])][$child_field['id']])) {
            $tmp_data = $this->isField_OnCoreData($this->current_data_form, $child_field['id']);
            if ($tmp_data) {
                $data = $tmp_data;
            } else {
                $return                = array();
                $return['output_html'] = '';
                return $return;
            }
        } else {
            $data = $this->current_data_form[intval($child_field['num_tab'])][$child_field['id']];
        }

        $data['quick_options'] = self::render_template('formbuilder/views/fields/templates/prevpanel_quickopts.php', $data, 'always');
        switch (intval($child_field['type'])) {
            case 6:
                // textbox
                $str_output .= self::render_template('formbuilder/views/fields/templates/prevpanel_textbox.php', $data, 'always');
                break;
            case 7:
                // textarea
                $str_output .= self::render_template('formbuilder/views/fields/templates/prevpanel_textarea.php', $data, 'always');
                break;
            case 8:
                // radio button
                $str_output .= self::render_template('formbuilder/views/fields/templates/prevpanel_radiobtn.php', $data, 'always');
                break;
            case 9:
                // checkbox
                $str_output .= self::render_template('formbuilder/views/fields/templates/prevpanel_checkbox.php', $data, 'always');
                break;
            case 10:
                // select
                $str_output .= self::render_template('formbuilder/views/fields/templates/prevpanel_select.php', $data, 'always');
                break;
            case 11:
                // multiselect
                $str_output .= self::render_template('formbuilder/views/fields/templates/prevpanel_multiselect.php', $data, 'always');
                break;
            case 12:
                // fileupload
                $str_output .= self::render_template('formbuilder/views/fields/templates/prevpanel_fileupload.php', $data, 'always');
                break;
            case 13:
                // imageupload
                $str_output .= self::render_template('formbuilder/views/fields/templates/prevpanel_imageupload.php', $data, 'always');
                break;
            case 14:
                // custom html
                $str_output .= self::render_template('formbuilder/views/fields/templates/prevpanel_customhtml.php', $data, 'always');
                break;
            case 15:
                // password
                $str_output .= self::render_template('formbuilder/views/fields/templates/prevpanel_password.php', $data, 'always');
                break;
            case 16:
                // slider
                $str_output .= self::render_template('formbuilder/views/fields/templates/prevpanel_slider.php', $data, 'always');
                break;
            case 17:
                // range
                $str_output .= self::render_template('formbuilder/views/fields/templates/prevpanel_range.php', $data, 'always');
                break;
            case 18:
                // spinner
                $str_output .= self::render_template('formbuilder/views/fields/templates/prevpanel_spinner.php', $data, 'always');
                break;
            case 19:
                // captcha
                $str_output .= self::render_template('formbuilder/views/fields/templates/prevpanel_captcha.php', $data, 'always');
                break;
            case 20:
                // submit button
                $str_output .= self::render_template('formbuilder/views/fields/templates/prevpanel_submitbtn.php', $data, 'always');
                break;
            case 21:
                // hidden field
                $str_output .= self::render_template('formbuilder/views/fields/templates/prevpanel_hiddeninput.php', $data, 'always');
                break;
            case 22:
                // star rating
                $str_output .= self::render_template('formbuilder/views/fields/templates/prevpanel_ratingstar.php', $data, 'always');
                break;
            case 23:
                // color picker
                $str_output .= self::render_template('formbuilder/views/fields/templates/prevpanel_colorpicker.php', $data, 'always');
                break;
            case 24:
                // date picker
                $str_output .= self::render_template('formbuilder/views/fields/templates/prevpanel_datepicker.php', $data, 'always');
                break;
            case 25:
                // time picker
                $str_output .= self::render_template('formbuilder/views/fields/templates/prevpanel_timepicker.php', $data, 'always');
                break;
            case 26:
                // date time
                $str_output .= self::render_template('formbuilder/views/fields/templates/prevpanel_datetime.php', $data, 'always');
                break;
            case 27:
                // recaptcha
                $str_output .= self::render_template('formbuilder/views/fields/templates/prevpanel_recaptcha.php', $data, 'always');
                break;
            case 28:
                // prepended text
                $str_output .= self::render_template('formbuilder/views/fields/templates/prevpanel_preptext.php', $data, 'always');
                break;
            case 29:
                // appended text
                $str_output .= self::render_template('formbuilder/views/fields/templates/prevpanel_appetext.php', $data, 'always');
                break;
            case 30:
                // prep app text
                $str_output .= self::render_template('formbuilder/views/fields/templates/prevpanel_prepapptext.php', $data, 'always');
                break;
            case 32:
                // divider
                $str_output .= self::render_template('formbuilder/views/fields/templates/prevpanel_divider.php', $data, 'always');
                break;
            case 33:
                // heading 1
                $str_output .= self::render_template('formbuilder/views/fields/templates/prevpanel_heading1.php', $data, 'always');
                break;
            case 34:
                // heading 2
                $str_output .= self::render_template('formbuilder/views/fields/templates/prevpanel_heading2.php', $data, 'always');
                break;
            case 35:
                // heading 3
                $str_output .= self::render_template('formbuilder/views/fields/templates/prevpanel_heading3.php', $data, 'always');
                break;
            case 36:
                // heading 4
                $str_output .= self::render_template('formbuilder/views/fields/templates/prevpanel_heading4.php', $data, 'always');
                break;
            case 37:
                // heading 5
                $str_output .= self::render_template('formbuilder/views/fields/templates/prevpanel_heading5.php', $data, 'always');
                break;
            case 38:
                // heading 6
                $str_output .= self::render_template('formbuilder/views/fields/templates/prevpanel_heading6.php', $data, 'always');
                break;
            case 39:
                // wizard buttons
                $str_output .= self::render_template('formbuilder/views/fields/templates/prevpanel_wizardbtn.php', $data, 'always');
                break;
            case 40:
                // switch
                $str_output .= self::render_template('formbuilder/views/fields/templates/prevpanel_switch.php', $data, 'always');
                break;
            case 41:
                // dyn checkbox
                $str_output .= self::render_template('formbuilder/views/fields/templates/prevpanel_dyncheckbox.php', $data, 'always');
                break;
            case 42:
                // dyn radiobtn
                $str_output .= self::render_template('formbuilder/views/fields/templates/prevpanel_dynradiobtn.php', $data, 'always');
                break;
            case 43:
                // date
                $str_output .= self::render_template('formbuilder/views/fields/templates/prevpanel_datetime_2.php', $data, 'always');
                break;
            default:
                break;
        }

        $return                = array();
        $return['output_html'] = $str_output;
        return $return;
    }
    protected function getChildren_innerGrid($type)
    {

        $str_output = '';
        switch (intval($type)) {
            case 1:
                ob_start();
        ?>
                <td data-maxpercent="100" data-blocks="12" width="100%">
                    <div class="uiform-items-container uiform-grid-inner-col">
                    </div>
                </td>
            <?php
                $str_output .= ob_get_contents();
                ob_end_clean();
                break;
            case 2:
                ob_start();
            ?>
                <td data-maxpercent="50" data-blocks="6" width="50%">
                    <div class="uiform-items-container uiform-grid-inner-col rkfm-bend-fcontainer-wrap">

                    </div>
                </td>
                <td data-maxpercent="100" data-blocks="6" width="50%">
                    <div class="uiform-items-container uiform-grid-inner-col">

                    </div>
                </td>
            <?php
                $str_output .= ob_get_contents();
                ob_end_clean();
                break;
            case 3:
                ob_start();
            ?>
                <td data-maxpercent="33.3" data-blocks="4" width="33.3%">
                    <div class="uiform-items-container uiform-grid-inner-col rkfm-bend-fcontainer-wrap">

                    </div>
                </td>
                <td data-maxpercent="66.6" data-blocks="4" width="33.3%">
                    <div class="uiform-items-container uiform-grid-inner-col rkfm-bend-fcontainer-wrap">

                    </div>
                </td>
                <td data-maxpercent="100" data-blocks="4" width="33.3%">
                    <div class="uiform-items-container uiform-grid-inner-col">

                    </div>
                </td>
            <?php
                $str_output .= ob_get_contents();
                ob_end_clean();
                break;
            case 4:
                ob_start();
            ?>
                <td data-maxpercent="25" data-blocks="3" width="25%">
                    <div class="uiform-items-container uiform-grid-inner-col rkfm-bend-fcontainer-wrap">

                    </div>
                </td>
                <td data-maxpercent="50" data-blocks="3" width="25%">
                    <div class="uiform-items-container uiform-grid-inner-col rkfm-bend-fcontainer-wrap">

                    </div>
                </td>
                <td data-maxpercent="75" data-blocks="3" width="25%">
                    <div class="uiform-items-container uiform-grid-inner-col rkfm-bend-fcontainer-wrap">

                    </div>
                </td>
                <td data-maxpercent="100" data-blocks="3" width="25%">
                    <div class="uiform-items-container uiform-grid-inner-col">

                    </div>
                </td>
            <?php
                $str_output .= ob_get_contents();
                ob_end_clean();
                break;
            case 5:
                ob_start();
            ?>
                <td data-maxpercent="16.6" data-blocks="2" width="16.6%">
                    <div class="uiform-items-container uiform-grid-inner-col rkfm-bend-fcontainer-wrap">

                    </div>
                </td>
                <td data-maxpercent="33.3" data-blocks="2" width="16.6%">
                    <div class="uiform-items-container uiform-grid-inner-col rkfm-bend-fcontainer-wrap">

                    </div>
                </td>
                <td data-maxpercent="50" data-blocks="2" width="16.6%">
                    <div class="uiform-items-container uiform-grid-inner-col rkfm-bend-fcontainer-wrap">

                    </div>
                </td>
                <td data-maxpercent="66.6" data-blocks="2" width="16.6%">
                    <div class="uiform-items-container uiform-grid-inner-col rkfm-bend-fcontainer-wrap">

                    </div>
                </td>
                <td data-maxpercent="83.3" data-blocks="2" width="16.6%">
                    <div class="uiform-items-container uiform-grid-inner-col rkfm-bend-fcontainer-wrap">

                    </div>
                </td>
                <td data-maxpercent="100" data-blocks="2" width="16.6%">
                    <div class="uiform-items-container uiform-grid-inner-col">

                    </div>
                </td>
        <?php
                $str_output .= ob_get_contents();
                ob_end_clean();
                break;
        }
        return $str_output;
    }

    protected function getChildren_genCol($type, $key, $cols)
    {
        $content = '';
        /*grid type*/
        switch (intval($type)) {
            case 1:
                switch (intval($key)) {
                    case 0:
                        $grid_maxpercent = '100';
                        $grid_blocks     = '12';
                        $grid_width      = '100%';
                        break;
                }
                break;
            case 2:
                switch (intval($key)) {
                    case 0:
                        $grid_maxpercent = '50';
                        $grid_blocks     = '6';
                        $grid_width      = '50%';
                        break;
                    case 1:
                        $grid_maxpercent = '100';
                        $grid_blocks     = '6';
                        $grid_width      = '50%';
                        break;
                }
                break;
            case 3:
                switch (intval($key)) {
                    case 0:
                        $grid_maxpercent = '33.3';
                        $grid_blocks     = '4';
                        $grid_width      = '33.3%';
                        break;
                    case 1:
                        $grid_maxpercent = '66.6';
                        $grid_blocks     = '4';
                        $grid_width      = '33.3%';
                        break;
                    case 2:
                        $grid_maxpercent = '100';
                        $grid_blocks     = '4';
                        $grid_width      = '33.3%';
                        break;
                }
                break;
            case 4:
                switch (intval($key)) {
                    case 0:
                        $grid_maxpercent = '25';
                        $grid_blocks     = '3';
                        $grid_width      = '25%';
                        break;
                    case 1:
                        $grid_maxpercent = '50';
                        $grid_blocks     = '3';
                        $grid_width      = '25%';
                        break;
                    case 2:
                        $grid_maxpercent = '75';
                        $grid_blocks     = '3';
                        $grid_width      = '25%';
                        break;
                    case 3:
                        $grid_maxpercent = '100';
                        $grid_blocks     = '3';
                        $grid_width      = '25%';
                        break;
                }
                break;
            case 5:
                switch (intval($key)) {
                    case 0:
                        $grid_maxpercent = '16.6';
                        $grid_blocks     = '2';
                        $grid_width      = '16.6%';
                        break;
                    case 1:
                        $grid_maxpercent = '33.3';
                        $grid_blocks     = '2';
                        $grid_width      = '16.6%';
                        break;
                    case 2:
                        $grid_maxpercent = '50';
                        $grid_blocks     = '2';
                        $grid_width      = '16.6%';
                        break;
                    case 3:
                        $grid_maxpercent = '66.6';
                        $grid_blocks     = '2';
                        $grid_width      = '16.6%';
                        break;
                    case 4:
                        $grid_maxpercent = '83.3';
                        $grid_blocks     = '2';
                        $grid_width      = '16.6%';
                        break;
                    case 5:
                        $grid_maxpercent = '100';
                        $grid_blocks     = '2';
                        $grid_width      = '16.6%';
                        break;
                }
                break;
        }

        ob_start();
        ?>
        <td data-maxpercent="<?php echo $grid_maxpercent; ?>" data-blocks="<?php echo $grid_blocks; ?>" width="<?php echo $grid_width; ?>">
            <?php
            $content .= ob_get_contents();
            ob_end_clean();

            return $content;
        }

        protected function generate_previewpanel_getChildren($child_field)
        {
        }

        protected function generate_form_getChildren($child_field)
        {
            $str_output   = '';
            $str_output_2 = '';
            switch (intval($child_field['type'])) {
                case 1:
                case 2:
                case 3:
                case 4:
                case 5:
                    // generating css
                    $data          = array();
                    $data          = $this->gen_post_src[$child_field['num_tab']][$child_field['id']];
                    $str_output_2 .= self::$_modules['formbuilder']['fields']->posthtml_gridsystem_css($data);

                    // generating html
                    if (intval($child_field['count_children']) >= 0) {
                        // generate class
                        $tmp_class = 'zgpbf-gridsystem-cont ';
                        if (isset($data['main']['skin']['custom_css']['ctm_class'])) {
                            $tmp_class .= $data['main']['skin']['custom_css']['ctm_class'];
                        }

                        $str_output .= '<div id="zgfb_' . $child_field['id'] . '" class="' . $tmp_class . '">';
                        $str_output .= '<div class="sfdc-container-fluid">';
                        $str_output .= '<div class="sfdc-row">';
                        $count_str   = 0;
                        if (isset($child_field['inner'])) {
                            foreach ($child_field['inner'] as $key => $value) {
                                // generate class
                                $tmpMobileClass = '';
                                if (isset($data['main']['skin']['mobile']['keep_grid_st'])) {
                                    $tmpKeepGrid = $data['main']['skin']['mobile']['keep_grid_st'];
                                    $tmpMobileClass = (intval($tmpKeepGrid) === 1) ? 'sfdc-col-xs-' . $value['cols'] : '';
                                }

                                $str_output .= '<div data-zgpb-blocknum="' . $value['num_tab'] . '" class="zgpb-fl-gs-block-style ' . $tmpMobileClass . ' sfdc-col-sm-' . $value['cols'] . '">';

                                // generate class
                                $tmp_class = 'zgpb-fl-gs-block-inner ';
                                if (isset($data['blocks'][$value['num_tab']]['skin']['custom_css']['ctm_class'])) {
                                    $tmp_class .= $data['blocks'][$value['num_tab']]['skin']['custom_css']['ctm_class'];
                                }

                                if ($count_str === $key) {
                                }

                                $str_output .= '<div class="' . $tmp_class . '">';

                                if (!empty($value['children'])) {
                                    foreach ($value['children'] as $key2 => $value2) {
                                        // get field
                                        $get_data    = array();
                                        $str_output .= '<div class="">';
                                        if (isset($value2['iscontainer']) && intval($value2['iscontainer']) === 1) {
                                            $get_data      = $this->generate_form_getChildren($value2);
                                            $str_output   .= $get_data['output_html'];
                                            $str_output_2 .= $get_data['output_css'];
                                        } else {
                                            $get_data      = $this->generate_form_getField($value2);
                                            $str_output   .= $get_data['output_html'];
                                            $str_output_2 .= $get_data['output_css'];
                                        }
                                        $str_output .= '</div>';
                                    }
                                }
                                $str_output .= '</div>';
                                $str_output .= '</div>';
                            }
                        }

                        $str_output .= '</div>';
                        $str_output .= '</div>';
                        $str_output .= '</div>';
                    }

                    break;
                case 31:
                    /*panel*/
                    $temp_str_output = '';
                    if (isset($child_field['count_children']) && intval($child_field['count_children']) > 0) {
                        $count_str = 0;
                        if (isset($child_field['inner'])) {
                            foreach ($child_field['inner'] as $key => $value) {
                                if (!empty($value['children'])) {
                                    foreach ($value['children'] as $key2 => $value2) {
                                        // get field
                                        $get_data = array();

                                        if (isset($value2['iscontainer']) && intval($value2['iscontainer']) === 1) {
                                            $get_data         = $this->generate_form_getChildren($value2);
                                            $temp_str_output .= $get_data['output_html'];
                                            $str_output_2    .= $get_data['output_css'];
                                        } else {
                                            $get_data         = $this->generate_form_getField($value2);
                                            $temp_str_output .= $get_data['output_html'];
                                            $str_output_2    .= $get_data['output_css'];
                                        }
                                    }
                                }
                            }
                        }
                    }

                    $temp_content      = '';
                    $data_tmp      = array();
                    $data_tmp      = $this->current_data_form[intval($child_field['num_tab'])][$child_field['id']];
                    $temp_content  = self::render_template('formbuilder/views/fields/formhtml_panelfld.php', $data_tmp, 'always');
                    $str_output   .= str_replace('[[%%fields%%]]', $temp_str_output, $temp_content);
                    $str_output_2 .= self::render_template('formbuilder/views/fields/formhtml_panelfld_css.php', $data_tmp, 'always');
                    break;
                default:
                    break;
            }
            $return                = array();
            $return['output_html'] = $str_output;
            $return['output_css']  = $str_output_2;

            return $return;
        }

        protected function generate_admin_form_getChildren($child_field)
        {
            $str_output   = '';
            $str_output_2 = '';

            $grid_order = array(
                1 => 'one',
                2 => 'two',
                3 => 'three',
                4 => 'four',
                5 => 'five',
                6 => 'six',
            );

            switch (intval($child_field['type'])) {
                case 1:
                case 2:
                case 3:
                case 4:
                case 5:
                    if (intval($child_field['count_children']) >= 0) {
                        ob_start();
            ?>
                        <div id="<?php echo $child_field['id']; ?>" data-typefield="<?php echo intval($child_field['type']); ?>" data-iscontainer="1" class="zgpb-gridsytem-box zgpb-field-template uiform-field zgpb-gridsystem-<?php echo $grid_order[intval($child_field['type'])]; ?>">
                            <div class="sfdc-container-fluid">
                                <div class="sfdc-row">
                                    <?php
                                    $str_output .= ob_get_contents();
                                    ob_end_clean();
                                    if (isset($child_field['inner'])) {
                                        $count_str    = 1;
                                        $count_total  = count($child_field['inner']);
                                        $tmp_col_rest = 12;

                                        foreach ($child_field['inner'] as $key => $value) {
                                            // controling 12 cols
                                            $tmp_col_rest2 = $tmp_col_rest - abs($value['cols']);
                                            if ($tmp_col_rest2 < 12 && $tmp_col_rest2 > 0) {
                                                $tmp_col      = abs($value['cols']);
                                                $tmp_col_rest = $tmp_col_rest2;
                                            } else {
                                                $tmp_col = $tmp_col_rest;
                                            }

                                            $str_output .= '<div class="zgpb-fl-gs-block-style sfdc-col-xs-' . $tmp_col . ' sfdc-col-sm-' . $tmp_col . '" data-zgpb-blocknum="' . $value['num_tab'] . '" data-zgpb-width="" data-zgpb-blockcol="' . $tmp_col . '">';
                                            $str_output .= '<div class="uiform-items-container zgpb-fl-gs-block-inner">';

                                            if (!empty($value['children'])) {
                                                foreach ($value['children'] as $key2 => $value2) {
                                                    // get field
                                                    $get_data = array();

                                                    if (isset($value2['iscontainer']) && intval($value2['iscontainer']) === 1) {
                                                        $get_data    = $this->generate_admin_form_getChildren($value2);
                                                        $str_output .= $get_data['output_html'];
                                                    } else {
                                                        $get_data    = $this->generate_previewpanel_getField($value2);
                                                        $str_output .= $get_data['output_html'];
                                                    }
                                                }
                                            }

                                            $str_output .= '</div>';

                                            if ($count_str < $count_total) {
                                                ob_start();
                                    ?>
                                                <div class="zgpb-fl-gridsystem-opt">
                                                    <div data-zgpb-side="1" class="zgpb-fl-gd-drag-line zgpb-fl-gd-drag-line-right">
                                                        <div class="zgpb-fl-gd-opt-icon-handler"></div>
                                                    </div>
                                                </div>
                                            <?php
                                                $str_output .= ob_get_contents();
                                                ob_end_clean();
                                            } else {
                                                ob_start();
                                            ?>
                                                <div class="zgpb-fl-gridsystem-opt"></div>
                                    <?php
                                                $str_output .= ob_get_contents();
                                                ob_end_clean();
                                            }

                                            $str_output .= '</div>';
                                            $count_str++;
                                        }
                                    }

                                    ob_start();
                                    ?>
                                </div>
                            </div>
                        </div>
                    <?php
                        $str_output .= ob_get_contents();
                        ob_end_clean();
                    }
                    break;
                case 31:
                    /*panel*/
                    ob_start();
                    ?>
                    <div id="<?php echo $child_field['id']; ?>" data-typefield="31" data-iscontainer="1" class="uiform-panelfld uiform-field  uiform-field-childs zgpb-field-template">
                        <div class="uiform-field-wrap">
                            <div class="uifm-input31-wrap">
                                <div class="uifm-input31-container">
                                    <div class="rkfm-inp18-row">
                                        <div class="rkfm-inp18-col-sm-2">
                                            <div class="uifm-inp31-txthtml-content"></div>
                                        </div>
                                        <div class="rkfm-inp18-col-sm-10">
                                            <div class="uifm-input31-main-wrap">
                                                <div class="uiform-items-container uiform-grid-inner-col zgpb-fl-gs-block-inner">

                                                    <?php
                                                    $str_output .= ob_get_contents();
                                                    ob_end_clean();
                                                    if (isset($child_field['inner'])) {
                                                        $count_str   = 0;
                                                        $count_total = count($child_field['inner']);

                                                        foreach ($child_field['inner'] as $key => $value) {
                                                            if (!empty($value['children'])) {
                                                                foreach ($value['children'] as $key2 => $value2) {
                                                                    // get field
                                                                    $get_data = array();

                                                                    if (isset($value2['iscontainer']) && intval($value2['iscontainer']) === 1) {
                                                                        $get_data    = $this->generate_admin_form_getChildren($value2);
                                                                        $str_output .= $get_data['output_html'];
                                                                    } else {
                                                                        $get_data    = $this->generate_previewpanel_getField($value2);
                                                                        $str_output .= $get_data['output_html'];
                                                                    }
                                                                }
                                                            }

                                                            $count_str++;
                                                        }
                                                    }
                                                    ob_start();
                                                    ?>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
    <?php
                    $str_output .= ob_get_contents();
                    ob_end_clean();

                    break;
                default:
                    break;
            }
            $return                = array();
            $return['output_html'] = $str_output;

            return $return;
        }


        public function generate_form_container($id, $numtab, $str_output)
        {
            $data = array();
            if (intval($numtab) > 1) {
                $data1                   = array();
                $data1['tab_title']      = $this->current_data_steps['tab_title'];
                $data1['tab_theme']      = $this->current_data_wizard;
                $data['form_tab_head']   = self::render_template('formbuilder/views/forms/formhtml_tabheader.php', $data1);
                $data2                   = array();
                $data['form_tab_footer'] = self::render_template('formbuilder/views/forms/formhtml_tabfooter.php', $data2);
            }

            $data['tab_count']    = $numtab;
            $data['form_content'] = $str_output;
            $data['form_id']      = $id;
            $data['wizard']       = $this->current_data_wizard;
            $data['onsubm']       = $this->current_data_onsubm;
            $data['main']         = $this->current_data_main;
            $data['clogic']       = $this->saveform_clogic;

            if ($this->is_multistep === true) {
                return self::render_template('formbuilder/views/forms/formhtml_form_multistep.php', $data);
            };

            return self::render_template('formbuilder/views/forms/formhtml_form.php', $data);
        }

        public function generate_admin_form_container($id, $numtab, $str_output)
        {
        }

        public function generate_previewpanel_container($id, $numtab, $str_output)
        {
            $data = array();
            if (intval($numtab) > 1) {
                $data1                   = array();
                $data1['tab_title']      = $this->current_data_steps['tab_title'];
                $data1['tab_theme']      = $this->current_data_wizard;
                $data['form_tab_head']   = self::render_template('formbuilder/views/forms/previewpanel_tabheader.php', $data1);
                $data2                   = array();
                $data['form_tab_footer'] = self::render_template('formbuilder/views/forms/previewpanel_tabfooter.php', $data2);
            }
            $data['tab_count']    = $numtab;
            $data['form_content'] = $str_output;
            $data['form_id']      = $id;
            $data['wizard']       = $this->current_data_wizard;
            $data['onsubm']       = $this->current_data_onsubm;
            $data['main']         = $this->current_data_main;
            $data['clogic']       = $this->saveform_clogic;
            return self::render_template('formbuilder/views/forms/previewpanel_form.php', $data);
        }

        public function generate_previewpanel_tabContent($tab_cont_num, $tabindex, $str_output)
        {
            $output                  = '';
            $data                    = array();
            $data['tabindex']        = $tabindex;
            $data['tab_html_fields'] = $str_output;
            // apply function
            $output .= self::render_template('formbuilder/views/forms/previewpanel_tabcontainer.php', $data, 'always');
            return $output;
        }

        public function generate_form_tabContent($tab_cont_num, $tabindex, $str_output)
        {
            $output                  = '';
            $data                    = array();
            $data['tabindex']        = $tabindex;
            $data['tab_html_fields'] = $str_output;
            if (intval($tab_cont_num) > 1) {
                // apply function
                $output .= self::render_template('formbuilder/views/forms/formhtml_tabcontainer.php', $data, 'always');
            } else {
                $output .= $str_output;
            }
            return $output;
        }

        public function generate_admin_form_tabContent($tab_cont_num, $tabindex, $str_output)
        {
            $output                  = '';
            $data                    = array();
            $data['tabindex']        = $tabindex;
            $data['tab_html_fields'] = $str_output;
            if (intval($tab_cont_num) > 1) {
                // apply function
                $output .= self::render_template('formbuilder/views/forms/formhtml_tabcontainer.php', $data, 'always');
            } else {
                $output .= $str_output;
            }
            return $output;
        }


        public function save_form_clogic()
        {
            $clogic_src = $this->saveform_clogic;
            if (!empty($clogic_src)) {
                // get fires
                $fields_fire = array();
                foreach ($clogic_src['cond'] as $key => $value) {
                    foreach ($value['list'] as $key2 => $value2) {
                        if (!empty($value2)) {
                            if (!isset($fields_fire[$value2['field_fire']]['list'][$value['field_cond']])) {
                                $fields_fire[$value2['field_fire']]['list'][] = $value['field_cond'];
                            }
                        } else {
                            unset($clogic_src['cond'][$key]['list'][$key2]);
                        }
                    }
                }
                $this->saveform_clogic = $clogic_src;
                // field fires
                $logic_field_fire = array();
                foreach ($fields_fire as $key => $value) {
                    $temp_logic               = array();
                    $temp_logic['field_fire'] = $key;
                    $tmp_list                 = array();
                    foreach ($value['list'] as $value2) {
                        $tmp_list[] = array('field_cond' => $value2);
                    }
                    $temp_logic['list']       = $tmp_list;
                    $logic_field_fire[$key] = $temp_logic;
                }

                $clogic_src['fire']    = $logic_field_fire;
                $this->saveform_clogic = $clogic_src;
            }
        }

        public function save_data_fields($form_id = null)
        {

            /* check for enabled field for reports */
            $check_rec_querys = $this->model_fields->queryGetQtyFieldsEnabled($form_id);
            if (intval($check_rec_querys) === 1) {
                $tmp_query_list  = array();
                $rec_querys_list = $this->model_fields->queryGetListFieldsEnabled($form_id);

                foreach ($rec_querys_list as $value) {
                    $tmp_query_list[] = $value->fmf_uniqueid;
                }

                // storing rec orders
                $tmp_recorder_list = array();
                $rec_querys_list   = $this->model_fields->queryGetListFieldsById($form_id);
                foreach ($rec_querys_list as $value) {
                    $tmp_recorder_list[$value->fmf_uniqueid] = $value->order_rec;
                }
            }

            // deleting form
            $where = array(
                'form_fmb_id' => $form_id,
            );
            $this->wpdb->delete($this->model_fields->table, $where);
            // creating again
            $data_form = $this->formsmodel->getFormById($form_id);
            $fmb_data  = json_decode($data_form->fmb_data, true);
            $steps_src = $fmb_data['steps_src'];

            $set_rec_querys = 0;
            if (!empty($steps_src)) {
                foreach ($steps_src as $tabindex => $fields) {
                    if (!empty($fields)) {
                        foreach ($fields as $key => $value) {
                            $data                 = array();
                            $data['fmf_uniqueid'] = $value['id'];
                            switch (intval($value['type'])) {
                                case 6:
                                case 7:
                                case 8:
                                case 9:
                                case 10:
                                case 11:
                                case 12:
                                case 13:
                                case 15:
                                case 16:
                                case 17:
                                case 18:
                                case 21:
                                case 22:
                                case 23:
                                case 24:
                                case 25:
                                case 26:
                                case 28:
                                case 29:
                                case 30:
                                case 40:
                                case 41:
                                case 42:
                                    // assign selected fields to the report
                                    if (intval($check_rec_querys) === 0 && $set_rec_querys < 5) {
                                        $data['fmf_status_qu'] = 1;
                                        $set_rec_querys++;
                                    } elseif (intval($check_rec_querys) === 1) {
                                        if (in_array($value['id'], $tmp_query_list)) {
                                            $data['fmf_status_qu'] = 1;
                                        }
                                    }
                                    $data['fmf_fieldname'] = $value['field_name'];
                                    $data['order_frm']     = $value['order_frm'];

                                    if (isset($tmp_recorder_list[$value['id']]) && intval($tmp_recorder_list[$value['id']]) > 0) {
                                        $data['order_rec'] = $tmp_recorder_list[$value['id']];
                                    } else {
                                        $data['order_rec'] = $value['order_frm'];
                                    }
                                    break;
                                case 19:
                                case 20:
                                case 27:
                                    // asigning order to fields
                                    $data['order_frm'] = $value['order_frm'];
                                    break;
                            }

                            $data['fmf_data']    = json_encode($value);
                            $data['type_fby_id'] = $value['type'];
                            $data['form_fmb_id'] = $form_id;
                            $this->wpdb->insert($this->model_fields->table, $data);

                            if (isset($value['clogic']) && intval($value['clogic']['show_st']) === 1) {
                                $tmp_clogic               = array();
                                $tmp_clogic['field_cond'] = $value['id'];
                                $tmp_clogic['action']     = $value['clogic']['f_show'];

                                foreach ($value['clogic']['list'] as $key2 => $value2) {
                                    if (empty($value2)) {
                                        unset($value['clogic']['list'][$key2]);
                                    }
                                }
                                $tmp_clogic['list']              = array_filter($value['clogic']['list']);
                                $tmp_clogic['req_match']         = (intval($value['clogic']['f_all']) === 1) ? count($value['clogic']['list']) : 1;
                                $this->saveform_clogic['cond'][] = $tmp_clogic;
                            }
                        }
                    }
                }
            }
        }

        public function generate_form_html($form_id = null)
        {

            // all fields position
            $tab_cont           = $this->current_data_tab_cont;
            $this->gen_post_src = $this->current_data_form;
            $tab_cont_num       = $this->current_data_num_tabs;

            // generating

            $str_output_2   = '';
            $str_output_tab = '';

            foreach ($tab_cont as $key => $value) {
                // tabs
                $str_output = '';
                if (!empty($value['content'])) {
                    foreach ($value['content'] as $key2 => $value2) {
                        $get_data = array();
                        // fields
                        if (isset($value2['iscontainer']) && intval($value2['iscontainer']) === 1) {
                            $get_data      = $this->generate_form_getChildren($value2);
                            $str_output   .= $get_data['output_html'];
                            $str_output_2 .= $get_data['output_css'];
                        } else {
                            $get_data      = $this->generate_form_getField($value2);
                            $str_output   .= $get_data['output_html'];
                            $str_output_2 .= $get_data['output_css'];
                        }
                    }
                }

                // set tab container
                $str_output_tab .= $this->generate_form_tabContent($tab_cont_num, $key, $str_output);
                // jump if it is one
                if (intval($tab_cont_num) === 1) {
                    break 1;
                }
            }

            // generate form css
            if ($this->is_multistep === false) {
                $str_output_2 .= $this->generate_form_css($form_id);
            }


            if ($tab_cont_num > 1) {
                $str_output_2 .= $this->generate_form_tab_css($form_id);
            }

            $return                = array();
            $return['output_html'] = $this->generate_form_container($form_id, $tab_cont_num, $str_output_tab);
            $return['output_css']  = $str_output_2;

            return $return;
        }

        public function generate_admin_form_html($form_id = null)
        {
            $data_form = $this->formsmodel->getFormById($form_id);
            $fmb_data  = json_decode($data_form->fmb_data, true);
            // all fields position
            $tab_cont = $fmb_data['steps']['tab_cont'];

            // generating

            $str_output_tab = '';
            $tab_cont_num   = $fmb_data['num_tabs'];

            if (!empty($tab_cont)) {
                foreach ($tab_cont as $key => $value) {
                    // tabs
                    $str_output = '';
                    if (!empty($value['content'])) {
                        foreach ($value['content'] as $key2 => $value2) {
                            $get_data = array();

                            // fields
                            if (isset($value2['iscontainer']) && intval($value2['iscontainer']) === 1) {
                                $get_data    = $this->generate_admin_form_getChildren($value2);
                                $str_output .= $get_data['output_html'];
                            } else {
                                $get_data    = $this->generate_previewpanel_getField($value2);
                                $str_output .= $get_data['output_html'];
                            }
                        }
                    }

                    // set tab container
                    $str_output_tab .= $this->generate_previewpanel_tabContent($tab_cont_num, $key, $str_output);
                    // jump if it is one
                    if (intval($tab_cont_num) === 1) {
                        break 1;
                    }
                }
            } else {
                // set tab container
                $str_output_tab .= $this->generate_previewpanel_tabContent(0, 0, '');
            }

            $return                = array();
            $return['output_html'] = $this->generate_previewpanel_container($form_id, $tab_cont_num, $str_output_tab);

            return $return['output_html'];
        }


        public function generate_previewpanel_html($data)
        {
            $fmb_data = $data['fmb_data'];
            // all fields position
            $tab_cont = $fmb_data['steps']['tab_cont'];
            // all data fields
            $steps_src                 = $fmb_data['steps_src'];
            $this->current_data_form   = $steps_src;
            $this->current_data_steps  = $fmb_data['steps'];
            $this->current_data_skin   = $fmb_data['skin'];
            $this->current_data_wizard = ($fmb_data['wizard']) ? $fmb_data['wizard'] : array();
            $this->current_data_onsubm = ($fmb_data['onsubm']) ? $fmb_data['onsubm'] : array();
            $this->current_data_main   = ($fmb_data['main']) ? $fmb_data['main'] : array();
            // generating

            $str_output_tab = '';
            $tab_cont_num   = $fmb_data['num_tabs'];

            if (!empty($tab_cont)) {
                foreach ($tab_cont as $key => $value) {
                    // tabs
                    $str_output = '';
                    if (!empty($value['content'])) {
                        foreach ($value['content'] as $key2 => $value2) {
                            $get_data = array();
                            // fields
                            if (isset($value2['iscontainer']) && intval($value2['iscontainer']) === 1) {
                                // $get_data = $this->generate_previewpanel_getChildren($value2);

                                $get_data = $this->generate_admin_form_getChildren($value2);

                                $str_output .= $get_data['output_html'];
                            } else {
                                $get_data    = $this->generate_previewpanel_getField($value2);
                                $str_output .= $get_data['output_html'];
                            }
                        }
                    }

                    // set tab container
                    $str_output_tab .= $this->generate_previewpanel_tabContent($tab_cont_num, $key, $str_output);
                    // jump if it is one
                    if (intval($tab_cont_num) === 1) {
                        break 1;
                    }
                }
            } else {
                // there nos data
                // set tab container
                $str_output_tab .= $this->generate_previewpanel_tabContent(0, 0, '');
            }

            $return                = array();
            $return['output_html'] = $this->generate_previewpanel_container(null, $str_output_tab, $str_output_tab);

            return $return;
        }
        public function import_form()
        {
            $data               = array();
            echo self::loadPartial('layout.php', 'formbuilder/views/forms/import_form.php', $data);
        }
        public function export_form()
        {
            $data               = array();
            $data['list_forms'] = $this->formsmodel->getListForms();
            echo self::loadPartial('layout.php', 'formbuilder/views/forms/export_form.php', $data);
        }

        public function ajax_load_export_form()
    {

        check_ajax_referer('zgfm_ajax_nonce', 'zgfm_security');

        $form_id   = (isset($_POST['form_id']) && $_POST['form_id']) ? Uiform_Form_Helper::sanitizeInput($_POST['form_id']) : 0;
        $data_form = $this->formsmodel->getFormById($form_id);

        if (empty($data_form)) {
            return;
        }

        $exportCode = $this->load_export_form_get_code($data_form);

        $code_export                  = Uiform_Form_Helper::base64url_encode(serialize($exportCode));
        echo $code_export;
        wp_die();
    }
    
    private function load_export_form_get_code($data_form){

             

            $type = $data_form->fmb_type;

            $data_exp = [];
            $data_exp['fmb_data']         = $data_form->fmb_data;
            $data_exp['fmb_html_backend'] = $data_form->fmb_html_backend;
            $data_exp['fmb_name']         = $data_form->fmb_name . ' - copy';
            $data_exp['fmb_rec_tpl_html'] = $data_form->fmb_rec_tpl_html;
            $data_exp['fmb_rec_tpl_st']   = $data_form->fmb_rec_tpl_st;
            $data_exp['fmb_type']   = $data_form->fmb_type;
            $data_exp['fmb_parent']   = $data_form->fmb_parent;
            $data_exp['fmb_id']   = $data_form->fmb_id;

            if (intval($type) === 1) {
                $data_exp['fmb_data2']         = $data_form->fmb_data2;
            }


            $exportCode = [];
            $exportCode['app_ver'] = UIFORM_VERSION;
            $exportCode['form'] = $data_exp;
            $exportCode['type'] = $type;



            if (intval($type) === 1) {

                $exportCode['children']         = [];
                $children = $this->formsmodel->getChildFormByParentId($data_form->fmb_id);
                foreach ($children as $key => $child) {
                    $data_exp2 = [];
                    $data_exp2['fmb_data']         = $child->fmb_data;
                    $data_exp2['fmb_html_backend'] = $child->fmb_html_backend;
                    $data_exp2['fmb_name']         = $child->fmb_name;
                    $data_exp2['fmb_rec_tpl_html'] = $child->fmb_rec_tpl_html;
                    $data_exp2['fmb_rec_tpl_st']   = $child->fmb_rec_tpl_st;
                    $data_exp2['fmb_type']   = $child->fmb_type;
                    $data_exp2['fmb_parent']   = $child->fmb_parent;
                    $data_exp2['fmb_id'] = $child->fmb_id;

                    $exportCode['children'][] = $data_exp2;
                }
            }


            //addons
            $tmpData = $this->model_addon_details->getAddonsDataByForm($data_form->fmb_id);
            $addsArr = [];
            if (!empty($tmpData)) {
                foreach ($tmpData as $key => $value) {
                    $addsArr2 = [
                        'add_name' => $value->add_name,
                        'flag_status' => $value->flag_status,
                        'adet_data' => $value->adet_data
                    ];
                    $addsArr[] = $addsArr2;
                }
            }
            $exportCode['addons'] = $addsArr;
            return $exportCode;
        }

        public function generate_form_css($form_id = null)
        {
            $data           = array();
            $data['idform'] = $form_id;
            $data['skin']   = $this->current_data_skin;
            return self::render_template('formbuilder/views/forms/formhtml_css_form.php', $data);
        }

        public function generate_form_tab_css($form_id = null)
        {
            $data           = array();
            $data['idform'] = $form_id;
            $data['wizard'] = $this->current_data_wizard;
            return self::render_template('formbuilder/views/forms/formhtml_css_wizard.php', $data);
        }

        public function ajax_mm_load_childform()
        {

            check_ajax_referer('zgfm_ajax_nonce', 'zgfm_security');

            $json    = array();
            $form_id = (isset($_POST['uifm_frm_main_child_id'])) ? Uiform_Form_Helper::sanitizeInput(trim($_POST['uifm_frm_main_child_id'])) : '';

            $data_form           = $this->formsmodel->getFormById($form_id);
            $data_form->fmb_data = json_decode($data_form->fmb_data);
            $json['data']        = $data_form;

            header('Content-Type: application/json');
            echo json_encode($json);
            wp_die();
        }

        public function ajax_load_form()
        {

            check_ajax_referer('zgfm_ajax_nonce', 'zgfm_security');

            $json    = array();
            $form_id = (isset($_POST['form_id'])) ? Uiform_Form_Helper::sanitizeInput(trim($_POST['form_id'])) : '';

            $data_form           = $this->formsmodel->getFormById($form_id);
            $data_form->fmb_data = json_decode($data_form->fmb_data);
            $json['data']        = $data_form;

            header('Content-Type: application/json');
            echo json_encode($json);
            wp_die();
        }

        public function list_uiforms()
        {
            if( ZIGAFORM_F_LITE  !== 1){
                $validateLicense = get_option('zgfm_wpfb_code', []);
                if (!isset($validateLicense['is_valid']) || (isset($validateLicense['is_valid']) && intval($validateLicense['is_valid']) === 0)) {
                    echo self::render_template('formbuilder/views/forms/verify_pcode.php', []);
                    return;
                }
            }
            
            
            $filter_data = get_option('zgfm_listform_searchfilter', true);
            $data2       = array();
            if (empty($filter_data)) {
                $data2['per_page']   = intval($this->per_page);
                $data2['search_txt'] = '';
                $data2['orderby']    = 'asc';
            } else {
                $data2['per_page']   = isset($filter_data['per_page']) ? intval($filter_data['per_page']) : '5';
                $data2['search_txt'] = isset($filter_data['search_txt']) ? $filter_data['search_txt'] : '';
                $data2['orderby']    = isset($filter_data['orderby']) ? $filter_data['orderby'] : '';
            }

            $offset          = (isset($_GET['offset'])) ? Uiform_Form_Helper::sanitizeInput($_GET['offset']) : 0;
            $data2['offset'] = $offset;

            $form_data = $this->formsmodel->ListTotals();

            $data2['all'] = $form_data->r_all??'';
            $data2['trash'] = $form_data->r_trash??'';
            $data2['subcurrent'] = 1;
            $data2['subsubsub'] = List_data::get()->subsubsub($data2);

            echo self::loadPartial('layout.php', 'formbuilder/views/forms/list_forms.php', $data2);
        }


        /**
         * Show trash list
         *
         * @return void
         */
        public function list_trash()
        {

            $filter_data = get_option('zgfm_listform_searchfilter', true);
            $data2       = array();
            if (empty($filter_data)) {
                $data2['per_page']   = intval($this->per_page);
                $data2['orderby']    = 'asc';
            } else {
                $data2['per_page']   = intval($filter_data['per_page'] ?? '');
                $data2['orderby']    = $filter_data['orderby'] ?? '';
            }

            $offset          = (isset($_GET['offset'])) ? Uiform_Form_Helper::sanitizeInput($_GET['offset']) : 0;
            $data2['offset'] = $offset;

            $form_data = $this->formsmodel->ListTotals();
            $data2['title'] = __('Forms in trash', 'FRocket_admin');
            $data2['all'] = $form_data->r_all;
            $data2['trash'] = $form_data->r_trash;
            $data2['header_buttons'] = List_data::get()->list_detail_form_headerbuttons();
            $data2['script_trigger'] = 'zgfm_back_general.formslist_trashsearch_process();';
            $data2['subcurrent'] = 2;
            $data2['subsubsub'] = List_data::get()->subsubsub($data2);

            $content = List_data::get()->show_list($data2);
            echo self::loadPartial2('layout.php', $content);
        }


        /**
         * List trash forms
         *
         * @return void
         */
        public function ajax_trashformlist_sendfilter()
        {
            check_ajax_referer('zgfm_ajax_nonce', 'zgfm_security');

            $data_filter = (isset($_POST['data_filter']) && $_POST['data_filter']) ? $_POST['data_filter'] : '';

            $opt_save   = (isset($_POST['opt_save']) && $_POST['opt_save']) ? Uiform_Form_Helper::sanitizeInput($_POST['opt_save']) : 0;
            $opt_offset = (isset($_POST['opt_offset']) && $_POST['opt_offset']) ? Uiform_Form_Helper::sanitizeInput($_POST['opt_offset']) : 0;

            parse_str($data_filter, $data_filter_arr);

            $per_page   = $data_filter_arr['zgfm-listform-pref-perpage'];
            $orderby    = $data_filter_arr['zgfm-listform-pref-orderby'];

            $data               = array();
            $data['per_page']   = $per_page;
            $data['orderby']    = $orderby;

            if (intval($opt_save) === 1) {
                update_option('zgfm_listform_trash', $data);
            }

            $data['segment'] = 0;
            $data['offset']  = $opt_offset;

            $result = $this->ajax_trashformlist_refresh($data);

            $json            = array();
            $json['content'] = $result;

            header('Content-Type: application/json');
            echo json_encode($json);
            wp_die();
        }

        /**
         * get forms in trash
         *
         * @param [type] $data
         * @return void
         */
        public function ajax_trashformlist_refresh($data)
        {

            require_once UIFORM_FORMS_DIR . '/classes/Pagination.php';
            $this->pagination = new CI_Pagination();

            $offset = $data['offset'];

            // list all forms
            $config                         = array();
            $config['base_url']             = admin_url() . '?page=zgfm_form_builder&zgfm_mod=formbuilder&zgfm_contr=forms&zgfm_action=list_trash';

            $tmp = $this->formsmodel->ListTotals();
            $config['total_rows']           = $tmp->r_trash;
            $config['per_page']             = $data['per_page'];
            $config['first_link']           = 'First';
            $config['last_link']            = 'Last';
            $config['full_tag_open']        = '<ul class="pagination pagination-sm">';
            $config['full_tag_close']       = '</ul>';
            $config['first_tag_open']       = '<li>';
            $config['first_tag_close']      = '</li>';
            $config['last_tag_open']        = '<li>';
            $config['last_tag_close']       = '</li>';
            $config['cur_tag_open']         = '<li class="zgfm-pagination-active"><span>';
            $config['cur_tag_close']        = '</span></li>';
            $config['next_tag_open']        = '<li>';
            $config['next_tag_close']       = '</li>';
            $config['prev_tag_open']        = '<li>';
            $config['prev_tag_close']       = '</li>';
            $config['num_tag_open']         = '<li>';
            $config['num_tag_close']        = '</li>';
            $config['page_query_string']    = true;
            $config['query_string_segment'] = 'offset';

            $this->pagination->initialize($config);
            // If the pagination library doesn't recognize the current page add:
            $this->pagination->cur_page = $offset;

            $data2               = array();
            $data2['per_page']   = $data['per_page'];
            $data2['segment']    = $offset;
            $data2['orderby']    = $data['orderby'];
            $data2['query']      = $this->formsmodel->getListTrashFormsFiltered($data2);
            $data2['pagination'] = $this->pagination->create_links();
            $data2['obj_list_data'] = List_data::get();
            $data2['is_trash']  = 1;

            //$data2['list_buttons'] = List_data::get()->list_detail_form_buttons();
            ///$content=List_data::get()->list_detail($data3);
            return List_data::get()->list_detail($data2);

            //return self::render_template( 'formbuilder/views/forms/list_forms_table.php', $data3 );
        }

        /**
         * list forms
         *
         * @return void
         */
        public function ajax_formlist_sendfilter()
        {
            check_ajax_referer('zgfm_ajax_nonce', 'zgfm_security');

            $data_filter = (isset($_POST['data_filter']) && $_POST['data_filter']) ? $_POST['data_filter'] : '';

            $opt_save   = (isset($_POST['opt_save']) && $_POST['opt_save']) ? Uiform_Form_Helper::sanitizeInput($_POST['opt_save']) : 0;
            $opt_offset = (isset($_POST['opt_offset']) && $_POST['opt_offset']) ? Uiform_Form_Helper::sanitizeInput($_POST['opt_offset']) : 0;

            parse_str($data_filter, $data_filter_arr);

            $per_page   = $data_filter_arr['zgfm-listform-pref-perpage'];
            $search_txt = $data_filter_arr['zgfm-listform-pref-search'];
            $orderby    = $data_filter_arr['zgfm-listform-pref-orderby'];

            $data               = array();
            $data['per_page']   = $per_page;
            $data['search_txt'] = $search_txt;
            $data['orderby']    = $orderby;

            if (intval($opt_save) === 1) {
                update_option('zgfm_listform_searchfilter', $data);
            }

            $data['segment'] = 0;
            $data['offset']  = $opt_offset;

            // self::$_models['formbuilder']['form']->getListFormsFiltered($data);

            $result = $this->ajax_formlist_refresh($data);

            $json            = array();
            $json['content'] = $result;

            header('Content-Type: application/json');
            echo json_encode($json);
            wp_die();
        }

        public function ajax_formlist_refresh($data)
        {

            require_once UIFORM_FORMS_DIR . '/classes/Pagination.php';
            $this->pagination = new CI_Pagination();

            $offset = $data['offset'];

            // list all forms
            $config                         = array();
            $config['base_url']             = admin_url() . '?page=zgfm_form_builder&zgfm_mod=formbuilder&zgfm_contr=forms&zgfm_action=list_uiforms';
            $config['total_rows']           = $this->formsmodel->CountForms();
            $config['per_page']             = $data['per_page'];
            $config['first_link']           = 'First';
            $config['last_link']            = 'Last';
            $config['full_tag_open']        = '<ul class="pagination pagination-sm">';
            $config['full_tag_close']       = '</ul>';
            $config['first_tag_open']       = '<li>';
            $config['first_tag_close']      = '</li>';
            $config['last_tag_open']        = '<li>';
            $config['last_tag_close']       = '</li>';
            $config['cur_tag_open']         = '<li class="zgfm-pagination-active"><span>';
            $config['cur_tag_close']        = '</span></li>';
            $config['next_tag_open']        = '<li>';
            $config['next_tag_close']       = '</li>';
            $config['prev_tag_open']        = '<li>';
            $config['prev_tag_close']       = '</li>';
            $config['num_tag_open']         = '<li>';
            $config['num_tag_close']        = '</li>';
            $config['page_query_string']    = true;
            $config['query_string_segment'] = 'offset';

            $this->pagination->initialize($config);
            // If the pagination library doesn't recognize the current page add:
            $this->pagination->cur_page = $offset;

            $data2               = array();
            $data2['per_page']   = $data['per_page'];
            $data2['segment']    = $offset;
            $data2['search_txt'] = $data['search_txt'];
            $data2['orderby']    = $data['orderby'];

            $data3               = array();
            $data3['query']      = $this->formsmodel->getListFormsFiltered($data2);
            $data3['pagination'] = $this->pagination->create_links();

            return self::render_template('formbuilder/views/forms/list_forms_table.php', $data3);

            // echo self::loadPartial('layout.php', 'formbuilder/views/forms/list_forms.php', $data);
        }


        public function edit_uiform()
        {
            $data = array();
            echo self::render_template('formbuilder/views/forms/edit_form.php', $data);
        }

        public function choose_mode()
        {

            $data = [];
            $data['test'] = 'test';
            echo self::loadPartial('layout.php', 'formbuilder/views/forms/new_choose_mode.php', $data);
        }

        public function create_uiform()
        {
            $data            = array();
            $data['form_id'] = (isset($_GET['form_id']) && $_GET['form_id']) ? Uiform_Form_Helper::sanitizeInput(trim($_GET['form_id'])) : 0;
            $data['multistep_id'] = (isset($_GET['multistep_id']) && $_GET['multistep_id']) ? Uiform_Form_Helper::sanitizeInput(trim($_GET['multistep_id'])) : 0;
            $data['is_multistep'] = (isset($_GET['is_multistep']) && $_GET['is_multistep']) ? Uiform_Form_Helper::sanitizeFileName(trim($_GET['is_multistep'])) : '';
            $data['obj_sfm'] = Uiform_Form_Helper::get_font_library();

            if (intval($data['form_id']) > 0) {
                $formdata = $this->formsmodel->getFormById($data['form_id']);
                /*
             * delete after a month
            $data['uifm_frm_record_tpl_enable']=$formdata->fmb_rec_tpl_st;
            $data['uifm_frm_record_tpl_content']=$formdata->fmb_rec_tpl_html;*/
            }

            $pdf_paper_size         = array(
                '4a0'                      => array(0, 0, 4767.87, 6740.79),
                '2a0'                      => array(0, 0, 3370.39, 4767.87),
                'a0'                       => array(0, 0, 2383.94, 3370.39),
                'a1'                       => array(0, 0, 1683.78, 2383.94),
                'a2'                       => array(0, 0, 1190.55, 1683.78),
                'a3'                       => array(0, 0, 841.89, 1190.55),
                'a4'                       => array(0, 0, 595.28, 841.89),
                'a5'                       => array(0, 0, 419.53, 595.28),
                'a6'                       => array(0, 0, 297.64, 419.53),
                'a7'                       => array(0, 0, 209.76, 297.64),
                'a8'                       => array(0, 0, 147.40, 209.76),
                'a9'                       => array(0, 0, 104.88, 147.40),
                'a10'                      => array(0, 0, 73.70, 104.88),
                'b0'                       => array(0, 0, 2834.65, 4008.19),
                'b1'                       => array(0, 0, 2004.09, 2834.65),
                'b2'                       => array(0, 0, 1417.32, 2004.09),
                'b3'                       => array(0, 0, 1000.63, 1417.32),
                'b4'                       => array(0, 0, 708.66, 1000.63),
                'b5'                       => array(0, 0, 498.90, 708.66),
                'b6'                       => array(0, 0, 354.33, 498.90),
                'b7'                       => array(0, 0, 249.45, 354.33),
                'b8'                       => array(0, 0, 175.75, 249.45),
                'b9'                       => array(0, 0, 124.72, 175.75),
                'b10'                      => array(0, 0, 87.87, 124.72),
                'c0'                       => array(0, 0, 2599.37, 3676.54),
                'c1'                       => array(0, 0, 1836.85, 2599.37),
                'c2'                       => array(0, 0, 1298.27, 1836.85),
                'c3'                       => array(0, 0, 918.43, 1298.27),
                'c4'                       => array(0, 0, 649.13, 918.43),
                'c5'                       => array(0, 0, 459.21, 649.13),
                'c6'                       => array(0, 0, 323.15, 459.21),
                'c7'                       => array(0, 0, 229.61, 323.15),
                'c8'                       => array(0, 0, 161.57, 229.61),
                'c9'                       => array(0, 0, 113.39, 161.57),
                'c10'                      => array(0, 0, 79.37, 113.39),
                'ra0'                      => array(0, 0, 2437.80, 3458.27),
                'ra1'                      => array(0, 0, 1729.13, 2437.80),
                'ra2'                      => array(0, 0, 1218.90, 1729.13),
                'ra3'                      => array(0, 0, 864.57, 1218.90),
                'ra4'                      => array(0, 0, 609.45, 864.57),
                'sra0'                     => array(0, 0, 2551.18, 3628.35),
                'sra1'                     => array(0, 0, 1814.17, 2551.18),
                'sra2'                     => array(0, 0, 1275.59, 1814.17),
                'sra3'                     => array(0, 0, 907.09, 1275.59),
                'sra4'                     => array(0, 0, 637.80, 907.09),
                'letter'                   => array(0, 0, 612.00, 792.00),
                'half-letter'              => array(0, 0, 396.00, 612.00),
                'legal'                    => array(0, 0, 612.00, 1008.00),
                'ledger'                   => array(0, 0, 1224.00, 792.00),
                'tabloid'                  => array(0, 0, 792.00, 1224.00),
                'executive'                => array(0, 0, 521.86, 756.00),
                'folio'                    => array(0, 0, 612.00, 936.00),
                'commercial #10 envelope'  => array(0, 0, 684, 297),
                'catalog #10 1/2 envelope' => array(0, 0, 648, 864),
                '8.5x11'                   => array(0, 0, 612.00, 792.00),
                '8.5x14'                   => array(0, 0, 612.00, 1008.0),
                '11x17'                    => array(0, 0, 792.00, 1224.00),
            );
            $data['pdf_paper_size'] = $pdf_paper_size;

            $data['fields_fastload'] = get_option('zgfm_fields_fastload', 0);
            $data['obj_sfm']            = Uiform_Form_Helper::get_font_library();
            //$data['modules_tab_extension'] = self::$_modules['addon']['backend']->addons_doActions( 'back_exttab_block', true );
            $data['modules_tab_extension'] = apply_filters('zgfm_back_exttab_block', array());
            echo self::loadPartial('layout_editform.php', 'formbuilder/views/forms/create_form.php', $data);
        }

        public function test_dcheckbox()
        {
            $data = array();
            echo self::loadPartial('layout.php', 'formbuilder/views/forms/test_dcheckbox.php', $data);
        }

        public function preview_fields()
        {
            $data = array();
            echo self::render_template('formbuilder/views/forms/preview_fields.php', $data);
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
    ?>