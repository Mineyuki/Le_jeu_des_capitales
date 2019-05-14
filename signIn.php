    <div class="container-fluid center-vertical" id="signIn">
        <div class="row">
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4"></div>
            <form id="signInForm" class="col-xs-4 col-sm-4 col-md-4 col-lg-4 form-vertical" action="" method="post">
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

<?php
    require_once('identificationBDD.php');

    if(isset($_POST['signInForm']) and
        trim($_POST['email']) != "" and
        trim($_POST['password']) != "")
    {
        $email = htmlspecialchars($_POST['email']);
        $password = sha1($_POST['password']);

        $request = $bd->prepare("SELECT * FROM member WHERE mail = :mail AND password = :password");
        $request->bindValue(':mail',$email);
        $request->bindValue(':password',$password);
        $request->execute();

        $result = $request->fetch();

        if($result)
        {
            $messageSignIn = 'Mauvais adresse mail ou mot de passe!';
        }
        else
        {
            session_start();
            $_SESSION['email'] = $result['mail'];
            header('Location : index.html');
        }
    }
    else
    {
        $messageSignIn = 'Veuillez remplir les champs !';
    }
?>