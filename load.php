<?php 
session_name('K1Q');
session_start();
if(empty($_SESSION['l'])  ||  empty($_SESSION['SUCe']) || $_SESSION['SUCe']!="xx88xxc1r123yyI;;::!!1a"    )
{
	header('location:index.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Auto-Parc - Chargement</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            background: linear-gradient(-45deg, #1a5276, #2e86c1, #3498db, #5dade2);
            background-size: 400% 400%;
            animation: gradientShift 8s ease infinite;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Poppins', sans-serif;
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .loader-container {
            text-align: center;
            animation: fadeIn 0.5s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .loader-icon {
            font-size: 60px;
            color: #fff;
            margin-bottom: 25px;
            animation: float 2s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }

        .loader-title {
            color: #fff;
            font-size: 28px;
            font-weight: 700;
            letter-spacing: 2px;
            margin-bottom: 30px;
            text-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }

        .loader-bar {
            width: 200px;
            height: 4px;
            background: rgba(255,255,255,0.2);
            border-radius: 4px;
            margin: 0 auto;
            overflow: hidden;
        }

        .loader-bar-fill {
            width: 0%;
            height: 100%;
            background: #fff;
            border-radius: 4px;
            animation: loadBar 2s ease-in-out forwards;
        }

        @keyframes loadBar {
            0% { width: 0%; }
            50% { width: 70%; }
            100% { width: 100%; }
        }

        .loader-text {
            color: rgba(255,255,255,0.7);
            font-size: 14px;
            margin-top: 15px;
            animation: pulse 1.5s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 0.7; }
            50% { opacity: 1; }
        }

        .fade-out {
            animation: fadeOut 0.5s ease-in forwards;
        }

        @keyframes fadeOut {
            from { opacity: 1; transform: scale(1); }
            to { opacity: 0; transform: scale(1.05); }
        }
    </style>
</head>

<body>
    <div class="loader-container" id="loader">
        <div class="loader-icon">
            <i class="fa fa-car"></i>
        </div>
        <div class="loader-title">Auto-Parc</div>
        <div class="loader-bar">
            <div class="loader-bar-fill"></div>
        </div>
        <div class="loader-text">Chargement en cours...</div>
    </div>
</body>

<script>
    setTimeout(function(){
        document.getElementById('loader').classList.add('fade-out');
    }, 1800);
    setTimeout(function(){
        location.href = "accueil.php";
    }, 2300);
</script>
</html>
