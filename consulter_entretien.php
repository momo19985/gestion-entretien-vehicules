<?php
require_once("header.php");

$id = isset($_GET['id']) ? $_GET['id'] : 0;
$data = $ent->select_entretien($id);

if (empty($data)) {
    echo '<script>document.location.replace("liste_entretien.php");</script>';
    exit;
}

$row = $data[0];

// Statut badge
$statut_class = 'label-danger';
$statut_label = 'En attente';
if ($row['statut'] == 'termine') { $statut_class = 'label-success'; $statut_label = 'Termine'; }
elseif ($row['statut'] == 'en_cours') { $statut_class = 'label-warning'; $statut_label = 'En cours'; }
?>

<div class="row animate-fadeInDown">
    <div class="col-lg-12">
        <h1 class="page-header">
            <i class="fa fa-file-text"></i> <small>Entretien #<?php echo $id; ?></small>
        </h1>
    </div>
</div>

<?php $user->affichage(); ?>

<div class="row animate-fadeInUp delay-1">
    <div class="col-lg-12">
        <div style="margin-bottom:15px;">
            <a href="liste_entretien.php" class="btn btn-default"><i class="fa fa-arrow-left"></i> Retour</a>
            <a href="modifier_entretien.php?id=<?php echo $id; ?>" class="btn btn-warning"><i class="fa fa-edit"></i> Modifier</a>
            <a href="#" onclick="window.print();" class="btn btn-info"><i class="fa fa-print"></i> Imprimer</a>
        </div>

        <!-- Info entretien -->
        <div class="panel panel-primary">
            <div class="panel-heading"><i class="fa fa-info-circle"></i> Details de l'entretien</div>
            <div class="panel-body">
                <table class="table table-bordered">
                    <tr><td style="width:200px; font-weight:bold;">ID</td><td><?php echo $row['id_entretien']; ?></td></tr>
                    <tr><td style="font-weight:bold;">Date</td><td><?php echo $row['date_entretien']; ?></td></tr>
                    <tr><td style="font-weight:bold;">Vehicule</td><td><?php echo $row['type'] . ' - ' . $row['marque'] . ' - ' . $row['matricule']; ?></td></tr>
                    <tr><td style="font-weight:bold;">Type d'entretien</td><td><span class="label label-info"><?php echo ucfirst($row['type_entretien']); ?></span></td></tr>
                    <tr><td style="font-weight:bold;">Description</td><td><?php echo nl2br(htmlspecialchars($row['description'])); ?></td></tr>
                    <tr><td style="font-weight:bold;">Prestataire</td><td><?php echo htmlspecialchars($row['prestataire']); ?></td></tr>
                    <tr><td style="font-weight:bold;">Statut</td><td><span class="label <?php echo $statut_class; ?>"><?php echo $statut_label; ?></span></td></tr>
                    <tr><td style="font-weight:bold;">Main d'oeuvre</td><td><?php echo number_format($row['main_oeuvre'], 2, ',', ' '); ?> CFA</td></tr>
                    <tr style="background:#f0f9ff;"><td style="font-weight:bold; font-size:16px;">Montant Total</td><td style="font-weight:bold; font-size:16px; color:#2980b9;"><?php echo number_format($row['montant_total'], 2, ',', ' '); ?> CFA</td></tr>
                </table>
            </div>
        </div>

        <!-- Pieces de rechange -->
        <div class="panel panel-success animate-fadeInUp delay-2">
            <div class="panel-heading"><i class="fa fa-cogs"></i> Pieces de rechange</div>
            <div class="panel-body">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr><th>Piece</th><th>Prix unitaire</th><th>Quantite</th><th>Montant</th><th>Fournisseur</th><th></th></tr>
                    </thead>
                    <tbody>
                        <?php $total_pieces = $piece_r->afficher_pieces_entretien($id); ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" style="text-align:right; font-weight:bold;">Total pieces :</td>
                            <td style="font-weight:bold; color:#27ae60;"><?php echo number_format($total_pieces, 2, ',', ' '); ?> CFA</td>
                            <td colspan="2"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

    </div>
</div>

<?php require_once 'footer.php'; ?>
