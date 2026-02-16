<?php 

 require_once("DataBase.php");
 require_once("user.php");
 require_once("ctrl.php");
 require_once("vehicule.php");
 require_once("entretien.php");
 require_once("piece.php");
 require_once("rapport.php");
 require_once("crypt.php");

 $user = new user();
 $controle = new ctrl();
 $db = new Database(); 
 $vh = new vehicule();
 $ent = new entretien();
 $piece_r = new piece();
 $rapport = new rapport();
 $crypt = new Crypt();

?>
