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
 * @link      https://softdiscover.com/zigaform/wordpress-cost-estimator
 */
if (! defined('ABSPATH')) {
    exit('No direct script access allowed');
}
if (class_exists('Uiform_Model_Addon')) {
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
 * @link      https://softdiscover.com/zigaform/wordpress-cost-estimator
 */
class Uiform_Model_Addon
{

    private $wpdb = '';
    public $table = '';
    public $tbaddon_details = '';
    
    public function __construct()
    {
        global $wpdb;
        $this->wpdb            = $wpdb;
        $this->table           = $wpdb->prefix . 'uiform_addon';
        $this->tbaddon_details = $wpdb->prefix . 'uiform_addon_details';
    }


    public function getListAddonsByBack()
    {
        $query = sprintf(
            '
            select c.add_name
                    ,c.add_title
                    ,c.add_system
                    ,c.add_hasconfig
                    ,c.add_version
                    ,c.add_icon
                    ,c.add_installed
                    ,c.add_order
                    ,c.add_params
                    ,c.add_log
                    ,c.addonscol
                    ,c.flag_status
                    ,c.created_date
                    ,c.updated_date
                    ,c.created_ip
                    ,c.updated_ip
                    ,c.created_by
                    ,c.updated_by
                    ,c.add_xml
                    ,c.add_load_back
                    ,c.add_load_front
                    ,c.is_field
            from %s c
            where c.flag_status=1 
            and c.add_load_back=1
            ORDER BY c.add_order desc
            ',
            $this->table
        );

        return $this->wpdb->get_results($query);
    }

    public function getListAddonsByFront()
    {
        $query = sprintf(
            '
            select c.add_name
                    ,c.add_title
                    ,c.add_info
                    ,c.add_system
                    ,c.add_hasconfig
                    ,c.add_version
                    ,c.add_icon
                    ,c.add_installed
                    ,c.add_order
                    ,c.add_params
                    ,c.add_log
                    ,c.addonscol
                    ,c.flag_status
                    ,c.created_date
                    ,c.updated_date
                    ,c.created_ip
                    ,c.updated_ip
                    ,c.created_by
                    ,c.updated_by
                    ,c.add_xml
                    ,c.add_load_back
                    ,c.add_load_front
                    ,c.is_field
            from %s c
            where c.flag_status=1 
            and c.add_load_front=1
            ORDER BY c.add_order desc
            ',
            $this->table
        );

        return $this->wpdb->get_results($query);
    }

    public function getActiveAddonsNamesOnBack()
    {
        $query = sprintf(
            '
            select c.add_name
            from %s c
            where c.flag_status=1 
            and c.add_load_back=1
            ORDER BY c.add_order desc
            ',
            $this->table
        );

        $tmp_result = $this->wpdb->get_results($query);

        $result = array();
        foreach ($tmp_result as $key => $value) {
            $result[] = $value->add_name;
        }

        return $result;
    }


    public function getAddonsNamesOnBackByForm($idform)
    {
        $query = sprintf(
            '
            select c.add_name
            from %s c
	    left join %s ad on ad.add_name = c.add_name
            where c.flag_status=1 and ad.fmb_id=%s
            and c.add_load_back=1
            ORDER BY c.add_order desc
            ',
            $this->table,
            $this->tbaddon_details,
            $idform
        );

        $tmp_result = $this->wpdb->get_results($query);

        $result = array();
        foreach ($tmp_result as $key => $value) {
            $result[] = $value->add_name;
        }

        return $result;
    }



    public function getListAddonsBySection($section = '')
    {
        $query = sprintf(
            '
            select c.add_name
                    ,c.add_title
                    ,c.add_info
                    ,c.add_system
                    ,c.add_hasconfig
                    ,c.add_version
                    ,c.add_icon
                    ,c.add_installed
                    ,c.add_order
                    ,c.add_params
                    ,c.add_log
                    ,c.addonscol
                    ,c.flag_status
                    ,c.created_date
                    ,c.updated_date
                    ,c.created_ip
                    ,c.updated_ip
                    ,c.created_by
                    ,c.updated_by
                    ,c.add_xml
                    ,c.add_load_back
                    ,c.add_load_front
                    ,c.is_field
            from %s c
            where c.flag_status=1 
            and c.add_section="%s"
            ORDER BY c.add_section_order desc
            ',
            $this->table,
            $section
        );

        return $this->wpdb->get_results($query);
    }


    /**
     * addonmodel::getListAddon()
     * List form estimator
     *
     * @param int $per_page max number of form estimators
     * @param int $segment  Number of pagination
     *
     * @return array
     */
    public function getListAddons($per_page = '', $segment = '')
    {
        $query = sprintf(
            '
            select  c.add_name, c.add_title, c.add_info, c.flag_status, c.add_params,
            extractvalue(c.add_xml, "/params/child::required_wp") as required_wp,
            extractvalue(c.add_xml, "/params/child::required_php") as required_php
            from %s c            
            where c.flag_status>=0
            ORDER BY c.created_date desc
            ',
            $this->table
        );

        if ($per_page != '' || $segment != '') {
            $segment = ( ! empty($segment) ) ? $segment : 0;
            $query  .= sprintf(' limit %s,%s', (int) $segment, (int) $per_page);
        }
        return $this->wpdb->get_results($query);
    }

    public function existAddon($addon_name)
    {
        $query = sprintf(
            'select 
                COUNT(*) as count
                from %s ad
                where ad.add_name ="%s" ',
            $this->table,
            $addon_name
        );
        $row   = $this->wpdb->get_row($query);
        if (intval($row->count) > 0) {
            return 1;
        } else {
            return 0;
        }
    }
}
