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
if (class_exists('Uiform_Model_Fields')) {
    return;
}

/**
 * Model FIeld class
 *
 * @category  PHP
 * @package   Rocket_form
 * @author    Softdiscover <info@softdiscover.com>
 * @copyright 2013 Softdiscover
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version   Release: 1.00
 * @link      http://wordpress-form-builder.zigaform.com/
 */
class Uiform_Model_Fields {

    private $wpdb = "";
    public $table = "";
    public $tbformtype = "";

    function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->table = $wpdb->prefix . "uiform_fields";
        $this->tbform = $wpdb->prefix . "uiform_form";
        $this->tbformtype = $wpdb->prefix . "uiform_fields_type";
    }

    function queryGetListFieldsEnabled($form_id) {
        $query = sprintf('select f.fmf_uniqueid,f.order_rec
            from %s f 
            where f.fmf_status_qu=1 and f.form_fmb_id=%s', $this->table, (int)$form_id);
        return $this->wpdb->get_results($query);
    }
    
    function queryGetListFieldsById($form_id) {
        $query = sprintf('select f.fmf_uniqueid,f.order_rec
            from %s f 
            where f.form_fmb_id=%s', $this->table, (int)$form_id);
        
         
        return $this->wpdb->get_results($query);
    }
    
    function queryGetQtyFieldsEnabled($form_id) {
        $query = sprintf('select COUNT(*) as count
            from %s f 
            where f.fmf_status_qu=1 and f.form_fmb_id=%s', $this->table, (int)$form_id);
        $row = $this->wpdb->get_row($query);
        if (intval($row->count) > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    function getFieldNameByUniqueId($uid, $form_id) {
        $query = sprintf('
            select f.type_fby_id as type,f.fmf_data as data,coalesce(NULLIF(f.fmf_fieldname,""),CONCAT(t.fby_name,f.fmf_id)) as fieldname
            from %s f
            join %s t on f.type_fby_id=t.fby_id 
            join %s frm on f.form_fmb_id=frm.fmb_id
            where
            frm.fmb_id=%s
            and f.fmf_uniqueid="%s"', $this->table, $this->tbformtype, $this->tbform, (int)$form_id, $uid);
        return $this->wpdb->get_row($query);
    }

    function getDataByUniqueId($uid, $form_id) {
        $query = sprintf('
            select fmf_data
            from %s f
            join %s frm
            where frm.fmb_id=%s
            and f.fmf_uniqueid="%s"
            ', $this->table, $this->tbform, (int)$form_id, $uid);
        return $this->wpdb->get_row($query);
    }

}

?>
