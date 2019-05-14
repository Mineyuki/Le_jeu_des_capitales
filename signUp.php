    <div class="container-fluid center-vertical" id="signUp">
        <div class="row">
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4"></div>
            <form id="signUpForm" class="col-xs-4 col-sm-4 col-md-4 col-lg-4 form-vertical" action="" method="post">
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
                    <button type="submit" class="col-xs-12 col-sm-12 col-md-12 col-lg-12 btn btn-primary" name="signUpForm">S'inscrire</button>
                </fieldset>
            </form>
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4"></div>
        </div>
    </div>

<?php
    // Demande utilisation identificationBDD.php
    require_once('identificationBDD.php');

    if(isset($_POST['signUpForm']) and
        trim($_POST['email']) != "" and
        trim($_POST['password1']) != "" and
        trim($_POST['password2']) != "")
    {
        $email = htmlspecialchars($_POST['email']);
        $password1 = sha1($_POST['password1']);
        $password2 = sha1($_POST['password2']);

        if(filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            $request = $bd->prepare("SELECT * FROM member WHERE mail = :mail");
            $request->bindValue(':mail',$email);
            $request->execute();
            if(($request->rowCount()) == 0)
            {
                if($password1 == $password2)
                {
                    $insertUser = $bd->prepare("INSERT INTO member (mail, password) VALUES (:mail, :password)");
                    $insertUser->bindValue(':mail',$email);
                    $insertUser->bindValue(':password',$password1);
                    $insertUser->execute();

                    $messageSignUp = 'Creation compte réussie';
                }
                else
                {
                    $messageSignUp = 'Mot de passe différent !';
                }
            }
            else
            {
                $messageSignUp = 'Compte existant !';
            }
        }
        else
        {
            $messageSignUp = 'Adresse mail non valide !';
        }
    }
    else
    {
        $messageSignUp = 'Veuillez renseigner les champs !';
    }
?>