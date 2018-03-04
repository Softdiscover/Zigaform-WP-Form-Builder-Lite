<?php
if (!defined('ABSPATH')) {
    exit('No direct script access allowed');
}
if (class_exists('Uiform_Backup')) {
    return;
}

class Uiform_Backup {
    private $tables = array();
    private $suffix = 'd-M-Y_H-i-s';
    /**
     * Constructor
     *
     * @mvc Controller
     */
    public function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
        define('nl', "\r\n");
    }
    
    public function uploadBackupFile(){
       $target_dir = UIFORM_FORMS_DIR . '/backups/';
        $target_file = $target_dir . basename($_FILES["uifm_bkp_fileupload"]["name"]);
        $uploadOk = 1;
        $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
        
        // Check if file already exists
        if (file_exists($target_file)) {
          
            $uploadOk = 0;
        }
        // Check file size
        if ($_FILES["uifm_bkp_fileupload"]["size"] > 5048576) {
          
            $uploadOk = 0;
        }
        // Allow certain file formats
        if($imageFileType != "sql") {
           
            $uploadOk = 0;
        }
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk === 0) {
        // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["uifm_bkp_fileupload"]["tmp_name"], $target_file)) {
               
            } else {
                
            }
        }
        
    }
    
    public function restoreBackup($file){
       try {
           
      
        /*Begin restore*/
                
                $dir = UIFORM_FORMS_DIR . '/backups/';
        $database_file=$dir.$file;
        
               
				$database_name=DB_NAME;
				$database_user=DB_USER;				
				$datadase_password=DB_PASSWORD;
				$database_host=DB_HOST;
				 
				ini_set("max_execution_time", "5000"); 
				ini_set("max_input_time",     "5000");
				ini_set('memory_limit', '1000M');
				set_time_limit(0);
				
				
	  if((trim((string)$database_name) != '') && (trim((string)$database_user) != '')  && (trim((string)$database_host) != '') && ($conn = @mysql_connect((string)$database_host, (string)$database_user, (string)$datadase_password))) {
		/*BEGIN: Select the Database*/
		if(!mysql_select_db((string)$database_name, $conn)) {
			$sql = "CREATE DATABASE IF NOT EXISTS `".(string)$database_name."`";
			mysql_query($sql, $conn);
			mysql_select_db((string)$database_name, $conn);
		}
		/*END: Select the Database*/
		
		/*BEGIN: Remove All Tables from the Database*/
		 
                require_once( UIFORM_FORMS_DIR . '/classes/uiform-installdb.php');
                $installdb = new Uiform_InstallDB();
                $dbTables=array();
                $dbTables[]=$installdb->form_history;
                $dbTables[]=$installdb->form_fields;
                $dbTables[]=$installdb->form_fields_type;
                $dbTables[]=$installdb->form;
                $dbTables[]=$installdb->settings;
                
			if (count($dbTables) > 0) {
				foreach($dbTables as $table_name){
                                   
					mysql_query("DROP TABLE `".(string)$database_name."`.{$table_name}", $conn);
				}
			}
		
                        
                        /*END: Remove All Tables from the Database*/
		
                    /*BEGIN: Restore Database Content*/
                    if(isset($database_file))
                    {

                        $sql_file = file_get_contents($database_file, true);

                        $sql_file = strtr($sql_file, array(
                            "\r\n" => "\n",
                            "\r" => "\n",
                        ));
                    $sql_queries = explode(";\n", $sql_file);                        
                      
                        for($i = 0; $i < count($sql_queries); $i++) {

                            @mysql_query($sql_queries[$i], $conn);
                            
                        }


                    }
		}
                 
		/*END: Restore Database Content*/
                
         /*End Begin restore*/       
         } catch (Exception $exception) {
            die($exception->getMessage());
        }
    }
    function makeDbBackup($name=''){
        require_once( UIFORM_FORMS_DIR . '/classes/uiform-installdb.php');
        $installdb = new Uiform_InstallDB();
        $dbTables=array();
        $dbTables[]=$installdb->form;
        $dbTables[]=$installdb->form_history;
        $dbTables[]=$installdb->form_fields_type;
        $dbTables[]=$installdb->form_fields;
        $dbTables[]=$installdb->settings;
        $this->tables=$dbTables;
        
        
          $dump = '';
            $database = DB_NAME;
            $server = DB_HOST;
          $dump .= '-- --------------------------------------------------------------------------------' . nl;
          $dump .= '-- ' . nl;
          $dump .= '-- @version: ' . $database . '.sql ' . date('M j, Y') . ' ' . date('H:i') . ' Softdiscover' . nl;
          $dump .= '-- @package Uiform - Wordpress Form Builder' . nl;
          $dump .= '-- @author softdiscover.com.' . nl;
          $dump .= '-- @copyright 2015' . nl;
          $dump .= '-- ' . nl;
          $dump .= '-- --------------------------------------------------------------------------------' . nl;
          $dump .= '-- Host: ' . $server . nl;
          $dump .= '-- Database: ' . $database . nl;
          $dump .= '-- Time: ' . date('M j, Y') . '-' . date('H:i') . nl;
          $dump .= '-- MySQL version: ' . Uiform_Form_Helper::mysql_version() . nl;
          $dump .= '-- PHP version: ' . phpversion() . nl;
          $dump .= '-- --------------------------------------------------------------------------------' . nl . nl;
          
          $dump .= 'DROP TABLE IF EXISTS `' . $installdb->form_history . '`;' . nl;
          $dump .= 'DROP TABLE IF EXISTS `' . $installdb->form_fields . '`;' . nl;
          $dump .= 'DROP TABLE IF EXISTS `' . $installdb->form_fields_type . '`;' . nl;
          $dump .= 'DROP TABLE IF EXISTS `' . $installdb->form . '`;' . nl;
          $dump .= 'DROP TABLE IF EXISTS `' . $installdb->settings . '`;' . nl;
          
          $database = DB_NAME;
          if (!empty($database)) {
              $dump .= '#' . nl;
              $dump .= '# Database: `' . $database . '`' . nl;
          }
          $dump .= '#' . nl . nl . nl;
          $tables = $this->getTables();
          if(!empty($tables)){
             foreach ($this->tables as $key=>$table) {
                if(intval($key)===0){
                 $table_dump = $this->dumpTable($table,true);   
                }else{
                 $table_dump = $this->dumpTable($table);   
                }
                 
                 if (!($table_dump)) {
                    return false;
                }
                $dump .= $table_dump;
            }   
          }
          
        
          $fname = UIFORM_FORMS_DIR . '/backups/';
              $fname .= (!empty($name))?$name:date($this->suffix);
              $fname .= '.sql';
              if (!($f = fopen($fname, 'w'))) {
                  return false;
              }
              fwrite($f,$dump);
              fclose($f);
    }
    
    function getTables()
    {
        $value = array();
        if (!($result = $this->wpdb->get_results("SHOW TABLES"))) {
            return false;
        }
        foreach ($result as $mytable)
        {
            foreach ($mytable as $t) 
            {
                if(in_array($t,$this->tables)){
                     $value[]= $t;
                }
            }
        }
        if (!sizeof($value)) {
            return false;
        }
        
        return $value;
       
    }
  
    
    function dumpTable($table, $flag=false)
      {
         
         // $dump = '';
          $this->wpdb->query('LOCK TABLES ' . $table . ' WRITE');
          
        // $tables = $this->wpdb->get_col('SHOW TABLES');
	$output = '';
	//foreach($tables as $table) {
		$result = $this->wpdb->get_results("SELECT * FROM {$table}", ARRAY_N);
                if($flag===true){
                    
                    //verifying the first table has content
                    $row = isset($result[0])?$result[0]:'';
                    if(empty($row[0])){
                        return false;
                    }
                }
                $output .= '-- --------------------------------------------------' . nl;
          $output .= '# -- Table structure for table `' . $table . '`' . nl;
          $output .= '-- --------------------------------------------------' . nl;
          $output .= 'DROP TABLE IF EXISTS `' . $table . '`;' . nl;
		$row2 = $this->wpdb->get_row('SHOW CREATE TABLE '.$table, ARRAY_N); 
		$output .= "\n\n".$row2[1].";\n\n";
		for($i = 0; $i < count($result); $i++) {
			$row = $result[$i];
			$output .= 'INSERT INTO '.$table.' VALUES(';
			for($j=0; $j<count($result[0]); $j++) {
				$row[$j] = Uiform_Form_Helper::escape_str($row[$j]);
				$output .= (isset($row[$j])) ? '"'.$row[$j].'"'	: '""'; 
				if ($j < (count($result[0])-1)) {
					$output .= ',';
				}
			}
			$output .= ");\n";
		}
		$output .= "\n";
	//}
          
          $this->wpdb->query('UNLOCK TABLES');
          return $output;
      }
      
    function insert($table)
	  {
            $output = '';
            if (!$query = $this->wpdb->get_results("SELECT * FROM `" . $table . "`")) {
                    return false;
            }
            foreach ($query as $result) {
                    $fields = '';
                   
                    foreach (array_keys((array)$result) as $value) {
                            $fields .= '`' . $value . '`, ';
                    }
                    $values = '';
                    
                    
                    foreach (array_values((array)$result) as $value) {
                            /*$value = str_replace(array("\x00", "\x0a", "\x0d", "\x1a"), array('\0', '\n', '\r', '\Z'), $value);
                            $value = str_replace(array("\n", "\r", "\t"), array('\n', '\r', '\t'), $value);
                            $value = str_replace('\\', '\\\\', $value);
                            $value = str_replace('\'', '\\\'', $value);
                            $value = str_replace('\\\n', '\n', $value);
                            $value = str_replace('\\\r', '\r', $value);
                            $value = str_replace('\\\t', '\t', $value);*/

                            $values .= '\'' . $value . '\', ';
                    }

                    $output .= 'INSERT INTO `' . $table . '` (' . preg_replace('/, $/', '', $fields) . ') VALUES (' . preg_replace('/, $/', '', $values) . ');' . "\n";
                   
            }
            return $output;
	  }  
      
    
}
?>
