<?php

include'lib/simple_html_dom.php';

$source_url = 'https://philanthropie.wordpress.com/repertoire-osbl-au-quebec/';

$html_source = file_get_html($source_url);

echo '<br>';
echo 'Title: '. $title = $html_source->find('h1', 0)->plaintext;
echo '<br>';
echo '<br>';
//echo 'Information: '.$informaiton = $html_source->find('p', 0)->plaintext;
echo '<br>';