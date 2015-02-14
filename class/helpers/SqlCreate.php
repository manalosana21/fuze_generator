<?php
class View_Helper_SqlCreate {
 	private $fields = array();
	
 	public function fkWhere() {
		$parameters =  View_Helper_Get::single('parameters');
		foreach($parameters->list_fields as $key => $list_field) {
			if(!View_Helper_CSCreate::isFklist($list_field)) {
				$this->fields[] =  $list_field['field']." = ".$list_field['fk_value'];
			}
		}	 
		return $this;
	}
	
 	public function statusWhere() {
		$parameters =  View_Helper_Get::single('parameters');
		if($parameters->is_status_exist == 1) {
 		$this->fields[] = $parameters->list_fields[$parameters->status_key]['field'].' &gt;= 0' ;
		}
 		return $this;
	}
 
   	public function executeWhere() {
		$fields = implode(' AND ', $this->fields); 
		$this->fields = array();
 		return $fields;
	} 
	
	public function filterCheck() {
	$parameters =  View_Helper_Get::single('parameters');	
	return ($parameters->is_fk_exist == 1 || $parameters->is_status_exist == 1) ? "AND":"WHERE";		
	}
	
 	public function insertFields() {
		$parameters =  View_Helper_Get::single('parameters');
		$fields = array();
		foreach($parameters->list_fields as $key => $list_field) {
			if($list_field['is_fk_list'] == 0 && !isset($list_field['is_status'])) {
			$this->fields[] =  $list_field['field'].' = $'.$list_field['field'];
			}
		}
	return $this;		 
 	}
		
 	public function fkFields() {
		$parameters =  View_Helper_Get::single('parameters');
		$fields = array();
		foreach($parameters->list_fields as $key => $list_field) {
			if($list_field['is_fk_list'] == 1 && !isset($list_field['is_status'])) {
			$this->fields[] =  $list_field['field'].' = '.$list_field['fk_value'];
			}
		}
	return $this;		 
 	}

   	public function executeFields() {
		$fields = implode(",\n", $this->fields); 
		$this->fields = array();
 		return $fields;
	} 	
}

?>
 