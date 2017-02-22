<?php
if(!isset($_SESSION)) {session_start();}
Class CSession // ***** Class 
{
    public static $verbose = False;
    private $servername = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname = "camagru";
    private $tbl = "tbl_camagru";
    private $tbl_photos = "photos";
    private $tbl_photos_like = "photos_like";

    private $servername1 = "db665127288.db.1and1.com";
    private $username1 = "dbo665127288";
    private $password1 = "42piscinedltp";
    private $dbname1 = "db665127288";

    //private $conn =''; pas necessaire

// **********  gestion de l'utilisateur ***********
    public function __construct() // initialise les info de la base de donnees
    {
        //print '__construct';
        // a l'initialisation de la class on genere la variable de conenxion a la base
        $Domaine_Serveur = str_replace ( 'www.' , '', $_SERVER['HTTP_HOST']);
        if ($Domaine_Serveur == 'camagru.photeam.com')
            $this->conn = new PDO('mysql:host='.$this->servername1.';dbname='.$this->dbname1, $this->username1, $this->password1);
        else
            $this->conn = new PDO('mysql:host='.$this->servername.';dbname='.$this->dbname, $this->username, $this->password);
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return;
    }

    public function host() // précise le nom du serveur pour l'envoi des liens par mail
    {
        $Domaine_Serveur = str_replace ( 'www.' , '', $_SERVER['HTTP_HOST']);
        if ($Domaine_Serveur == 'camagru.photeam.com') 
            $host = 'http://www.camagru.photeam.com';
        else
            $host = $this->servername.':8080/camagru/';
        return ($host);
    }

    public function user_login() // check si login mot de passe sont valides
    {
        $email = strtolower(strip_tags($_POST['email']));
        $Password = $this->user_pass_hash($_POST['Password']);
        $retour = 'user not exit';
        try {
            $rq = $this->secure("SELECT Id, Nom, Prenom, email, Password, Confirm, Keyuser FROM $this->tbl WHERE email = '$email'");
            $requete = $this->conn->prepare($rq); //
            $requete->execute();
            while($lignes = $requete->fetch(PDO::FETCH_OBJ)){
                if ($lignes->email == $email && $lignes->Password == $Password && $lignes->Confirm == 1)
                {
                    $this->set_session($lignes->email, $lignes->Nom, $lignes->Prenom, $lignes->Confirm, $lignes->Id );
                    $retour = 'user_login';
                }
                if ($lignes->email == $email && $lignes->Password == $Password && $lignes->Confirm == 0)
                    $retour = 'user not confirmed';

                if ($lignes->email == $email && $lignes->Password != $Password)
                    $retour = 'user password bad';

                if ($lignes->email != $email )
                    $retour = 'user not exit';
            }
        }
        catch(PDOException $e)
        { echo "Error Database : " . $e->getMessage(); }
        //$conn = null;
        $this->write_log('Login : '.$email.' '.$retour);
        return ($retour);
    }

        public function user_info($email_key, $origin) // lit les informations de l'user
        {
        // tester si origin = email ou key
        //print $email_key.' ' . $origin;
            if ($origin == 'key' ) {$email = $this->userkey_exist($email_key);}
            if ($origin == 'email' ) {$email = $email_key;}

        if ($this->user_exist($email) == 'no') // le mail n'est pas bon
        { 
            $tbl['email'] = 'no';
            return ($tbl);
        } 
        try {
            $rq = $this->secure("SELECT Id, Nom, Prenom, email, Password, Confirm, Keyuser, Questionsecrete, Reponsesecrete FROM $this->tbl WHERE email = '$email'");
            $requete = $this->conn->prepare($rq); //
            $requete->execute();
            while($lignes = $requete->fetch(PDO::FETCH_OBJ)){
                $tbl['Id'] = $lignes->Id;
                $tbl['email'] = $lignes->email;
                $tbl['Nom'] = $lignes->Nom;
                $tbl['Prenom'] = $lignes->Prenom;
                $tbl['Password'] = $lignes->Password;
                $tbl['Confirm'] = $lignes->Confirm;
                $tbl['Keyuser'] = $lignes->Keyuser;
                $tbl['Questionsecrete'] = $lignes->Questionsecrete;
                $tbl['Reponsesecrete'] = $lignes->Reponsesecrete;
            }
        }
        catch(PDOException $e)
        { echo "Error Database : " . $e->getMessage(); }
        //$conn = null;
        return($tbl);
    }

    public function maj_key($email) // met a jour la key de reinit
    {
        $generatedKey = uniqid();
        try {
            $rq = $this->secure("UPDATE $this->tbl SET Keyuser = '$generatedKey' WHERE email = '$email'");
            $requete = $this->conn->prepare($rq); 
            $requete->execute();
            if ($requete)
                $retour = $generatedKey;
            else
            {
                $retour = 'maj key err';
                $CPrint = new CPrint();
                $CPrint->content('Erreur maj key', 'msg_err');
                exit;
            }
        }

        catch(PDOException $e)
        { echo "Error Database : " . $e->getMessage(); $exist = 'Erreur'; return($exist);}
        //$conn = null;
        return($retour);
    }

    public function user_exist($email) // check si user existe
    {
        if (!$email) $email = strtolower(strip_tags($_POST['email']));
       ///   securisation : https://openclassrooms.com/courses/securite-php-securiser-les-flux-de-donnees
        try {
            //$requete = $this->conn->prepare("SELECT email FROM $this->tbl WHERE email = :email"); 
            //$requete->bindValue(':email', $email, PDO::PARAM_STR);
            $rq = $this->secure("SELECT email FROM $this->tbl WHERE email = '$email'");
            $requete = $this->conn->prepare($rq);
            $requete->execute();
            $exist = 'no';
            while($lignes=$requete->fetch(PDO::FETCH_OBJ))
                if ($lignes->email == strtolower($email) ) { $exist = 'yes'; }
        }
        catch(PDOException $e)
        { echo "Error Database : " . $e->getMessage(); $exist = 'Erreur'; return($exist);}
        //$conn = null;
        return($exist);
    }

    public function userkey_exist($key) // check si la key existe pour traitement
    {
        if (!$key) {print 'erreur in userkey_exist'; exit; }
       ///   securisation : https://openclassrooms.com/courses/securite-php-securiser-les-flux-de-donnees
        try {
            $rq = $this->secure("SELECT email, Keyuser FROM $this->tbl WHERE Keyuser = '$key'");
            $requete = $this->conn->prepare($rq);
            $requete->execute();
            $retour = 'no';
            while($lignes=$requete->fetch(PDO::FETCH_OBJ))
                if ($lignes->Keyuser == $key ) { $retour = $lignes->email;}
        }
        catch(PDOException $e)
        { echo "Error Database : " . $e->getMessage(); $exist = 'Erreur'; return($exist);}
        return($retour);
    }

        public function user_add() // ajoute un user 
        {
            $email = strtolower(strip_tags($_POST['email']));
            $Nom = strip_tags($_POST['Nom']);
            $Prenom = strip_tags($_POST['Prenom']);
            $Password = $this->user_pass_hash($_POST['Password']);
            $Confirm = 0;
            $CInscription = new CInscription();
            $Keyuser = $CInscription->set_key_validation();
            $Cpt_reinit = 5;

        // contre les injection sql : https://openclassrooms.com/courses/pdo-comprendre-et-corriger-les-erreurs-les-plus-frequentes

            try {
            $rq = $this->secure("INSERT INTO $this->tbl (Nom, Prenom, email, Password, Confirm, Keyuser, Cpt_reinit, Questionsecrete, Reponsesecrete, Info) VALUES ('$Nom', '$Prenom', '$email', '$Password', '$Confirm', '$Keyuser', '$Cpt_reinit', '$_POST[Question]', '$_POST[Reponse]', 'Info')"); // ne pas mettre les '' dans $_POST['Nom']
            $requete = $this->conn->prepare($rq);
            $requete->execute();

            // envoi validation par mail uniquement si base maj
            //print ($email.' '.$lignes->Prenom.' '.$lignes->Nom.' '.$lignes->Keyuser);
            $CInscription->send_validation($email, $Prenom, $Nom, $Keyuser);
        }
        catch(PDOException $e)
        { echo "Error Database : " . $e->getMessage(); print 'Erreur user_add'; exit;}
        $this->write_log('user_add : '.$email.' '. $Nom);
        return('user_add');
    }

    function user_pass_modify($email, $pass) // modifie le password de l'user
    {
        $hashkey = $this->user_pass_hash($pass);
        try {
            $rq = $this->secure("UPDATE $this->tbl SET Password = '$hashkey' WHERE email = '$email'");
            
            $requete = $this->conn->prepare($rq); 
            $requete->execute();
            if ($requete)
                $retour = 'ok';
            else
                $retour = 'Erreur modification password';
        }

        catch(PDOException $e)
        { echo "Error Database : " . $e->getMessage(); $exist = 'Erreur'; return($exist);}
        //$conn = null;
        $this->write_log('user_pass_modify : '.$email.' '. $pass);
        return($retour);
    }

    public function user_list($class1, $class2) // reserved superuser
    {
        try {
            //$conn = new PDO('mysql:host='.$this->servername.';dbname='.$this->dbname, $this->username, $this->password);
            //$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $requete = $this->conn->prepare("SELECT Id, Nom, Prenom, email, Confirm, Keyuser, Cpt_reinit, Questionsecrete, Reponsesecrete, Info FROM ".$this->tbl); 
            $requete->execute();
            print '<table>';
            print "<tr><td><p class=\"$class1\">Id</p></td>";
            print "<td><p class=\"$class1\">email</p></td>";
            print "<td><p class=\"$class1\">Nom</p></td>";
            print "<td><p class=\"$class1\">Prenom</p></td>";
            print "<td><p class=\"$class1\">Confirm</p></td>";
            print "<td><p class=\"$class1\">Keyuser</p></td>";
            print "<td><p class=\"$class1\">Cpt_reinit</p></td>";
            print "<td><p class=\"$class1\">Question secrete</p></td>";
            print "<td><p class=\"$class1\">Reponse secrete</p></td>";
            print "<td><p class=\"$class1\">Info</p></td>";
            print '</tr>';
            while($lignes=$requete->fetch(PDO::FETCH_OBJ))
            {
                print '<tr><td><p class="'.$class1.'">'.$lignes->Id.'</p></td>';
                print '<td><p class="'.$class1.'">'.$lignes->email.'</p></td>';
                print '<td><p class="'.$class1.'">'.$lignes->Nom.'</p></td>';
                print '<td><p class="'.$class1.'">'.$lignes->Prenom.'</p></td>';
                print '<td><p class="'.$class1.'">'.$lignes->Confirm.'</p></td>';
                print '<td><p class="'.$class1.'">'.$lignes->Keyuser.'</p></td>';
                print '<td><p class="'.$class1.'">'.$lignes->Cpt_reinit.'</p></td>';
                print '<td><p class="'.$class1.'">'.$lignes->Questionsecrete.'</p></td>';
                print '<td><p class="'.$class1.'">'.$lignes->Reponsesecrete.'</p></td>';
                print '<td><p class="'.$class1.'">'.$lignes->Info.'</p></td>';
                print '</tr>';
            }
            print '</table>';
        }
        catch(PDOException $e)
        { echo "Error Database : " . $e->getMessage(); $exist = 'Erreur';}
        //$conn = null;
        return;
    }

    public function set_session($email, $nom, $prenom, $confirm, $Id) // set les variables de session
    {
        $_SESSION['Id'] = $Id;
        $_SESSION['email'] = $email;
        $_SESSION['Nom'] = $nom;
        $_SESSION['Prenom'] = $prenom;
        $_SESSION['Confirme'] = $confirm;
        $_SESSION['valide'] = 'ok';
        if ($_SESSION["email"] == 'dominique@lievre.net' or $_SESSION["email"] == 'te42pe@gmail.com') $_SESSION['Superuser'] = 'yes';
        return('ok');
    }

    public function get_profile() // récupere les infos de l'utilisateur
    {
        if ($_SESSION['valide'] == 'ok') {
            $tab = array();
            foreach ($_SESSION as $nom => $value)
                $tab[$nom] = $value;
            return($tab);
        }
        else
            return('erreur');
    }

// **********  divers ***********
    function user_pass_hash($pass) // codage whirlpool du password
    { 
        return (hash('whirlpool',$pass));
    }

    function secure($var) // supprime les tag html
    {
        $var = strip_tags($var);
        return($var); // pour l'instant on ne fait que le strip_tags car protege par 'value'
        //return (mysql_real_escape_string($var)); // ne fonctionne pas
        //return($var);
    }

    function ismajuscule($var)// verifie si presence majuscule pour les mauvais password
    {
        $nb = strlen($var);
        $retour = 'minuscule';
        for($i = 0; $i < $nb; $i++) {
            if (ctype_upper (substr($var, $i, 1))) 
                $retour = 'majuscule';
        }
        return($retour);
    }

    private function quotesep($val)// securise les variables dans sql, avec separateur
    {
        return("'".$val."', ");
    }

    private function quote($val)// securise le passage des variables dans la requete sql, sans separateur
    {
        return("'".$val."'");
    }

    public function kill_session() // on tue la session et les variables
    {
        $_SESSION = array(); session_destroy(); 
        return('ok');
    }

    public function write_log($err_txt) // ecriture dans le fichier Log
    {
        $fp = fopen('superuser/log.txt','a+'); // ouvrir le fichier ou le créer
        fseek($fp,SEEK_END); // poser le point de lecture à la fin du fichier
        $err = date("F j, Y, g:i a").' | '.$err_txt."\r\n"; // ajouter un retour à la ligne au fichier
        fputs($fp,$err); // ecrire ce texte
        fclose($fp); //fermer le fichier
        return 'write_log ok';
    }

        public function write_doc($tbl) // cree le fichier superuser/doc.txt
        {
        $fp = fopen('superuser/doc.txt','w+'); // ouvrir le fichier ou le créer
        //fseek($fp,SEEK_END); // poser le point de lecture à la fin du fichier
        foreach ($tbl as $line => $code) {
            fputs($fp, $line.'   '.$code); // ecrire ce texte
        }
        
        fclose($fp); //fermer le fichier
        return 'write_log ok';
    }

    public function read_log($file) // lecture d'un fichier
    {
        $fp = fopen($file,'r') or die("Unable to open file!".$file); ; // ouvrir le fichier 
        print '<p class="content_left">';
        //echo fread($fp,filesize("log.txt"));
        //$CView = new $CPrint();
        while(!feof($fp))
        {
            echo fgets($fp) . "<br>";
        }
        print '</p>';
        fclose($fp); //fermer le fichier
        return 'read_log '.$file.' ok';
    }

//*********   gestion des images dans la base *********

    function image_add($id_photo, $Id)// ajoute une image dans la base
    {
        //$this->write_log('var '.$id_photo. ' '. $Id);
        try
        {
            $rq = $this->secure("INSERT INTO $this->tbl_photos (Id_owner, Name_img) VALUES ('$Id', '$id_photo')"); // ne pas mettre 
            $requete = $this->conn->prepare($rq);
            $requete->execute();
        }
        catch(PDOException $e)
        { 
            return "image_add Error Database : " . $e->getMessage();
        }

        return('image_add'); 
    }

    function image_getid($name_photo)// ajoute une image dans la base
    {
        try
        {
            $rq = $this->secure("SELECT Id  FROM $this->tbl_photos WHERE Name_img = $name_photo"); 
            $requete = $this->conn->prepare($rq);
            $requete->execute();

            $lignes = $requete->fetch(PDO::FETCH_OBJ);
                $retour = $lignes->Id;
            // qwertyecho '******'.$retour.'***';            

        }
        catch(PDOException $e)
        { 
            return "image_getid Error Database : " . $e->getMessage();
        }

        return($retour); 
    }


     function comment_add($name_photo, $IdUser_comment, $comment)// ajoute un commentaire a une image dans la base
    {
        // a faire controle de ne pas se commenter sois meme
        $Id_tblphotos = intval ($this->image_getid($name_photo)); // id de la photo
        // check si user a deja pour cette image mis un comment ou like 
        // et donc on fera soit insert soit update
        $retour = $this->is_user_cmtlike($name_photo, $IdUser_comment);
        $this->write_log($retour.' retour is ');
        if ($retour == 'interdit') {return ($retour);}
        if ($retour == 'no')
        {       
            // plus controle de ne pas se commenter sois meme
            try
            {
                $rq = $this->secure("INSERT INTO $this->tbl_photos_like (Id_tblphotos, Id_img, Id_user_comment, comment) VALUES ('$Id_tblphotos', '$name_photo', '$IdUser_comment', '$comment')"); 
                $requete = $this->conn->prepare($rq);
                $requete->execute();
                $this->write_log($rq.' create '); //qwerty
            }
            catch(PDOException $e)
            { 
                return "comment_add Error Database : " . $e->getMessage();
            }

            return('comment_add'); 
        }
        if ($retour == 'yes')
        {
            // on fait update du comment
            try
            {
                $rq = $this->secure("UPDATE $this->tbl_photos_like SET Comment = '$comment' WHERE Id_img = '$name_photo' AND Id_user_comment = '$IdUser_comment'"); 
                $this->write_log($rq.' update ');// qwerty
                $requete = $this->conn->prepare($rq);
                $requete->execute();
                
            }
            catch(PDOException $e)
            { 
                return "comment_add Error Database : " . $e->getMessage();
            }

            return('comment_add'); 
        }
    }   

    function is_user_cmtlike($name_photo, $IdUser_comment)// check si l'user a deja commente ou like
    {
        try // test si owner = user_comment : interdit
        {
            $rq = $this->secure("SELECT Id_owner FROM $this->tbl_photos WHERE Name_img = $name_photo"); 
            $requete = $this->conn->prepare($rq);
            $requete->execute();
            $result = $requete->fetch(PDO::FETCH_OBJ);
            $owner = $result->Id_owner;
            $this->write_log($rq.'is_user_cmtlike '.$owner .' vs '.$IdUser_comment );
            if ($owner == $IdUser_comment) return ('interdit');
           
            ///if ($ligne->Id_owner) return ('interdit');
        }
        catch(PDOException $e)
        { 
            return "is_user_cmtlike Error Database : " . $e->getMessage(); exit;
        }

        try
        {
            $rq = $this->secure("SELECT Id_img, Id_user_comment FROM $this->tbl_photos_like WHERE Id_img = $name_photo AND Id_user_comment = $IdUser_comment"); 
            $requete = $this->conn->prepare($rq);
            $requete->execute();
            $ligne = $requete->fetch(PDO::FETCH_OBJ);
            if ($ligne->Id_img) return ('yes'); // deja like
        }
        catch(PDOException $e)
        { 
            return "is_user_cmtlike Error Database : " . $e->getMessage(); exit;
        }
        return ('no');
    }
    
        public function images_galerie() // lit toutes les images pour la galerie
        {

            try {
            $rq = $this->secure("SELECT Id, Id_owner, Name_img FROM $this->tbl_photos ORDER BY Date DESC");  //ORDER BY 'Date' DESC  , 'Date'
            $requete = $this->conn->prepare($rq); //
            $requete->execute();
            $nb = 0;
            while($lignes = $requete->fetch(PDO::FETCH_OBJ)){
                $tbl[$nb]['Id'] = $lignes->Id;
                $tbl[$nb]['Id_owner'] = $lignes->Id_owner;
                $tbl[$nb]['Name_img'] = $lignes->Name_img;
                $nb++;
            }
        }
        catch(PDOException $e)
        { echo "Error Database : " . $e->getMessage(); }
        //$conn = null;
        return($tbl);
    }

    public function image_comment($id_img) // lit les commentaires d'une image
    {
        try {
            $tab_users = $this->tbl_users_name();
            $rq = $this->secure("SELECT Id, Comment, Id_user_comment FROM $this->tbl_photos_like WHERE Id_img = '$id_img'");  //ORDER BY 'Date' DESC  , 'Date'
            $this->write_log($rq);
            $requete = $this->conn->prepare($rq); //
            $requete->execute();
            while($lignes = $requete->fetch(PDO::FETCH_OBJ)){
                $tbl[$tab_users[$lignes->Id_user_comment]] = $lignes->Comment;
            }
        }
        catch(PDOException $e)
        { echo "image_comment Error Database : " . $e->getMessage(); }
        return($tbl);
    }

        public function tbl_users_name() // lit les commentaires d'une image
    {
        try {
            $rq = $this->secure("SELECT Id, Prenom FROM $this->tbl");  //ORDER BY 'Date' DESC  , 'Date'
            $this->write_log($rq);
            $requete = $this->conn->prepare($rq); //
            $requete->execute();
            $tab_users = array();
            while($lignes = $requete->fetch(PDO::FETCH_OBJ)){
                $tab_users[$lignes->Id] = $lignes->Prenom;
            }
        }
        catch(PDOException $e)
        { echo "tbl_users_name Error Database : " . $e->getMessage(); }
        return($tab_users);
    }

    public function image_like_count($id_img) // compte le nb de like des comment
    {
        try {
            $rq = $this->secure("SELECT COUNT(*) AS nb FROM $this->tbl_photos_like WHERE Id_img = '$id_img' AND Grave_bien = 1");  
            //$this->write_log($rq);
            $cpt = 0;
            $requete = $this->conn->prepare($rq); //
            $requete->execute();
            $cpt = $requete->fetch(PDO::FETCH_OBJ);
            $cpt = $cpt->nb;
        }
        catch(PDOException $e)
        { echo "image_like Error Database : " . $e->getMessage(); }
        return($cpt[0]);
    }

        public function image_nb_liked($id_img) // nb de like d'une image
        {
            try {
                $rq = $this->secure("SELECT Nb_liked FROM $this->tbl_photos WHERE Name_img = '$id_img'");  
                $cpt = 0;
            $requete = $this->conn->prepare($rq); //
            $requete->execute();
            $cpt = $requete->fetch(PDO::FETCH_OBJ);
            $cpt = $cpt->Nb_liked;
        }
        catch(PDOException $e)
        { echo "image_nb_liked Error Database : " . $e->getMessage(); }
        return($cpt);
    }

    //***** structure *****

    public function __destruct() // on efface la connexion a la base
    {
        //print ('<p>destruct</p>');
        $this->conn = null; 
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
    $info = '';
    return (file_get_contents('superuser/documentation.txt'));
}


}


?>
