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
if (class_exists('Uiform_Model_Addon_Details')) {
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
class Uiform_Model_Addon_Details
{


    private $wpdb = '';
    public $table = '';
    public $tbaddon = '';
    
    public function __construct()
    {
         global $wpdb;
        $this->wpdb    = $wpdb;
        $this->table   = $wpdb->prefix . 'uiform_addon_details';
        $this->tbaddon = $wpdb->prefix . 'uiform_addon';
    }
    
    public function getAddonsDataByForm($form_id)
    {
        $query = sprintf(
            '
            select ad.adet_data, ad.add_name, ad.fmb_id, ad.flag_status
            from %s c
	    left join %s ad on ad.add_name = c.add_name
            where c.flag_status=1 and ad.fmb_id=%s 
            and c.add_load_back=1
            ORDER BY c.add_order desc
            ',
            $this->tbaddon,
            $this->table,
            (int) $form_id
        );

        return $this->wpdb->get_results($query);
    }
    
    public function getAddonDataByForm($addon_name, $form_id)
    {
        $query = sprintf(
            '
            select ad.adet_data
            from %s c
	    left join %s ad on ad.add_name = c.add_name
            where c.flag_status=1 and ad.fmb_id=%s and ad.add_name ="%s"
            and c.add_load_back=1
            ORDER BY c.add_order desc
            ',
            $this->tbaddon,
            $this->table,
            (int) $form_id,
            $addon_name
        );

        return $this->wpdb->get_row($query);
    }

    public function existRecord($addon_name, $form_id)
    {
        $query = sprintf(
            'select
                COUNT(*) as count
                from %s ad
                where ad.add_name ="%s" and ad.fmb_id=%s',
            $this->table,
            $addon_name,
            (int) $form_id
        );
        $row   = $this->wpdb->get_row($query);
        if (intval($row->count) > 0) {
            return 1;
        } else {
            return 0;
        }
    }
}
