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
if (class_exists('Uiform_Model_Form_Log')) {
    return;
}

/**
 * Model Form class
 *
 * @category  PHP
 * @package   Rocket_form
 * @author    Softdiscover <info@softdiscover.com>
 * @copyright 2013 Softdiscover
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version   Release: 1.00
 * @link      https://wordpress-form-builder.zigaform.com/
 */
class Uiform_Model_Form_Log
{

    private $wpdb = '';
    public $table = '';

    public function __construct()
    {
        global $wpdb;
        $this->wpdb  = $wpdb;
        $this->table = $wpdb->prefix . 'uiform_form_log';
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
    public function getListForms($per_page = '', $segment = '')
    {
        $query = sprintf(
            '
            select uf.log_id,uf.log_frm_data,uf.log_frm_name,uf.log_frm_html,uf.log_frm_html_backend,uf.log_frm_html_css,uf.log_frm_id,uf.log_frm_hash,uf.flag_status,uf.created_date,uf.updated_date
            from %s uf
            where uf.flag_status>0 
            ORDER BY uf.updated_date desc
            ',
            $this->table
        );

        if ($per_page != '' || $segment != '') {
            $segment = ( ! empty($segment) ) ? $segment : 0;
            $query  .= sprintf(' limit %s,%s', $segment, $per_page);
        }

        return $this->wpdb->get_results($query);
    }

    public function getLogById($id)
    {
        $query = sprintf(
            '
            select uf.log_id,uf.log_frm_data,uf.log_frm_name,uf.log_frm_html,uf.log_frm_html_backend,uf.log_frm_html_css,uf.log_frm_id,uf.log_frm_hash,uf.flag_status,uf.created_date,uf.updated_date
            from %s uf
            where 
            uf.flag_status=1 and
            uf.log_id=%s  
            ',
            $this->table,
            $id
        );

        return $this->wpdb->get_row($query);
    }

    public function getLogChildrenByParentId($id)
    {
        $query = sprintf(
            '
            select uf.log_id,uf.log_frm_data,uf.log_frm_name,uf.log_frm_html,uf.log_frm_html_backend,uf.log_frm_html_css,uf.log_frm_id,uf.log_frm_hash,uf.flag_status,uf.created_date,uf.updated_date
            from %s uf
            where 
            uf.flag_status=1 and
            uf.log_frm_parent=%s
            ',
            $this->table,
            $id
        );
        
        return $this->wpdb->get_results($query);
    }
    public function getAvailableLogById($id)
    {
        $query = sprintf(
            '
            select uf.log_id,uf.log_frm_data,uf.log_frm_name,uf.log_frm_html,uf.log_frm_html_backend,uf.log_frm_html_css,uf.log_frm_id,uf.log_frm_hash,uf.flag_status,uf.created_date,uf.updated_date
            from %s uf
            where 
            uf.flag_status=1 and
            uf.log_frm_id=%s 
            ORDER BY uf.updated_date desc
            ',
            $this->table,
            $id
        );

        return $this->wpdb->get_results($query);
    }

    public function getLastLogById($id)
    {
        $query = sprintf(
            '
            select uf.log_id,uf.log_frm_data,uf.log_frm_name,uf.log_frm_html,uf.log_frm_html_backend,uf.log_frm_html_css,uf.log_frm_id,uf.log_frm_hash,uf.flag_status,uf.created_date,uf.updated_date
            from %s uf
            where uf.log_frm_id=%s
            ORDER BY uf.log_id desc
            LIMIT 1
            ',
            $this->table,
            $id
        );

        return $this->wpdb->get_row($query);
    }

    public function getOldLogById($id)
    {
        $query = sprintf(
            '
            select uf.log_id,uf.log_frm_data,uf.log_frm_name,uf.log_frm_html,uf.log_frm_html_backend,uf.log_frm_html_css,uf.log_frm_id,uf.log_frm_hash,uf.flag_status,uf.created_date,uf.updated_date
            from %s uf
            where uf.log_frm_id=%s
            ORDER BY uf.log_id asc
            LIMIT 1
            ',
            $this->table,
            $id
        );

        return $this->wpdb->get_row($query);
    }

    public function CountLogsByFormId($id)
    {
        $query = sprintf(
            '
            select COUNT(*) AS counted
            from %s c
            where c.flag_status>0 
            and c.log_frm_id=%s
            ORDER BY c.updated_date desc
            ',
            $this->table,
            $id
        );
        $row   = $this->wpdb->get_row($query);
        if (isset($row->counted)) {
            return $row->counted;
        } else {
            return 0;
        }
    }
}
