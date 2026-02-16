<?php
require_once("header.php");

$id = isset($_GET['id']) ? $_GET['id'] : 0;
$data = $ent->select_entretien($id);

if (empty($data)) {
    echo '<script>document.location.replace("liste_entretien.php");</script>';
    exit;
}

$row = $data[0];
?>

<div class="row animate-fadeInDown">
    <div class="col-lg-12">
        <h1 class="page-header">
            <i class="fa fa-edit"></i> <small>Modifier entretien #<?php echo $id; ?></small>
        </h1>
    </div>
</div>

<?php
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_entretien'])) {
    $id_v = $_POST['id_v'];
    $date_e = $_POST['date_entretien'];
    $type_e = $_POST['type_entretien'];
    $desc = $_POST['description'];
    $mo = $_POST['main_oeuvre'];
    $prest = $_POST['prestataire'];
    $statut_e = $_POST['statut'];

    if (empty($id_v)) $errors[] = "Vehicule obligatoire";
    if (empty($date_e)) $errors[] = "Date obligatoire";
    if (empty($type_e)) $errors[] = "Type obligatoire";

    if (empty($errors)) {
        $ent->modifier_entretien($id, $id_v, $date_e, $type_e, $desc, $mo, $prest, $statut_e);

        // Ajouter nouvelles pieces
        if (isset($_POST['nom_piece'])) {
            $noms = $_POST['nom_piece'];
            $prix = $_POST['prix_unitaire'];
            $qtes = $_POST['quantite'];
            $fourns = $_POST['fournisseur_piece'];

            for ($i = 0; $i < count($noms); $i++) {
                if (!empty($noms[$i]) && !empty($prix[$i])) {
                    $piece_r->ajouter_piece($id, $noms[$i], $prix[$i], $qtes[$i], $fourns[$i]);
                }
            }
        }

        $ent->recalculer_montant($id);

        echo '<script>document.location.replace("consulter_entretien.php?id=' . $id . '&message=update");</script>';
        exit;
    }
}
?>

<?php if (!empty($errors)): ?>
<div class="alert alert-danger animate-shake">
    <ul>
    <?php foreach ($errors as $e): ?>
        <li><?php echo $e; ?></li>
    <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>

<form class="form-horizontal animate-fadeInUp delay-1" role="form" method="post">
    <div class="row">
        <div class="col-sm-1"></div>
        <div class="col-sm-10">

            <div class="panel panel-primary">
                <div class="panel-heading"><i class="fa fa-info-circle"></i> Informations de l'entretien</div>
                <div class="panel-body">
                    <div class="form-group">
                        <label class="control-label col-sm-3">Vehicule *</label>
                        <div class="col-sm-9">
                            <select name="id_v" class="form-control" required>
                                <option value="">-- Choisir un vehicule --</option>
                                <?php $vh->liste_vehicule_options($row['id_v']); ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3">Date *</label>
                        <div class="col-sm-9">
                            <input type="date" name="date_entretien" class="form-control" value="<?php echo $row['date_entretien']; ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3">Type d'entretien *</label>
                        <div class="col-sm-9">
                            <select name="type_entretien" class="form-control" required>
                                <?php
                                $types = ['vidange'=>'Vidange','revision'=>'Revision','reparation'=>'Reparation','pneumatique'=>'Pneumatique','freinage'=>'Freinage','carrosserie'=>'Carrosserie','electricite'=>'Electricite','autre'=>'Autre'];
                                foreach ($types as $k => $v) {
                                    $sel = ($row['type_entretien'] == $k) ? 'selected' : '';
                                    echo "<option value='$k' $sel>$v</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3">Description</label>
                        <div class="col-sm-9">
                            <textarea name="description" class="form-control" rows="3"><?php echo htmlspecialchars($row['description']); ?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3">Prestataire / Garage</label>
                        <div class="col-sm-9">
                            <input type="text" name="prestataire" class="form-control" value="<?php echo htmlspecialchars($row['prestataire']); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3">Main d'oeuvre</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="number" name="main_oeuvre" class="form-control" value="<?php echo $row['main_oeuvre']; ?>" step="0.01" min="0">
                                <span class="input-group-addon">CFA</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3">Statut</label>
                        <div class="col-sm-9">
                            <select name="statut" class="form-control">
                                <option value="en_attente" <?php if($row['statut']=='en_attente') echo 'selected'; ?>>En attente</option>
                                <option value="en_cours" <?php if($row['statut']=='en_cours') echo 'selected'; ?>>En cours</option>
                                <option value="termine" <?php if($row['statut']=='termine') echo 'selected'; ?>>Termine</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pieces existantes -->
            <div class="panel panel-info">
                <div class="panel-heading"><i class="fa fa-cogs"></i> Pieces existantes</div>
                <div class="panel-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr><th>Piece</th><th>Prix unitaire</th><th>Qte</th><th>Montant</th><th>Fournisseur</th><th></th></tr>
                        </thead>
                        <tbody>
                            <?php $piece_r->afficher_pieces_entretien($id); ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Ajouter nouvelles pieces -->
            <div class="panel panel-success">
                <div class="panel-heading">
                    <i class="fa fa-plus"></i> Ajouter des pieces supplementaires
                    <button type="button" class="btn btn-sm btn-default pull-right" id="ajouter-piece" style="margin-top:-5px;">
                        <i class="fa fa-plus"></i> Ajouter une piece
                    </button>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-body">
                    <table class="table table-bordered" id="table-pieces">
                        <thead>
                            <tr><th>Nom</th><th>Prix unitaire</th><th>Quantite</th><th>Fournisseur</th><th>Montant</th><th></th></tr>
                        </thead>
                        <tbody id="pieces-body">
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="form-actions" style="margin-bottom:30px;">
                <a href="consulter_entretien.php?id=<?php echo $id; ?>" class="btn btn-default"><i class="fa fa-arrow-left"></i> Retour</a>
                <button type="submit" name="submit_entretien" class="btn btn-primary"><i class="fa fa-save"></i> Enregistrer</button>
            </div>

        </div>
        <div class="col-sm-1"></div>
    </div>
</form>

<script>
$('#ajouter-piece').click(function() {
    var row = '<tr class="piece-row animate-fadeInUp">' +
        '<td><input type="text" name="nom_piece[]" class="form-control" placeholder="Nom piece"></td>' +
        '<td><input type="number" name="prix_unitaire[]" class="form-control prix-unit" step="0.01" min="0" value="0"></td>' +
        '<td><input type="number" name="quantite[]" class="form-control qte" min="1" value="1"></td>' +
        '<td><input type="text" name="fournisseur_piece[]" class="form-control" placeholder="Fournisseur"></td>' +
        '<td class="montant-piece" style="vertical-align:middle; font-weight:bold;">0,00</td>' +
        '<td><button type="button" class="btn btn-danger btn-sm suppr-piece"><i class="fa fa-times"></i></button></td>' +
        '</tr>';
    $('#pieces-body').append(row);
});

$(document).on('click', '.suppr-piece', function() {
    $(this).closest('tr').fadeOut(300, function() { $(this).remove(); });
});

$(document).on('input', '.prix-unit, .qte', function() {
    var row = $(this).closest('tr');
    var prix = parseFloat(row.find('.prix-unit').val()) || 0;
    var qte = parseInt(row.find('.qte').val()) || 0;
    row.find('.montant-piece').text((prix * qte).toLocaleString('fr-FR', {minimumFractionDigits: 2}));
});
</script>

<?php require_once 'footer.php'; ?>
