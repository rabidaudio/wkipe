<?php
#recently accessed links
include('functions.inc.php');

$top_articles = array();
$top_articles[] = total_redirects();
$top_articles[] = top_normal();
$top_articles[] = top_custom();
echo stripslashes(json_encode($top_articles));
