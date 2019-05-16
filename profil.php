<?php
    session_start();

    require_once('head.php');
    require_once('identificationBDD.php');

    if($_SESSION['connected'])
    { // Si on est connecte
        // Prepare la requete
        $request = $bd->prepare("SELECT * FROM member WHERE id_member = :id");
        $request->bindValue(':id',$_SESSION['id_member']);
        $request->execute();
        $result = $request->fetch(PDO::FETCH_ASSOC);
?>
        <div class="container">
            <div class="well well-lg">
                <p class="text-center"><?php echo $result['pseudo']; ?></p>
            </div>
            <?php
                if(isset($_GET['message']) && trim($_GET['message']) != "")
                {
                    echo '<p class="alert alert-warning">'.$_GET['message'].'</p>';
                }
            ?>
            <div class="well well-lg">
                <div class="row">
                    <p class="col-xs-11 col-sm-11 col-md-11 col-lg-11">Pseudo : <?php echo $result['pseudo'];?></p>
                    <a class="col-xs-1 col-sm-1 col-md-1 col-lg-1" id="profilLinkPseudo" href="#">Modifier</a>
                </div>
                <div class="row" id="profilPseudo"></div>
                <div class="row">
                    <p class="col-xs-12 col-sm-12 col-md-12 col-lg-12">Adresse mail : <?php echo $result['mail']; ?></p>
                </div>
                <div class="row">
                    <p class="col-xs-11 col-sm-11 col-md-11 col-lg-11">Mot de passe : **********</p>
                    <a class="col-xs-1 col-sm-1 col-md-1 col-lg-1" id="profilLinkPassword" href="#">Modifier</a>
                </div>
                <div class="row" id="profilPassword"></div>
            </div>
            <form id="delete" class="form-vertical" method="POST">
                <button type="submit" class="col-xs-12 col-sm-12 col-md-12 col-lg-12 btn btn-danger" name="delete">Supprimer le compte</button>
            </form>
        </div>

        <script>document.title = 'Profil';</script>

<?php
        if(isset($_POST['pseudoForm']))
        {
            if(trim($_POST['pseudo']) != "")
            {
                $pseudo = htmlspecialchars($_POST['pseudo']);

                $request = $bd->prepare("UPDATE member SET pseudo = :pseu WHERE id_member = :id");
                $request->bindValue(':pseu',$pseudo);
                $request->bindValue(':id',$_SESSION['id_member']);
                $request->execute();

                $message = 'Pseudo modifié avec succès !';
                header('Location: header.html?url=profil.html?message='.$message);
                exit();
            }
            else
            {
                $message = 'Champ pseudo vide !';
                header('Location: header.html?url=profil.html?message='.$message);
                exit();
            }
        }

        if(isset($_POST['passwordForm']))
        {
            if(trim($_POST['passwordOld']) != "" and
            trim($_POST['password1']) != "" and
            trim($_POST['password2']) != "")
            {
                if($_POST['password1'] != $_POST['password2'])
                {
                    $message = 'Mot de passe différent !';
                    header('Location: header.html?url=profil.html?message='.$message);
                    exit();
                }

                $passwordOld = sha1($_POST['passwordOld']);
                $password1 = sha1($_POST['password1']);
                $password2 = sha1($_POST['password2']);

                $request = $bd->prepare("SELECT * FROM member WHERE id_member = :id AND password = :password");
                $request->bindValue('id', $_SESSION['id_member']);
                $request->bindValue('password', $passwordOld);
                $request-> execute();

                $result = $request->fetch(PDO::FETCH_ASSOC);

                if($result)
                {
                    $request = $bd->prepare("UPDATE member SET password = :password WHERE id_member = :id");
                    $request->bindValue(':password',$password1);
                    $request->bindValue(':id',$_SESSION['id_member']);
                    $request->execute();

                    $message = 'Mot de passe modifié avec succès !';
                    header('Location: header.html?url=profil.html?message='.$message);
                    exit();
                }
                else
                {
                    $message = 'Mot de passe incorrect !';
                    header('Location: header.html?url=profil.html?message='.$message);
                    exit();
                }
            }
            else
            {
                $message = 'Champ mot de passe vide !';
                header('Location: header.html?url=profil.html?message='.$message);
                exit();
            }
        }

        if(isset($_POST['delete']))
        {
            $request = $bd->prepare("DELETE FROM have_role WHERE id_member = :id_member");
            $request->bindValue(':id_member',$_SESSION['id_member']);
            $request->execute();

            $request = $bd->prepare("DELETE FROM score WHERE id_member = :id_member");
            $request->bindValue(':id_member',$_SESSION['id_member']);
            $request->execute();

            $request = $bd->prepare("DELETE FROM member WHERE id_member = :id_member");
            $request->bindValue(':id_member',$_SESSION['id_member']);
            $request->execute();

            header('Location: logout.html');
            exit();
        }
    }
    else
    {
        header('Location: signIn.html');
        exit();
    }
    require_once('footer.php');
?>
