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
if (! defined('ABSPATH')) {
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
 * @link      https://wordpress-form-builder.zigaform.com/
 */
class Uiform_Model_Form_Records
{

    private $wpdb      = '';
    public $table      = '';
    public $tbform     = '';
    public $tbformtype = '';
    public $tbformfields = '';
    
    public function __construct()
    {
        global $wpdb;
        $this->wpdb         = $wpdb;
        $this->table        = $wpdb->prefix . 'uiform_form_records';
        $this->tbform       = $wpdb->prefix . 'uiform_form';
        $this->tbformtype   = $wpdb->prefix . 'uiform_fields_type';
        $this->tbformfields = $wpdb->prefix . 'uiform_fields';
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
    public function getListRecords($per_page = '', $segment = '')
    {
        $query = sprintf(
            '
            select c.fbh_id,c.fbh_data,c.fbh_data_rec,
            c.created_date,c.flag_status,c.fbh_data_user,c.form_fmb_id,c.fbh_data_rec_xml,c.fbh_user_agent,c.fbh_page,
            c.fbh_referer,c.fbh_params,f.fmb_name
            from %s c
            join %s f on c.form_fmb_id=f.fmb_id
            where c.flag_status>0
            ORDER BY c.created_date desc
            ',
            $this->table,
            $this->tbform
        );

        if ($per_page != '' || $segment != '') {
            $segment = ( ! empty($segment) ) ? $segment : 0;
            $query  .= sprintf(' limit %s,%s', (int) $segment, (int) $per_page);
        }
        return $this->wpdb->get_results($query);
    }

    /**
     * Show all records according to filter
     *
     * @param string $per_page
     * @param string $segment
     * @return void
     */
    public function getListAllRecordsFiltered($data)
    {

        $per_page   = $data['per_page'];
        $segment    = $data['segment'];
        $orderby    = $data['orderby'];

        $query = sprintf(
            '
			select c.fbh_id,c.fbh_data,c.fbh_data_rec,
			c.created_date,c.flag_status,c.fbh_data_user,c.form_fmb_id,c.fbh_data_rec_xml,c.fbh_user_agent,c.fbh_page,
			c.fbh_referer,c.fbh_params,f.fmb_name
			from %s c
			join %s f on c.form_fmb_id=f.fmb_id
			where c.flag_status>0 ',
            $this->table,
            $this->tbform
        );

        $orderby = ( $orderby === 'asc' ) ? 'asc' : 'desc';

        $query .= sprintf(' ORDER BY c.created_date %s ', $orderby);

        if ($per_page != '' && $segment != '') {
            $segment = ( ! empty($segment) ) ? $segment : 0;
            $query  .= sprintf(' limit %s,%s', (int) $segment, (int) $per_page);
        }

        return $this->wpdb->get_results($query);
    }

    /**
     * Show trash records according to filter
     *
     * @param string $per_page
     * @param string $segment
     * @return void
     */
    public function getListTrashRecordsFiltered($data)
    {

        $per_page   = $data['per_page'];
        $segment    = $data['segment'];
        $orderby    = $data['orderby'];

        $query = sprintf(
            '
			select c.fbh_id,c.fbh_data,c.fbh_data_rec,
			c.created_date,c.flag_status,c.fbh_data_user,c.form_fmb_id,c.fbh_data_rec_xml,c.fbh_user_agent,c.fbh_page,
			c.fbh_referer,c.fbh_params,f.fmb_name
			from %s c
			join %s f on c.form_fmb_id=f.fmb_id
			where c.flag_status=0 ',
            $this->table,
            $this->tbform
        );

        $orderby = ( $orderby === 'asc' ) ? 'asc' : 'desc';

        $query .= sprintf(' ORDER BY c.created_date %s ', $orderby);

        if ($per_page != '' || $segment != '') {
            $segment = ( ! empty($segment) ) ? $segment : 0;
            $query  .= sprintf(' limit %s,%s', (int) $segment, (int) $per_page);
        }

        return $this->wpdb->get_results($query);
    }


    public function getDetailRecord($names, $form_id)
    {
        if (intval($form_id) > 0) {
            $unique_names = array();

            $sql  = 'select ';
            $temp = array();
            foreach ($names as $value) {
                if (! in_array(Uiform_Form_Helper::sanitizeFnamestring($value->fieldname), $unique_names)) {
                    $temp[]         = "extractvalue(fbh_data_rec_xml,'/params/child::" . $value->fmf_uniqueid . "') AS " . Uiform_Form_Helper::sanitizeFnamestring($value->fieldname);
                    $unique_names[] = Uiform_Form_Helper::sanitizeFnamestring($value->fieldname);
                } else {
                    $temp[]         = "extractvalue(fbh_data_rec_xml,'/params/child::" . $value->fmf_uniqueid . "') AS " . Uiform_Form_Helper::sanitizeFnamestring($value->fieldname) . count($unique_names);
                    $unique_names[] = Uiform_Form_Helper::sanitizeFnamestring($value->fieldname) . count($unique_names);
                }
            }
            $temp[] = 'r.fbh_id';
            $temp[] = 'r.created_date';
            $sql   .= implode(',', $temp) . ' from %1$s r';
            $sql   .= ' join %2$s frm on frm.fmb_id=r.form_fmb_id
                where r.flag_status>0 and r.form_fmb_id=%3$s';
            $query  = sprintf($sql, $this->table, $this->tbform, (int) $form_id);
            return $this->wpdb->get_results($query);
        } else {
            return array();
        }
    }


    public function getFieldOptRecord($rec_id, $f_type = null, $f_id = null, $f_atr1 = null, $f_atr2 = null, $f_atr3 = null)
    {
        if (intval($rec_id) > 0) {
            $result = '';

            switch (intval($f_type)) {
                case 8:
                case 10:
                    /*radio button, select*/

                    $tmp_str  = '';
                    $tmp_str .= $f_id;
                    if (! empty($f_atr1)) {
                        $tmp_str .= '_' . $f_atr1;
                        if (! empty($f_atr2)) {
                            $tmp_str .= '_' . $f_atr2;
                            if (! empty($f_atr3)) {
                                $tmp_str .= '_' . $f_atr3;
                            }
                        }
                    }

                    $sql       = "select extractvalue(fbh_data_rec_xml,'/params/child::" . $tmp_str . "') AS uifmoptvalue,";
                        $sql  .= 'r.fbh_id,r.created_date';
                        $sql  .= ' from %1$s r';
                        $sql  .= ' join %2$s frm on frm.fmb_id=r.form_fmb_id
                            where r.flag_status>0 and r.fbh_id=%3$s';
                        $query = sprintf($sql, $this->table, $this->tbform, (int) $rec_id);

                    if (false) {
                        // not tested yet
                        $sql   = "select extractvalue(fbh_data_rec_xml,'/params/child::" . $f_id . "_chosen') AS uifmoptvalue,";
                        $sql  .= 'r.fbh_id,r.created_date';
                        $sql  .= ' from %1$s r';
                        $sql  .= ' join %2$s frm on frm.fmb_id=r.form_fmb_id
                            where r.flag_status>0 and r.fbh_id=%3$s';
                        $query = sprintf($sql, $this->table, $this->tbform, (int) $rec_id);
                        $row   = $this->wpdb->get_row($query);

                        $chosen = $row->uifmoptvalue;
                        // get value or label
                        $sql    = 'select ';
                        $temp   = array();
                        $temp[] = "extractvalue(fbh_data_rec_xml,'/params/child::" . $f_id . '_input_' . $chosen . '_' . $f_atr1 . "') AS uifmoptvalue";
                        $temp[] = 'r.fbh_id';
                        $temp[] = 'r.created_date';
                        $sql   .= implode(',', $temp) . ' from %1$s r';
                        $sql   .= ' join %2$s frm on frm.fmb_id=r.form_fmb_id
                            where r.flag_status>0 and r.fbh_id=%3$s';
                        $query  = sprintf($sql, $this->table, $this->tbform, (int) $rec_id);
                    }

                    $row = $this->wpdb->get_row($query);
                    if (isset($row->uifmoptvalue)) {
                        return $row->uifmoptvalue;
                    } else {
                        return '';
                    }

                    break;
                case 9:
                case 11:
                    /*checkbox , Multiple select*/
                    $tmp_str  = '';
                    $tmp_str .= $f_id;
                    if (! empty($f_atr1)) {
                        $tmp_str .= '_' . $f_atr1;
                        if (! empty($f_atr2)) {
                            $tmp_str .= '_' . $f_atr2;
                            if (! empty($f_atr3)) {
                                $tmp_str .= '_' . $f_atr3;
                            }
                        }
                    }

                    $sql   = "select extractvalue(fbh_data_rec_xml,'/params/child::" . $tmp_str . "') AS uifmoptvalue,";
                    $sql  .= 'r.fbh_id,r.created_date';
                    $sql  .= ' from %1$s r';
                    $sql  .= ' join %2$s frm on frm.fmb_id=r.form_fmb_id
                        where r.flag_status>0 and r.fbh_id=%3$s';
                    $query = sprintf($sql, $this->table, $this->tbform, (int) $rec_id);

                    $row = $this->wpdb->get_row($query);
                    if (isset($row->uifmoptvalue)) {
                        return $row->uifmoptvalue;
                    } else {
                        return '';
                    }

                    break;
                case 16:
                case 17:
                case 18:
                    /*cspinner*/
                    $tmp_flag = $f_id . '_' . $f_atr1;
                    if (! empty($f_atr2)) {
                        $tmp_flag .= '_' . $f_atr2;
                    }

                    $sql   = "select extractvalue(fbh_data_rec_xml,'/params/child::" . $tmp_flag . "') AS uifmoptvalue,";
                    $sql  .= 'r.fbh_id,r.created_date';
                    $sql  .= ' from %1$s r';
                    $sql  .= ' join %2$s frm on frm.fmb_id=r.form_fmb_id
                        where r.flag_status>0 and r.fbh_id=%3$s';
                    $query = sprintf($sql, $this->table, $this->tbform, (int) $rec_id);

                    $row = $this->wpdb->get_row($query);
                    if (isset($row->uifmoptvalue)) {
                        return $row->uifmoptvalue;
                    } else {
                        return '';
                    }

                    break;
                default:
                    $option = $f_id . '_' . $f_atr1;

                    $sql    = 'select ';
                    $temp   = array();
                    $temp[] = "extractvalue(fbh_data_rec_xml,'/params/child::" . $option . "') AS uifmoptvalue";
                    $temp[] = 'r.fbh_id';
                    $temp[] = 'r.created_date';
                    $sql   .= implode(',', $temp) . ' from %1$s r';
                    $sql   .= ' join %2$s frm on frm.fmb_id=r.form_fmb_id
                        where r.flag_status>0 and r.fbh_id=%3$s';
                    $query  = sprintf($sql, $this->table, $this->tbform, (int) $rec_id);

                    $row = $this->wpdb->get_row($query);
                    if (isset($row->uifmoptvalue)) {
                        return $row->uifmoptvalue;
                    } else {
                        return '';
                    }
                    break;
            }
        } else {
            return '';
        }
    }

    public function getNameFieldEnabledByForm($id_form, $filter = false)
    {

        if (intval($id_form) > 0) {
            $tmp_qu = 'select t.fby_id,f.fmf_uniqueid, coalesce(NULLIF(f.fmf_fieldname,""),CONCAT(t.fby_name,f.fmf_id)) as fieldname 
                from %s f 
                join %s t on f.type_fby_id=t.fby_id 
                join %s fm on fm.fmb_id=f.form_fmb_id
                where f.type_fby_id in (6,7,8,9,10,11,12,13,15,16,17,18,21,22,23,24,25,26,28,29,30,39,40,41,42,43) and';

            if ($filter === true) {
                $tmp_qu .= ' f.fmf_status_qu=1 and';
            }

            $tmp_qu .= ' fm.fmb_id=%s order by f.order_rec asc';

            $query = sprintf($tmp_qu, $this->tbformfields, $this->tbformtype, $this->tbform, (int) $id_form);

            return $this->wpdb->get_results($query);
        } else {
            return array();
        }
    }

    public function getAllNameFieldEnabledByForm($id_field)
    {
        if (intval($id_field) > 0) {
            $query = sprintf(
                'select f.fmf_uniqueid, coalesce(NULLIF(f.fmf_fieldname,""),CONCAT(t.fby_name,f.fmf_id)) as fieldname 
        from %s f 
        join %s t on f.type_fby_id=t.fby_id 
        join %s fm on fm.fmb_id=f.form_fmb_id
        where f.type_fby_id in (6,7,8,9,10,11,12,13,15,16,17,18,21,22,23,24,25,26,28,29,30,39,40,41,42,43) and
        fm.fmb_id=%s order by f.order_rec asc',
                $this->tbformfields,
                $this->tbformtype,
                $this->tbform,
                (int) $id_field
            );

            return $this->wpdb->get_results($query);
        } else {
            return array();
        }
    }
    public function getFormDataById($id_rec)
    {
        $query = sprintf(
            'select  f.fmb_type, f.created_ip, f.fmb_name,frec.form_fmb_id,f.fmb_data,f.fmb_data2,f.fmb_rec_tpl_st,f.fmb_rec_tpl_html,frec.fbh_data
        from %s frec
        join %s f on f.fmb_id=frec.form_fmb_id
        where frec.flag_status>=0
        and frec.fbh_id=%s',
            $this->table,
            $this->tbform,
            (int) $id_rec
        );

        return $this->wpdb->get_row($query);
    }
    public function getAllFieldsForReport($id_form)
    {
        $query = sprintf(
            'select f.fmf_status_qu,f.fmf_uniqueid, coalesce(NULLIF(f.fmf_fieldname,""),CONCAT(t.fby_name,f.fmf_id)) as fieldname, f.order_rec 
            from %s f 
            join %s t on f.type_fby_id=t.fby_id 
            where f.form_fmb_id=%s and f.type_fby_id in (6,7,8,9,10,11,12,13,15,16,17,18,21,22,23,24,25,26,28,29,30,39,40,41,42,43)',
            $this->tbformfields,
            $this->tbformtype,
            (int) $id_form
        );
        return $this->wpdb->get_results($query);
    }

    public function getNameField($id_field)
    {
        $query = sprintf(
            'select f.fmf_uniqueid,f.fmf_id, coalesce(NULLIF(f.fmf_fieldname,""),CONCAT(t.fby_name,f.fmf_id)) as fieldname ,f.type_fby_id,f.fmf_data
        from %s f 
        join %s t on f.type_fby_id=t.fby_id 
        join %s fm on fm.fmb_id=f.form_fmb_id
        join %s rc on rc.form_fmb_id=fm.fmb_id
        where rc.fbh_id=%s',
            $this->tbformfields,
            $this->tbformtype,
            $this->tbform,
            $this->table,
            (int) $id_field
        );
        return $this->wpdb->get_results($query);
    }

    public function getChartDataByIdForm($id_field)
    {
        $query = 'SELECT 
                                DATE_FORMAT(r.created_date ,"%Y-%m-%d") as days, COUNT(r.fbh_id) as requests
                                FROM ' . $this->table . ' r
                                WHERE r.flag_status>0  
                                AND DATE_FORMAT(r.created_date,"%e") BETWEEN 1 AND 31
				AND r.form_fmb_id=' . (int) $id_field . '
                                GROUP BY DAY(r.created_date)
                                ORDER BY r.created_date ASC
                                limit 31';
        return $this->wpdb->get_results($query);
    }

    public function getRecordById($id)
    {
        $query = sprintf(
            '
            select uf.fbh_id,uf.fbh_data,uf.fbh_data_rec,uf.created_ip,
            uf.created_date,uf.flag_status,uf.fbh_data_user,uf.form_fmb_id,uf.fbh_data_rec_xml,uf.fbh_user_agent,uf.fbh_page,
            uf.fbh_referer,uf.fbh_params
            from %s uf
            where uf.fbh_id=%s
            ',
            $this->table,
            (int) $id
        );

        return $this->wpdb->get_row($query);
    }

    public function getOptRecordById($field, $id)
    {
        $query = sprintf(
            '
            select uf.%s
            from %s uf
            where uf.fbh_id=%s
            ',
            $field,
            $this->table,
            (int) $id
        );

        $row = $this->wpdb->get_row($query);
        if (! empty($row)) {
            return $row;
        } else {
            return '';
        }
    }

    public function CountRecords()
    {
        $query = sprintf(
            '
            select COUNT(*) AS counted
            from %s c
            where c.flag_status=1 
            ORDER BY c.created_date desc
            ',
            $this->table
        );
        $row   = $this->wpdb->get_row($query);
        if (isset($row->counted)) {
            return $row->counted;
        } else {
            return 0;
        }
    }
    
    public function getFieldDataByIdOnMultistep($id_rec, $ui_field, $idform)
    {
        $query = sprintf(
            "select f.type_fby_id as type,f.fmf_data
            from wp_uiform_fields f
            join wp_uiform_fields_type t on f.type_fby_id=t.fby_id 
            where f.fmf_uniqueid='%s' and f.form_fmb_id=%s ",
            $ui_field,
            $idform
        );
        
        $row = $this->wpdb->get_row($query);
        if (! empty($row)) {
            return $row;
        } else {
            return '';
        }
    }
    
    public function getFieldDataById($id_rec, $ui_field)
    {
        $query = sprintf(
            "select f.type_fby_id as type,f.fmf_data
            from %s f
            join %s t on f.type_fby_id=t.fby_id 
            join %s frm on f.form_fmb_id=frm.fmb_id
	    join %s frc on frc.form_fmb_id=frm.fmb_id
            where frc.fbh_id = %s and f.fmf_uniqueid='%s'",
            $this->tbformfields,
            $this->tbformtype,
            $this->tbform,
            $this->table,
            $id_rec,
            $ui_field
        );

        $row = $this->wpdb->get_row($query);
        if (! empty($row)) {
            return $row;
        } else {
            return '';
        }
    }

        /*
    * list all and trash forms
    */
    public function ListTotals()
    {
        $query = sprintf(
            '
			SELECT 
			  SUM(CASE WHEN flag_status = 0 THEN 1 ELSE 0 END) AS r_trash,
			  SUM(CASE WHEN flag_status != 0 THEN 1 ELSE 0 END) AS r_all
			FROM %s
			',
            $this->table
        );

        return $this->wpdb->get_row($query);
    }
}
