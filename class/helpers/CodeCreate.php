<?php
class View_Helper_CodeCreate {
 	private $options_disp_f_type_list;
	
 	public function GetSQLValueString($list_field) {
		return "
	if( isset(\$_POST['".$list_field['field']."'])) {  
	\$filter[] = '".$list_field['field']." = '.GetSQLValueString(\$_POST['".$list_field['field']."'],\"".$list_field['type']."\"); 
	}  
		";	
	}
	
	public function DisplayGetSQLValue() { 
		$parameters =  View_Helper_Get::single('parameters'); 
		$get_page = "";
		foreach($parameters->list_fields as $key => $list_field) {
			if($list_field['is_fk_list'] == 0 && !isset($list_field['is_status'])) {
			$get_page .= $this->GetSQLValueString($list_field);
			}
		}
		return $get_page;
	}
	
	public function setShowDefaultValue($allow) { 
		$this->options_disp_f_type_list['show_default_value'] = $allow;
		return $this;
	}
	
	public function DisplayFieldTypeList() { 
		$parameters =  View_Helper_Get::single('parameters'); 
		$get_page = "";
		$store_fields = array();
		 
		foreach($parameters->list_fields as $key => $list_field) {
  			$allow = 1;
			if(isset($this->options_disp_f_type_list['show_default_value']) && $this->options_disp_f_type_list['show_default_value'] == 0) {
				if(isset($list_field['default_value']) && empty($list_field['default_value']))  {
				$allow = 1;	
				
				} else {
				$allow = 0;	
				}
			}
			if($list_field['is_fk_list'] == 0 && ($parameters->allow_status != 0 || $key != $parameters->status_key) && $allow == 1) {
			 	
			 $store_fields[$list_field['type']][] = $list_field['field'];
			}
		}
        foreach ($store_fields as $type => $fields) {
					
			$get_page .= "'".$type."' => array('".implode("','",$fields)."',)";
		}
		$this->options_disp_f_type_list = NULL;
		return '$list = array('.$get_page.');';
	}
 
 
 	public function setShowJSDefaultValue($allow) { 
		$this->options_disp_f_type_list['show_js_default_value'] = $allow;
		return $this;
	}
	
	
 	public function DisplayJavascriptFieldList($is_implode=1) { 
		$parameters =  View_Helper_Get::single('parameters'); 
 		$get_page = "";
		$store_fields = array();
		foreach($parameters->list_fields as $key => $list_field) {
			$allow = 1;
			if(isset($this->options_disp_f_type_list['show_js_default_value']) && $this->options_disp_f_type_list['show_js_default_value'] == 0) {
				if(isset($list_field['default_value']) && empty($list_field['default_value']))  {
				$allow = 1;	
				} else {
				$allow = 0;	
				}
			}			
 			if($list_field['is_fk_list'] == 0 && ($parameters->allow_status != 0 || $key != $parameters->status_key) && $allow == 1) {
 				 $store_fields[] = $list_field['field'];
 			}
		}
   		if($is_implode == 1) {
 		return "'".implode("','",$store_fields)."'";
		} else {
		return $store_fields;	
		}
	}	
	
}
 

?>
 