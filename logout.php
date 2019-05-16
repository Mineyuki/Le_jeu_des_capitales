<?php
    // Deconnexion
    session_start();
    if($_SESSION['connected'])
    { // Si on est connecte
        session_destroy(); // Detruit la session - deconnexion
    }
    header('Location: index.html'); // Redirige vers la page d'accueil
    exit();
?>
