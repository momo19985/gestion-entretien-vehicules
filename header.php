<?php
session_name('K1Q');
session_start();
if(empty($_SESSION['l']) || empty($_SESSION['SUCe']) || $_SESSION['SUCe']!="xx88xxc1r123yyI;;::!!1a")
{
    header('location:./');
    exit;
}
require_once("class/main.php");

$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestion Entretien - Auto-Parc</title>

    <!-- CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/datepicker.css" rel="stylesheet">
    <link href="css/sb-admin.css" rel="stylesheet">
    <link href="css/animations.css" rel="stylesheet">
    <link href="css/plugins/morris.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/fakeLoader.css">

    <!-- JS -->
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap-datepicker.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/fakeLoader.min.js"></script>

    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="accueil.php">
                    <i class="fa fa-wrench"></i> Gestion Entretien
                </a>
            </div>

            <!-- Top Menu -->
            <ul class="nav navbar-right top-nav">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-user-circle"></i> <?php echo htmlspecialchars($_SESSION['l']); ?> <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="changer_pass.php"><i class="fa fa-fw fa-key"></i> Mot de passe</a></li>
                        <li class="divider"></li>
                        <li><a href="class/logout.php"><i class="fa fa-fw fa-power-off"></i> Deconnexion</a></li>
                    </ul>
                </li>
            </ul>

            <!-- Sidebar -->
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav side-nav">
                    <li <?php if($current_page == 'accueil.php') echo 'class="active"'; ?>>
                        <a href="accueil.php"><i class="fa fa-fw fa-dashboard"></i> Tableau de bord</a>
                    </li>
                    <li <?php if(in_array($current_page, ['liste_vehicule.php','ajouter_v.php','modifier_v.php','consulter_v.php'])) echo 'class="active"'; ?>>
                        <a href="liste_vehicule.php"><i class="fa fa-fw fa-car"></i> Vehicules</a>
                    </li>
                    <li <?php if(in_array($current_page, ['liste_entretien.php','ajouter_entretien.php','modifier_entretien.php','consulter_entretien.php'])) echo 'class="active"'; ?>>
                        <a href="liste_entretien.php"><i class="fa fa-fw fa-wrench"></i> Entretiens</a>
                    </li>
                    <li <?php if($current_page == 'rapport.php') echo 'class="active"'; ?>>
                        <a href="rapport.php"><i class="fa fa-fw fa-bar-chart"></i> Rapports</a>
                    </li>
                    <li>
                        <a href="class/logout.php"><i class="fa fa-fw fa-sign-out"></i> Deconnexion</a>
                    </li>
                </ul>
            </div>
        </nav>

    <div id="page-wrapper">
        <div class="container-fluid">
