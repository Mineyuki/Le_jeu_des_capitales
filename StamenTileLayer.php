<!DOCTYPE html>
<html lang="fr">
<head>
	<title>The Game</title>
	<meta charset="utf-8" />
	<link href="https://unpkg.com/leaflet@1.4.0/dist/leaflet.css" rel="stylesheet">
	<!-- lien vers la bibliothèque bootstrap -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="StamenTileLayer.css" rel="stylesheet">
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
                <a class="navbar-brand navbar-center" href="StamenTileLayer.php">THE GAME</a>
            </div>
            <div class="collapse navbar-collapse" id="barre-de-navigation">
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="#signUp" id="signUpLink">Inscription</a></li>
                    <li><a href="#signIn" id="signInLink">Connexion</a></li>
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

    <div class="container-fluid center-vertical" id="signUp">
        <div class="row">
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4"></div>
            <form class="col-xs-4 col-sm-4 col-md-4 col-lg-4 form-vertical" action="signIn.php" method="post">
                <fieldset>
                    <legend>Inscription :</legend>
                    <div class="form-group">
                        <label for="emailSignUp">Adresse email</label>
                        <input type="email" class="form-control" id="emailSignUp" placeholder="Votre adresse mail"/>
                    </div>
                    <div class="form-group">
                        <label for="password1">Mot de passe</label>
                        <input type="password" class="form-control" id="password1" placeholder="Votre mot de passe"/>
                    </div>
                    <div class="form-group">
                        <label for="password2">Confirmer votre mot de passe</label>
                        <input type="password" class="form-control" id="password2" placeholder="Confirmer votre mot de passe"/>
                    </div>
                    <button type="submit" class="col-xs-12 col-sm-12 col-md-12 col-lg-12 btn btn-primary">S'inscrire</button>
                </fieldset>
            </form>
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4"></div>
        </div>
    </div>

    <div class="container-fluid center-vertical" id="signIn">
        <div class="row">
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4"></div>
            <form class="col-xs-4 col-sm-4 col-md-4 col-lg-4 form-vertical" action="signIn.php" method="post">
                <fieldset>
                    <legend>Accéder à votre compte :</legend>
                    <div class="form-group">
                        <label for="emailSignIn">Adresse email</label>
                        <input type="email" class="form-control" id="emailSignIn" placeholder="Votre adresse mail"/>
                    </div>
                    <div class="form-group">
                        <label for="password">Mot de passe</label>
                        <input type="password" class="form-control" id="password" placeholder="Votre mot de passe"/>
                    </div>
                    <button type="submit" class="col-xs-12 col-sm-12 col-md-12 col-lg-12 btn btn-primary">Connexion</button>
                </fieldset>
            </form>
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4"></div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.4.0/dist/leaflet.js"></script>
    <script src="/js/leaflet-0.7.2/leaflet.ajax.min.js"></script>
    <!-- lien vers le script contenant la fonction getXMLHttpRequest-->
    <script type="text/javascript" src="oXHR.js"></script>
    <script src="generateStateName.js" type="text/javascript"></script>
</body>
</html>