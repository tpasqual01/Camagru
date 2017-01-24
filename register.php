<?php
//session_start();
require_once('includes.php');
require_once('head.php');
require_once('header.php');

$TabForm = array();

$inscription = new CForm;
$TabForm[] = $inscription->Form('FormGet.class.php', 'Form', 'POST');
$TabForm[] = $inscription->InputLabel("Nom", "Votre Nom", "Nom");
$TabForm[] = $inscription->InputText("Votre Nom", "Nom");
$TabForm[] = $inscription->InputLabel("Prenom", "Votre Prénom", "Prenom");
$TabForm[] = $inscription->InputText("Votre Prénom", "Prenom");
$TabForm[] = $inscription->InputLabel("Mail", "Votre Mail", "Mail");
$TabForm[] = $inscription->InputMail("Votre Mail", "email");
$TabForm[] = $inscription->InputLabel("Password", "Password", "Password");
$TabForm[] = $inscription->InputPassword("Password", "Password");
$TabForm[] = $inscription->Submit("Envoyer");

include_once ('FormPrint.class.php');
$a = new CFormPrint('test');
$a->FormPrint('Inscriptions', $TabForm);

//var_dump($TabForm);


?>