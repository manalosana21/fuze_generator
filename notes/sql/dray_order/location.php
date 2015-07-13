<?php
require_once('../js/good_query.php'); 
require_once('../includes/secure_global.php');

good_connect();

$result = 0;

$manifest_id = intval(GetSQLValueString($_POST['manifest_id'],"int"));
if($manifest_id > 0) {
	$rows = good_query_table("SELECT task_master_name,
								loc1.location_name AS location1_name,
								loc1.location_street AS location1_street,
								loc1.location_city AS location1_city,
								loc1.location_state AS location1_state,
								loc1.location_zip AS location1_zip,
								loc1.location_phone AS location1_phone,
								loc2.location_name AS location2_name,
								loc2.location_street AS location2_street,
								loc2.location_city AS location2_city,
								loc2.location_state AS location2_state,
								loc2.location_zip AS location2_zip,
								loc2.location_phone AS location2_phone
								FROM
								task_group
									INNER JOIN
								task_master ON task_group.fk_task_master_id = task_master.task_master_id
									INNER JOIN
								location AS loc1 ON task_group.fk_location_id_previous = loc1.location_id
									INNER JOIN
								location AS loc2 ON task_group.fk_location_id = loc2.location_id
								WHERE fk_dray_manifest_id = $manifest_id
								AND task_group_status = 2
								order by task_group_completed_date desc;");
	if($rows != NULL){
		$result = $rows;
	}
}
echo json_encode($result);

good_close();

?>