<?php
    /*
     * Redirection des pages en php
     */
    if(isset($_GET['url']) && trim($_GET['url']) != "")
    { // Si $_GET['url] existe et qu'il n'est pas vide
        // Redirige la page vers l'url donnee
        header("Location: ".$_GET['url']);
        exit();
    }

    // Redirige la page vers l'accueil
    header("Location: index.html");
    exit();
?>