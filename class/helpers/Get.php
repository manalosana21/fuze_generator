<?php
class View_Helper_Get {
    private static $instance = array();
 	/*
	sample
	$query_b =  View_Helper_CommonDB::model('BannedUsers')->all();
	while($query_b->fetch()) {
		echo $query_b->field('username')."<BR>";
	}
 	*/
	public static function single($className,$arguments = array()) {
          if (!isset(self::$instance[$className])) {
             self::$instance[$className] = new $className($arguments);
        }
        return self::$instance[$className];		
	}
	
		
	public static function helper($className,$arguments = array()) {
	     $className = "View_Helper_".$className;
         return  self::single($className,$arguments);		
	}
	
		
	public static function model($className,$arguments = array()) {
  		 $className = "Application_Model_".$className;
         return self::single($className,$arguments);		
	} 	
}
/*
class View_Helper_Get {
    private static $instance;
  
	public static function helper($className) {
  
        if (!isset(self::$instance)) {
             echo 'View_Helper_';
			 $className = "View_Helper_".$className;
             self::$instance = new $className;
        }
        return self::$instance;		
	}
	
		
	public static function model($className) {
  
        if (!isset(self::$instance)) {
             echo 'Application_Model_';
			 $className = "Application_Model_".$className;
             self::$instance = new $className;
        }
        return self::$instance;		
	} 	
}
*/
/*
View_Helper_Get::model('TestDB')->say();
View_Helper_Get::helper('TestHelper')->go();
View_Helper_Get::model('TestDB')->say();
would go error
*/

?>