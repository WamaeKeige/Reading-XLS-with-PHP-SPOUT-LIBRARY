<?php

use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;

# Include Spout library
require_once '../library/Spout/Autoloader/autoload.php';

#check file name is not empty
if (!empty($_FILES['file']['name'])) {

     #Get File extension eg. 'xlsx' to check file is excel sheet
    $pathinfo = pathinfo($_FILES["file"]["name"]);

    #check file has extension xlsx, xls and also check
    #file is not empty
   if (($pathinfo['extension'] == 'xlsx' || $pathinfo['extension'] == 'xls')
           && $_FILES['file']['size'] > 0 ) {

        #Temporary file name
        $inputFileName = $_FILES['file']['tmp_name'];

        #Read excel file by using ReadFactory object.
        $reader = ReaderFactory::create(Type::XLSX);
        //$reader=ReaderFactory::create(Type::XLS);
        //$reader=ReaderFactory::create(Type::CSV);

        #Open file
        $reader->open($inputFileName);
        $count = 1;
        $rows = array();

        #Number of sheet in excel file
        foreach ($reader->getSheetIterator() as $sheet) {

            #Number of Rows in Excel sheet
            foreach ($sheet->getRowIterator() as $row) {

                #reads data after header.
                if ($count > 1) {

                    #Data of excel sheet and its row number.
                    $data['Name'] = $row[0];
                    $data['Truck Registration'] = $row[1];
                    $data['Trailer No'] = $row[2];
                    $data['A/C Name'] = $row[3];
                    $data['Invoice No'] = $row[4];
                    


                    #Push all data into array to be insert as
                    #batch into database.
                    array_push($rows, $data);
                    $json_data = json_encode($rows);
                    file_put_contents('assets/json__files/truck_fueling.json',$json_data);
                }
                $count++;
            }

            #Print All data
         echo "successfully uploaded!";

            #insert all data into database table.
        }

        #Close excel file
        $reader->close();

    } else {

        echo "PLEASE SELECT A VALID EXCEL FILE";
    }

} else {

    echo "UPLOAD AN EXCEL FILE";

}
?>
