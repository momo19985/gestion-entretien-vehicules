<?php
require_once('class/main.php');
$matricule = isset($_POST['matricule']) ? $_POST['matricule'] : '';
$vh->chercher_vehicule_matricule($matricule);
?>
