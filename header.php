<?php
if ($_SESSION["valide"]=='ok') {
	print '<div id="header"><h1>Camagru</h1>'.$_SESSION["user"].' logoff</div>';
}
else
	print '<div id="header"><h1>Camagru</h1>'.'</div>';
?>