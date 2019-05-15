<!DOCTYPE html>
<html lang="fr">
<head>
    <title>The Game</title>
    <meta charset="utf-8" />
    <link href="https://unpkg.com/leaflet@1.4.0/dist/leaflet.css" rel="stylesheet">
    <!-- lien vers la bibliothèque bootstrap -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="index.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#barre-de-navigation">
                    <span class="sr-only"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand navbar-center" href="index.html">THE GAME</a>
            </div>
            <div class="collapse navbar-collapse" id="barre-de-navigation">
                <ul class="nav navbar-nav navbar-right">
                    <?php
                        session_start();
                        if(isset($_SESSION['connected']))
                        {
                            if($_SESSION['role']=='administrateur')
                            {
                    ?>
                        <li><a href="tableUser.html">Paramètre utilisateurs</a></li>
                    <?php
                            }
                    ?>
                        <li><a href="profil.html" id="profilLink">Profil</a></li>
                        <li><a href="logout.html">Deconnexion</a></li>
                    <?php
                        }
                        else
                        {
                    ?>
                        <li><a href="signUp.html" id="signUpLink">Inscription</a></li>
                        <li><a href="signIn.html" id="signInLink">Connexion</a></li>
                    <?php
                        }
                    ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container div-padding-top-10">
        <div class="row">
            <noscript>
                <p class="alert alert-warning">Veuillez activer le JavaScript.</p>
            </noscript>
        </div>
    </div>