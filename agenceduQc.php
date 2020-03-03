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
$cieInfos=array();
$links[]=array();
$i=1;
function getDocument($link){
    $html = file_get_contents($link);
    $document = new DOMDocument;
    @$document ->loadHTML($html);
    $xPath = new DOMXPath($document );

    return $xPath;
}
while($i<3){
    $xPath=getDocument('https://www.agenceswebduquebec.com/agencies?page='.$i.'');
    $anchorTags = $xPath->evaluate("//div[@class=\"left\"]//a/@href");


    foreach ($anchorTags  as $anchorTag) {
        //ignore les sites
        if(substr( $anchorTag->nodeValue, 0, 4 )!="http" &&! in_array($anchorTag->nodeValue,$links)){

                array_push($links,$anchorTag->nodeValue);


        }


    }


    $i++;
}

//echo count($links);
//var_dump($links);
foreach($links as $cie){
    $array_infos=array("Description"=>"","Budget"=>"","Ville"=>"","Expertise"=>"","Photos"=>"");

     $link='https://www.agenceswebduquebec.com'.$cie.'';
     echo $cie."<br>";
//     $xPath=getDocument($link);
//
//    $anchorTags = $xPath->evaluate("//div[@id=\"description\"]//a/@href");
//
//    foreach ($anchorTags  as $anchorTag) {
//
//
//          echo $anchorTag->nodeValue;
//
//
//
//
//
//    }

}
