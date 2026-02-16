<?php
require_once("header.php");

$id = $_GET["id"];
$liste = $vh->select_vehicule($id);

if (empty($liste)) {
    echo '<script>document.location.replace("liste_vehicule.php");</script>';
    exit;
}

foreach ($liste as $row) {
    $date_f = $row["date_f"];
    $mat = $row["matricule"];
    $type = $row["type"];
    $marque = $row["marque"];
    $km = $row["kilometrage"];
}
?>

<div class="row animate-fadeInDown">
    <div class="col-lg-12">
        <h1 class="page-header">
            <i class="fa fa-edit"></i> <small>Modifier vehicule</small>
        </h1>
    </div>
</div>

<form class="form-horizontal animate-fadeInUp delay-1" role="form" method="post" action="<?php echo $_SERVER['PHP_SELF'] . "?id=" . $id; ?>">
    <input type="hidden" name="id" value="<?php echo $id; ?>">

<?php
$matE = $typeE = $ageE = $marqueE = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($controle->vide($_POST["mat"])) $matE = " * champ obligatoire";
    if ($controle->vide($_POST["type"])) $typeE = " * champ obligatoire";
    if ($controle->vide($_POST["date_f"])) $ageE = " * champ obligatoire";
    if ($controle->vide($_POST["marque"])) $marqueE = " * champ obligatoire";

    if ($controle->no_vide($_POST["mat"], $_POST["type"], $_POST["date_f"])) {
        $mat = $_POST['mat'];
        $type = $_POST['type'];
        $date_f = $_POST['date_f'];
        $marque = $_POST['marque'];
        $km = isset($_POST['kilometrage']) ? $_POST['kilometrage'] : 0;
        $id = $_POST['id'];

        $ajout = $vh->modifier_vehicule($id, $mat, $type, $date_f, $marque, $km);

        if ($ajout) {
            echo '<script>document.location.replace("consulter_v.php?id=' . $id . '&message=update");</script>';
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
                <input type="text" name="mat" value="<?php echo htmlspecialchars($mat); ?>" class="form-control">
                <p class="help-block"><?php echo $matE ?></p>
            </div>
            <div class="form-group">
                <label class="control-label">Annee de fabrication :</label>
                <input type="number" name="date_f" value="<?php echo $date_f; ?>" class="form-control" min="1990" max="2030">
                <p class="help-block"><?php echo $ageE ?></p>
            </div>
            <div class="form-group">
                <label class="control-label">Type :</label>
                <select name="type" class="form-control">
                    <option value="voiture" <?php if($type=='voiture') echo 'selected'; ?>>Voiture</option>
                    <option value="camion" <?php if($type=='camion') echo 'selected'; ?>>Camion</option>
                </select>
            </div>
            <div class="form-group">
                <label class="control-label">Marque :</label>
                <input type="text" name="marque" value="<?php echo htmlspecialchars($marque); ?>" class="form-control">
                <p class="help-block"><?php echo $marqueE ?></p>
            </div>
            <div class="form-group">
                <label class="control-label">Kilometrage :</label>
                <div class="input-group">
                    <input type="number" name="kilometrage" value="<?php echo $km; ?>" class="form-control" min="0">
                    <span class="input-group-addon">km</span>
                </div>
            </div>
            <br>
            <div class="form-actions">
                <a href="liste_vehicule.php" class="btn btn-default"><i class="fa fa-arrow-left"></i> Annuler</a>
                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Modifier</button>
            </div>
        </div>
        <div class="col-sm-2"></div>
    </div>
</form>

<?php require_once 'footer.php'; ?>
