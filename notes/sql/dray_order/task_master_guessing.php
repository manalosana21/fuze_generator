<?php 
require_once('../js/good_query.php'); 
require_once('../js/secured.php');

good_connect();
$task_to = array();	
$task_group_id = GetSQLValueString($_POST['task_group_id'],"int");
$input_location_id = GetSQLValueString($_POST['input_location_id'],"int");

$task_group = good_query_assoc("SELECT task_master_from, task_master_to, task_group.fk_dray_manifest_id, task_group.task_group_void
	FROM task_group 
	INNER JOIN task_master ON task_group.fk_task_master_id = task_master.task_master_id
 	WHERE task_group.task_group_id = $task_group_id");
 
if($task_group == NULL) {
	echo json_encode(0);
	die();
}
 /*
			if (location_value == dispatch_consignee_id) {
                $('#dispatch_from').val(dispatch_location_type);
                $('#dispatch_to').val('Consignee');
            } else {
                $('#dispatch_from').val(dispatch_location_type);
                $('#dispatch_to').val(location_type);
            }
*/
$consignee_id =	intval(good_query_value("SELECT dray_order.fk_consignee_id FROM task_group 
										INNER JOIN
										dray_order ON task_group.fk_dray_order_id = dray_order_id
										WHERE task_group.task_group_id =  $task_group_id"));
 

if($consignee_id == $input_location_id) {
	$task_to[] = "'Consignee'";	
} else {
	$location = good_query_assoc("SELECT location_parking,location_port,location_yard
		FROM location 
		WHERE location_id = $input_location_id");
		
	/* cannot separate priority*/
	if($location['location_parking'] == 1 || $location['location_yard'] == 1) {
		$task_to[] = "'Parking'";	
	} elseif($location['location_port'] == 1) {
		$task_to[] = "'Port'"; 	
	}
}
$fk_dray_manifest_id = $task_group['fk_dray_manifest_id'];
$task_master_from = $task_group['task_master_from'];
$task_master_to = $task_group['task_master_to'];
$task_group_void = intval($task_group['task_group_void']);
$task_master_id  = 0;
if(count($task_to) >= 1) {
	$tmp_task_master_from = GetSQLValueString($task_master_from,"text"); 
	$task_master_id = intval(good_query_value("SELECT task_master_id
		FROM task_master 
		WHERE task_master_from = $tmp_task_master_from and task_master_to in (".implode(",",$task_to).")  AND 
		task_master_company_id = $CompanyID "));
		/*
 echo "SELECT task_master_id
		FROM task_master 
		WHERE task_master_from = $tmp_task_master_from and task_master_to in (".implode(",",$task_to).")  AND 
		task_master_company_id = $CompanyID ";
		*/
	if($task_master_id > 0) {
		good_query("UPDATE task_group SET fk_task_master_id = $task_master_id WHERE task_group_id = $task_group_id;");
	}
 
}
if($task_group_void > 0) {
	echo json_encode(-1);
	die();
}

if($_POST['loc_type'] == 'current') {
	good_query("UPDATE task_group SET fk_location_id = $input_location_id WHERE task_group_id = $task_group_id;");
	
}
else {
	
	if($task_master_from == "Parking") {
		good_query("UPDATE dray_manifest SET fk_parking_id = $input_location_id WHERE dray_manifest_id = $fk_dray_manifest_id;");
	}
	
	else if($task_master_from == "Port") {
		good_query("UPDATE dray_manifest SET fk_port_id = $input_location_id WHERE dray_manifest_id = $fk_dray_manifest_id;");
	}
	
	good_query("UPDATE task_group SET fk_location_id_previous = $input_location_id WHERE task_group_id = $task_group_id;");
}

echo json_encode(1);

good_close();

?>