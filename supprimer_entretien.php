<?php 
require_once('class/main.php');
$id = $_GET['id'];
$ent->supprimer_entretien($id);
$user->location("liste_entretien.php?message=delete");
?>
