$(document).ready(function() {
	$('.table').DataTable({
		columnDefs: [
		   { orderable: false, targets: -1 }
		],
		// dom: 'lfrtip',
		// buttons: [ 'copyHtml5', 'excelHtml5', 'csvHtml5', 'pdfHtml5' ] 
	});
})

