<?php
$mysql_host = '';
$mysql_user = '';
$mysql_password = '';
$mysql_database = '';
$db=mysql_connect($mysql_host, $mysql_user, $mysql_password) or die('could not connect:'.mysql_error());
mysql_select_db($mysql_database) or die('could not select database');

/*echo "connected";
$result = mysql_query("select * from custom_url") or die('Query failed: ' . mysql_error());

echo "<table>\n";
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
    echo "\t<tr>\n";
    foreach ($line as $col_value) {
        echo "\t\t<td>$col_value</td>\n";
    }
    echo "\t</tr>\n";
}
echo "</table>\n";*/
?>
