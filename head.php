<!DOCTYPE html>
<html lang="fr">
<head>
    <!-- Titre du site -->
    <title>The Game</title>
    <!-- Metadonnees : codage en UTF-8 -->
    <meta charset="utf-8" />
    <!-- Utilisation css pour la carte -->
    <link href="https://unpkg.com/leaflet@1.4.0/dist/leaflet.css" rel="stylesheet">
    <!-- lien vers la bibliothèque bootstrap -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" rel="stylesheet">
    <!-- Utilisation css personnel -->
    <link href="index.css" rel="stylesheet">
</head>
<body>
    <!-- Voir documentation bootstrap : navbar -->
    <!-- Barre de navigation fixe en haut de page-->
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <!-- Permet d'avoir une icone et un menu deroulant lorsqu'on a un petit ecran -->
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#barre-de-navigation">
                    <span class="sr-only"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <!-- Nom du site -->
                <a class="navbar-brand" href="index.html">THE GAME</a>
            </div>
            <div class="collapse navbar-collapse" id="barre-de-navigation">
                <!-- Tous les menus seront a droite -->
                <ul class="nav navbar-nav navbar-right">
                    <?php
                        session_start();
                        if(isset($_SESSION['connected']))
                        { // Si on est connecte
                            if($_SESSION['role']=='administrateur')
                            { // Si on est administrateur
                    ?>
                        <!-- Affichage du menu parametre utilisateurs -->
                        <li><a href="tableUser.html">Paramètre utilisateurs</a></li>
                    <?php
                            }
                    ?>
                        <!-- Affichage des menus score, profil et deconnexion -->
                        <li><a href="score.html">Score</a></li>
                        <li><a href="profil.html" id="profilLink">Profil</a></li>
                        <li><a href="logout.html">Deconnexion</a></li>
                    <?php
                        }
                        else
                        { // Si on n'est pas connecte
                    ?>
                        <!-- Affichage des menus inscription et connexion -->
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
            <!-- Affichage message lorsque le JavaScript n'est pas active -->
            <noscript>
                <p class="alert alert-warning">Veuillez activer le JavaScript.</p>
            </noscript>
        </div>
    </div>