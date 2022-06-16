<?php
    session_start(); 
    $db = 'test';
    $user = 'root';
    $password = 'root';
    $serveur = 'localhost';

    $connection = mysqli_connect($serveur,$user,$password,$db);
   

    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

    if(isset($_POST['submitBtn']))
    {

        
        
        
        

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
                echo "Table Product created successfully.";
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
                header('location: importXLSX.php');
                exit(0);
            }else
            {
                $_SESSION['message'] = "Importation non réussi";
                header('location: importXLSX.php');
                exit(0);
            };


        }else 
        {
            $_SESSION['message'] = "Le fichier n'est pas un fichier *.xlsx";
            header('location: importXLSX.php');
            exit(0);
        }



    }
?>
