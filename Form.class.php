<?php
Class CForm{
    public $newvar = 0;
    public static $verbose = False;
    //private tabpost = array();

    public function __construct()
    {
             //print('C').PHP_EOL;

        return;
    }

    public function __destruct()
    {
         if (self::$verbose == True)
            print('D').PHP_EOL;
       return;
    }

   public function __toString() //print ($Form);
   {
        return('toString');
   }

   public function __invoke() //print ($Form());
   {
        return('invoke');
   }

    static function doc()
    {
       return (file_get_contents('Form.doc.txt'));
    }

   public  function FormTitre($titre)
    {
        print('<table>');
        print ('<h2 class="FormTitre">'.$titre.'</h2>');
        return;
    }
   public  function Form($uri, $class, $method )
    {
        print('<FORM class="'.$class.'" action="'. $uri.'" method="'.$method.'">');
        // !$uri || !$method
        return;
    }

    public  function InputText($labelFor, $labelTitre, $id)
    {
        print('<tr>');
        print('<td><LABEL for="'.$labelFor.'">'. $labelTitre."</LABEL>"."</td>");
        //if (isset($_POST[$id])) $value = $_POST[$id]; else $value ="";
        print('<td><INPUT type="text" name="'. $id.'" id="'. $id.'" value="'.$value.'"></td>');
        print('</tr>');
        // !$id || !$labelFor
        return;
    }

        public  function InputMail($labelFor, $labelTitre, $id)
    {
        print('<tr>');
        print('<td><LABEL for="'.$labelFor.'">'. $labelTitre."</LABEL>"."</td>");
        //if (isset($_POST[$id])) $value = $_POST[$id]; else $value ="";
        print('<td><INPUT type="mail" name="'. $id.'" id="'. $id.'" value="'.$value.'">'."</td>");
        print('</tr>');
        // !$id || !$labelFor
        return;
    }

        public  function InputPassword($labelFor, $labelTitre, $id)
    {
        print('<tr>');
        print('<td><LABEL for="'.$labelFor.'">'. $labelTitre."</LABEL></td>");
        print('<td><INPUT type="password" name="'. $id.'" id="'. $id.'"></td>');
        print('</tr>');
        // !$id || !$labelFor
        return;
    }

        public  function Submit($Titre)
    {   
        print('<tr>');
        print('<td><INPUT type="submit" value="'. $Titre.'"></td>');
        print ('<td></td></tr></table>');

        // !$id || !$labelFor
        return;
    }


}
?>

<?php


$inscription = new CForm;
$inscription->FormTitre('Inscription');
$inscription->Form('FormGet.class.php', 'Form', 'POST');
$inscription->InputText("Nom", "Votre Nom", "Nom");
$inscription->InputText("Prenom", "Votre Prénom", "Prenom");
$inscription->InputMail("Mail", "Votre Mail", "Mail");
$inscription->InputPassword("Password", "Password", "Password");
$inscription->Submit("Envoyer");


?>