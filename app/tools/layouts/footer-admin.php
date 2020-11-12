
<!-- Javascript -->
<script src="app/tools/assets/plugins/jquery-3.3.1.min.js"></script>
<script src="app/tools/assets/plugins/popper.min.js"></script>
<script src="app/tools/assets/plugins/bootstrap/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>

<script>
	$(document).ready(function() {
		var dt = document.getElementsByClassName('dt');
		if (dt.length > 0) {
			$('.dt').DataTable();
		}
	})
</script>


</body>
</html>
