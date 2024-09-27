<?php
if ( ! defined('ABSPATH')) {
    exit('No direct script access allowed');
}
if ( class_exists('Uiform_Backup')) {
    return;
}

class Uiform_Backup
{
    private $tables = array();
    private $suffix = 'd-M-Y_H-i-s';
    private $wpdb;
    private $backup_dir;
    /**
     * Constructor
     *
     * @mvc Controller
     */
    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->backup_dir = WP_CONTENT_DIR.'/uploads/softdiscover/' . UIFORM_SLUG.'/backups/';
        define('NL', "\r\n");
    }

    public function uploadBackupFile()
    {
        $target_dir    = $this->backup_dir;
        $target_file   = $target_dir .basename($_FILES['uifm_bkp_fileupload']['name']);
        $uploadOk      = 1;
        $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

        // Check if file already exists
        if ( file_exists($target_file)) {
            $uploadOk = 0;
        }
        // Check file size
        if ( $_FILES['uifm_bkp_fileupload']['size'] > 5048576) {
            $uploadOk = 0;
        }
        // Allow certain file formats
        if ( $imageFileType != 'sql') {
            $uploadOk = 0;
        }
        // Check if $uploadOk is set to 0 by an error
        if ( $uploadOk === 0) {
            // if everything is ok, try to upload file
        } else {
            if ( move_uploaded_file($_FILES['uifm_bkp_fileupload']['tmp_name'], $target_file)) {
            } else {
            }
        }
    }

    public function restoreBackup($file) {
        global $wpdb;
    
        try {
            // Validate user capability
            if (!current_user_can('manage_options')) {
                wp_die('You do not have permission to perform this action.');
            }
    
            // Define backup directory
            $dir = $this->backup_dir;
            $database_file = $dir . $file;
    
            // Validate file type
            $allowed_file_types = ['sql'];
            $file_info = pathinfo($file);
            if (!in_array($file_info['extension'], $allowed_file_types)) {
                wp_die('Invalid file type. Only SQL files are allowed.');
            }
    
            // Set execution limits
            ini_set('max_execution_time', '5000');
            set_time_limit(0);
    
            // Check if the backup file exists
            if (!file_exists($database_file)) {
                throw new Exception('Backup file does not exist.');
            }
    
            // Read the SQL file content
            $sql_file = file_get_contents($database_file);
    
            // Normalize line breaks for consistency
            $sql_file = strtr($sql_file, array("\r\n" => "\n", "\r" => "\n"));
    
            // Split the SQL file into separate queries
            $sql_queries = explode(";\n", $sql_file);
    
            // Start transaction
            $wpdb->query('START TRANSACTION');
            $wpdb->query('SET foreign_key_checks = 0');
             
            // Execute each query
            foreach ($sql_queries as $query) {
                $query = $this->_real_unescape(trim($query));
               
                if (!empty($query)) {
                    $result = $wpdb->query($query);
                    if ($result === false) {
                        throw new Exception('Error executing query: ' . $query);
                    }
                }
            }
    
            // Re-enable foreign key checks
            $wpdb->query('SET foreign_key_checks = 1');
    
            // Commit transaction
            $wpdb->query('COMMIT');
    
        } catch (Exception $exception) {
            // Rollback transaction on error
            $wpdb->query('ROLLBACK');
            error_log($exception->getMessage());
            wp_die('Database restore failed. Please check the error log for details.');
        }
    }
    
    public function makeDbBackup($name = '')
    {
        require_once UIFORM_FORMS_DIR . '/classes/uiform-installdb.php';
        $installdb  = new Uiform_InstallDB();
        $dbTables   = array();
        $dbTables[] = $installdb->form;
        $dbTables[] = $installdb->form_history;
        $dbTables[] = $installdb->form_fields_type;
        $dbTables[] = $installdb->form_fields;
        $dbTables[] = $installdb->settings;
        $dbTables[] = $installdb->core_addon;
        $dbTables[] = $installdb->core_addon_detail;
        $dbTables[] = $installdb->core_addon_log;
        
        // $dbTables[]=$installdb->visitor;
        // $dbTables[]=$installdb->visitor_error;
        $this->tables = $dbTables;

          $dump       = '';
            $database = DB_NAME;
            $server   = DB_HOST;
          $dump      .= '-- --------------------------------------------------------------------------------' . NL;
          $dump      .= '-- ' . NL;
          $dump      .= '-- @version: ' . $database . '.sql ' . date('M j, Y') . ' ' . date('H:i') . ' Softdiscover' . NL;
          $dump      .= '-- @package Uiform - WordPress Cost Estimation & Payment' . NL;
          $dump      .= '-- @author softdiscover.com.' . NL;
          $dump      .= '-- @copyright 2015' . NL;
          $dump      .= '-- ' . NL;
          $dump      .= '-- --------------------------------------------------------------------------------' . NL;
          $dump      .= '-- Host: ' . $server . NL;
          $dump      .= '-- Database: ' . $database . NL;
          $dump      .= '-- Time: ' . date('M j, Y') . '-' . date('H:i') . NL;
          $dump      .= '-- MySQL version: ' . Uiform_Form_Helper::mysql_version() . NL;
          $dump      .= '-- PHP version: ' . phpversion() . NL;
          $dump      .= '-- --------------------------------------------------------------------------------' . NL . NL;

          $dump .= 'DROP TABLE IF EXISTS `' . $installdb->form_history . '`;' . NL;
          $dump .= 'DROP TABLE IF EXISTS `' . $installdb->form_fields . '`;' . NL;
          $dump .= 'DROP TABLE IF EXISTS `' . $installdb->form_fields_type . '`;' . NL;
          $dump .= 'DROP TABLE IF EXISTS `' . $installdb->form . '`;' . NL;
          $dump .= 'DROP TABLE IF EXISTS `' . $installdb->settings . '`;' . NL;
          $dump .= 'DROP TABLE IF EXISTS `' . $installdb->core_addon_detail . '`;' . NL;
          $dump .= 'DROP TABLE IF EXISTS `' . $installdb->core_addon_log . '`;' . NL;
          $dump .= 'DROP TABLE IF EXISTS `' . $installdb->core_addon . '`;' . NL;
          
          $database = DB_NAME;
        if ( ! empty($database)) {
            $dump .= '#' . NL;
            $dump .= '# Database: `' . $database . '`' . NL;
        }
          $dump  .= '#' . NL . NL . NL;
          $tables = $this->getTables();
        if ( ! empty($tables)) {
            foreach ( $this->tables as $key => $table) {
                if ( intval($key) === 0) {
                    $table_dump = $this->dumpTable($table, true);
                } else {
                    $table_dump = $this->dumpTable($table);
                }

                if ( ! ( $table_dump )) {
                    return false;
                }
                $dump .= $table_dump;
            }
        }

        $fname      = $this->backup_dir;
              $fname .= ( ! empty($name) ) ? $name : date($this->suffix);
              $fname .= '.sql';
        if ( ! ( $f = fopen($fname, 'w') )) {
            return false;
        }
              fwrite($f, $dump);
              fclose($f);
    }

    public function getTables()
    {
        $value = array();
        if ( ! ( $result = $this->wpdb->get_results('SHOW TABLES') )) {
            return false;
        }
        foreach ( $result as $mytable) {
            foreach ( $mytable as $t) {
                if ( in_array($t, $this->tables)) {
                     $value[] = $t;
                }
            }
        }
        if ( ! sizeof($value)) {
            return false;
        }

        return $value;
    }


    public function dumpTable($table, $flag = false)
    {
         // $dump = '';
          $this->wpdb->query('LOCK TABLES ' . $table . ' WRITE');

        // $tables = $this->wpdb->get_col('SHOW TABLES');
        $output = '';
        // foreach($tables as $table) {
        $result = $this->wpdb->get_results("SELECT * FROM {$table}", ARRAY_N);
        if ( $flag === true) {
            // verifying the first table has content
            $row = isset($result[0]) ? $result[0] : '';
            if ( empty($row[0])) {
                return false;
            }
        }
                $output .= '-- --------------------------------------------------' . NL;
          $output       .= '# -- Table structure for table `' . $table . '`' . NL;
          $output       .= '-- --------------------------------------------------' . NL;
          $output       .= 'DROP TABLE IF EXISTS `' . $table . '`;' . NL;
        $row2            = $this->wpdb->get_row('SHOW CREATE TABLE ' . $table, ARRAY_N);
        $output         .= "\n\n" . $row2[1] . ";\n\n";
        for ( $i = 0; $i < count($result); $i++) {
            $row     = $result[ $i ];
            $output .= 'INSERT INTO ' . $table . ' VALUES(';
            for ( $j = 0; $j < count($result[0]); $j++) {
                $row[ $j ] = $this->_real_escape($row[ $j ]);
                $output   .= ( isset($row[ $j ]) ) ? '"' . $row[ $j ] . '"' : '""';
                if ( $j < ( count($result[0]) - 1 )) {
                    $output .= ',';
                }
            }
            $output .= ");\n";
        }
        $output .= "\n";
        // }

          $this->wpdb->query('UNLOCK TABLES');
          return $output;
    }

    public function insert($table)
    {
             $output = '';
        if ( ! $query = $this->wpdb->get_results('SELECT * FROM `' . $table . '`')) {
                return false;
        }
        foreach ( $query as $result) {
                $fields = '';

            foreach ( array_keys((array) $result) as $value) {
                    $fields .= '`' . $value . '`, ';
            }
                $values = '';

            foreach ( array_values((array) $result) as $value) {
                    $values .= '\'' . $value . '\', ';
            }

                $output .= 'INSERT INTO `' . $table . '` (' . preg_replace('/, $/', '', $fields) . ') VALUES (' . preg_replace('/, $/', '', $values) . ');' . "\n";
        }
            return $output;
    }
    
     /**
	 * Real escape using mysqli_real_escape_string().
	 *
	 * @since 2.8.0
	 *
	 * @see mysqli_real_escape_string()
	 *
	 * @param string $data String to escape.
	 * @return string Escaped string.
	 */
	public function _real_escape( $data ) {
        global $wpdb;
		if ( ! is_scalar( $data ) ) {
			return '';
		}
        
			 
		$escaped = mysqli_real_escape_string( $wpdb->dbh, $data );
		return $this->add_placeholder_escape( $escaped );
	}
	public function _real_unescape( $data ) {
         
		if ( ! is_scalar( $data ) ) {
			return '';
		}

		return $this->remove_placeholder_escape( $data );
	}
	
	public function add_placeholder_escape( $query ) {
		 
		/*
		 * To prevent returning anything that even vaguely resembles a placeholder,
		 * we clobber every % we can find.
		 */
		return str_replace( '%', $this->placeholder_escape(), $query );
	}
	
	
	public function remove_placeholder_escape( $query ) {
		 
		return str_replace( $this->placeholder_escape(), '%', $query );
	}
	
	
	public function placeholder_escape() {
		static $placeholder;

		if ( ! $placeholder ) {
			// If ext/hash is not present, compat.php's hash_hmac() does not support sha256.
			$algo = 'sha1';
			 
			$salt = 'zigaform';

			$placeholder = '{' . hash_hmac( $algo,'', $salt ) . '}';
		}

		 

		return $placeholder;
	}
    
}
