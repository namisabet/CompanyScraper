<?php


$conn = new mysqli("localhost", "root", "","agencewebquebec");
$selectImages="Select Image,company_id from gallery ";
$res=$conn->query($selectImages) or die($conn->error);
if($res->num_rows>0){


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
            


        for($i=$half;$i<$half1;$i++){


            $img=$companyId."_image".".$i."."png";

            chdir("photos");
            file_put_contents($img, file_get_contents($splitImages[$i]));

        }




    }




}


