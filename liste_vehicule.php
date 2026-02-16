<?php
require_once("header.php");
?>

<div class="row animate-fadeInDown">
    <div class="col-lg-12">
        <h1 class="page-header">
            <i class="fa fa-car"></i> <small>Liste des vehicules</small>
        </h1>
    </div>
</div>

<div class="row animate-fadeInUp delay-1">
    <div class="col-lg-12">
        <a href="ajouter_v.php" class="btn btn-primary" style="margin-bottom:15px;">
            <i class="fa fa-plus"></i> Nouveau vehicule
        </a>

        <?php $user->affichage(); ?>

        <!-- Filtres -->
        <form class="form-inline" id="recherche" role="form" method="post" style="margin-bottom:15px;">
            <div class="form-group" style="margin-right:10px;">
                <input type="text" class="form-control" placeholder="Chercher par matricule" id="matricule" name="matricule">
            </div>
            <div class="form-group" style="margin-right:10px;">
                <select name="type" class="form-control" id="type">
                    <option selected value="tous">Tous</option>
                    <option value="voiture">Voiture</option>
                    <option value="camion">Camion</option>
                </select>
            </div>
        </form>

        <div id="resultat">
            <table class="table table-responsive table-bordered table-hover" id="liste_s">
                <thead>
                    <tr>
                        <th>ID</th><th>Matricule</th><th>Type</th><th>Marque</th><th>Kilometrage</th><th>Age (ans)</th><th>Voir</th><th>Modifier</th><th>Supprimer</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $vh->liste_vehicule(); ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
$("#type").change(function() {
    var formData = $("#recherche").serialize();
    $.ajax({
        type: "POST",
        url: "chercher_vehicule.php",
        cache: false,
        data: formData,
        success: function(data) {
            $("#liste_s").hide('slow');
            $("#resultat").html(data);
        },
        error: function() { alert('Erreur de connexion'); }
    });
});

$("#matricule").keyup(function() {
    var formData = $("#recherche").serialize();
    $.ajax({
        type: "POST",
        url: "chercher_vehicule_matricule.php",
        cache: false,
        data: formData,
        success: function(data) {
            $("#liste_s").hide('slow');
            $("#resultat").html(data);
        },
        error: function() { alert('Erreur de connexion'); }
    });
});
</script>

<?php require_once 'footer.php'; ?>
