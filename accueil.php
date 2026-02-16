<?php
require_once("header.php");

// Recuperer les filtres
$date_debut = isset($_POST['date_debut']) ? $_POST['date_debut'] : '';
$date_fin = isset($_POST['date_fin']) ? $_POST['date_fin'] : '';

// Stats
$total_vehicules = $rapport->total_vehicules();
$total_entretiens = $rapport->total_entretiens($date_debut, $date_fin);
$total_depenses = $rapport->total_depenses($date_debut, $date_fin);
$en_cours = $rapport->entretiens_en_cours();

// Donnees pour les tableaux
$cout_vehicules = $rapport->cout_par_vehicule($date_debut, $date_fin);
$cout_pieces = $rapport->cout_par_piece($date_debut, $date_fin);
$evolution = $rapport->evolution_mensuelle($date_debut, $date_fin);
?>

<!-- Titre -->
<div class="row animate-fadeInDown" style="margin-top:15px;">
    <div class="col-lg-12">
        <h1 class="page-header">
            <i class="fa fa-dashboard"></i> <small>Tableau de bord</small>
        </h1>
    </div>
</div>

<!-- Filtre par periode -->
<div class="row animate-fadeInUp delay-1">
    <div class="col-lg-12">
        <form method="post" class="form-inline" style="margin-bottom:25px; background:#f8f9fa; padding:15px; border-radius:10px;">
            <i class="fa fa-filter"></i>&nbsp;
            <label>Periode :</label>&nbsp;
            <div class="form-group" style="margin-right:10px;">
                <input type="date" name="date_debut" class="form-control" value="<?php echo $date_debut; ?>" placeholder="Du">
            </div>
            <div class="form-group" style="margin-right:10px;">
                <input type="date" name="date_fin" class="form-control" value="<?php echo $date_fin; ?>" placeholder="Au">
            </div>
            <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Filtrer</button>
            <?php if (!empty($date_debut) || !empty($date_fin)): ?>
                <a href="accueil.php" class="btn btn-default" style="margin-left:5px;"><i class="fa fa-times"></i> Reinitialiser</a>
            <?php endif; ?>

            <?php if (!empty($date_debut) || !empty($date_fin)): ?>
                <span style="margin-left:15px; color:#7f8c8d;">
                    <i class="fa fa-calendar"></i>
                    <?php
                    if (!empty($date_debut) && !empty($date_fin)) echo "Du $date_debut au $date_fin";
                    elseif (!empty($date_debut)) echo "A partir du $date_debut";
                    else echo "Jusqu'au $date_fin";
                    ?>
                </span>
            <?php endif; ?>
        </form>
    </div>
</div>

<!-- Cartes de stats -->
<div class="row">
    <div class="col-lg-3 col-md-6 animate-fadeInUp delay-1">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3"><i class="fa fa-car fa-4x"></i></div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"><?php echo $total_vehicules; ?></div>
                        <div>Vehicules</div>
                    </div>
                </div>
            </div>
            <a href="liste_vehicule.php">
                <div class="panel-footer">
                    <span class="pull-left">Voir la liste</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 animate-fadeInUp delay-2">
        <div class="panel panel-green">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3"><i class="fa fa-wrench fa-4x"></i></div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"><?php echo $total_entretiens; ?></div>
                        <div>Entretiens</div>
                    </div>
                </div>
            </div>
            <a href="liste_entretien.php">
                <div class="panel-footer">
                    <span class="pull-left">Voir la liste</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 animate-fadeInUp delay-3">
        <div class="panel panel-yellow">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3"><i class="fa fa-money fa-4x"></i></div>
                    <div class="col-xs-9 text-right">
                        <div class="huge" style="font-size:28px;"><?php echo number_format($total_depenses, 0, ',', ' '); ?></div>
                        <div>Cout Total (CFA)</div>
                    </div>
                </div>
            </div>
            <a href="rapport.php">
                <div class="panel-footer">
                    <span class="pull-left">Voir les rapports</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 animate-fadeInUp delay-4">
        <div class="panel panel-red">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3"><i class="fa fa-clock-o fa-4x"></i></div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"><?php echo $en_cours; ?></div>
                        <div>En cours</div>
                    </div>
                </div>
            </div>
            <a href="liste_entretien.php">
                <div class="panel-footer">
                    <span class="pull-left">Entretiens en attente</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
</div>

<!-- Graphique Evolution Mensuelle -->
<div class="row" style="margin-top:20px;">
    <div class="col-lg-12 animate-fadeInUp delay-5">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-bar-chart"></i> Evolution mensuelle des depenses
            </div>
            <div class="panel-body">
                <?php if (count($evolution) > 0): ?>
                    <div id="chart-depenses" style="height:300px;"></div>
                <?php else: ?>
                    <p style="text-align:center; color:#999; padding:40px;">Aucune donnee pour la periode selectionnee.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Tableaux -->
<div class="row" style="margin-top:10px;">
    <!-- Cout par vehicule -->
    <div class="col-lg-6 animate-fadeInUp delay-5">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-car"></i> Cout par vehicule
            </div>
            <div class="panel-body">
                <?php if (count($cout_vehicules) > 0): ?>
                <table class="table table-bordered table-hover table-condensed">
                    <thead>
                        <tr><th>Vehicule</th><th>Entretiens</th><th>Cout total</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cout_vehicules as $cv): ?>
                        <tr>
                            <td><?php echo $cv->type . ' - ' . $cv->marque . '<br><small class="text-muted">' . $cv->matricule . '</small>'; ?></td>
                            <td><span class="label label-info"><?php echo $cv->nb_entretiens; ?></span></td>
                            <td style="font-weight:bold;"><?php echo number_format($cv->cout_total, 2, ',', ' '); ?> CFA</td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <p style="text-align:center; color:#999;">Aucune donnee.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Cout par piece -->
    <div class="col-lg-6 animate-fadeInUp delay-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-cogs"></i> Cout par piece de rechange
            </div>
            <div class="panel-body">
                <?php if (count($cout_pieces) > 0): ?>
                <table class="table table-bordered table-hover table-condensed">
                    <thead>
                        <tr><th>Piece</th><th>Quantite totale</th><th>Cout total</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cout_pieces as $cp): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($cp->nom_piece); ?></td>
                            <td><span class="label label-default"><?php echo $cp->total_quantite; ?></span></td>
                            <td style="font-weight:bold;"><?php echo number_format($cp->cout_total, 2, ',', ' '); ?> CFA</td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <p style="text-align:center; color:#999;">Aucune donnee.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Morris.js Chart -->
<script src="js/plugins/morris/raphael.min.js"></script>
<script src="js/plugins/morris/morris.min.js"></script>
<script>
<?php if (count($evolution) > 0): ?>
$(function() {
    Morris.Bar({
        element: 'chart-depenses',
        data: [
            <?php
            $items = [];
            foreach ($evolution as $ev) {
                $items[] = "{mois: '{$ev->mois}', montant: {$ev->montant}}";
            }
            echo implode(",\n            ", $items);
            ?>
        ],
        xkey: 'mois',
        ykeys: ['montant'],
        labels: ['Depenses (CFA)'],
        barColors: ['#3498db'],
        resize: true,
        hideHover: 'auto'
    });
});
<?php endif; ?>
</script>

<?php require_once 'footer.php'; ?>
