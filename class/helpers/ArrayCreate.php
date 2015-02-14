<?php
class View_Helper_ArrayCreate {
 
 	public function fk_lists() {
		$parameters =  View_Helper_Get::single('parameters'); 
		$fk_lists = array(
				'fk_company_id'=>'$CompanyID',
				'fk_group_id'=>'$GroupID',
				'fk_user_id'=>'$UserID',
				);
		$parameters->fk_lists = $fk_lists;		
	} 
	
		
	public function stored_lists() {
 		$parameters =  View_Helper_Get::single('parameters'); 
		$stored_lists = array(
							'created_date'=>'now()',
							);	
		$parameters->stored_lists = $stored_lists;								
	} 	


	public function list_fields($sql_fields,$required_fields,$enum_fields,$allow_status=0) {
		$parameters =  View_Helper_Get::single('parameters'); 
		$is_status_exist = 0;
		$is_fk_exist = 0;
		$list_fields_array = array();
		$enum_list = array();
		$x_count = 0;
		$table = $parameters->table;
		$temptable = str_replace("_"," ",$table);
		$parameters->table_title = ucwords($temptable);
		
		foreach  ($sql_fields as $key => $sql_field) {
			if ($sql_field['Key'] == 'PRI') {
				$parameters->primary_key  = $sql_field['Field'];
			} else {
					$field_name = str_replace($table."_","",$sql_field['Field']);
					$field_name = ucwords(str_replace("_"," ",$field_name));
 					$list_fields_array[$x_count] = array(
												 'field' => $sql_field['Field'],
												 'field_name' => $field_name,
												 );
					$list_fields_array[$x_count]['type'] = (preg_match("/int\(/i",$sql_field['Type'])) ? 'int':'text';								
					$list_fields_array[$x_count]['is_fk_list'] = (isset($parameters->fk_lists[$sql_field['Field']])) ? 1:0;	
					$list_fields_array[$x_count]['fk_value'] = (isset($parameters->fk_lists[$sql_field['Field']])) ? $parameters->fk_lists[$sql_field['Field']]:'';	
					$list_fields_array[$x_count]['default_value'] = ''; 
					$list_fields_array[$x_count]['is_required'] = (in_array($sql_field['Field'],$required_fields)) ? 1:0;
					$list_fields_array[$x_count]['is_enum'] = (isset($enum_fields['enum_'.$sql_field['Field']])) ? 1:0;
  					if((isset($enum_fields['enum_'.$sql_field['Field']])) ) {
					$list_fields_array[$x_count]['enum'] =$enum_fields['enum_'.$sql_field['Field']];	
					$enum_list[] = $sql_field['Field'];
 					}
					foreach ($parameters->stored_lists as $stored_list_key => $stored_list ) {
							if(preg_match("/".$stored_list_key."/i",$sql_field['Field'])) {
							$list_fields_array[$x_count]['default_value'] = $stored_list;	
							} 					
					}
						if(preg_match("/status/i",$sql_field['Field']) && !preg_match("/fk_/i",$sql_field['Field'])) {
						$list_fields_array[$x_count]['is_status'] = 1;	
						$is_status_exist = 1;
						$parameters->status_key = $x_count; 
						}
						if(isset($parameters->fk_lists[$sql_field['Field']])) {
						$is_fk_exist = 1;
						}
					
					$x_count++;
			}
		
		}	

		$parameters->allow_status = $allow_status; 
 		$parameters->enum_list = $enum_list; 
 		$parameters->list_fields = $list_fields_array; 
		$parameters->is_status_exist = $is_status_exist;
 		$parameters->is_fk_exist = $is_fk_exist;						
	} 
}
 

?>
 