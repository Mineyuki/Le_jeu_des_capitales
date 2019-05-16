<?php
/*
 * Documentation htaccess :
 * https://www.aidoweb.com/tutoriaux/fichier-htaccess-qui-ne-fonctionne-pas-solutions-configuration-apache-648
 * https://docs.bolt.cm/3.6/howto/making-sure-htaccess-works
 * https://stackoverflow.com/questions/4548860/replacing-php-ext-with-html-through-htaccess
 */
    require_once('head.php'); // Demande l'entete
?>
    <div class="container" id="secondPannel">
        <div class="well well-lg">
            <p class="text-center"><strong>Règles du jeu</strong></p>
            <p>Pour jouer, vous devez cliquer sur la carte.</p>
            <p>Vous obtiendrez la réponse et le nombre de point qui vous est attribué.</p>
            <p>Nous avons défini la zone de point avec un cercle d'un certain rayon. A vous de découvrir, pour les zones très large, quel est son point central...!</p>
            <p>Si vous ne savez pas, cliquez quand même sur la carte pour connaître la réponse.</p>
            <p>A la derniere question, lorsque vous aurez répondu, la réponse s'affichera.</p>
            <p>Cliquez encore une fois pour revenir à cette page.</p>
            <p><strong>Recommandation :</strong></p>
            <p>Connectez-vous pour sauvegarder votre score.</p>
            <p>Aucun score ne sera sauvegardé avant la connexion.</p>
            <p><strong>Indice :</strong></p>
            <p>Un rayon de <span id="rayonCapitales"></span>km a été établi autour des capitales...</p>
            <p>Un rayon de <span id="rayonPays"></span>km a été établi autour des pays...</p>
            <p class="text-center"><strong>Bon jeu !</strong></p>
        </div>
        <div class="row">
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4"></div>
            <button type="button" class="col-xs-4 col-sm-4 col-md-4 col-lg-4 btn btn-primary" id="play">Jouer</button>
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4"></div>
        </div>
    </div>

    <div class="container" id="thirdPannel">
        <div class="row">
            <div class="well well-lg">
                <p class="text-center">Jeux disponibles</p>
            </div>
        </div>
        <div class="row">
            <?php
                require_once('identificationBDD.php'); // Connexion base de donnees

                // Prepare la requete pour recuperer le nom du jeu
                $request = $bd->prepare("SELECT * FROM game");
                $request->execute(); // Execute la requete
                while($row = $request->fetch(PDO::FETCH_ASSOC))
                { // Pour chaque ligne, affiche un bouton
            ?>
                    <button type="button" id="<?php echo $row['name_game'];?>" class="col-xs-12 col-sm-2 col-sm-offset-3 col-md-3 col-md-offset-2 col-lg-3 col-lg-offset-2 btn btn-primary button-padding-bottom"><?php echo $row['name_game'];?></button>
            <?php
                }
            ?>
        </div>
        <div class="row div-padding-top-10">
            <div class="well well-lg">
                <p class="text-center"><strong>Record</strong></p>
                <table class="table">
                    <thead>
                        <tr>
                            <td>Jeu</td>
                            <td>Pseudo</td>
                            <td>Score</td>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        $request = $bd->prepare("SELECT * FROM score JOIN member USING (id_member) JOIN from_game USING (id_score) JOIN game USING (id_game) ORDER BY point DESC LIMIT 3");
                        $request->execute();
                        while($row = $request->fetch(PDO::FETCH_ASSOC))
                        {
                            echo '<tr>';
                            echo '<td>'.$row['name_game'].'</td>';
                            echo '<td>'.$row['pseudo'].'</td>';
                            echo '<td>'.$row['point'].'</td>';
                            echo '</tr>';
                        }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="container-fluid" id="principalPannel">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                <div class="well well-lg">
                    Localiser <span id="typeQuestion"></span> : <strong id="nameQuestion"></strong> - Score : <strong id="score"></strong>
                </div>
                <div class="progress">
                    <div class="progress-bar progress-bar-info"></div>
                </div>
                <div id="maDiv" class="height-70"></div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                <div class="well well-lg">
                    <h2 class="text-center">Historique</h2>
                </div>
                <div class="well well-lg">
                    <div id="history"></div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                <div class="row">
                    <div id="myCarrousel" class="carousel slide" data-ride="carousel">
                        <!-- Indicateurs -->
                        <ol class="carousel-indicators">
                            <li data-targer="#myCarrousel" data-slide-to="0" class="active"></li>
                            <li data-targer="#myCarrousel" data-slide-to="1"></li>
                            <li data-targer="#myCarrousel" data-slide-to="2"></li>
                        </ol>
                        <!-- Wrapper pour les slides -->
                        <div class="carousel-inner" role="listbox">
                            <div id="image0" class="item active height-40"></div>
                            <div id="image1" class="item height-40"></div>
                            <div id="image2" class="item height-40"></div>
                        </div>
                        <!-- Les controles droite et gauche -->
                        <a class="left carousel-control" href="#myCarrousel" role="button" data-slide="prev">
                            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                            <span class="sr-only">Précédent</span>
                        </a>
                        <a class="right carousel-control" href="#myCarrousel" role="button" data-slide="next">
                            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                            <span class="sr-only">Suivant</span>
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div id="wikipedia"></div> <!-- Fenetre du wikipedia -->
                </div>
            </div>
        </div>
    </div>

<?php
    require_once('footer.php'); // Demande le footer
?>
