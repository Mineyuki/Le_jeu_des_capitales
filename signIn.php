<?php
    require_once('head.php');
?>

    <div class="container-fluid" id="signInPannel">
        <div class="row">
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4"></div>
            <form id="signInForm" class="col-xs-4 col-sm-4 col-md-4 col-lg-4 form-vertical" action="signIn.html" method="POST">
                <fieldset>
                    <?php
                    if (isset($_GET['messageSignIn']) && trim($_GET['messageSignIn']) != "")
                    {
                        echo '<p class="alert alert-warning">'.$_GET['messageSignIn'].'</p>';
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
        if(securisation($_POST['emailSignIn']) != "" and
        securisation($_POST['password']) != "")
        {
            $email = securisation(htmlspecialchars($_POST['emailSignIn']));
            $password = sha1(securisation($_POST['password']));

            $request = $bd->prepare("SELECT * FROM member JOIN have_role USING (id_member) JOIN role USING (id_role) WHERE mail = :mail AND password = :password");
            $request->bindValue(':mail',$email);
            $request->bindValue(':password',$password);
            $request->execute();

            $result = $request->fetch(PDO::FETCH_ASSOC);

            if($result)
            {
                session_start();
                $_SESSION['connected'] = true;
                $_SESSION['id_member'] = $result['id_member'];
                $_SESSION['role'] = $result['nom'];
                header('Location: index.html');
                exit();
            }
            else
            {
                $messageSignIn = 'Mauvais adresse mail ou mot de passe!';
                header('Location: header.html?url=signIn.html?messageSignIn='.$messageSignIn);
                exit();
            }
        }
        else
        {
            $messageSignIn = 'Veuillez remplir les champs !';
            header('Location: header.html?url=signIn.html?messageSignIn='.$messageSignIn);
            exit();
        }
    }
?>
    <script>document.title = 'Connexion';</script>
<?php
    require_once('footer.php');
?>