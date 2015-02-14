<?php


require_once('../bootstrap.php');
good_connect();
$enum_fields = array(); 
$required_fields = array();
 //simulation
$enum_fields = array('enum_file_category_type' =>  array("1"=>"Active","0"=>"Disabled"),'enum_file_category_status' =>  array("0"=>"None","1"=>"Test"),);
$required_fields = array('file_category_name','file_category_type');
$table = 'file_category'; 

//$required_fields = array('location_contacts_name');
//$table = 'location_contacts'; 

//$required_fields = array('location_port_message_text');
//$table = 'location_cx1'; 
$allow_status = 0;
/*
$table = 'zip_route_line';
*/
$title_table = ucwords(str_replace("_"," ",$table));
$parameters->table = $table;
$sql = "SHOW COLUMNS FROM ".$table.";";

$sql_fields = good_query_table($sql);

View_Helper_Get::helper('ArrayCreate')->list_fields($sql_fields,$required_fields,$enum_fields,$allow_status);
 
include('../template/get.phtml');
include('../template/write.phtml');
include('../template/dashboard.phtml');
good_close();
 
?>		
 