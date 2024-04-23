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
if (class_exists('Uiform_Model_Settings')) {
    return;
}

/**
 * Model Setting class
 *
 * @category  PHP
 * @package   Rocket_form
 * @author    Softdiscover <info@softdiscover.com>
 * @copyright 2013 Softdiscover
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version   Release: 1.00
 * @link      https://wordpress-form-builder.zigaform.com/
 */
class Uiform_Model_Settings
{

    private $wpdb = '';
    public $table = '';

    public function __construct()
    {
        global $wpdb;
        $this->wpdb  = $wpdb;
        $this->table = $wpdb->prefix . 'uiform_settings';
    }

    public function getOptions()
    {
        $query = sprintf(
            '
            select uf.version,uf.type_email,uf.smtp_host,uf.smtp_port,uf.smtp_user,uf.smtp_pass,uf.sendmail_path,uf.language
            from %s uf
            where uf.id=%s
            ',
            $this->table,
            1
        );

        return $this->wpdb->get_row($query);
    }

    public function getLangOptions()
    {
        $query = sprintf(
            '
            select uf.language
            from %s uf
            where uf.id=%s
            ',
            $this->table,
            1
        );

        return $this->wpdb->get_row($query);
    }

    public function getAllDatabases()
    {
        return $this->wpdb->get_results('SHOW TABLES', ARRAY_N);
    }

    public function getColsFromTable($table)
    {
        return $this->wpdb->get_results('SHOW COLUMNS FROM ' . $table);
    }
}
