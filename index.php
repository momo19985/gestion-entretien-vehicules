<?php
require_once("class/main.php");
?>
<html lang="en">

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Auto-Parc</title>

        <!-- Bootstrap Core CSS -->
        <link href="css/bootstrap.min.css" rel="stylesheet">

        <!-- Custom CSS -->
        <link href="css/sb-admin.css" rel="stylesheet">
        <!-- Custom CSS -->
        <link href="css/xstyle.css" rel="stylesheet">
        <!-- Animations CSS -->
        <link href="css/animations.css" rel="stylesheet">
        <!-- Morris Charts CSS -->
        <link href="css/plugins/morris.css" rel="stylesheet">
        <!-- jQuery -->
        <script src="js/jquery.js"></script>

        <!-- Bootstrap Core JavaScript -->
        <script src="js/bootstrap.min.js"></script>
        <!-- Custom Fonts -->
        <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <style>
            body.login-body * {
                font-family: 'Poppins', sans-serif;
            }
        </style>

    </head>

    <body class="login-body">

        <div class="wrapper" style="width:100%; display:flex; justify-content:center; align-items:center;">

            <form class="form-signin" method="post" id="login-form">       
                <center>
                    <h2 class="animate-fadeInDown">Auto-Parc</h2>
                    <p style="color:#7f8c8d; font-size:14px; margin-top:-5px;" class="animate-fadeInDown delay-1">Gestion de parc automobile</p>
                    <br>
                    <div class="login-logo">
                        <img src="img/parking.png" height="180" width="190" style="filter: drop-shadow(0 4px 8px rgba(0,0,0,0.15));">
                    </div>
                </center>
                <div class="animate-fadeInUp delay-3">
                    <div style="position:relative; margin-bottom:14px;">
                        <i class="fa fa-user" style="position:absolute; left:14px; top:15px; color:#aab; z-index:2;"></i>
                        <input type="text" class="form-control" name="login" placeholder="Login" id="email" style="padding-left:40px;" />
                    </div>
                    <div style="position:relative; margin-bottom:14px;">
                        <i class="fa fa-lock" style="position:absolute; left:14px; top:15px; color:#aab; z-index:2;"></i>
                        <input type="password" class="form-control" name="pass" placeholder="Mot de passe" id="password" style="padding-left:40px;" />
                    </div>
                </div>

                <button class="btn btn-lg btn-primary btn-block btn-ripple animate-fadeInUp delay-4" id="login-submit" type="submit">
                    <i class="fa fa-sign-in"></i>&nbsp; Connecter
                </button>   

                <div id="msgbox" class="msgbox" style="text-align:center;"></div>

            </form>
        </div>

        <script src="js/connect.js"></script>

        <?php
        require_once 'footer.php';
        ?>
