<?php
require_once("header.php");

$id = $_GET["id"];
$liste = $vh->select_vehicule($id);

if (empty($liste)) {
    echo '<script>document.location.replace("liste_vehicule.php");</script>';
    exit;
}

$row = $liste[0];
?>

<div class="row animate-fadeInDown">
    <div class="col-lg-12">
        <h1 class="page-header">
            <i class="fa fa-car"></i> <small>Consulter Vehicule</small>
        </h1>
    </div>
</div>

<?php $user->affichage(); ?>

<div class="row animate-fadeInUp delay-1">
    <div class="col-lg-12">
        <div style="margin-bottom:15px;">
            <a href="liste_vehicule.php" class="btn btn-default"><i class="fa fa-arrow-left"></i> Retour</a>
            <a href="modifier_v.php?id=<?php echo $id; ?>" class="btn btn-warning"><i class="fa fa-edit"></i> Modifier</a>
            <a href="#" onclick="window.print();" class="btn btn-info"><i class="fa fa-print"></i> Imprimer</a>
        </div>

        <div class="panel panel-primary">
            <div class="panel-heading"><i class="fa fa-info-circle"></i> Fiche vehicule</div>
            <div class="panel-body">
                <table class="table table-bordered">
                    <tr><td style="width:200px; font-weight:bold;">ID</td><td><?php echo $row["id_v"]; ?></td></tr>
                    <tr><td style="font-weight:bold;">Matricule</td><td><?php echo $row["matricule"]; ?></td></tr>
                    <tr><td style="font-weight:bold;">Type</td><td><?php echo ucfirst($row["type"]); ?></td></tr>
                    <tr><td style="font-weight:bold;">Marque</td><td><?php echo $row["marque"]; ?></td></tr>
                    <tr><td style="font-weight:bold;">Kilometrage</td><td><?php echo number_format($row["kilometrage"], 0, ',', ' '); ?> km</td></tr>
                    <tr>
                        <td style="font-weight:bold;">Age</td>
                        <td>
                            <?php
                            $age = date('Y') - $row["date_f"];
                            echo ($age <= 0) ? '< 1 an' : $age . ' ans';
                            ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Historique des entretiens pour ce vehicule -->
        <div class="panel panel-success animate-fadeInUp delay-2">
            <div class="panel-heading"><i class="fa fa-wrench"></i> Historique des entretiens</div>
            <div class="panel-body">
                <?php
                $entretiens = DataBase::connect()->prepare("SELECT * FROM entretien WHERE id_v=:id ORDER BY date_entretien DESC");
                $entretiens->execute(['id' => $id]);
                $total_cout = 0;

                if ($entretiens->rowCount() > 0) {
                    echo "<table class='table table-bordered table-hover'>";
                    echo "<thead><tr><th>Date</th><th>Type</th><th>Prestataire</th><th>Statut</th><th>Montant</th><th>Voir</th></tr></thead><tbody>";

                    while ($e = $entretiens->fetch(PDO::FETCH_OBJ)) {
                        $total_cout += $e->montant_total;
                        $sc = 'label-danger'; $sl = 'En attente';
                        if ($e->statut == 'termine') { $sc = 'label-success'; $sl = 'Termine'; }
                        elseif ($e->statut == 'en_cours') { $sc = 'label-warning'; $sl = 'En cours'; }

                        echo "<tr>";
                        echo "<td>{$e->date_entretien}</td>";
                        echo "<td>" . ucfirst($e->type_entretien) . "</td>";
                        echo "<td>{$e->prestataire}</td>";
                        echo "<td><span class='label {$sc}'>{$sl}</span></td>";
                        echo "<td>" . number_format($e->montant_total, 2, ',', ' ') . " CFA</td>";
                        echo "<td><a href='consulter_entretien.php?id={$e->id_entretien}'><i class='fa fa-eye' style='color:#3498db;'></i></a></td>";
                        echo "</tr>";
                    }

                    echo "<tr style='background:#f0f9ff;'><td colspan='4' style='text-align:right; font-weight:bold;'>Cout total entretiens :</td>";
                    echo "<td style='font-weight:bold; color:#2980b9;'>" . number_format($total_cout, 2, ',', ' ') . " CFA</td><td></td></tr>";
                    echo "</tbody></table>";
                } else {
                    echo "<p style='text-align:center; color:#999;'>Aucun entretien enregistre pour ce vehicule.</p>";
                }
                ?>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
