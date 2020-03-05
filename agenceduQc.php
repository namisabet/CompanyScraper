<html>
<head><meta http-equiv="Content-Type" content="text/html"; charset="utf-8"></head>
<body>
<!--<script>


    // ==UserScript==
    // @name     sitegetter
    // @version  1
    // @grant    none
    // @require https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js
    // ==/UserScript==

    var i = 0;
    var cnt = document.getElementsByClassName('left').length;
    console.log(cnt);
    while (i < cnt) {

        var email=document.getElementById('contact').children[1].innerText;
        var title=document.getElementByTagName('h1').getAttribute('itemProp').innerText;

        console.log(document.getElementsByClassName('left')[i]);

        $.ajax({
            type: 'GET',
            url: 'http://localhost/pusher/index.php',
            dataType: 'json',
            data:{title: title,email: email},
            success : function(data){
                if (data){
                    //console.log("Success: " +data);

                }
                else
                {
                    //console.log("pas de succes");
                }
            }
        });

        i++;
    }
    setTimeout(function(){ document.getElementsByClassName('next')[0].children[0].click(); }, 3000);







    //document.write("HEY");
     emails=[];
    for(var i=1;i<2;i++){
        var doc=document.querySelectorAll('https://www.agenceswebduquebec.com/agencies?page='+i);
        var email=doc.getElementById('contact').children[1].innerText;
        emails.push(email);
        alert(email);
    }
    document.write(emails.toString())
</script>!-->
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
header ('Content-type: text/html; charset=utf-8');
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
while($i<2){

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
    $_SESSION["link"]=$link;

     //get tags
    $nameTag=$xPath->evaluate("/html/body/div[1]/div[1]/div[3]/h1");
    $descriptionTag= $xPath->evaluate("/html/body/div[1]/div[1]/div[3]/div[2]/div[1]/div[1]");
    $budgetTag=$xPath->evaluate("/html/body/div[1]/div[1]/div[3]/div[2]/div[1]/div[2]/span");
    $cityTag=$xPath->evaluate("/html/body/div[1]/div[1]/div[3]/div[2]/div[1]/div[3]/span");
    $expertiseTag=$xPath->evaluate(" /html/body/div[1]/div[1]/div[3]/div[2]/div[1]/div[4]");
    $emailTag=$xPath->evaluate("//*[@id=\"contact\"]/p[2]/a");
    $companyLinkTag=$xPath->evaluate("/html/body/div[1]/div[1]/div[3]/div[2]/div[2]/div/p[1]/a");


    //get images
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
    $infos=array(   "Description"=>getAnchorsSingle($descriptionTag),
                    "Budget"=>getAnchorsSingle($budgetTag),
                    "Ville"=>getAnchorsSingle($cityTag),
                    "Expertise"=>getAnchorsSingle($expertiseTag),
                    "email"=>getAnchorsSingle($emailTag),
                    "link"=>getAnchorsSingle($companyLinkTag),
                    "Photos"=>$image_list
                    );
    array_push($cieInfos,array(getAnchorsSingle($nameTag)=>$infos));





}

//print array
//var_dump($cieInfos);

//iterate through the array of information///////////////////////
foreach($cieInfos as $cie){

            foreach($cie as $key=>$res){
                  var_dump($res["email"]);
               // displays the data fetched from agencewebquebec
//                echo "NAME:".$key."<br> DESC: ".$res["Description"]." <br> BUDGET: ".$res["Budget"]." "." <br> CITY:  ".
//                    $res["Ville"]."  <br>EXPERTISE: ".$res["Expertise"].".".$res["email"] . $res["link"].".".$res["Photos"].".
//                    <br>__________________________________<br>";
               insertRecords($key,$res["Ville"],$res["Description"],$res["Budget"],$res["Expertise"],$res["Photos"],$res["link"],$res["email"]);
            }


}

//function to encode
function encode($string){
    $string=htmlspecialchars($string,ENT_QUOTES,'utf-8');
    return $string;
}

//function to insert in DB
function insertRecords($titre,$ville,$description,$budget,$expertise,$images,$link,$email){

    /////////CONNECTION///////
    $conn = new mysqli("localhost", "root", "","agencewebquebec");
    $conn->set_charset('utf8');
   // print_r($conn->get_charset());

    //encode informations for insertion
    $titre=encode($titre);
    $ville=encode($ville);
    $description=encode($description);
    $budget=encode($budget);
    $expertise=encode($expertise);
    $images=mysqli_real_escape_string($conn,$images);


        //insert record in information table
        $sqlStatement="Insert into informations (Titre,Ville,Description,Budget,Expertise,link,email)
                        values('$titre','$ville','$description','$budget','$expertise','$link','$email')";

      //get id for new inserted row
          $last_id=$conn->insert_id;
          if($conn->query($sqlStatement)===true){
              $last_id=$conn->insert_id;
          }

    //insert images in gallery
    $sqlInsertImage="Insert into gallery(Image,company_id) values('$images','$last_id')";
    $conn->query($sqlInsertImage) or die ($conn->error);


}

//fetch info from DB
//function selectRecords(){
//    $companies=array();
//
//    $conn = new mysqli("localhost", "root", "","agencewebquebec");
//
//    $selectStatement="SELECT * FROM informations i,Gallery g where i.id=g.company_id";
//
//    $res=$conn->query($selectStatement) or die($conn->error);
//
//    if($res->num_rows>0){
//
//        while($rec=$res->fetch_array()){
//
//            array_push($companies,
//                        array($rec["Titre"]=>array(
//                            $rec["Ville"],
//                            $rec["Description"],
//                            $rec["Budget"],
//                            $rec["Expertise"],
//                            //$rec["Image"],
//                            $rec["link"],
//                            $rec["email"]
//                                                )
//                                )
//                        );
//
//        }
//    }
//
//    return $companies;
//}
//
//$companies=selectRecords();
//foreach($companies as $c){
//    foreach($c as $v){
//       print_r($v);
//    }
//}?>

</body>
</html>