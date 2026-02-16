<?php
require_once("header.php");
?>

<div class="row animate-fadeInDown">
    <div class="col-lg-12">
        <h1 class="page-header">
            <i class="fa fa-plus-circle"></i> <small>Nouvel entretien</small>
        </h1>
    </div>
</div>

<?php
$errors = [];
$id_v = $date_e = $type_e = $desc = $mo = $prest = $statut_e = "";

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
        $ajout = $ent->ajouter_entretien($id_v, $date_e, $type_e, $desc, $mo, $prest, $statut_e);
        if ($ajout) {
            $last_id = $ent->last_entretien_id();

            // Ajouter les pieces de rechange
            if (isset($_POST['nom_piece'])) {
                $noms = $_POST['nom_piece'];
                $prix = $_POST['prix_unitaire'];
                $qtes = $_POST['quantite'];
                $fourns = $_POST['fournisseur_piece'];

                for ($i = 0; $i < count($noms); $i++) {
                    if (!empty($noms[$i]) && !empty($prix[$i])) {
                        $piece_r->ajouter_piece($last_id, $noms[$i], $prix[$i], $qtes[$i], $fourns[$i]);
                    }
                }
            }

            // Recalculer le montant total
            $ent->recalculer_montant($last_id);

            echo '<script>document.location.replace("liste_entretien.php?message=add");</script>';
            exit;
        }
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
                                <?php $vh->liste_vehicule_options($id_v); ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3">Date *</label>
                        <div class="col-sm-9">
                            <input type="date" name="date_entretien" class="form-control" value="<?php echo $date_e ?: date('Y-m-d'); ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3">Type d'entretien *</label>
                        <div class="col-sm-9">
                            <select name="type_entretien" class="form-control" required>
                                <option value="">-- Choisir --</option>
                                <option value="vidange" <?php if($type_e=='vidange') echo 'selected'; ?>>Vidange</option>
                                <option value="revision" <?php if($type_e=='revision') echo 'selected'; ?>>Revision</option>
                                <option value="reparation" <?php if($type_e=='reparation') echo 'selected'; ?>>Reparation</option>
                                <option value="pneumatique" <?php if($type_e=='pneumatique') echo 'selected'; ?>>Pneumatique</option>
                                <option value="freinage" <?php if($type_e=='freinage') echo 'selected'; ?>>Freinage</option>
                                <option value="carrosserie" <?php if($type_e=='carrosserie') echo 'selected'; ?>>Carrosserie</option>
                                <option value="electricite" <?php if($type_e=='electricite') echo 'selected'; ?>>Electricite</option>
                                <option value="autre" <?php if($type_e=='autre') echo 'selected'; ?>>Autre</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3">Description</label>
                        <div class="col-sm-9">
                            <textarea name="description" class="form-control" rows="3" placeholder="Details de l'entretien..."><?php echo htmlspecialchars($desc); ?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3">Prestataire / Garage</label>
                        <div class="col-sm-9">
                            <input type="text" name="prestataire" class="form-control" value="<?php echo htmlspecialchars($prest); ?>" placeholder="Nom du garage ou prestataire">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3">Main d'oeuvre</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="number" name="main_oeuvre" class="form-control" value="<?php echo $mo ?: '0'; ?>" step="0.01" min="0">
                                <span class="input-group-addon">CFA</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3">Statut</label>
                        <div class="col-sm-9">
                            <select name="statut" class="form-control">
                                <option value="en_attente" <?php if($statut_e=='en_attente') echo 'selected'; ?>>En attente</option>
                                <option value="en_cours" <?php if($statut_e=='en_cours') echo 'selected'; ?>>En cours</option>
                                <option value="termine" <?php if($statut_e=='termine') echo 'selected'; ?>>Termine</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pieces de rechange -->
            <div class="panel panel-success">
                <div class="panel-heading">
                    <i class="fa fa-cogs"></i> Pieces de rechange
                    <button type="button" class="btn btn-sm btn-default pull-right" id="ajouter-piece" style="margin-top:-5px;">
                        <i class="fa fa-plus"></i> Ajouter une piece
                    </button>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-body">
                    <table class="table table-bordered" id="table-pieces">
                        <thead>
                            <tr>
                                <th>Nom de la piece</th>
                                <th>Prix unitaire</th>
                                <th>Quantite</th>
                                <th>Fournisseur</th>
                                <th>Montant</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="pieces-body">
                            <tr class="piece-row">
                                <td><input type="text" name="nom_piece[]" class="form-control" placeholder="Ex: Filtre a huile"></td>
                                <td><input type="number" name="prix_unitaire[]" class="form-control prix-unit" step="0.01" min="0" value="0"></td>
                                <td><input type="number" name="quantite[]" class="form-control qte" min="1" value="1"></td>
                                <td><input type="text" name="fournisseur_piece[]" class="form-control" placeholder="Fournisseur"></td>
                                <td class="montant-piece" style="vertical-align:middle; font-weight:bold;">0,00</td>
                                <td><button type="button" class="btn btn-danger btn-sm suppr-piece"><i class="fa fa-times"></i></button></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" style="text-align:right; font-weight:bold;">Total pieces :</td>
                                <td id="total-pieces" style="font-weight:bold; color:#27ae60;">0,00</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="form-actions" style="margin-bottom:30px;">
                <a href="liste_entretien.php" class="btn btn-default"><i class="fa fa-arrow-left"></i> Annuler</a>
                <button type="submit" name="submit_entretien" class="btn btn-primary"><i class="fa fa-save"></i> Enregistrer</button>
            </div>

        </div>
        <div class="col-sm-1"></div>
    </div>
</form>

<script>
// Ajouter une ligne de piece
$('#ajouter-piece').click(function() {
    var row = '<tr class="piece-row animate-fadeInUp">' +
        '<td><input type="text" name="nom_piece[]" class="form-control" placeholder="Ex: Filtre a huile"></td>' +
        '<td><input type="number" name="prix_unitaire[]" class="form-control prix-unit" step="0.01" min="0" value="0"></td>' +
        '<td><input type="number" name="quantite[]" class="form-control qte" min="1" value="1"></td>' +
        '<td><input type="text" name="fournisseur_piece[]" class="form-control" placeholder="Fournisseur"></td>' +
        '<td class="montant-piece" style="vertical-align:middle; font-weight:bold;">0,00</td>' +
        '<td><button type="button" class="btn btn-danger btn-sm suppr-piece"><i class="fa fa-times"></i></button></td>' +
        '</tr>';
    $('#pieces-body').append(row);
});

// Supprimer une ligne
$(document).on('click', '.suppr-piece', function() {
    if ($('.piece-row').length > 1) {
        $(this).closest('tr').fadeOut(300, function() {
            $(this).remove();
            calculerTotal();
        });
    }
});

// Calculer montant par ligne et total
$(document).on('input', '.prix-unit, .qte', function() {
    var row = $(this).closest('tr');
    var prix = parseFloat(row.find('.prix-unit').val()) || 0;
    var qte = parseInt(row.find('.qte').val()) || 0;
    var montant = prix * qte;
    row.find('.montant-piece').text(montant.toLocaleString('fr-FR', {minimumFractionDigits: 2}));
    calculerTotal();
});

function calculerTotal() {
    var total = 0;
    $('.piece-row').each(function() {
        var prix = parseFloat($(this).find('.prix-unit').val()) || 0;
        var qte = parseInt($(this).find('.qte').val()) || 0;
        total += prix * qte;
    });
    $('#total-pieces').text(total.toLocaleString('fr-FR', {minimumFractionDigits: 2}));
}
</script>

<?php
require_once 'footer.php';
?>
