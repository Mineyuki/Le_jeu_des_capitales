<?php
/*
 * Documentation htaccess :
 * https://www.aidoweb.com/tutoriaux/fichier-htaccess-qui-ne-fonctionne-pas-solutions-configuration-apache-648
 * https://docs.bolt.cm/3.6/howto/making-sure-htaccess-works
 * https://stackoverflow.com/questions/4548860/replacing-php-ext-with-html-through-htaccess
 */
    session_start();
    if(isset($_SESSION['connected']))
    {
        require_once('headerConnected.php');
    }
    else
    {
        require_once('header.php');
    }

?>
    <div class="container-fluid center-vertical" id="secondPannel">
        <div class="row">
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4"></div>
            <button type="button" class="col-xs-4 col-sm-4 col-md-4 col-lg-4 btn btn-primary" id="play">Jouer</button>
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4"></div>
        </div>
    </div>

    <div class="container-fluid center-vertical" id="thirdPannel">
        <div class="row">
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3"></div>
            <button type="button" class="col-xs-2 col-sm-2 col-md-2 col-lg-2 btn btn-primary" id="playState">Pays</button>
            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2"></div>
            <button type="button" class="col-xs-2 col-sm-2 col-md-2 col-lg-2 btn btn-primary" id="playCapital">Capitale</button>
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3"></div>
        </div>
    </div>

    <div class="container-fluid" id="principalPannel">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                <div class="well well-lg">
                    Vous devez localiser : <strong id="nameQuestion"></strong> - Score : <strong id="score"></strong>
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
                    <div id="wikipedia"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid center-vertical" id="signInPannel">
        <div class="row">
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4"></div>
            <form id="signInForm" class="col-xs-4 col-sm-4 col-md-4 col-lg-4 form-vertical" action="signIn.html" method="POST">
                <fieldset>
                    <?php
                    if (isset($messageSignIn))
                    {
                        echo '<p class="alert alert-warning">'.$messageSignIn.'</p>';
                    }
                    ?>
                    <legend>Accéder à votre compte :</legend>
                    <div class="form-group">
                        <label for="emailSignIn">Adresse email</label>
                        <input type="email" class="form-control" name="emailSignIn" placeholder="Votre adresse mail"/>
                    </div>
                    <div class="form-group">
                        <label for="password">Mot de passe</label>
                        <input type="password" class="form-control" name="password" placeholder="Votre mot de passe"/>
                    </div>
                    <button type="submit" class="col-xs-12 col-sm-12 col-md-12 col-lg-12 btn btn-primary" name="signInForm">Connexion</button>
                </fieldset>
            </form>
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4"></div>
        </div>
    </div>

    <div class="container-fluid center-vertical" id="signUpPannel">
        <div class="row">
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4"></div>
            <form id="signUpForm" class="col-xs-4 col-sm-4 col-md-4 col-lg-4 form-vertical" action="signUp.html" method="POST">
                <fieldset>
                    <legend>Inscription :</legend>
                    <?php
                    if(isset($messageSignUp))
                    {
                        echo '<p class="alert alert-warning">'.$messageSignUp.'</p>';
                    }
                    ?>
                    <div class="form-group">
                        <label for="emailSignUp">Adresse email</label>
                        <input type="email" class="form-control" name="emailSignUp" placeholder="Votre adresse mail"/>
                    </div>
                    <div class="form-group">
                        <label for="password1">Mot de passe</label>
                        <input type="password" class="form-control" name="password1" placeholder="Votre mot de passe"/>
                    </div>
                    <div class="form-group">
                        <label for="password2">Confirmer votre mot de passe</label>
                        <input type="password" class="form-control" name="password2" placeholder="Confirmer votre mot de passe"/>
                    </div>
                    <button type="submit" class="col-xs-12 col-sm-12 col-md-12 col-lg-12 btn btn-primary submitButton" name="signUpForm">S'inscrire</button>
                </fieldset>
            </form>
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4"></div>
        </div>
    </div>

<?php
    require_once('footer.php');
?>