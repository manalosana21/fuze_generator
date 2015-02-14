 <?php
 require_once('../../js/good_query.php'); 	
 
	$assign_vars = array();
	$assign_vars['BOXED_MESSAGE'] ="";
	$DIR = __DIR__;
     function my_autoload ($pClassName) {
		global $DIR;
 		if (preg_match("/View_Helper/i", $pClassName)) {
			$pClassName_new = str_replace("View_Helper_","",$pClassName);
			include($DIR . "/class/helpers/" . $pClassName_new . ".php");	
		} else {
			$pClassName_new = str_replace("View_Helper_","",$pClassName);
			include($DIR . "/class/helpers/" . $pClassName_new . ".php");	
		}		
    }
	spl_autoload_register("my_autoload");
 	class parameters extends View_Helper_Dataset{}
 
	
  	$parameters =  View_Helper_Get::single('parameters');
 	$parameters->DIR = $DIR;
	View_Helper_Get::helper('ArrayCreate')->fk_lists();
	View_Helper_Get::helper('ArrayCreate')->stored_lists();
 ?>