$(document).ready(function() {
	// console.log('Hello, here we go');

	
    var pd = parseInt($('#pd').val());
    var bl = parseInt($('#receipt_remaining').val())

    $('#received').keyup(function() {
        var p = $(this).val();
        p = p.trim();
        console.log(p)
        if(p.length > 0) {
            if($('#receipt_remaining').val() > 0) {
                var balance =parseInt($('#receipt_remaining').val()) - parseInt(p);
                $('#receipt_balance').val(balance); 
                var new_paid = parseInt($('#pd').val()) + parseInt(p);
                $('#receipt_paid').val(new_paid); 
            }
        } else {
            $('#receipt_paid').val(pd);
            $('#receipt_balance').val(bl); 
        }
    })



})