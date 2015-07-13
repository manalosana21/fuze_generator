<?php require_once('../js/good_query.php'); 
require_once('../js/secured.php');

good_connect();
$quote_lines = json_decode($_POST['quote_lines']);
$billto_id = intval(GetSQLValueString($_POST['location_id'],"int"));
if ( count($quote_lines) <= 0 || $billto_id == 0) { 
 	echo json_encode(0);
	good_die();
}
$last_id = intval(good_query_value("SELECT tms_ar_invoice_no FROM tms_ar WHERE fk_company_id = $CompanyID AND tms_ar_dray = 1
		AND tms_ar_invoice_no IS NOT NULL
		AND fk_group_id = $GroupIDParent ORDER BY tms_ar_invoice_no DESC LIMIT 1"));
		
$last_id++; 
good_query("INSERT INTO tms_ar SET
		fk_company_id = $CompanyID,
		fk_group_id = $GroupIDParent,
		fk_user_id = $UserID,
		fk_location_id = $billto_id,
		tms_ar_created_date = NOW(),
		tms_ar_invoice_date = NOW(),
		tms_ar_invoice_no = $last_id,
		tms_ar_type = 1,
		tms_ar_dray = 1;");

$invoice_id = good_last();
 
foreach($quote_lines as $quote_line) {
	 	 
 	$order_id = intval($quote_line->order_id);
 	$manifest_id = intval($quote_line->manifest_id);
 	$desc = GetSQLValueString($quote_line->desc,"text");
 	$gl_code = intval($quote_line->gl_code);
 	$amount = intval($quote_line->amount);
 	
	if($order_id <= 0 || $manifest_id <= 0 || $gl_code <= 0) {	
		continue;
	}
	$container = good_query_assoc("select dray_manifest.fk_dray_order_id
		from dray_manifest 
		inner join dray_order on dray_manifest.fk_dray_order_id = dray_order.dray_order_id 
		inner join location on dray_order.fk_billto_id = location.location_id
		where fk_company_id = $CompanyID  
		AND dray_manifest_id = $manifest_id
		;");
		
 
	if($container == NULL) {
		continue;
	}	
 
	good_query("INSERT INTO tms_quote_lines SET 
		fk_user_id = $UserID,
		fk_company_id = $CompanyID,
 		tms_quote_lines_created_date = NOW(),
		tms_quote_lines_manual = 1,
		tms_quote_lines_include = 1,
		tms_quote_lines_type = 'CHARGE-MISC',
		tms_quote_lines_quote_amount = 0,
		tms_quote_lines_override_amount = $amount,
		fk_tms_gl_code_id = $gl_code,
		fk_tms_ar_id = $invoice_id,
		fk_dray_manifest_id = $manifest_id,
		fk_dray_order_id = $order_id,
		tms_quote_lines_lane_text = $desc ,
		tms_quote_lines_calc_text =	 $desc
		"); 
 
}

 

	 
echo json_encode($last_id);
 
good_close();

?>