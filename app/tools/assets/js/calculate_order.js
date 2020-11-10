$(document).ready(function() {
	// console.log('Hello, here we go');

	$('input[type="checkbox"]').click(function(){
        if($(this).prop("checked") == true){



            var id = $(this).val()
            $('#order_quantity_'+id).attr("readonly", false);
            var price = parseInt($('#order_price_per_item_'+id).val())
            var original_quantity = parseInt($('#order_quantity_'+id).val())
            var original_total = price * original_quantity;
            $('#order_total_'+id).val(original_total)
            getTotals()


            $('#order_quantity_'+id).bind('keyup input', function() {
            	var new_quantity = parseInt($('#order_quantity_'+id).val());
            	var new_total = price * new_quantity;
            	$('#order_total_'+id).val(new_total)
	            getTotals()
            })
        }
        else if($(this).prop("checked") == false){
            var id = $(this).val()
            $('#order_quantity_'+id).attr("readonly", true);
            $('#order_quantity_'+id).val('1.00');
            $('#order_total_'+id).val('0.00')
            getTotals()


        }
    });


    $('#receipt_paid').keyup(function() {
        var p = $(this).val();
        p = p.trim();
        if(p.length > 0) {
            if($('#receipt_total_amount').val() > 0) {
                var balance =parseInt($('#receipt_total_amount').val()) - parseInt(p);
                $('#receipt_balance').val(balance);
            }
        }
    })






    function getTotals() {
    	// body...
    	var arr = document.getElementsByClassName('totals');
				    var tot=0;
	    for(var i=0;i<arr.length;i++){
	        if(parseInt(arr[i].value))
	            tot += parseInt(arr[i].value);
	    }
	    document.getElementById('receipt_total_amount').value = tot;
	    document.getElementById('receipt_balance').value = tot;
    }
})
