<?php
Class CFormPrint{
    public $newvar = 0;
    public static $verbose = False;

    public function __construct()
    {
        return;
    }

    public function __destruct()
    {
         if (self::$verbose == True)
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

   public  function FormPrint($Titre, $Tab)
    {
    	print ('<h2 class="FormTitre">'.$Titre.'</h2>');
    	print($Tab[0]);
    	print('<p>');
        print('<table>');
        for ($key = 1; $key <=count($Tab);$key = $key + 2)
			echo '<tr><td>'.'<p>'.$Tab[$key].'</p>'.'</td><td>'.$Tab[$key + 1].'</td></tr>';
        print('</table>');
        print('</form>');
        print('</p>');
        return;
    }
}
?>