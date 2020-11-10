$(document).ready(function() {
	console.log('hi')
	var url_root = $('#url_root').val()
	var query;
	$("#order_menu_id").change(function() {
		query = $(this).val();
		if(query.trim().length > 0) {
			getprices(query);
		} else {
			// $('#prescription_list').html('');
		}
	});



	$('#order_quantity').keyup(function() {
		var q = $(this).val();
		q = q.trim();
		if(q.length > 0) {
			if($('#order_price_per_item').val() > 0) {
				var order_tot =$('#order_price_per_item').val() * q;
				$('#order_total').val(order_tot); 
			}
		}
	})



	$('#receipt_paid').keyup(function() {
		var p = $(this).val();
		p = p.trim();
		if(p.length > 0) {
			
				var balance =parseInt($('#receipt_balance').val()) - parseInt(p);
				 
			}
		}
	})




    
	function getprices(query) {
		$.ajax({
			url: url_root+"menus/find_with_ajax",
			method: 'POST',
			data:{query:query},
			dataType:'json',
			success: function(data) {
				console.log(data);
					var html = "";
				
					var price = data.price;
					
					if(price == null) {
						price = '0.00'
					}
					var total_price;
					if($('#order_quantity').val() > 0) {
						total_price = price * $('#order_quantity').val();
					} else {
						total_price = price * 1;
					}	
					console.log(total_price);
					$('#order_price_per_item').val(price);
					$('#order_total').val(total_price);				
				
			}
		})
	}









	
})