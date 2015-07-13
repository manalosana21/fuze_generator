$("body").on('keypress', '.input_account_num', function () {
    var val = $(this).val();
	var tmp_val =  val.substr(val.length-1, 1);
 	numReg = "^[0-9]$"
 	var regex = new RegExp(numReg);
	if (!regex.test(tmp_val)) {
		$(this).val(val.substr(0, val.length-1));
 	}
 });