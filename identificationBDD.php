<?php
    try
    {
        /*
         * Acces la base de donnees par l'objet de la classe PDO
         * Prend 3 parametres : le DSN, le login et le mot de passe
         * DSN (Data Source Name) : nom du pilote PDO, nom de la base de donnes et nom serveur
         */
        $bd = new PDO('mysql:host=localhost;dbname=project', 'project', 'project');
        // Le script en PHP communique avec la base en UTF-8
        $bd->query("SET NAMES 'UTF8'");
        // En cas d'erreur, exception doit etre levee
        $bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch (PDOException $e)
    { // En cas d'erreur lors de la connexion a la base, affichage d'un probleme
        die('<p>La connexion a échoué ! Erreur ['.$e->getCode().'] : '.$e->getMessage().'</p>');
    }

    function securisation($donnees)
    {
        $donnees = trim($donnees);
        $donnees = stripslashes($donnees);
        $donnees = strip_tags($donnees);
        return $donnees;
    }
?>