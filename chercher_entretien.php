<?php
require_once('class/main.php');

$date_debut = isset($_POST['date_debut']) ? $_POST['date_debut'] : '';
$date_fin = isset($_POST['date_fin']) ? $_POST['date_fin'] : '';
$type = isset($_POST['type_entretien']) ? $_POST['type_entretien'] : '';
$vehicule = isset($_POST['vehicule']) ? $_POST['vehicule'] : '';
$statut = isset($_POST['statut']) ? $_POST['statut'] : '';

$ent->chercher_entretien($date_debut, $date_fin, $type, $vehicule, $statut);
?>
