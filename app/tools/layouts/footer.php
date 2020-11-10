

</div>
<input type="hidden" value="<?php echo URL_ROOT;?>" id="url_root">

<script src="app/tools/assets/js/jquery.js"></script>
<script src="app/tools/assets/js/bootstrap.bundle.min.js"></script>
<script src="app/tools/assets/js/scripts.js"></script>
<?php
	if(isset($scripts)) {
		if(!empty($scripts)) {
			foreach ($scripts as $value) {		
?>
		<script src="app/tools/assets/js/<?php echo $value; ?>"></script>
		
<?php 
			}
		}
	}
	
 ?>

 


    </body>
</html>

