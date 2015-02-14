<?php
/*test commit */
require_once('js/good_query.php'); 

good_connect();



$file_category_table = good_query_table("SELECT * FROM file_category     ");


print_r($file_category_table );