<?php
require_once("header.php");
?>

<div class="row animate-fadeInDown">
    <div class="col-lg-12">
        <h1 class="page-header">
            <i class="fa fa-plus-circle"></i> <small>Ajouter vehicule</small>
        </h1>
    </div>
</div>

<form class="form-horizontal animate-fadeInUp delay-1" role="form" method="post">

<?php
$matE = $typeE = $ageE = $marqueE = $kmE = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($controle->vide($_POST["mat"])) $matE = " * champ obligatoire";
    if ($controle->vide($_POST["type"])) $typeE = " * champ obligatoire";
    if ($controle->vide($_POST["date_f"])) $ageE = " * champ obligatoire";
    if ($controle->vide($_POST["marque"])) $marqueE = " * champ obligatoire";

    if ($controle->no_vide($_POST["mat"], $_POST["type"], $_POST["date_f"], $_POST["marque"])) {
        $mat = htmlentities($_POST['mat']);
        $type = $_POST['type'];
        $date_f = $_POST['date_f'];
        $marque = $_POST['marque'];
        $km = isset($_POST['kilometrage']) ? $_POST['kilometrage'] : 0;

        $ajout = $vh->ajouter_vehicule($mat, $type, $date_f, $marque, $km);

        if ($ajout) {
            echo '<script>document.location.replace("liste_vehicule.php?message=add");</script>';
            exit;
        }
    }
}
?>

    <div class="row">
        <div class="col-sm-2"></div>
        <div class="col-sm-8">
            <div class="form-group">
                <label class="control-label">Matricule :</label>
                <input type="text" name="mat" class="form-control" placeholder="Ex: TN-1234-A">
                <p class="help-block"><?php echo $matE ?></p>
            </div>
            <div class="form-group">
                <label class="control-label">Annee de fabrication :</label>
                <input type="number" name="date_f" class="form-control" placeholder="Ex: 2020" min="1990" max="2030">
                <p class="help-block"><?php echo $ageE ?></p>
            </div>
            <div class="form-group">
                <label class="control-label">Type :</label>
                <select name="type" class="form-control">
                    <option value="voiture">Voiture</option>
                    <option value="camion">Camion</option>
                </select>
            </div>
            <div class="form-group">
                <label class="control-label">Marque :</label>
                <input type="text" name="marque" class="form-control" placeholder="Ex: Toyota Corolla">
                <p class="help-block"><?php echo $marqueE ?></p>
            </div>
            <div class="form-group">
                <label class="control-label">Kilometrage :</label>
                <div class="input-group">
                    <input type="number" name="kilometrage" class="form-control" placeholder="Ex: 45000" min="0" value="0">
                    <span class="input-group-addon">km</span>
                </div>
            </div>
            <br>
            <div class="form-actions">
                <a href="liste_vehicule.php" class="btn btn-default"><i class="fa fa-arrow-left"></i> Annuler</a>
                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Ajouter</button>
            </div>
        </div>
        <div class="col-sm-2"></div>
    </div>
</form>

<?php require_once 'footer.php'; ?>
