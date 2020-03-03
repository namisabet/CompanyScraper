<?php
//$html = file_get_contents('https://www.agenceswebduquebec.com/');
//$links = [];
//$document = new DOMDocument;
//@$document ->loadHTML($html);
//$xPath = new DOMXPath($document );
//$anchorTags = $xPath->evaluate("//div[@class=\"left\"]//a/@href");
//foreach ($anchorTags  as $anchorTag) {
//    $links[] = $anchorTag->nodeValue;
//}
//
////print_r($links);
//$infos=array();
//foreach($links as $company){
//    echo $company."<br>";
//    $html = file_get_contents('https://www.agenceswebduquebec.com/');
//
//}

//array to hold all the companies
$cieInfos=array();
//array tp store all the links
$links[]=array();


//function to load each page
function getDocument($link){
    $html = @file_get_contents($link);
    $document = new DOMDocument;
    @$document ->loadHTML($html);
    $xPath = new DOMXPath($document );

    return $xPath;
}



//get the links of the different companies
$i=1;
while($i<5){

    $xPath=getDocument('https://www.agenceswebduquebec.com/agencies?page='.$i.'');
    if($xPath!=null){
        $anchorTags = $xPath->evaluate("//div[@class=\"left\"]//a/@href");


        foreach ($anchorTags  as $anchorTag) {
            //ignore the links that are not needed to navigate through the website
            if(substr( $anchorTag->nodeValue, 0, 4 )!="http" &&! in_array($anchorTag->nodeValue,$links)){
                //push all the links into an array
                array_push($links,$anchorTag->nodeValue);


            }


        }

        $i++;
    }

}

//remove the 1st element (Array)
unset($links[0]);



/////function to get the content of each tag//////////////////
/// get multiple values
function getAnchorsMultiple($evaluate){
    $tagContent=array();
    foreach($evaluate as $val){
        array_push($tagContent,$val->nodeValue);
    }

    return $tagContent;
}
////get a single value
function getAnchorsSingle($evaluate){
   $string="";
    foreach($evaluate as $val){
        $string.=$val->nodeValue;
    }

    return $string;

}



foreach($links as $cie){
    $all_images[]=array();

    //automatically goes to the next link
     $link='https://www.agenceswebduquebec.com'.$cie.'';


     //get the content of each link
     $xPath=getDocument($link);

     //get tags
    $nameTag=$xPath->evaluate("/html/body/div[1]/div[1]/div[3]/h1");
    $descriptionTag= $xPath->evaluate("/html/body/div[1]/div[1]/div[3]/div[2]/div[1]/div[1]");
    $budgetTag=$xPath->evaluate("/html/body/div[1]/div[1]/div[3]/div[2]/div[1]/div[2]/span");
    $cityTag=$xPath->evaluate("/html/body/div[1]/div[1]/div[3]/div[2]/div[1]/div[3]/span");
    $expertiseTag=$xPath->evaluate(" /html/body/div[1]/div[1]/div[3]/div[2]/div[1]/div[4]");

    //get image sources

    $html = @file_get_contents($link);
    preg_match_all( '|<img.*?src=[\'"](.*?)[\'"].*?>|i',$html, $matches );
    $image_list="";
    foreach($matches as $m){
        foreach($m as $val){
           // print $val."<br>";
            $image_list.=$val."<br>";
        }

    }


        //to store information about each company
    $infos=array("Description"=>getAnchorsSingle($descriptionTag),"Budget"=>getAnchorsSingle($budgetTag),"Ville"=>getAnchorsSingle($cityTag),"Expertise"=>getAnchorsSingle($expertiseTag),"Photos"=>$image_list);
    array_push($cieInfos,array(getAnchorsSingle($nameTag)=>$infos));





}

//print array
//var_dump($cieInfos);

//PRINT BIG ARRAY WITH ALL THE COMPANIES AND THE INFOS///////////////////////
foreach($cieInfos as $cie){

            foreach($cie as $key=>$res){

                echo "NAME:".$key."<br> DESC: ".$res["Description"]." <br> BUDGET: ".$res["Budget"]." "." <br> CITY:  ".$res["Ville"]."  <br>EXPERTISE: ".$res["Expertise"].".".$res["Photos"].".
                    <br>__________________________________<br>";

            }


}