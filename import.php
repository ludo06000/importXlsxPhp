<?php
    session_start(); 
    $db = 'test';
    $user = 'root';
    $password = 'root';
    $serveur = 'localhost';
    $tabledb = 'products';

    $connection = mysqli_connect($serveur,$user,$password,$db);
    require 'vendor/autoload.php';
   

    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

    if(isset($_POST['submitBtn']))
    {
        // SQL query
        $sql = "SHOW TABLES IN `".$db."` WHERE `Tables_in_".$db."` = '".$tabledb."'";

       // perform the query and store the result
       $result = $connection->query($sql);
       $table = $result->num_rows;

       if ($table < 1) 
       {
           $sql = "CREATE TABLE products (
               id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
               referenceProduct VARCHAR(255) NOT NULL,
               nameProduct VARCHAR(255) NOT NULL,
               brandProduct VARCHAR(255),
               statusProduct VARCHAR(255),
               autreProduct VARCHAR(255),
               reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
               )";
           //executing the query
           if (!$connection->query($sql)) {
               echo "Error creating table: ". $connection->error;
           } else { 
               echo "Table ".$tabledb." created successfully.";
           }

       }
       $fileName=$_FILES['file']['name'];
       $file_extension = pathinfo($fileName, PATHINFO_EXTENSION);

       //if the file is not xlsx error message
       if($file_extension === 'xlsx')
       {
           $inputFileNamePath = $_FILES['file']['tmp_name'];;

           $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
           $reader->setReadDataOnly(true);
           $spreadsheet = $reader->load($inputFileNamePath);
           
           $data = $spreadsheet->getActiveSheet()->toArray();

           $count = "0";

           foreach($data as $row)
           {
               if($count > 0) 
               {
                   $reference = $row['0'];
                   $name = $row['1'];
                   $brand = $row['2'];
                   $status = $row['3'];
                   $autre = $row['4'];
       
                   $products = "INSERT INTO products (referenceProduct,nameProduct,brandProduct,statusProduct,autreProduct) VALUES('$reference','$name','$brand','$status','$autre')";
                   $result = mysqli_query($connection, $products);
                   $msg = true;
               }else
               {
                   $count = "1";
               }

           };

          
           if(isset($msg))
           {
               $_SESSION['message'] = "Importation réussie";
               header(plugin_dir_path(__FILE__) . 'admin/importXLSX.php');
               exit(0);
           }else
           {
               $_SESSION['message'] = "Importation non réussi";
               header(plugin_dir_path(__FILE__) . 'admin/importXLSX.php');
               exit(0);
           };


       }else 
       {
           $_SESSION['message'] = "Le fichier n'est pas un fichier *.xlsx";
           header(plugin_dir_path(__FILE__) . 'admin/importXLSX.php');
           exit(0);
       }



   }
?>
