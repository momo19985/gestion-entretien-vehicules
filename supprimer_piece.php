<?php 
require_once('class/main.php');
$id = $_GET['id'];
$id_entretien = isset($_GET['entretien']) ? $_GET['entretien'] : 0;

$piece_r->supprimer_piece($id);

// Recalculer le montant de l'entretien
if ($id_entretien > 0) {
    $ent->recalculer_montant($id_entretien);
    $user->location("consulter_entretien.php?id=$id_entretien&message=delete");
} else {
    $user->location("liste_entretien.php");
}
?>
