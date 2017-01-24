<?php
//session_start();
//include ('includes.php');

$TabForm = array();

$login = new CForm;
$TabForm[] = $login->Form('FormGet.class.php', 'Form', 'POST');
$TabForm[] = $login->InputLabel("Mail", "Votre Mail", "Mail");
$TabForm[] = $login->InputMail("Votre Mail", "email");
$TabForm[] = $login->InputLabel("Password", "Password", "Password");
$TabForm[] = $login->InputPassword("Password", "Password");
$TabForm[] = $login->Submit("Envoyer");


$a = new CFormPrint('test');
$a->FormPrint('Login', $TabForm);

print('<p><a href="register.php" target="_self">Register</a></>');
//var_dump($TabForm);


?>