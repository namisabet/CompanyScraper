<?php
$html = file_get_contents('https://philanthropie.wordpress.com/repertoire-osbl-au-quebec/');
$links = [];
$document = new DOMDocument;
@$document ->loadHTML($html);
$xPath = new DOMXPath($document );
$anchorTags = $xPath->evaluate("//div[@class=\"entry\"]//a/@href");
foreach ($anchorTags  as $anchorTag) {
    $links[] = $anchorTag->nodeValue;
}
//print_r($links);

//foreach($links as $a){
//
//    $a="'".$a."'";
//    echo $a."<br>";
////    $email_array=scrape_email($a);
////
////    print_r($email_array);
//}

function scrapWebsite($link){
    $url="'".$link."'";
    $url= file_get_contents($url);
    scrape_email($url);

}


print_r(scrape_email('https://philanthropie.wordpress.com/repertoire-osbl-au-quebec/'));
echo"<br><br><br><br><br>";
var_dump(scrape_phone('https://philanthropie.wordpress.com/repertoire-osbl-au-quebec/'));


function scrape_email($url)
{
    if (!is_string($url)) {
        return '';
    }
    //$result = @file_get_contents($url);
    $result = @curl_get_contents($url);

    if ($result === FALSE) {
        return 'Site Web Vide';
    }

    // Convert to lowercase
    $result = strtolower($result);

    // Replace EMAIL DOT COM
    $result = preg_replace('#[(\\[\\<]?AT[)\\]\\>]?\\s*(\\w*)\\s*[(\\[\\<]?DOT[)\\]\\>]?\\s*[a-z]{3}#ms', '@$1.com', $result);

    // Email matches
    preg_match_all('#\\b([\\w\\._]*)[\\s(]*@[\\s)]*([\\w_\\-]{3,})\\s*\\.\\s*([a-z]{3})\\b#msi', $result, $matches);




    $usernames = $matches[1];
    $accounts = $matches[2];
    $suffixes = $matches[3];
    $emails = array();
    for ($i = 0; $i < count($usernames); $i++) {
        $emails[$i] = $usernames[$i] . '@' . $accounts[$i] . '.' . $suffixes[$i];
    }

    return $emails;
}

function scrape_phone($url)
{
    if (!is_string($url)) {
        return '';
    }
    $result = @file_get_contents($url);
    //$result = @curl_get_contents($url);

    if ($result === FALSE) {
        return 'Site Web Vide';
    }

    // Convert to lowercase
    $result = strtolower($result);



    //number matches

   // preg_match_all('/\+?([0-9]{2})-?([0-9]{3})-?([0-9]{6,7})/',$result,$phones);
    preg_match_all('/\+?([0-9]{2})-?([0-9]{3})-?([0-9]{6,7})/',$result,$phones);
   // preg_match_all('/^(\+\d{12}|\d{11}|\+\d{2}-\d{3}-\d{7})$/',$result,$phones);
      //  preg_match_all('/^\(?([2-9][0-8][0-9])\)?[-. ]?([2-9][0-9]{2})[-. ]?([0-9]{4})$/',$result,$phones);


     return $phones;
}
function clean($str)
{
    if (!is_string($str)) {
        return '';
    } else {
        return trim(strtolower($str));
    }
}

function curl_get_contents($url)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    // For https connections, we do not require SSL verification
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    $content = curl_exec($ch);
    //$error = curl_error($ch);
    //$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return $content;
}

