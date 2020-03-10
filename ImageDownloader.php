<?php

function conn(){

    return $conn = new mysqli("localhost", "root", "","agencewebquebec");
}


function downloadImages()
{
    $conn=conn()();
    $selectImages = "Select Image,company_id from gallery ";
    $res = $conn->query($selectImages) or die($conn->error);
    if ($res->num_rows > 0) {


        while ($rec = $res->fetch_array()) {

            $companyBlob = $rec["Image"];
            $companyId = $rec["company_id"];
            //convert Blob to String for REGEX
            $companyImageString = (string)$companyBlob;


            //Split Images
            $splitImages = explode('<br>', $companyImageString);
            //Get image du milleu, la première moitier est du garbage (Pour data du web crawler)
            $half = ((count($splitImages) - 1) / 2) + 1;
            //Get avant dernier image, la dernière image est du garbage (Pour data du web crawler)
            $half1 = count($splitImages) - 2;


            for ($i = $half; $i < $half1; $i++) {

                //puts the ID of the company to which the image belongs to ID_IMAGENAME.PNG
                $img = $companyId . "_image" . ".$i." . "png";

                chdir("photos");
                file_put_contents($img, file_get_contents($splitImages[$i]));

            }


        }


    }

}


//save filepath in gallery instead of blob
function gallery2(){

    //get all the files in photos directory
    $pics=scandir("photos");
    unset($pics[0]);unset($pics[1]);
    $conn=conn();

    foreach($pics as $path){

        ///get company id from filepath/////
        $block=explode("_",$path);
        $id=$block[0]; // echo $id."<br>";

        echo "PATH: ".$path."  ID: ".$id."<br>";
        $insert="Insert into gallery2 (img,company_id)values('$path','$id')";
        $conn->query($insert)or die ($conn->error);



    }


}
//gallery2();
