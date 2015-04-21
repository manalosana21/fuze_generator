<?php
/* Control Structures */
class View_Helper_CSCreate {
 
  function isFklist($list_field) {
  	return  ($list_field['is_fk_list'] == 0); 
   }
  
   function isStatus($key) {
	$parameters =  View_Helper_Get::single('parameters');  
 	if($parameters->is_status_exist == 1) {
		return ($parameters->allow_status != 0 || $key != $parameters->status_key);	
	} else {
		return 1;
	}
   }  

  function isDefaultValue($list_field) {
   	return (isset($list_field['default_value']) && empty($list_field['default_value']));
  } 
 
  function IsRequired($list_field) {
   	return ($list_field['is_required'] == 1);
  }   
  
}
 

?>
 