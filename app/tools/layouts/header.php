<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
		<title><?php echo APP_NAME ?></title>

        <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,400;1,500&display=swap" rel="stylesheet">

		<link rel="stylesheet" type="text/css" href="app/tools/assets/css/styles.css">
        <?php
            if(isset($styles)) {
                if(!empty($styles)) {
                    foreach ($styles as $value) {      
        ?>
                <link rel="stylesheet" type="text/css" href="app/tools/assets/css/<?php echo $value; ?>">
                
        <?php 
                    }
                }
            }
            
         ?>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.js" crossorigin="anonymous"></script>
        <link rel="stylesheet" type="text/css" href="app/tools/assets/css/style.css">
    </head>
     <body class="sb-nav-fixed bg-light">