<?php


require_once('../bootstrap.php');
good_connect();
$enum_fields = array(); 
$required_fields = array();
 //simulation
//$enum_fields = array('enum_file_category_type' =>  array("0"=>"None","1"=>"Test"));
//$required_fields = array('file_category_name','file_category_type');
$table = 'location'; 

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
 
$store_fields = View_Helper_Get::helper('CodeCreate')->DisplayJavascriptFieldList(0);
include('../template/crud_nonarray/get.phtml');
include('../template/crud_nonarray/write.phtml');
include('../template/crud_nonarray/dashboard.phtml');
include('../template/crud_nonarray/delete.phtml');
good_close();
 
?>		
 