<?php
session_start();
/*
Plugin Name: Import XLSX
Description: Upload XLSX files.
Version: 1.0.00
Author: Ludovic CAPACCI
*/
?>
<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
</head>
<body>
<form action="import.php" method="post" enctype="multipart/form-data" >
        <input type="file" name="file" id="file" />
        <input type="submit" name="submitBtn" value="Import">
</form>
<?php

        if(isset($_SESSION['message']))
        {?>
        <h4><?=$_SESSION['message'];?></h4>
        <?php unset($_SESSION['message']);
        };
?>
        
</body>
</html>
