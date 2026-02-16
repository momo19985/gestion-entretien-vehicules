<?php
require_once("header.php");

$date_debut = isset($_POST['date_debut']) ? $_POST['date_debut'] : '';
$date_fin = isset($_POST['date_fin']) ? $_POST['date_fin'] : '';

$total_depenses = $rapport->total_depenses($date_debut, $date_fin);
$total_entretiens = $rapport->total_entretiens($date_debut, $date_fin);
$cout_vehicules = $rapport->cout_par_vehicule($date_debut, $date_fin);
$cout_pieces = $rapport->cout_par_piece($date_debut, $date_fin);
$evolution = $rapport->evolution_mensuelle($date_debut, $date_fin);
?>

<div class="row animate-fadeInDown" style="margin-top:15px;">
    <div class="col-lg-12">
        <h1 class="page-header">
            <i class="fa fa-bar-chart"></i> <small>Rapports & Analyses</small>
        </h1>
    </div>
</div>

<!-- Filtre par periode -->
<div class="row animate-fadeInUp delay-1">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading"><i class="fa fa-filter"></i> Filtrer par periode</div>
            <div class="panel-body">
                <form method="post" class="form-inline">
                    <div class="form-group" style="margin-right:10px;">
                        <label>Du :</label>
                        <input type="date" name="date_debut" class="form-control" value="<?php echo $date_debut; ?>">
                    </div>
                    <div class="form-group" style="margin-right:10px;">
                        <label>Au :</label>
                        <input type="date" name="date_fin" class="form-control" value="<?php echo $date_fin; ?>">
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Generer le rapport</button>
                    <?php if (!empty($date_debut) || !empty($date_fin)): ?>
                        <a href="rapport.php" class="btn btn-default" style="margin-left:5px;"><i class="fa fa-times"></i> Reinitialiser</a>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Resume chiffres -->
<div class="row animate-fadeInUp delay-2">
    <div class="col-lg-4 col-md-4">
        <div class="panel panel-primary" style="text-align:center; padding:20px;">
            <i class="fa fa-wrench fa-3x" style="color:#3498db;"></i>
            <h2 style="margin:10px 0 5px;"><?php echo $total_entretiens; ?></h2>
            <p style="color:#7f8c8d;">Entretiens</p>
        </div>
    </div>
    <div class="col-lg-4 col-md-4">
        <div class="panel panel-primary" style="text-align:center; padding:20px;">
            <i class="fa fa-money fa-3x" style="color:#e67e22;"></i>
            <h2 style="margin:10px 0 5px;"><?php echo number_format($total_depenses, 2, ',', ' '); ?> CFA</h2>
            <p style="color:#7f8c8d;">Depenses totales</p>
        </div>
    </div>
    <div class="col-lg-4 col-md-4">
        <div class="panel panel-primary" style="text-align:center; padding:20px;">
            <i class="fa fa-calculator fa-3x" style="color:#27ae60;"></i>
            <h2 style="margin:10px 0 5px;">
                <?php echo ($total_entretiens > 0) ? number_format($total_depenses / $total_entretiens, 2, ',', ' ') : '0,00'; ?> CFA
            </h2>
            <p style="color:#7f8c8d;">Cout moyen / entretien</p>
        </div>
    </div>
</div>

<!-- Graphique -->
<div class="row animate-fadeInUp delay-3">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-line-chart"></i> Evolution mensuelle des depenses
                <button onclick="window.print();" class="btn btn-sm btn-info pull-right" style="margin-top:-5px;"><i class="fa fa-print"></i> Imprimer</button>
                <div class="clearfix"></div>
            </div>
            <div class="panel-body">
                <?php if (count($evolution) > 0): ?>
                    <div id="chart-rapport" style="height:350px;"></div>
                <?php else: ?>
                    <p style="text-align:center; color:#999; padding:40px;">Aucune donnee pour la periode selectionnee.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Tableau Cout par vehicule -->
<div class="row animate-fadeInUp delay-4">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading"><i class="fa fa-car"></i> Rapport par vehicule</div>
            <div class="panel-body">
                <?php if (count($cout_vehicules) > 0): ?>
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th>Vehicule</th>
                            <th>Matricule</th>
                            <th>Nb Entretiens</th>
                            <th>Cout Total</th>
                            <th>% du Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cout_vehicules as $cv): ?>
                        <tr>
                            <td><?php echo ucfirst($cv->type) . ' - ' . $cv->marque; ?></td>
                            <td><strong><?php echo $cv->matricule; ?></strong></td>
                            <td><span class="label label-info"><?php echo $cv->nb_entretiens; ?></span></td>
                            <td style="font-weight:bold;"><?php echo number_format($cv->cout_total, 2, ',', ' '); ?> CFA</td>
                            <td>
                                <?php
                                $pct = ($total_depenses > 0) ? round(($cv->cout_total / $total_depenses) * 100, 1) : 0;
                                ?>
                                <div class="progress" style="margin:0; min-width:80px;">
                                    <div class="progress-bar progress-bar-info" style="width:<?php echo $pct; ?>%;"><?php echo $pct; ?>%</div>
                                </div>
                            </td>
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

<!-- Tableau Cout par piece -->
<div class="row animate-fadeInUp delay-5">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading"><i class="fa fa-cogs"></i> Rapport par piece de rechange</div>
            <div class="panel-body">
                <?php if (count($cout_pieces) > 0): ?>
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th>Piece de rechange</th>
                            <th>Quantite totale</th>
                            <th>Cout Total</th>
                            <th>% du Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total_pieces_global = 0;
                        foreach ($cout_pieces as $cp) $total_pieces_global += $cp->cout_total;

                        foreach ($cout_pieces as $cp):
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($cp->nom_piece); ?></td>
                            <td><span class="label label-default"><?php echo $cp->total_quantite; ?></span></td>
                            <td style="font-weight:bold;"><?php echo number_format($cp->cout_total, 2, ',', ' '); ?> CFA</td>
                            <td>
                                <?php
                                $pct = ($total_pieces_global > 0) ? round(($cp->cout_total / $total_pieces_global) * 100, 1) : 0;
                                ?>
                                <div class="progress" style="margin:0; min-width:80px;">
                                    <div class="progress-bar progress-bar-success" style="width:<?php echo $pct; ?>%;"><?php echo $pct; ?>%</div>
                                </div>
                            </td>
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
        element: 'chart-rapport',
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
        barColors: ['#2980b9'],
        resize: true,
        hideHover: 'auto'
    });
});
<?php endif; ?>
</script>

<?php require_once 'footer.php'; ?>
