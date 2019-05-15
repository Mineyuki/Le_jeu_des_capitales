<?php
    require_once('head.php');
?>
    <div class="container-fluid" id="signUpPannel">
        <div class="row">
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4"></div>
            <form id="signUpForm" class="col-xs-4 col-sm-4 col-md-4 col-lg-4 form-vertical" action="signUp.html" method="POST">
                <fieldset>
                    <legend>Inscription :</legend>
                    <?php
                    if(isset($_GET['messageSignUp']) && trim($_GET['messageSignUp']) != "")
                    {
                        echo '<p class="alert alert-warning">'.$_GET['messageSignUp'].'</p>';
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
    // Demande utilisation identificationBDD.php
    require_once('identificationBDD.php');

    if(isset($_POST['signUpForm']))
    {
        if(trim($_POST['emailSignUp']) != "" and
        trim($_POST['password1']) != "" and
        trim($_POST['password2']) != "")
        {
            $email = htmlspecialchars($_POST['emailSignUp']);
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
                        $insertUser = $bd->prepare("INSERT INTO member (mail, password, pseudo) VALUES (:mail, :password, :pseudo)");
                        $insertUser->bindValue(':mail',$email);
                        $insertUser->bindValue(':password',$password1);
                        $insertUser->bindValue(':pseudo',$email);
                        $insertUser->execute();

                        $getUser = $bd->prepare("SELECT id_member FROM member WHERE mail = :mail");
                        $getUser->bindValue(':mail',$email);
                        $getUser->execute();
                        $resultUser = $getUser->fetch(PDO::FETCH_ASSOC);

                        $getRole = $bd->prepare("SELECT id_role FROM role WHERE nom='utilisateur'");
                        $getRole->execute();
                        $resultRole = $getRole->fetch(PDO::FETCH_ASSOC);

                        $giveRole = $bd->prepare("INSERT INTO have_role (id_member, id_role) VALUES (:member, :role)");
                        $giveRole->bindValue(':member',$resultUser['id_member']);
                        $giveRole->bindValue(':role',$resultRole['id_role']);
                        $giveRole->execute();

                        $messageSignUp = 'Creation compte réussie';
                        header('Location: signIn.html');
                        exit();
                    }
                    else
                    {
                        $messageSignUp = 'Mot de passe différent !';
                        header('Location: header.html?url=signUp.html?messageSignUp='.$messageSignUp);
                        exit();
                    }
                }
                else
                {
                    $messageSignUp = 'Compte existant !';
                    header('Location: header.html?url=signUp.html?messageSignUp='.$messageSignUp);
                    exit();
                }
            }
            else
            {
                $messageSignUp = 'Adresse mail non valide !';
                header('Location: header.html?url=signUp.html?messageSignUp='.$messageSignUp);
                exit();
            }
        }
        else
        {
            $messageSignUp = 'Veuillez renseigner les champs !';
            header('Location: header.html?url=signUp.html?messageSignUp='.$messageSignUp);
            exit();
        }
    }
?>

    <script>document.title = 'Inscription';</script>

<?php
    require_once('footer.php');
?>