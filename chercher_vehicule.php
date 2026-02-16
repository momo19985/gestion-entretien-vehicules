<?php
require_once('class/main.php');
$type = isset($_POST['type']) ? $_POST['type'] : 'tous';
$vh->chercher_vehicule($type);
?>
