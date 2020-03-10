<?php


for($i=230;$i<240;$i++){

    $url="photos";



    $url =
        'https://s3.amazonaws.com/awq-production/portfolio-images/3964/large.jpg?1539101751';

    $img = $i.'logo1.png';


    file_put_contents($img, file_get_contents($url));




}

