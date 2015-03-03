<?php


require_once('../bootstrap.php');
good_connect();
$parameters->auto_main_table = 'luna_countries'; 

$parameters->auto_field = 'fk_luna_countries_id';  
$parameters->auto_table = 'luna_countries';
$parameters->auto_label =  array('luna_countries_name');
$parameters->auto_table_id = 'luna_countries_id';
 
$table = $parameters->auto_main_table;
$temptable = str_replace("_"," ",$table);
$parameters->auto_main_table_title = ucwords($temptable);

$table = $parameters->auto_table;
$temptable = str_replace("_"," ",$table);
$parameters->auto_table_title = ucwords($temptable);

include('../template/autocomplete/dashboard.phtml');
include('../template/autocomplete/search.phtml');
 
good_close();
 
?>		
 