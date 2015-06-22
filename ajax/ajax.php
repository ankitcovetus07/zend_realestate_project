<?php
$e=$_REQUEST['email'];
$host 		= "localhost";
$dbname 	= "laxit_sacondo";
$username 	= "laxit_sscondo";
$password 	= "(zm]1R-F]J}g";
$conn=mysql_connect($host,$username,$password);
mysql_select_db($dbname,$conn);

$r=mysql_query("select * from users where email_address='".$e."'");

if(mysql_num_rows($r)>0)
{
	echo 0;
}
else
{
	echo 1;
}
?>