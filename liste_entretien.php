<?php
require_once("header.php");
?>

<div class="row animate-fadeInDown">
    <div class="col-lg-12">
        <h1 class="page-header">
            <i class="fa fa-wrench"></i> <small>Liste des entretiens</small>
        </h1>
    </div>
</div>

<div class="row animate-fadeInUp delay-1">
    <div class="col-lg-12">
        <a href="ajouter_entretien.php" class="btn btn-primary" style="margin-bottom:15px;">
            <i class="fa fa-plus"></i> Nouvel entretien
        </a>

        <?php $user->affichage(); ?>

        <!-- Filtres -->
        <div class="panel panel-default" style="margin-top:10px;">
            <div class="panel-heading"><i class="fa fa-filter"></i> Filtrer</div>
            <div class="panel-body">
                <form class="form-inline" id="recherche" method="post">
                    <div class="form-group" style="margin-right:10px;">
                        <input type="date" class="form-control" name="date_debut" id="date_debut" placeholder="Date debut">
                    </div>
                    <div class="form-group" style="margin-right:10px;">
                        <input type="date" class="form-control" name="date_fin" id="date_fin" placeholder="Date fin">
                    </div>
                    <div class="form-group" style="margin-right:10px;">
                        <select class="form-control" name="type_entretien" id="type_entretien">
                            <option value="">-- Type --</option>
                            <option value="vidange">Vidange</option>
                            <option value="revision">Revision</option>
                            <option value="reparation">Reparation</option>
                            <option value="pneumatique">Pneumatique</option>
                            <option value="freinage">Freinage</option>
                            <option value="carrosserie">Carrosserie</option>
                            <option value="electricite">Electricite</option>
                            <option value="autre">Autre</option>
                        </select>
                    </div>
                    <div class="form-group" style="margin-right:10px;">
                        <select class="form-control" name="vehicule" id="vehicule_filtre">
                            <option value="">-- Vehicule --</option>
                            <?php $vh->liste_vehicule_options(); ?>
                        </select>
                    </div>
                    <div class="form-group" style="margin-right:10px;">
                        <select class="form-control" name="statut" id="statut_filtre">
                            <option value="">-- Statut --</option>
                            <option value="en_attente">En attente</option>
                            <option value="en_cours">En cours</option>
                            <option value="termine">Termine</option>
                        </select>
                    </div>
                    <button type="button" class="btn btn-primary" id="btn-chercher">
                        <i class="fa fa-search"></i> Chercher
                    </button>
                </form>
            </div>
        </div>

        <div id="resultat">
            <table class="table table-responsive table-bordered table-hover" id="liste_e">
                <thead>
                    <tr>
                        <th>ID</th><th>Date</th><th>Vehicule</th><th>Type</th><th>Statut</th><th>Montant</th><th>Voir</th><th>Modifier</th><th>Supprimer</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $ent->liste_entretien(); ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
$("#btn-chercher").click(function() {
    var formData = $("#recherche").serialize();
    $.ajax({
        type: "POST",
        url: "chercher_entretien.php",
        cache: false,
        data: formData,
        success: function(data) {
            $("#liste_e").hide('slow');
            $("#resultat").html(data);
        },
        error: function() {
            alert('Erreur de connexion');
        }
    });
});
</script>

<?php
require_once 'footer.php';
?>
