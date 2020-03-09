<?php


$conn = new mysqli("localhost", "root", "","agencewebquebec");
$selectImages="Select Image,company_id from gallery ";
$res=$conn->query($selectImages) or die($conn->error);

    while($rec=$res->fetch_array()){

        $companyBlob=$rec["Image"];
        $companyId=$rec["company_id"];
        //convert Blob to String for REGEX
        $companyImageString = (string)$companyBlob;


        //Split Images
        $splitImages = explode('<br>',$companyImageString);
        //Get image du milleu, la première moitier est du garbage (Pour data du web crawler)
        $half=((count($splitImages)-1)/2)+1;
        //Get avant dernier image, la dernière image est du garbage (Pour data du web crawler)
        $half1=count($splitImages)-2;




        $path="C:/Users/admin/Documents/photos/".$companyId;


        if(!is_dir($path)){
            mkdir($path,0777,false);
        }


        for($i=$half;$i<$half1;$i++){
            echo $splitImages[$i]."  :  ID  => id:".$companyId."<br>";
            $file=$splitImages[$i];
            //$file_type=$_FILES[$file]['type'];
            $file_type="";
            $saveFormat=$splitImages[$i].".".$file_type;
          file_put_contents($path,file_get_contents($saveFormat));


        }




}

