<?php


require_once('../bootstrap.php');
good_connect();
$action = "";

$fk_list = array(
				'fk_company_id'=>'$CompanyID',
				'fk_group_id'=>'$GroupID',
				'fk_user_id'=>'$UserID',
				);
$stored_lists  = array(
				'created_date'=>'now()',
  				);	
 
 
	$required_fields = array('file_category_name','file_category_type');
	$table = 'file_category'; 
	$title_table = ucwords(str_replace("_"," ",$table));
	$top_php = "<?php 
require_once('../js/good_query.php');  
require_once('../js/secured.php'); 

good_connect(); 
 
\$action  = \$_POST['action']; 
  
";
	$top_php_get = "\$where = \"\";  
\$filter = array(); \n
	";
	$bottom_php = "\n good_close();\n

?>";
	/* 
	get_page = get list  
	write_page = save and edit list
	html_page = html	 
	*/
	$list_pages = array();
	$get_page = $write_page = $html_page = '';
	$is_fk_exist = 0;
	$is_status_exist = 0;
	$status_key = 0;
	/*
	pass where list if needed on single sql
	*/
	$store_where = "";
	/*
	required files on insert
	*/
	$required_lists = array();
 	
	$primary_key = '';
	$list_fields_array = array();

		
	$sql = "SHOW COLUMNS FROM ".$table.";";

	$sql_fields = good_query_table($sql);

	$x_count = 0;
	foreach  ($sql_fields as $key => $sql_field) {
		if ($sql_field['Key'] == 'PRI') {
			$primary_key  = $sql_field['Field'];
		} else {
				if(in_array($sql_field['Field'],$required_fields)) {
				$required_lists[] = $x_count;
				}
				$list_fields_array[$x_count] = array(
											 'field' => $sql_field['Field'],
											 'field_name' => ucwords(str_replace("_"," ",$sql_field['Field'])),
											 );
				$list_fields_array[$x_count]['type'] = (preg_match("/int\(/i",$sql_field['Type'])) ? 'int':'text';								
				$list_fields_array[$x_count]['is_fk_list'] = (isset($fk_list[$sql_field['Field']])) ? 1:0;	
				$list_fields_array[$x_count]['fk_value'] = (isset($fk_list[$sql_field['Field']])) ? $fk_list[$sql_field['Field']]:'';	
				$list_fields_array[$x_count]['default_value'] = '';
				foreach ($stored_lists as $stored_list_key => $stored_list ) {
						if(preg_match("/".$stored_list_key."/i",$sql_field['Field'])) {
						$list_fields_array[$x_count]['default_value'] = $stored_list;	
						} 					
				}
					if(preg_match("/status/i",$sql_field['Field'])) {
					$list_fields_array[$x_count]['is_status'] = 1;	
					$is_status_exist = 1;
					$status_key = $x_count; 
					}
					if(isset($fk_list[$sql_field['Field']])) {
					$is_fk_exist = 1;
					}
				
				$x_count++;
		}
	
	}
  	
	$get_page .= "if(\$action == 'list') {  ";
	foreach($list_fields_array as $key => $list_field) {
		if($list_field['is_fk_list'] == 0 && !isset($list_field['is_status'])) {
		$get_page .= "  
		if( isset(\$_POST['".$list_field['field']."'])) {  
		\$filter[] = '".$list_field['field']." = '.GetSQLValueString(\$_POST['".$list_field['field']."'],\"".$list_field['type']."\"); 
		}  
		";
		}
	}
	
	$syntax = ($is_fk_exist == 1 || $is_status_exist == 1) ? "AND":"WHERE";
	$get_page .=  " 
	if(count(\$filter) >= 1 ) {   
	\$where = \" ".$syntax." \".implode(' AND ',\$filter); 
	}	
	";
	$get_page .=  "
	\$".$table."_table = good_query_table(\"SELECT * FROM ".$table." ";
	$where_list = "";
	if($is_fk_exist == 1) {
	$temp_array = array();
		foreach($list_fields_array as $key => $list_field) {
			if($list_field['is_fk_list'] == 1) {
				$temp_array[] =  " ".$list_field['field']." = ".$list_field['fk_value']." ";
			}
		}			
	$where_list .=  " WHERE ".implode(' AND ',$temp_array);
	}
	if($is_status_exist == 1) {
	$where_list .= (preg_match("/WHERE/i",$where_list)) ? ' AND ':' WHERE ';
	$where_list .= $list_fields_array[$status_key]['field'].' >= 0 ' ;
	}
	/*  storing for single sql where */
	if(!empty($where_list)) {
	$store_where = $where_list;	
	}
	$where_list .= " \$where \");\n";
	$get_page .= $where_list;
	$get_page .= "\n } elseif(\$action == 'single')  { \n";
	
	$get_page .= "\n\$".$primary_key." = GetSQLValueString(\$_POST['".$primary_key."'],\"int\");\n";
	$get_page .= "if(empty(\$file_category_id)) {
echo json_encode(array());	
return false;	
}\n
";
 	$get_page .= "\$file_category_table = good_query_assoc(\"SELECT * FROM file_category ";
	if(!empty($store_where)) {
	$get_page .= $store_where." AND ".$primary_key." = \$".$primary_key." \");" ;  
	} else {
	$get_page .= " WHERE ".$primary_key." = \$".$primary_key." \");" ;  	
	}
	$get_page .= "\n}
echo json_encode(\$file_category_table);
";
	 $list_pages['get_page'] = $top_php.$top_php_get.$get_page.$bottom_php;

 	$write_page = "
\$result = 0 ;
\$allow = 1;
if(\$action == 'add') {
";	 
	$required_lists_array = array();
	foreach($required_lists as $required_list) {	
		$required_lists_array[] = 
		"
		\$input_".$list_fields_array[$required_list]['field']." = trim(\$_POST['input_".$list_fields_array[$required_list]['field']."']);
		if(empty(\$input_".$list_fields_array[$required_list]['field'].")) {
		\$allow = 0;	
		}
		";	
	}
	$fk_lists = array();
	$field_lists = array();
	$update_lists = array();
	$write_page .= implode("",$required_lists_array);
	$write_page .= "\nif(\$allow == 1) {\n";
	foreach($list_fields_array as $key => $list_field) {
		if($list_field['is_fk_list'] == 0) {
 				if(!empty($list_field['default_value'])) {
				$field_lists[] = "\n".$list_field['field']." = ".$stored_list.",";	
				} else {
				$write_page .= "\n\$".$list_field['field']." = GetSQLValueString(\$input_".$list_field['field']." ,\"".$list_field['type']."\");\n";
				$field_lists[] = "\n".$list_field['field']." = \$".$list_field['field'].",";
				$update_lists[] =  
				"
				\$input_".$list_field['field']." =  trim(\$_POST['input_".$list_field['field']."']);
				if(!empty(\$input_".$list_field['field'].")) {
				\$".$list_field['field']." = GetSQLValueString(\$input_".$list_field['field']." ,\"".$list_field['type']."\");
				\$update_list[] = \"".$list_field['field']." = \$".$list_field['field']."\";
				}
				";
				}
 
		} else {
 		$fk_lists[] = "\n".$list_field['field']." = ".$list_field['fk_value'].",";	
		}
	}	
	$insert = " \$sql = \" INSERT INTO ".$table." set  ";
	$insert  .= implode("",$field_lists);
	$insert  .= implode("",$fk_lists);
 	$write_page .= substr_replace($insert ,"",-1)."\";";;
	$write_page .= "
good_query(\$sql);
\$result = good_last();
}
} elseif(\$action == 'update') { 
"; 
	$write_page .="\$input_".$primary_key." = GetSQLValueString(\$_POST['input_".$primary_key."'],\"int\");\n
	\$update_list = array();\n";	
	$write_page .= implode("",$update_lists);
	$write_page .=
	"
	if(count(\$update_list) >= 1) {   
	good_query(\"update ".$table." set \".implode(\",\",\$update_list).\" WHERE ".$primary_key." = \$input_".$primary_key.";\");
		\$result = 1;
	} ";
	if($is_status_exist == 1) {
	$write_page .= "		
	} elseif(\$action == 'delete') { 
		";
		$write_page .= "\$".$primary_key." = GetSQLValueString(\$_POST['".$primary_key."'],\"int\");\n";
		$write_page .= "		if(!empty(\$".$primary_key.")) {
			good_query(\"update  ".$table." set ".$list_fields_array[$status_key]['field']." = -1 WHERE ".$primary_key." = \$".$primary_key.";\");
				\$result = 1;
			}";
	}
$write_page .= "		
}
echo json_encode(\$result);\n";
 	 $list_pages['write_page'] = $top_php.$write_page.$bottom_php;

	$insert_title_name = array(); 
	$insert_title_field = array(); 
	$search_title_field = array();
	$th_title_field = array();
	foreach($list_fields_array as $key => $list_field) {
		if($list_field['is_fk_list'] == 0 && empty($list_field['default_value'])) {
 		$insert_title_name[] = "<td class=\"small_text\">".$list_field['field_name']."</td>";
		$th_title_field[] = "<th><label>".$list_field['field_name']."</label></th>";
  		$insert_title_field[] = View_Helper_FrontCreate::input_box('input',$list_field['field']);
 		$search_title_field[] = View_Helper_FrontCreate::input_box('search',$list_field['field']);
		}
	}	
	  
good_close();
 
?>		
<textarea cols="100" rows="80"><?php print_r($th_title_field); ?></textarea>
 
<textarea cols="100" rows="80"><?php echo  $list_pages['write_page']; ?></textarea>