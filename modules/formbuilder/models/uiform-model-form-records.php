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
if (class_exists('Uiform_Model_Form_Records')) {
    return;
}

/**
 * Model Form record class
 *
 * @category  PHP
 * @package   Rocket_form
 * @author    Softdiscover <info@softdiscover.com>
 * @copyright 2013 Softdiscover
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version   Release: 1.00
 * @link      http://wordpress-form-builder.zigaform.com/
 */
class Uiform_Model_Form_Records {

    private $wpdb = "";
    public $table = "";
    public $tbform = "";
    public $tbformtype = "";

    function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->table = $wpdb->prefix . "uiform_form_records";
        $this->tbform = $wpdb->prefix . "uiform_form";
        $this->tbformtype = $wpdb->prefix . "uiform_fields_type";
        $this->tbformfields = $wpdb->prefix . "uiform_fields";
    }

    /**
     * formsmodel::getListForms()
     * List form estimator
     * 
     * @param int $per_page max number of form estimators
     * @param int $segment  Number of pagination
     * 
     * @return array
     */
    function getListRecords($per_page = '', $segment = '') {
        $query = sprintf('
            select c.*,f.fmb_name
            from %s c
            join %s f on c.form_fmb_id=f.fmb_id
            where c.flag_status>0
            ORDER BY c.created_date desc
            ', $this->table, $this->tbform);

        if ($per_page != '' || $segment != '') {
            $segment=(!empty($segment))?$segment:0;
        $query.=sprintf(' limit %s,%s', (int)$segment, (int)$per_page);
        }
        return $this->wpdb->get_results($query);
    }

    function getDetailRecord($names,$form_id) {
        if(intval($form_id)>0){
            
            $unique_names=array();
            
            $sql = 'select ';
            $temp = array();
            foreach ($names as $value) {
                
                if(!in_array(Uiform_Form_Helper::sanitizeFnamestring($value->fieldname), $unique_names)){
                    $temp[] = "extractvalue(fbh_data_rec_xml,'/params/child::" . $value->fmf_uniqueid . "') AS " . Uiform_Form_Helper::sanitizeFnamestring($value->fieldname);
                    $unique_names[]=Uiform_Form_Helper::sanitizeFnamestring($value->fieldname);
                }else{
                    $temp[] = "extractvalue(fbh_data_rec_xml,'/params/child::" . $value->fmf_uniqueid . "') AS " . Uiform_Form_Helper::sanitizeFnamestring($value->fieldname).count($unique_names);
                    $unique_names[]=Uiform_Form_Helper::sanitizeFnamestring($value->fieldname).count($unique_names);
                }
                
            }
            $temp[] = "r.fbh_id";
            $temp[] = "r.created_date";
            $sql.=implode(',', $temp) . ' from %1$s r';
            $sql.=' join %2$s frm on frm.fmb_id=r.form_fmb_id
                where r.flag_status>0 and r.form_fmb_id=%3$s'; 
            $query = sprintf($sql,$this->table,$this->tbform,(int)$form_id);
            return $this->wpdb->get_results($query);
        }else{
            return array();
        }
        
    }
    
    
    function getFieldOptRecord($option,$form_id) {
        if(intval($form_id)>0){
            
            $sql = 'select ';
            $temp = array();
            $temp[] = "extractvalue(fbh_data_rec_xml,'/params/child::" . $option . "') AS uifmoptvalue";
            $temp[] = "r.fbh_id";
            $temp[] = "r.created_date";
            $sql.=implode(',', $temp) . ' from %1$s r';
            $sql.=' join %2$s frm on frm.fmb_id=r.form_fmb_id
                where r.flag_status>0 and r.fbh_id=%3$s'; 
            $query = sprintf($sql,$this->table,$this->tbform,(int)$form_id);
            
            $row = $this->wpdb->get_row($query);
            if (isset($row->uifmoptvalue)) {
                return $row->uifmoptvalue;
            } else {
                return '';
            }
            
        }else{
            return '';
        }
        
    }
    
    function getNameFieldEnabledByForm($id_field) {
        
        if(intval($id_field)>0){
            $query = sprintf('select f.fmf_uniqueid, coalesce(NULLIF(f.fmf_fieldname,""),CONCAT(t.fby_name,f.fmf_id)) as fieldname 
        from %s f 
        join %s t on f.type_fby_id=t.fby_id 
        join %s fm on fm.fmb_id=f.form_fmb_id
        where f.type_fby_id in (6,7,8,9,10,11,12,13,15,16,17,18,21,22,23,24,25,26,28,29,30,39,40,41,42) and 
        f.fmf_status_qu=1 and
        fm.fmb_id=%s  order by f.order_rec asc', $this->tbformfields, $this->tbformtype, $this->tbform, (int)$id_field);

        return $this->wpdb->get_results($query);
        }else{
            return array();
        }
        
        
    }

    function getAllNameFieldEnabledByForm($id_field) {
        if(intval($id_field)>0){
            $query = sprintf('select f.fmf_uniqueid, coalesce(NULLIF(f.fmf_fieldname,""),CONCAT(t.fby_name,f.fmf_id)) as fieldname 
        from %s f 
        join %s t on f.type_fby_id=t.fby_id 
        join %s fm on fm.fmb_id=f.form_fmb_id
        where f.type_fby_id in (6,7,8,9,10,11,12,13,15,16,17,18,21,22,23,24,25,26,28,29,30,39,40,41,42) and
        fm.fmb_id=%s order by f.order_rec asc', $this->tbformfields, $this->tbformtype, $this->tbform, (int) $id_field);

        return $this->wpdb->get_results($query);
        }else{
            return array();
        }
        
        
    }
    function getFormDataById($id_rec){
        $query = sprintf('select  f.fmb_name,frec.form_fmb_id, f.fmb_data
        from %s frec
        join %s f on f.fmb_id=frec.form_fmb_id
        where frec.flag_status>0
        and frec.fbh_id=%s', $this->table,$this->tbform,(int)$id_rec);
        
        return $this->wpdb->get_row($query);
    }
    function getAllFieldsForReport($id_form) {
        $query = sprintf('select f.fmf_status_qu,f.fmf_uniqueid, coalesce(NULLIF(f.fmf_fieldname,""),CONCAT(t.fby_name,f.fmf_id)) as fieldname, f.order_rec 
            from %s f 
            join %s t on f.type_fby_id=t.fby_id 
            where f.form_fmb_id=%s and f.type_fby_id in (6,7,8,9,10,11,12,13,15,16,17,18,21,22,23,24,25,26,28,29,30,39,40,41,42)', $this->tbformfields, $this->tbformtype, (int)$id_form);
        return $this->wpdb->get_results($query);
    }

    function getNameField($id_field) {
        $query = sprintf('select f.fmf_uniqueid,f.fmf_id, coalesce(NULLIF(f.fmf_fieldname,""),CONCAT(t.fby_name,f.fmf_id)) as fieldname 
        from %s f 
        join %s t on f.type_fby_id=t.fby_id 
        join %s fm on fm.fmb_id=f.form_fmb_id
        join %s rc on rc.form_fmb_id=fm.fmb_id
        where rc.fbh_id=%s', $this->tbformfields, $this->tbformtype, $this->tbform, $this->table, (int)$id_field);
        return $this->wpdb->get_results($query);
    }

    function getChartDataByIdForm($id_field) {
        $query = 'SELECT 
                                DATE_FORMAT(r.created_date ,"%Y-%m-%d") as days, COUNT(r.fbh_id) as requests
                                FROM ' . $this->table . ' r
                                WHERE r.flag_status>0  
                                AND DATE_FORMAT(r.created_date,"%e") BETWEEN 1 AND 31
				AND r.form_fmb_id=' . (int)$id_field . '
                                GROUP BY DAY(r.created_date)
                                ORDER BY r.created_date ASC
                                limit 31';
        return $this->wpdb->get_results($query);
    }

    function getRecordById($id) {
        $query = sprintf('
            select uf.*
            from %s uf
            where uf.fbh_id=%s
            ', $this->table, (int)$id);

        return $this->wpdb->get_row($query);
    }
    
    function getOptRecordById($field, $id) {
        $query = sprintf('
            select uf.%s
            from %s uf
            where uf.fbh_id=%s
            ',$field, $this->table, (int)$id);

        
        $row = $this->wpdb->get_row($query);
        if (!empty($row)) {
            return $row;
        } else {
            return '';
        }
    }

    function CountRecords() {
        $query = sprintf('
            select COUNT(*) AS counted
            from %s c
            where c.flag_status=1 
            ORDER BY c.created_date desc
            ', $this->table);
        $row = $this->wpdb->get_row($query);
        if (isset($row->counted)) {
            return $row->counted;
        } else {
            return 0;
        }
    }

}

?>
