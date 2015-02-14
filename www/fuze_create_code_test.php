<?php
/*
            array("0"=>"None","1"=>"Test");
            array("1"=>"Active","0"=>"Disabled");
    
*/
require_once('../bootstrap.php');
good_connect();


$fk_list = array(
				'fk_company_id'=>'$CompanyID',
				'fk_group_id'=>'$GroupID',
				'fk_user_id'=>'$UserID',
				);
				
				
	$table = 'file_category';
	echo "<pre>"; 
	print_r($_POST);
	echo "</pre>";
	$list_fields_array = array();
	$sql = "SHOW COLUMNS FROM ".$table.";";

	$sql_fields = good_query_table($sql);
	$x = 0;
?>
<form action="fuze_create_code_test.php" method="post">
<input type="hidden" name="action" value="process">
<?php	

	foreach  ($sql_fields as $key => $sql_field) {
		if (!$sql_field['Key'] == 'PRI') {
			if(!isset($fk_list[ $sql_field['Field']])) {
 ?>
                <label><?php echo $sql_field['Field']; ?></label>
                    <input type="checkbox" name="required_fields[]" value="<?php echo $sql_field['Field']; ?>" id="required_fields_<?php echo $x; ?>"> <BR>
                    
                <label><?php echo $sql_field['Field']; ?></label>  
                	<input type="checkbox" name="enum_fields[]" value="<?php echo $sql_field['Field']; ?>" id="enum_fields<?php echo $x; ?>"><BR>
                    <input type="text" name="enum_<?php echo $sql_field['Field']; ?>" value="" id="enum_<?php echo $sql_field['Field']; ?>">
                 <br>   
<?php				
				$x++;	 
			}
		}
	
	}
?>
<input type="submit" id="submit" value="submit"><BR>
</form>	
<?php
good_close();

?>

 