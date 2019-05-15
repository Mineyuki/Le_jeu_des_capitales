<?php
    require_once('header.php');
?>

    <div class="container-fluid" id="signInPannel">
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

<?php
    require_once('identificationBDD.php');

    if(isset($_POST['signInForm']))
    {
        if(trim($_POST['emailSignIn']) != "" and
        trim($_POST['password']) != "")
        {
            $email = htmlspecialchars($_POST['emailSignIn']);
            $password = sha1($_POST['password']);

            $request = $bd->prepare("SELECT * FROM member JOIN have_role USING (id_member) JOIN role USING (id_role) WHERE mail = :mail AND password = :password");
            $request->bindValue(':mail',$email);
            $request->bindValue(':password',$password);
            $request->execute();

            $result = $request->fetch(PDO::FETCH_ASSOC);

            if($result)
            {
                session_start();
                $_SESSION['connected'] = true;
                $_SESSION['role'] = $result['nom'];
                header('Location: index.html');
            }
            else
            {
                $messageSignIn = 'Mauvais adresse mail ou mot de passe!';
            }
        }
        else
        {
            $messageSignIn = 'Veuillez remplir les champs !';
        }
    }

    require_once('footer.php');
?>