<?php
//session_start();
require_once('includes.php');
require_once('head.php');
require_once('header.php');

/*session oui ou non
login ok ou non
inscription
logoff*/



if (!$_SESSION["user"]) {print('session vide<br />');include "login.php"; } //include (login//$b = new CLogin.class();
if ($_SESSION["valide"]=='ok') {

	print '<p>suite</p>';
	include ('main.php');
//include ('aside.php');
	
include ('footer.php');

}


	/*if $login == ok
	print"Kill session<br />";
	$_SESSION = array();
	session_destroy();*/




/*include ('header.php');
include ('main.php');
include ('aside.php');
include ('footer.php');
include_once('Form.class.php');

$_SESSION["user"]=$value;
$_SESSION["status"]=$value;
*/

?>
